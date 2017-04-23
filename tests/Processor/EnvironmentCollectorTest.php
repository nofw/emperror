<?php

namespace Nofw\Emperror\Tests\Processor;

use Nofw\Emperror\Processor;
use Nofw\Emperror\Processor\EnvironmentCollector;
use Nofw\Error\Context;
use PHPUnit\Framework\TestCase;

final class EnvironmentCollectorTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_a_processor()
    {
        $collector = new EnvironmentCollector();

        $this->assertInstanceOf(Processor::class, $collector);
    }

    /**
     * @test
     */
    public function it_collects_environment()
    {
        $_SERVER = [
            'key' => 'value',
        ];

        $collector = new EnvironmentCollector();

        $expected = [
            Context::ENVIRONMENT => [
                'key' => 'value',
            ],
        ];
        $actual = $collector->process(new \Exception(), []);

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

        $collector = new EnvironmentCollector();

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
        $actual = $collector->process(new \Exception(), $context);

        $this->assertEquals($expected, $actual);
    }
}
