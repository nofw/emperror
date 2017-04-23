<?php

namespace Nofw\Emperror\Tests\Processor;

use Nofw\Emperror\Processor;
use Nofw\Emperror\Processor\EnvironmentCollector;
use Nofw\Error\Context;
use PHPUnit\Framework\TestCase;

final class EnvironmentCollectorTest extends TestCase
{
    /**
     * @var EnvironmentCollector
     */
    private $collector;

    public function setUp()
    {
        $this->collector = new EnvironmentCollector();
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
    public function it_collects_environment()
    {
        $_SERVER = [
            'key' => 'value',
        ];

        $expected = [
            Context::ENVIRONMENT => [
                'key' => 'value',
            ],
        ];
        $actual = $this->collector->process(new \Exception(), []);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_does_not_overwrite_already_existing_values()
    {
        $_SERVER = [
            'key' => 'value',
            'key2' => 'value2',
        ];

        $context = [
            Context::ENVIRONMENT => [
                'key' => 'value',
            ],
        ];

        $expected = [
            Context::ENVIRONMENT => [
                'key' => 'value',
                'key2' => 'value2',
            ],
        ];
        $actual = $this->collector->process(new \Exception(), $context);

        $this->assertEquals($expected, $actual);
    }
}
