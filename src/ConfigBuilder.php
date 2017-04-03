<?php

namespace SpeedyConfig;

use SpeedyConfig\Loader\LoaderInterface;
use SpeedyConfig\Processor\ProcessorInterface;

/**
 * ConfigBuilder loads resources using the supplied loaders, processes
 * the configuration using the supplied processors, and returns a
 * single resolved Config instance.
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class ConfigBuilder
{
    protected $resources = [];
    protected $loaders = [];
    protected $processors = [];

    public function __construct($loaders = [], $processors = [])
    {
        $this->loaders = is_array($loaders) ? $loaders : [$loaders];
        $this->processors = is_array($processors) ? $processors : [$processors];
    }

    /**
     * Add a resource to load configuration values from.
     *
     * @param mixed       $resource
     * @param string|null $prefix   The prefix to give the values, if any
     */
    public function addResource($resource, $prefix = null)
    {
        $this->resources[] = [$resource, $prefix];
    }

    /**
     * Load a resource using a loader. The first loader that supports
     * the resource will be used.
     *
     * @param mixed $resource
     *
     * @throws ResourceException
     *
     * @return array
     */
    protected function loadResource($resource)
    {
        if (is_array($resource)) {
            return $resource;
        }

        foreach ($this->loaders as $loader) {
            if (!$loader->supports($resource)) {
                continue;
            }

            return $loader->load($resource);
        }

        throw new ResourceException(sprintf('There is no configuration loader available to load the resource "%s"', $resource));
    }

    /**
     * Get the resolved configuration.
     *
     * @return Config
     */
    public function getConfig()
    {
        $config = new Config();

        foreach ($this->resources as $resource) {
            $values = $this->loadResource($resource[0]);
            if (is_string($prefix = $resource[1])) {
                $values = [
                    $prefix => $values,
                ];
            }

            $config->merge($values);
        }

        foreach ($this->processors as $processor) {
            $processor->onPostMerge($config);
        }

        return $config;
    }
}
