<?php

namespace Nofw\Emperror\Tests\Context;

use Nofw\Emperror\Context\EnvironmentCollector;
use Nofw\Error\Context;
use PHPUnit\Framework\TestCase;

final class EnvironmentCollectorTest extends TestCase
{
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
