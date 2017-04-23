<?php

namespace Nofw\Emperror\Processor;

use Nofw\Emperror\Processor;
use Nofw\Error\Context;

/**
 * Collects common application info about the host.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class CommonInfoCollector implements Processor
{
    /**
     * {@inheritdoc}
     */
    public function process(\Throwable $t, array $context): array
    {
        $common = [
            Context::APP => [
                'os' => php_uname(),
                'language' => 'PHP '.phpversion(),
            ],
        ];

        if (($hostname = gethostname()) !== false) {
            $common[Context::APP]['hostname'] = $hostname;
        }

        return array_replace_recursive($common, $context);
    }
}
