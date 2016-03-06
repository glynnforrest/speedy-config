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

    public function testAddResource()
    {
        $loader = $this->getMock('SpeedyConfig\Loader\LoaderInterface');
        $loader->expects($this->any())
            ->method('supports')
            ->with('config.yml')
            ->will($this->returnValue(true));
        $loader->expects($this->any())
            ->method('load')
            ->with('config.yml')
            ->will($this->returnValue(['foo' => 'bar']));

        $this->resolver->addLoader($loader);
        $this->resolver->addResource('config.yml');
        $config = $this->resolver->getConfig();
        $this->assertInstanceOf('SpeedyConfig\Config', $config);
        $this->assertSame(['foo' => 'bar'], $config->get());
    }

    public function testResourcesAreLoadedLazily()
    {
        $loader = $this->getMock('SpeedyConfig\Loader\LoaderInterface');
        $loader->expects($this->never())
            ->method('load');

        $this->resolver->addLoader($loader);
        $this->resolver->addResource('config.yml');
    }

    public function testCorrectLoaderIsUsed()
    {
        $this->resolver->addResource('config.yml');

        $jsonLoader = $this->getMock('SpeedyConfig\Loader\LoaderInterface');
        $jsonLoader->expects($this->any())
            ->method('supports')
            ->with('config.yml')
            ->will($this->returnValue(false));
        $this->resolver->addLoader($jsonLoader);

        $yamlLoader = $this->getMock('SpeedyConfig\Loader\LoaderInterface');
        $yamlLoader->expects($this->any())
            ->method('supports')
            ->with('config.yml')
            ->will($this->returnValue(true));

        $yamlLoader->expects($this->any())
            ->method('load')
            ->with('config.yml')
            ->will($this->returnValue(['something' => ['foo' => 'bar']]));
        $this->resolver->addLoader($yamlLoader);

        $config = $this->resolver->getConfig();
        $this->assertInstanceOf('SpeedyConfig\Config', $config);
        $this->assertSame(['foo' => 'bar'], $config->get('something'));
    }

    public function testLoadFailsWithNoSuitableLoader()
    {
        $this->resolver->addResource('invalid.txt');
        $this->setExpectedException('SpeedyConfig\ResourceException', 'There is no configuration loader available to load the resource "invalid.txt"');
        $this->resolver->getConfig();
    }
}
