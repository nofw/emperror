<?php

namespace Nofw\Emperror\Handler;

use Airbrake\Notifier;
use Nofw\Emperror\Version;
use Nofw\Error\Context;

/**
 * PHP Brake (Airbrake) implementation.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class PhpbrakeHandler implements \Nofw\Error\ErrorHandler
{
    /**
     * Notifier details.
     */
    private const NOTIFIER = [
        'name' => 'emperror',
        'version' => Version::VERSION,
        'url' => 'https://github.com/nofw/emperror',
    ];

    /**
     * @var Notifier
     */
    private $notifier;

    public function __construct(Notifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function handle(\Throwable $t, array $context = []): void
    {
        $error = [
            'type' => get_class($t),
            'message' => $t->getMessage(),
            'backtrace' => $this->backtrace($t),
        ];

        $pcontext = $context[Context::APP] ?? [];
        $pcontext['notifier'] = self::NOTIFIER;

        if (isset($pcontext['root_dir'])) {
            $pcontext['rootDirectory'] = $pcontext['root_dir'];

            unset($pcontext['root_dir']);
        }

        if (isset($context[Context::USER])) {
            $pcontext['user'] = $context[Context::USER];
        }

        $notice = [
            'errors' => [$error],
            'context' => $pcontext,
        ];

        if (isset($context[Context::ENVIRONMENT])) {
            $notice['environment'] = $context[Context::ENVIRONMENT];
        }

        if (isset($context[Context::SESSION])) {
            $notice['session'] = $context[Context::SESSION];
        }

        if (isset($context[Context::PARAMETERS])) {
            $notice['params'] = $context[Context::PARAMETERS];
        }

        if (isset($context[Context::REQUEST])) {
            $notice['params'] = array_merge($context[Context::REQUEST], $notice['params'] ?? []);
        }

        $this->notifier->sendNotice($notice);
    }

    /**
     * Creates an Airbrake compatible backtrace representation.
     */
    private function backtrace(\Throwable $t): array
    {
        $backtrace = [];

        $backtrace[] = [
            'file' => $t->getFile(),
            'line' => $t->getLine(),
            'function' => '',
        ];

        $trace = $t->getTrace();

        foreach ($trace as $frame) {
            $func = $frame['function'];

            if (isset($frame['class']) && isset($frame['type'])) {
                $func = $frame['class'].$frame['type'].$func;
            }

            if (count($backtrace) > 0) {
                $backtrace[count($backtrace) - 1]['function'] = $func;
            }

            $backtrace[] = [
                'file' => isset($frame['file']) ? $frame['file'] : '',
                'line' => isset($frame['line']) ? $frame['line'] : 0,
                'function' => '',
            ];
        }

        return $backtrace;
    }
}
