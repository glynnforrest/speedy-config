<?php

namespace SpeedyConfig\Processor;

use SpeedyConfig\Config;
use SpeedyConfig\Schema\Schema;

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
    public function onPostMerge(Config $config, Schema $schema);
}
