<?php

namespace Nofw\Emperror;

use Nofw\Emperror\Context\Processor;

/**
 * Error handler.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class ErrorHandler implements \Nofw\Error\ErrorHandler
{
    private $context = [];

    /**
     * @var ErrorHandler[]
     */
    private $handlers = [];

    /**
     * @var Processor[]
     */
    private $processors = [];

    public function __construct(array $context = [])
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(\Throwable $t, array $context = []): void
    {
        $context = array_replace_recursive($this->context, $context);

        // Process the context
        foreach ($this->processors as $processor) {
            $context = $processor->process($t, $context);
        }

        // Handle the error
        foreach ($this->handlers as $handler) {
            $handler->handle($t, $context);
        }
    }

    /**
     * Pushes a handler on to the stack.
     *
     * @param \Nofw\Error\ErrorHandler $handler
     *
     * @return ErrorHandler
     */
    public function pushHandler(\Nofw\Error\ErrorHandler $handler): self
    {
        array_unshift($this->handlers, $handler);

        return $this;
    }

    /**
     * Pops a handler from the stack.
     *
     * @return \Nofw\Error\ErrorHandler
     *
     * @throws \LogicException If the handler stack is empty
     */
    public function popHandler(): \Nofw\Error\ErrorHandler
    {
        if (empty($this->handlers)) {
            throw new \LogicException('Tried to pop from an empty handler stack.');
        }

        return array_shift($this->handlers);
    }

    /**
     * @return ErrorHandler[]
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * @param ErrorHandler[] $handlers
     *
     * @return ErrorHandler
     */
    public function setHandlers(array $handlers): self
    {
        $this->handlers = [];

        foreach ($handlers as $handler) {
            $this->pushHandler($handler);
        }

        return $this;
    }

    /**
     * Pushes a processor on to the stack.
     *
     * @param Processor $processor
     *
     * @return ErrorHandler
     */
    public function pushProcessor(Processor $processor): self
    {
        array_unshift($this->processors, $processor);

        return $this;
    }

    /**
     * Pops a processor from the stack.
     *
     * @return Processor
     *
     * @throws \LogicException If the processor stack is empty
     */
    public function popProcessor(): Processor
    {
        if (empty($this->processors)) {
            throw new \LogicException('Tried to pop from an empty processor stack.');
        }

        return array_shift($this->processors);
    }

    /**
     * @return Processor[]
     */
    public function getProcessors(): array
    {
        return $this->processors;
    }

    /**
     * @param Processor[] $processors
     *
     * @return ErrorHandler
     */
    public function setProcessors(array $processors): self
    {
        $this->processors = [];

        foreach ($processors as $processor) {
            $this->pushProcessor($processor);
        }

        return $this;
    }
}
