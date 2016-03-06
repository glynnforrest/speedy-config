<?php

namespace SpeedyConfig\Tests;

use SpeedyConfig\ConfigResolver;

/**
 * ConfigResolverTest
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class ConfigResolverTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->resolver = new ConfigResolver();
    }

    public function testGetEmptyConfig()
    {
        $config = $this->resolver->getConfig();
        $this->assertInstanceOf('SpeedyConfig\Config', $config);
    }
}
