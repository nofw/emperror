<?php

namespace Nofw\Foundation\Tests\Whoops\Handler;

use Nofw\Emperror\Integration\Whoops\Handler;
use Nofw\Error\ErrorHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Whoops\Handler\HandlerInterface;

final class HandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_a_handler()
    {
        /** @var ErrorHandler|ObjectProphecy $errorHandler */
        $errorHandler = $this->prophesize(ErrorHandler::class);
        $handler = new Handler($errorHandler->reveal());

        $this->assertInstanceOf(HandlerInterface::class, $handler);
    }

    /**
     * @test
     */
    public function it_handles_an_exception()
    {
        /** @var ErrorHandler|ObjectProphecy $errorHandler */
        $errorHandler = $this->prophesize(ErrorHandler::class);
        $handler = new Handler($errorHandler->reveal());

        $exception = new \Exception();

        $errorHandler->handle($exception)->shouldBeCalled();

        $handler->setException($exception);

        $result = $handler->handle();

        $this->assertEquals(Handler::DONE, $result);
    }
}
