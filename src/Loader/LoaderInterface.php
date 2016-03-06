<?php

namespace SpeedyConfig\Loader;

/**
 * LoaderInterface.
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
interface LoaderInterface
{
    /**
     * Load configuration from a resource.
     *
     * @param string $resource
     *
     * @return array An array of values
     */
    public function load($resource);

    /**
     * Check if this loader can load configuration from a resource.
     *
     * @param string $resource
     *
     * @return bool
     */
    public function supports($filename);
}
