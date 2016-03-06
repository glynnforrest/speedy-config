<?php

namespace SpeedyConfig;

/**
 * ConfigResolver loads various resources, processes the
 * configuration, and returns a single resolved Config instance.
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class ConfigResolver
{
    /**
     * Get the resolved configuration.
     *
     * @return Config
     */
    public function getConfig()
    {
        return new Config();
    }
}
