<?php

namespace SpeedyConfig\Processor;

use SpeedyConfig\Config;

/**
 * ProcessorInterface
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
interface ProcessorInterface
{
    /**
     * Optionally modify the merged configuration.
     *
     * @var Config $config
     */
    public function onPostMerge(Config $config);
}
