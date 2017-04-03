<?php

namespace SpeedyConfig\Tests;

use SpeedyConfig\ConfigBuilder;
use SpeedyConfig\Loader\PhpLoader;
use SpeedyConfig\Loader\YamlLoader;
use SpeedyConfig\Processor\ReferenceProcessor;

/**
 * UsageTest.
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class UsageTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadPhp()
    {
        $builder = new ConfigBuilder(new PhpLoader());
        $builder->addResource(__DIR__.'/fixtures/config.php');
        $config = $builder->getConfig();
        $this->assertSame(['foo' => 'bar'], $config->get());
    }

    public function testLoadYaml()
    {
        $builder = new ConfigBuilder(new YamlLoader());
        $builder->addResource(__DIR__.'/fixtures/config.yml');
        $config = $builder->getConfig();
        $this->assertSame(['one' => 'one.one', 'two' => 'one.two'], $config->get('one'));
    }

    public function testYamlWithReferences()
    {
        $builder = new ConfigBuilder(new YamlLoader(), new ReferenceProcessor());
        $builder->addResource(__DIR__.'/fixtures/references.yml');
        $config = $builder->getConfig();
        $expected = [
            'config' => [
                'database' => [
                    'one' => ['user' => 'root', 'password' => 'hunter2'],
                    'two' => ['user' => 'root', 'password' => 'hunter2'],
                ],
            ],
        ];
        $this->assertSame($expected, $config->get());
    }
}
