<?php

namespace Nofw\Emperror\Tests\Context;

use Nofw\Emperror\Context\CommonInfoCollector;
use Nofw\Error\Context;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;

final class CommonInfoCollectorTest extends TestCase
{
    use PHPMock;

    /**
     * @test
     */
    public function it_collects_common_info()
    {
        $namespace = substr(CommonInfoCollector::class, 0, strrpos(CommonInfoCollector::class, '\\'));

        $os = $this->getFunctionMock($namespace, 'php_uname');
        $os->expects($this->once())->willReturn('myos');

        $hostname = $this->getFunctionMock($namespace, 'gethostname');
        $hostname->expects($this->once())->willReturn('myhost');

        $phpversion = $this->getFunctionMock($namespace, 'phpversion');
        $phpversion->expects($this->once())->willReturn('7.1.0');

        $collector = new CommonInfoCollector();

        $expected = [
            Context::APP => [
                'os' => 'myos',
                'language' => 'PHP 7.1.0',
                'hostname' => 'myhost',
            ],
        ];
        $actual = $collector->process(new \Exception(), []);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_does_not_include_hostname_if_cannot_be_determined()
    {
        $namespace = substr(CommonInfoCollector::class, 0, strrpos(CommonInfoCollector::class, '\\'));

        $hostname = $this->getFunctionMock($namespace, 'gethostname');
        $hostname->expects($this->once())->willReturn(false);

        $collector = new CommonInfoCollector();

        $context = $collector->process(new \Exception(), []);

        $this->assertArrayNotHasKey('hostname', $context);
    }

    /**
     * @test
     */
    public function it_does_not_overwrite_already_existing_values()
    {

        $collector = new CommonInfoCollector();

        $expected = [
            Context::APP => [
                'os' => 'myos',
                'language' => 'PHP 5.6.0',
                'hostname' => 'myhost',
            ],
        ];
        $actual = $collector->process(new \Exception(), $expected);

        $this->assertEquals($expected, $actual);
    }
}