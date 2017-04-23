<?php

namespace Nofw\Emperror\Tests;

use Nofw\Emperror\Processor;
use Nofw\Emperror\ErrorHandler;
use Nofw\Error\TestErrorHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

final class ErrorHandlerTest extends TestCase
{
    /**
     * @var ErrorHandler
     */
    private $errorHandler;

    public function setUp(): void
    {
        $this->errorHandler = new ErrorHandler();
    }

    /**
     * @test
     */
    public function it_is_an_error_handler(): void
    {
        $this->assertInstanceOf(\Nofw\Error\ErrorHandler::class, $this->errorHandler);
    }

    /**
     * @test
     */
    public function it_has_a_handler_stack(): void
    {
        $handler = new TestErrorHandler();

        $this->errorHandler->pushHandler($handler);

        $this->assertSame($handler, $this->errorHandler->popHandler());
    }

    /**
     * @test
     */
    public function its_handler_stack_is_actually_a_stack(): void
    {
        $handler1 = new TestErrorHandler();
        $handler2 = new TestErrorHandler();

        $this->errorHandler
            ->pushHandler($handler1)
            ->pushHandler($handler2)
        ;

        $this->assertSame([$handler2, $handler1], $this->errorHandler->getHandlers());
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function it_cannot_pop_from_an_empty_handler_stack(): void
    {
        $this->errorHandler->popHandler();
    }

    /**
     * @test
     */
    public function it_returns_the_handler_stack(): void
    {
        $handler = new TestErrorHandler();

        $this->errorHandler->pushHandler($handler);

        $this->assertSame([$handler], $this->errorHandler->getHandlers());
    }

    /**
     * @test
     */
    public function it_accepts_a_handler_stack(): void
    {
        $handler1 = new TestErrorHandler();
        $handler2 = new TestErrorHandler();

        $this->errorHandler->pushHandler($handler1);

        $this->errorHandler->setHandlers([$handler2]);

        $this->assertSame([$handler2], $this->errorHandler->getHandlers());
    }

    /**
     * @test
     */
    public function it_exposes_a_fluent_interface_when_pushing_handlers(): void
    {
        $handler = new TestErrorHandler();

        $this->assertSame($this->errorHandler, $this->errorHandler->pushHandler($handler));
    }

    /**
     * @test
     */
    public function it_exposes_a_fluent_interface_when_setting_handlers(): void
    {
        $handler = new TestErrorHandler();

        $this->errorHandler->pushHandler($handler);

        $this->assertSame($this->errorHandler, $this->errorHandler->setHandlers([$handler]));
    }

    /**
     * @test
     */
    public function it_has_a_processor_stack(): void
    {
        /** @var Processor|ObjectProphecy $processor */
        $processor = $this->prophesize(Processor::class);
        $processor = $processor->reveal();

        $this->errorHandler->pushProcessor($processor);

        $this->assertSame($processor, $this->errorHandler->popProcessor());
    }

    /**
     * @test
     */
    public function its_processor_stack_is_actually_a_stack(): void
    {
        /** @var Processor|ObjectProphecy $processor1 */
        $processor1 = $this->prophesize(Processor::class);
        $processor1 = $processor1->reveal();

        /** @var Processor|ObjectProphecy $processor2 */
        $processor2 = $this->prophesize(Processor::class);
        $processor2 = $processor2->reveal();

        $this->errorHandler
            ->pushProcessor($processor1)
            ->pushProcessor($processor2)
        ;

        $this->assertSame([$processor2, $processor1], $this->errorHandler->getProcessors());
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function it_cannot_pop_from_an_empty_processor_stack(): void
    {
        $this->errorHandler->popProcessor();
    }

    /**
     * @test
     */
    public function it_returns_the_processor_stack(): void
    {
        /** @var Processor|ObjectProphecy $processor */
        $processor = $this->prophesize(Processor::class);
        $processor = $processor->reveal();

        $this->errorHandler->pushProcessor($processor);

        $this->assertSame([$processor], $this->errorHandler->getProcessors());
    }

    /**
     * @test
     */
    public function it_accepts_a_processor_stack(): void
    {
        /** @var Processor|ObjectProphecy $processor1 */
        $processor1 = $this->prophesize(Processor::class);
        $processor1 = $processor1->reveal();

        /** @var Processor|ObjectProphecy $processor2 */
        $processor2 = $this->prophesize(Processor::class);
        $processor2 = $processor2->reveal();

        $this->errorHandler->pushProcessor($processor1);

        $this->errorHandler->setProcessors([$processor2]);

        $this->assertSame([$processor2], $this->errorHandler->getProcessors());
    }

    /**
     * @test
     */
    public function it_exposes_a_fluent_interface_when_pushing_processors(): void
    {
        /** @var Processor|ObjectProphecy $processor */
        $processor = $this->prophesize(Processor::class);
        $processor = $processor->reveal();

        $this->assertSame($this->errorHandler, $this->errorHandler->pushProcessor($processor));
    }

    /**
     * @test
     */
    public function it_exposes_a_fluent_interface_when_setting_processors(): void
    {
        /** @var Processor|ObjectProphecy $processor */
        $processor = $this->prophesize(Processor::class);
        $processor = $processor->reveal();

        $this->errorHandler->pushProcessor($processor);

        $this->assertSame($this->errorHandler, $this->errorHandler->setProcessors([$processor]));
    }
}
