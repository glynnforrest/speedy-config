<?php

namespace SpeedyConfig\Tests\Processor;

use SpeedyConfig\Config;
use SpeedyConfig\Schema\Schema;
use SpeedyConfig\Schema\RuleInterface;
use SpeedyConfig\Schema\ViolationException;
use SpeedyConfig\Schema\InvalidSchemaException;
use SpeedyConfig\Processor\SchemaProcessor;

/**
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class SchemaProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->processor = new SchemaProcessor();
    }

    public function testFailingRule()
    {
        $config = new Config(['some_key' => 'some_value']);
        $schema = new Schema();
        $rule = $this->createMock(RuleInterface::class);
        $schema->node('some_key')->addRule($rule);
        $rule->expects($this->any())
            ->method('validate')
            ->with('some_value', 'some_key')
            ->will($this->throwException(new ViolationException('Value is wrong.')));

        $this->setExpectedException(InvalidSchemaException::class);
        $this->processor->onPostMerge($config, $schema);
    }
}
