<?php

namespace Nofw\Emperror\Tests;

use Nofw\Emperror\Context\Processor;
use Nofw\Emperror\ErrorHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

final class ErrorHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_a_processor_stack()
    {
        /** @var Processor|ObjectProphecy $processor */
        $processor = $this->prophesize(Processor::class);
        $processor = $processor->reveal();

        $errorHandler = new ErrorHandler();

        $errorHandler->pushProcessor($processor);

        $this->assertSame($processor, $errorHandler->popProcessor());
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

        $errorHandler = new ErrorHandler();

        $errorHandler
            ->pushProcessor($processor1)
            ->pushProcessor($processor2)
        ;

        $this->assertSame([$processor2, $processor1], $errorHandler->getProcessors());
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function it_cannot_pop_from_an_empty_processor_stack()
    {
        $errorHandler = new ErrorHandler();

        $errorHandler->popProcessor();
    }

    /**
     * @test
     */
    public function it_returns_the_processor_stack()
    {
        /** @var Processor|ObjectProphecy $processor */
        $processor = $this->prophesize(Processor::class);
        $processor = $processor->reveal();

        $errorHandler = new ErrorHandler();

        $errorHandler->pushProcessor($processor);

        $this->assertSame([$processor], $errorHandler->getProcessors());
    }

    /**
     * @test
     */
    public function it_accepts_a_processor_stack()
    {
        /** @var Processor|ObjectProphecy $processor1 */
        $processor = $this->prophesize(Processor::class);
        $processor1 = $processor->reveal();

        /** @var Processor|ObjectProphecy $processor2 */
        $processor2 = $this->prophesize(Processor::class);
        $processor2 = $processor2->reveal();

        $errorHandler = new ErrorHandler();

        $errorHandler->pushProcessor($processor1);

        $errorHandler->setProcessors([$processor2]);

        $this->assertSame([$processor2], $errorHandler->getProcessors());
    }

    /**
     * @test
     */
    public function it_exposes_a_fluent_interface_when_pushing_processors()
    {
        /** @var Processor|ObjectProphecy $processor */
        $processor = $this->prophesize(Processor::class);
        $processor = $processor->reveal();

        $errorHandler = new ErrorHandler();

        $this->assertSame($errorHandler, $errorHandler->pushProcessor($processor));
    }

    /**
     * @test
     */
    public function it_exposes_a_fluent_interface_when_setting_processors()
    {
        /** @var Processor|ObjectProphecy $processor */
        $processor = $this->prophesize(Processor::class);
        $processor = $processor->reveal();

        $errorHandler = new ErrorHandler();

        $errorHandler->pushProcessor($processor);

        $this->assertSame($errorHandler, $errorHandler->setProcessors([$processor]));
    }
}
