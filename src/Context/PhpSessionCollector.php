<?php

namespace Nofw\Emperror\Context;

use Nofw\Error\Context;

/**
 * Collects the session from the PHP $_SESSION global if available.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class PhpSessionCollector implements Processor
{
    /**
     * {@inheritdoc}
     */
    public function process(\Throwable $t, array $context): array
    {
        if (!empty($_SESSION)) {
            return array_replace_recursive(
                [
                    Context::SESSION => $_SESSION,
                ],
                $context
            );
        }

        return $context;
    }
}
