<?php

namespace SpeedyConfig\Tests\Processor;

use SpeedyConfig\Processor\ReferenceProcessor;
use SpeedyConfig\Config;

/**
 * ReferenceProcessorTest.
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class ReferenceProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->processor = new ReferenceProcessor();
    }

    public function postMergeProvider()
    {
        return [
            [
                //original
                [
                    'foo' => 'bar',
                    'bar' => '%foo%',
                ],
                //expected
                [
                    'foo' => 'bar',
                    'bar' => 'bar',
                ],
            ],

            [
                //original
                [
                    'foo' => 'bar',
                    'bar' => '%baz%',
                    'baz' => '%foo%',
                ],
                //expected
                [
                    'foo' => 'bar',
                    'bar' => 'bar',
                    'baz' => 'bar',
                ],
            ],

            [
                //original
                [
                    'foo' => 'foo-%bar.baz%',
                    'bar' => [
                        'baz' => 'baz-%bar.foo%',
                        'foo' => 'value',
                    ],
                ],
                //expected
                [
                    'foo' => 'foo-baz-value',
                    'bar' => [
                        'baz' => 'baz-value',
                        'foo' => 'value',
                    ],
                ],
            ],
            [
                //original
                [
                    'foo' => [
                        'bar' => [
                            'baz' => [
                                'quo' => '%some.key%',
                                'another' => 'value',
                            ],
                        ],
                    ],
                    'some' => [
                        'key' => 'joined-%foo.bar.baz.another%',
                    ],
                ],
                //expected
                [
                    'foo' => [
                        'bar' => [
                            'baz' => [
                                'quo' => 'joined-value',
                                'another' => 'value',
                            ],
                        ],
                    ],
                    'some' => [
                        'key' => 'joined-value',
                    ],
                ]
            ],
        ];
    }

    /**
     * @dataProvider postMergeProvider
     */
    public function testPostMerge($original, $expected)
    {
        $config = new Config($original);
        $this->processor->onPostMerge($config);
        $this->assertSame($expected, $config->get());
    }

    public function testUnknownKeyThrowsException()
    {
        $config = new Config([
            'foo' => '%bar%',
        ]);
        $this->setExpectedException('SpeedyConfig\KeyException');
        $this->processor->onPostMerge($config);
    }

    public function circularReferenceProvider()
    {
        return [
            [[
                'foo' => '%foo%',
            ]],

            [[
                'foo' => [
                    'bar' => '%foo.bar%',
                ],
            ]],

            [[
                'foo' => [
                    'bar' => '%baz%',
                ],
                'baz' => '%foo.bar%',
            ]],

            [[
                'foo' => [
                    'bar' => [
                        'baz' => [
                            'quo' => '%some.key%',
                        ],
                    ],
                ],
                'some' => [
                    'key' => '%foo.bar.baz.quo%',
                ],
            ]],
        ];
    }

    /**
     * @dataProvider circularReferenceProvider
     */
    public function testCircularReferenceThrowsException($config)
    {
        $config = new Config($config);
        $this->setExpectedException('SpeedyConfig\KeyException');
        $this->processor->onPostMerge($config);
    }

    public function testReferenceToArrayThrowsException()
    {
        $config = new Config([
            'foo' => [
                'bar' => 'value',
            ],
            'bar' => '%foo%',
        ]);
        $this->setExpectedException('SpeedyConfig\KeyException');
        $this->processor->onPostMerge($config);
    }
}
