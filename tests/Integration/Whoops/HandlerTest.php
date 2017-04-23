<?php

namespace Nofw\Foundation\Tests\Whoops\Handler;

use Nofw\Emperror\Integration\Whoops\Handler;
use Nofw\Error\TestErrorHandler;
use PHPUnit\Framework\TestCase;
use Whoops\Handler\HandlerInterface;

final class HandlerTest extends TestCase
{
    /**
     * @var TestErrorHandler
     */
    private $errorHandler;

    /**
     * @var Handler
     */
    private $handler;

    public function setUp(): void
    {
        $this->errorHandler = new TestErrorHandler();
        $this->handler = new Handler($this->errorHandler);
    }

    /**
     * @test
     */
    public function it_is_a_handler(): void
    {
        $this->assertInstanceOf(HandlerInterface::class, $this->handler);
    }

    /**
     * @test
     */
    public function it_handles_an_exception(): void
    {
        $e = new \Exception();

        $this->handler->setException($e);

        $result = $this->handler->handle();

        $this->assertEquals(Handler::DONE, $result);
        $this->assertTrue($this->errorHandler->contains($e));
    }
}
