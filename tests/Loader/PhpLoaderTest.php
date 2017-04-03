<?php

namespace SpeedyConfig\Tests\Loader;

use SpeedyConfig\Loader\PhpLoader;

/**
 * PhpLoaderTest
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class PhpLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->loader = new PhpLoader();
    }

    public function supportsProvider()
    {
        return [
            ['foo.php'],
            ['config/config.php'],
            ['config.yml', false],
            ['config', false],
            ['configphp', false],
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
        $expected = ['foo' => 'bar'];
        $this->assertSame($expected, $this->loader->load(__DIR__.'/../fixtures/config.php'));
    }

    public function testLoadThrowsExceptionWithNoFile()
    {
        $this->setExpectedException('SpeedyConfig\ResourceNotFoundException');
        $this->loader->load('some_file.php');
    }

    public function testLoadInvalidFileThrowsException()
    {
        $this->setExpectedException('SpeedyConfig\ResourceException');
        $this->loader->load(__DIR__.'/../fixtures/invalid.php');
    }
}
