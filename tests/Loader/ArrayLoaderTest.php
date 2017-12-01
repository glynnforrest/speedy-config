<?php

namespace SpeedyConfig\Tests\Loader;

use SpeedyConfig\Loader\ArrayLoader;

/**
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class ArrayLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->loader = new ArrayLoader();
    }

    public function supportsProvider()
    {
        return [
            [[]],
            [[1, 2, 3]],
            [['foo' => 'bar', 'key' => ['config' => 'value']]],
            ['config.yml', false],
        ];
    }

    /**
     * @dataProvider supportsProvider
     */
    public function testSupports($filename, $expected = true)
    {
        $this->assertSame($expected, $this->loader->supports($filename));
    }

    public function testLoad()
    {
        $this->assertSame(['foo' => 'bar'], $this->loader->load(['foo' => 'bar']));
    }
}
