<?php

namespace Nofw\Emperror\Context;

/**
 * Context processor collects context from various sources and filters it.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Processor
{
    /**
     * Process receives the current error and the context and returns a new context.
     *
     * @param \Throwable $t
     * @param array      $context
     *
     * @return array
     */
    public function process(\Throwable $t, array $context): array;
}
