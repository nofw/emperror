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
     * @return array
     */
    public function getProcessors(): array
    {
        return $this->processors;
    }

    /**
     * @param array $processors
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
