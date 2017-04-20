<?php

namespace Nofw\Emperror;

/**
 * Error handler.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class ErrorHandler implements \Nofw\Error\ErrorHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(\Throwable $t, array $context = []): void
    {
        // TODO
    }
}
