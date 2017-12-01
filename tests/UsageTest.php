<?php

namespace SpeedyConfig\Tests;

use SpeedyConfig\ConfigBuilder;
use SpeedyConfig\Loader\PhpLoader;
use SpeedyConfig\Loader\YamlLoader;
use SpeedyConfig\Processor\ReferenceProcessor;
use SpeedyConfig\Loader\ArrayLoader;
use SpeedyConfig\Processor\SchemaProcessor;
use SpeedyConfig\Schema\Schema;
use SpeedyConfig\Schema\InvalidSchemaException;

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

    public function testLoadArray()
    {
        $builder = new ConfigBuilder(new ArrayLoader());
        $builder->addResource(['one' => 1, 'two' => 2]);
        $config = $builder->getConfig();
        $this->assertSame(['one' => 1, 'two' => 2], $config->get());
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

    public function testBasicValidSchema()
    {
        $builder = new ConfigBuilder(new ArrayLoader(), new SchemaProcessor());
        $builder->addResource(['foo' => 'bar']);
        $schema = new Schema();
        $schema->node('foo')->isRequired();

        $config = $builder->getConfig($schema);
        $this->assertSame(['foo' => 'bar'], $config->get());
    }

    public function testBasicInvalidSchema()
    {
        $builder = new ConfigBuilder(new ArrayLoader(), new SchemaProcessor());
        $builder->addResource(['foo' => 'bar']);
        $schema = new Schema();
        $schema->node('bar')->isRequired();

        $this->setExpectedException(InvalidSchemaException::class, 'The resolved configuration is invalid: The key "bar" is required.');
        $builder->getConfig($schema);
    }

    public function testValidSchema()
    {
        $builder = new ConfigBuilder(new YamlLoader(), new SchemaProcessor());
        $builder->addResource(__DIR__.'/fixtures/valid_schema.yml');
        $schema = new Schema();
        $schema->node('integer')->isRequired()->hasType('integer');
        $schema->node('deeply.nested.integer')->hasType('integer');
        $schema->node('one.two')->hasType('array')->hasCount(2);
        $schema->node('one.two.four')->isRequired()->hasType('string');

        $config = $builder->getConfig($schema);
        $this->assertSame('124', $config->get('one.two.four'));
    }
}
