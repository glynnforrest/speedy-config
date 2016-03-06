<?php

namespace SpeedyConfig;

use SpeedyConfig\Loader\LoaderInterface;

/**
 * ConfigResolver loads various resources, processes the
 * configuration, and returns a single resolved Config instance.
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class ConfigResolver
{
    protected $resources = [];
    protected $loaders = [];

    /**
     * Add a resource to load.
     *
     * @param string $resource
     */
    public function addResource($resource)
    {
        $this->resources[] = $resource;
    }

    public function addLoader(LoaderInterface $loader)
    {
        $this->loaders[] = $loader;
    }

    /**
     * Load a resource using a loader. The first loader that supports
     * the resource will be used.
     *
     * @param string $resource
     *
     * @throws ResourceException
     *
     * @return array
     */
    protected function loadResource($resource)
    {
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
            $config->merge($this->loadResource($resource));
        }

        return $config;
    }
}
