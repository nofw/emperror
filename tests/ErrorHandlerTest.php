<?php

namespace Nofw\Emperror\Tests;

use Nofw\Emperror\Processor;
use Nofw\Emperror\ErrorHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

final class ErrorHandlerTest extends TestCase
{
    /**
     * @var ErrorHandler
     */
    private $errorHandler;

    public function setUp()
    {
        $this->errorHandler = new ErrorHandler();
    }

    /**
     * @test
     */
    public function it_is_an_error_handler()
    {
        $this->assertInstanceOf(\Nofw\Error\ErrorHandler::class, $this->errorHandler);
    }

    /**
     * @test
     */
    public function it_has_a_handler_stack()
    {
        /** @var \Nofw\Error\ErrorHandler|ObjectProphecy $handler */
        $handler = $this->prophesize(\Nofw\Error\ErrorHandler::class);
        $handler = $handler->reveal();

        $this->errorHandler->pushHandler($handler);

        $this->assertSame($handler, $this->errorHandler->popHandler());
    }

    /**
     * @test
     */
    public function its_handler_stack_is_actually_a_stack()
    {
        /** @var \Nofw\Error\ErrorHandler|ObjectProphecy $handler1 */
        $handler1 = $this->prophesize(\Nofw\Error\ErrorHandler::class);
        $handler1 = $handler1->reveal();

        /** @var \Nofw\Error\ErrorHandler|ObjectProphecy $handler2 */
        $handler2 = $this->prophesize(\Nofw\Error\ErrorHandler::class);
        $handler2 = $handler2->reveal();

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
    public function it_cannot_pop_from_an_empty_handler_stack()
    {
        $this->errorHandler->popHandler();
    }

    /**
     * @test
     */
    public function it_returns_the_handler_stack()
    {
        /** @var \Nofw\Error\ErrorHandler|ObjectProphecy $handler */
        $handler = $this->prophesize(\Nofw\Error\ErrorHandler::class);
        $handler = $handler->reveal();

        $this->errorHandler->pushHandler($handler);

        $this->assertSame([$handler], $this->errorHandler->getHandlers());
    }

    /**
     * @test
     */
    public function it_accepts_a_handler_stack()
    {
        /** @var \Nofw\Error\ErrorHandler|ObjectProphecy $handler1 */
        $handler = $this->prophesize(\Nofw\Error\ErrorHandler::class);
        $handler1 = $handler->reveal();

        /** @var \Nofw\Error\ErrorHandler|ObjectProphecy $handler2 */
        $handler2 = $this->prophesize(\Nofw\Error\ErrorHandler::class);
        $handler2 = $handler2->reveal();

        $this->errorHandler->pushHandler($handler1);

        $this->errorHandler->setHandlers([$handler2]);

        $this->assertSame([$handler2], $this->errorHandler->getHandlers());
    }

    /**
     * @test
     */
    public function it_exposes_a_fluent_interface_when_pushing_handlers()
    {
        /** @var \Nofw\Error\ErrorHandler|ObjectProphecy $handler */
        $handler = $this->prophesize(\Nofw\Error\ErrorHandler::class);
        $handler = $handler->reveal();

        $this->assertSame($this->errorHandler, $this->errorHandler->pushHandler($handler));
    }

    /**
     * @test
     */
    public function it_exposes_a_fluent_interface_when_setting_handlers()
    {
        /** @var \Nofw\Error\ErrorHandler|ObjectProphecy $handler */
        $handler = $this->prophesize(\Nofw\Error\ErrorHandler::class);
        $handler = $handler->reveal();

        $this->errorHandler->pushHandler($handler);

        $this->assertSame($this->errorHandler, $this->errorHandler->setHandlers([$handler]));
    }

    /**
     * @test
     */
    public function it_has_a_processor_stack()
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
    public function its_processor_stack_is_actually_a_stack()
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
    public function it_cannot_pop_from_an_empty_processor_stack()
    {
        $this->errorHandler->popProcessor();
    }

    /**
     * @test
     */
    public function it_returns_the_processor_stack()
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
    public function it_accepts_a_processor_stack()
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
    public function it_exposes_a_fluent_interface_when_pushing_processors()
    {
        /** @var Processor|ObjectProphecy $processor */
        $processor = $this->prophesize(Processor::class);
        $processor = $processor->reveal();

        $this->assertSame($this->errorHandler, $this->errorHandler->pushProcessor($processor));
    }

    /**
     * @test
     */
    public function it_exposes_a_fluent_interface_when_setting_processors()
    {
        /** @var Processor|ObjectProphecy $processor */
        $processor = $this->prophesize(Processor::class);
        $processor = $processor->reveal();

        $this->errorHandler->pushProcessor($processor);

        $this->assertSame($this->errorHandler, $this->errorHandler->setProcessors([$processor]));
    }
}
