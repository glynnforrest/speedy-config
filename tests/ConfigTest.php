<?php

namespace SpeedyConfig\Tests;

use SpeedyConfig\Config;

/**
 * ConfigTest.
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    protected $config;

    public function setUp()
    {
        $this->config = new Config();
        $this->config->set('one', 'one');
        $this->config->set('two', [
            'one' => 'two-one',
            'two' => 'two-two',
        ]);
    }

    public function testIsDotArray()
    {
        $this->assertInstanceOf('Crutches\DotArray', new Config());
    }

    public function testSetCanBeChained()
    {
        $config = new Config();
        $this->assertSame($config, $config->set('foo', 'bar'));
    }

    public function testGetRequired()
    {
        $this->assertSame('two-one', $this->config->getRequired('two.one'));
    }

    public function testGetRequiredThrowsException()
    {
        $msg = 'Required value not found: fake';
        $this->setExpectedException('SpeedyConfig\KeyException', $msg);
        $this->config->getRequired('fake');
    }

    public function testGetRequiredEmptyString()
    {
        $this->config->set('string', '');
        $this->assertSame('', $this->config->getRequired('string'));
    }

    public function testGetFirstRequired()
    {
        $this->assertSame('two-one', $this->config->getFirstRequired('two'));
    }

    public function testGetFirstRequiredEmptyString()
    {
        $this->config->set('string', ['', 'foo']);
        $this->assertSame('', $this->config->getFirstRequired('string'));
    }

    public function testGetFirstRequiredThrowsException()
    {
        $msg = 'Required first value not found: fake';
        $this->setExpectedException('SpeedyConfig\KeyException', $msg);
        $this->config->getFirstRequired('fake');
    }

    public function testGetFirstRequiredThrowsExceptionNoArray()
    {
        $this->config->set('3.1', 'not-an-array');
        $msg = 'Required first value not found: 3.1';
        $this->setExpectedException('SpeedyConfig\KeyException', $msg);
        $this->config->getFirstRequired('3.1');
    }

    public function testLoop()
    {
        $config = new Config([
            'one' => 1,
            'two' => [
                'one' => 1,
                'two' => [
                    'one' => 1,
                    'two' => 2,
                ],
                'three' => 3,
            ],
            'three' => 3,
        ]);

        $keys = [];
        $values = [];
        foreach ($config as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }

        $expected_keys = ['one', 'two.one', 'two.two.one', 'two.two.two', 'two.three', 'three'];
        $this->assertSame($expected_keys, $keys);

        $expected_values = [1, 1, 1, 2, 3, 3];
        $this->assertSame($expected_values, $values);
    }
}
