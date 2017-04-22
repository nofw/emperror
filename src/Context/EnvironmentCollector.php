<?php

namespace Nofw\Emperror\Context;

use Nofw\Error\Context;

/**
 * Collects environment variables.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class EnvironmentCollector implements Processor
{
    /**
     * {@inheritdoc}
     */
    public function process(\Throwable $t, array $context): array
    {
        return array_replace_recursive(
            [
                Context::ENVIRONMENT => $_SERVER,
            ],
            $context
        );
    }
}
