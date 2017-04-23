<?php

namespace Nofw\Emperror\Tests\Processor;

use Nofw\Emperror\Processor;
use Nofw\Emperror\Processor\PhpSessionCollector;
use Nofw\Error\Context;
use PHPUnit\Framework\TestCase;

final class PhpSessionCollectorTest extends TestCase
{
    /**
     * @var PhpSessionCollector
     */
    private $collector;

    public function setUp()
    {
        $this->collector = new PhpSessionCollector();
    }

    /**
     * @test
     */
    public function it_is_a_processor()
    {
        $this->assertInstanceOf(Processor::class, $this->collector);
    }

    /**
     * @test
     */
    public function it_collects_session_info()
    {
        $_SESSION = [
            'key' => 'value',
        ];

        $expected = [
            Context::SESSION => [
                'key' => 'value',
            ],
        ];
        $actual = $this->collector->process(new \Exception(), []);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_does_not_collect_session_info_if_session_is_empty()
    {
        $_SESSION = [];

        $actual = $this->collector->process(new \Exception(), []);

        $this->assertArrayNotHasKey(Context::SESSION, $actual);
    }

    /**
     * @test
     */
    public function it_does_not_overwrite_already_existing_values()
    {
        $_SESSION = [
            'key' => 'value',
            'key2' => 'value2',
        ];

        $context = [
            Context::SESSION => [
                'key' => 'value',
            ],
        ];

        $expected = [
            Context::SESSION => [
                'key' => 'value',
                'key2' => 'value2',
            ],
        ];
        $actual = $this->collector->process(new \Exception(), $context);

        $this->assertEquals($expected, $actual);
    }
}
