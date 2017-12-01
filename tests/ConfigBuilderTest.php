<?php

namespace SpeedyConfig\Tests;

use SpeedyConfig\ConfigBuilder;
use SpeedyConfig\Config;
use SpeedyConfig\Loader\LoaderInterface;
use SpeedyConfig\ResourceException;
use SpeedyConfig\Processor\ProcessorInterface;
use SpeedyConfig\ResourceNotFoundException;

/**
 * ConfigBuilderTest
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class ConfigBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetEmptyConfig()
    {
        $builder = new ConfigBuilder();
        $this->assertInstanceOf(Config::class, $builder->getConfig());
    }

    public function testAddResource()
    {
        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->any())
            ->method('supports')
            ->with('config.yml')
            ->will($this->returnValue(true));
        $loader->expects($this->any())
            ->method('load')
            ->with('config.yml')
            ->will($this->returnValue(['foo' => 'bar']));
        $builder = new ConfigBuilder($loader);
        $builder->addResource('config.yml');
        $config = $builder->getConfig();

        $this->assertInstanceOf(Config::class, $config);
        $this->assertSame(['foo' => 'bar'], $config->get());
    }

    public function testResourcesAreLoadedLazily()
    {
        $loader = $this->createMock('SpeedyConfig\Loader\LoaderInterface');
        $loader->expects($this->never())
            ->method('load');
        $builder = new ConfigBuilder($loader);
        $builder->addResource('config.yml');
    }

    public function testCorrectLoaderIsUsed()
    {
        $jsonLoader = $this->createMock(LoaderInterface::class);
        $jsonLoader->expects($this->any())
            ->method('supports')
            ->with('config.yml')
            ->will($this->returnValue(false));

        $yamlLoader = $this->createMock(LoaderInterface::class);
        $yamlLoader->expects($this->any())
            ->method('supports')
            ->with('config.yml')
            ->will($this->returnValue(true));

        $yamlLoader->expects($this->any())
            ->method('load')
            ->with('config.yml')
            ->will($this->returnValue(['something' => ['foo' => 'bar']]));

        $builder = new ConfigBuilder([$jsonLoader, $yamlLoader]);
        $builder->addResource('config.yml');

        $config = $builder->getConfig();
        $this->assertInstanceOf(Config::class, $config);
        $this->assertSame(['foo' => 'bar'], $config->get('something'));
    }

    public function testLoadFailsWithNoSuitableLoader()
    {
        $builder = new ConfigBuilder();
        $builder->addResource('invalid.txt');
        $this->setExpectedException(ResourceException::class, 'There is no configuration loader available to load the resource "invalid.txt"');
        $builder->getConfig();
    }

    public function testLoadResourceWithPrefix()
    {
        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->any())
            ->method('supports')
            ->will($this->returnValue(true));
        $loader->expects($this->any())
            ->method('load')
            ->will($this->returnValue(['something' => ['foo' => 'bar']]));
        $builder = new ConfigBuilder($loader);
        $builder->addResource('some_resource', 'some_prefix');

        $config = $builder->getConfig();
        $this->assertInstanceOf(Config::class, $config);
        $expected = [
            'some_prefix' => [
                'something' => ['foo' => 'bar'],
            ],
        ];
        $this->assertSame($expected, $config->get());
    }

    public function testProcessorIsCalledAfterMerge()
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects($this->once())
            ->method('onPostMerge')
            ->with($this->callback(function($arg) {
                return $arg instanceof Config;
            }));
        $builder = new ConfigBuilder([], $processor);
        $builder->getConfig();
    }

    public function testLoadUnknownResource()
    {
        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->any())
            ->method('supports')
            ->will($this->returnValue(true));
        $loader->expects($this->any())
            ->method('load')
            ->will($this->throwException(new ResourceNotFoundException()));
        $builder = new ConfigBuilder($loader);
        $builder->addResource('not_a_resource');
        $this->setExpectedException(ResourceNotFoundException::class);
        $builder->getConfig();
    }

    public function testLoadUnknownOptionalResource()
    {
        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->any())
            ->method('supports')
            ->will($this->returnValue(true));
        $loader->expects($this->any())
            ->method('load')
            ->will($this->throwException(new ResourceNotFoundException()));
        $builder = new ConfigBuilder($loader);
        $builder->addOptionalResource('not_a_resource');

        $this->assertInstanceOf(Config::class, $builder->getConfig());
    }

    public function testResourceCallsCanBeChained()
    {
        $builder = new ConfigBuilder();
        $this->assertSame($builder, $builder->addResource('foo'));
        $this->assertSame($builder, $builder->addOptionalResource('foo'));
    }
}
