<?php

namespace Nofw\Emperror\Integration\Whoops;

use Nofw\Error\ErrorHandler;

/**
 * This handler provides integration with Whoops.
 *
 * @see http://filp.github.io/whoops/
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class Handler extends \Whoops\Handler\Handler
{
    /**
     * @var ErrorHandler
     */
    private $errorHandler;

    public function __construct(ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    public function handle(): int
    {
        $exception = $this->getException();

        $this->errorHandler->handle($exception);

        return self::DONE;
    }
}
