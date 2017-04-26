<?php

namespace Nofw\Emperror\Tests\Handler;

use Airbrake\Notifier;
use Nofw\Emperror\Handler\PhpbrakeHandler;
use Nofw\Emperror\Version;
use Nofw\Error\Context;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

final class PhpbrakeHandlerTest extends TestCase
{
    /**
     * @var Notifier|ObjectProphecy
     */
    private $notifier;

    /**
     * @var PhpbrakeHandler
     */
    private $errorHandler;

    public function setUp()
    {
        $this->notifier = $this->prophesize(Notifier::class);

        $this->errorHandler = new PhpbrakeHandler($this->notifier->reveal());
    }

    /**
     * @test
     */
    public function it_includes_errors_and_backtrace_in_the_notice()
    {
        $e = $this->getException();

        $this->notifier->sendNotice(
            Argument::withEntry(
                'errors',
                Argument::withEveryEntry(
                    Argument::allOf(
                        Argument::withEntry('type', get_class($e)),
                        Argument::withEntry('message', 'Message'),
                        Argument::withEntry(
                            'backtrace',
                            Argument::withEveryEntry(
                                Argument::allOf(
                                    Argument::withEntry('file',  Argument::type('string')),
                                    Argument::withEntry('line', Argument::type('int')),
                                    Argument::withEntry('function',  Argument::type('string'))
                                )
                            )
                        )
                    )
                )
            )
        )->shouldBeCalled();

        $this->errorHandler->handle($e);
    }

    /**
     * @test
     */
    public function it_creates_the_context_from_the_app_context()
    {
        $e = $this->getException();

        $context = [
            Context::APP => [
                'os' => 'linux',
                'language' => 'PHP 7.1',
                'version' => '1.0.0',
                'environment' => 'prod',
                'url' => 'https://website.url',
            ],
        ];

        $arguments = [];

        foreach ($context[Context::APP] as $key => $value) {
            $arguments[] = Argument::withEntry($key, $value);
        }

        $this->notifier->sendNotice(
            Argument::withEntry(
                'context',
                Argument::allOf(...$arguments)
            )
        )->shouldBeCalled();

        $this->errorHandler->handle($e, $context);
    }

    /**
     * @test
     */
    public function it_includes_notifier_info_in_the_context()
    {
        $e = $this->getException();

        $this->notifier->sendNotice(
            Argument::withEntry(
                'context',
                Argument::withEntry(
                    'notifier',
                    [
                        'name' => 'emperror',
                        'version' => Version::VERSION,
                        'url' => 'https://github.com/nofw/emperror',
                    ]
                )
            )
        )->shouldBeCalled();

        $this->errorHandler->handle($e);
    }

    /**
     * @test
     */
    public function it_renames_root_dir_to_rootDirectory()
    {
        $e = $this->getException();

        $this->notifier->sendNotice(
            Argument::withEntry(
                'context',
                Argument::withEntry(
                    'rootDirectory',
                    'path/to/root'
                )
            )
        )->shouldBeCalled();

        $this->errorHandler->handle(
            $e,
            [
                Context::APP => [
                    'root_dir' => 'path/to/root',
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function it_includes_user_info_in_the_context()
    {
        $e = $this->getException();

        $context = [
            Context::USER => [
                'id' => 1234,
                'email' => 'john@doe.com',
            ],
        ];

        $this->notifier->sendNotice(
            Argument::withEntry(
                'context',
                Argument::withEntry(
                    'user',
                    $context[Context::USER]
                )
            )
        )->shouldBeCalled();

        $this->errorHandler->handle($e, $context);
    }

    /**
     * @test
     */
    public function it_includes_environment_variables_in_the_notice()
    {
        $e = $this->getException();

        $context = [
            Context::ENVIRONMENT => [
                'USER' => 'www-data',
            ],
        ];

        $this->notifier->sendNotice(
            Argument::withEntry(
                'environment',
                $context[Context::ENVIRONMENT]
            )
        )->shouldBeCalled();

        $this->errorHandler->handle($e, $context);
    }

    /**
     * @test
     */
    public function it_includes_the_session_in_the_notice()
    {
        $e = $this->getException();

        $context = [
            Context::SESSION => [
                'user_id' => 1234,
            ],
        ];

        $this->notifier->sendNotice(
            Argument::withEntry(
                'session',
                $context[Context::SESSION]
            )
        )->shouldBeCalled();

        $this->errorHandler->handle($e, $context);
    }

    /**
     * @test
     */
    public function it_includes_the_parameters_in_the_notice()
    {
        $e = $this->getException();

        $context = [
            Context::PARAMETERS => [
                'important_parameter' => 1234,
            ],
        ];

        $this->notifier->sendNotice(
            Argument::withEntry(
                'params',
                $context[Context::PARAMETERS]
            )
        )->shouldBeCalled();

        $this->errorHandler->handle($e, $context);
    }

    /**
     * @test
     */
    public function it_includes_the_request_in_the_notice()
    {
        $e = $this->getException();

        $context = [
            Context::REQUEST => [
                'page' => 1,
            ],
        ];

        $this->notifier->sendNotice(
            Argument::withEntry(
                'params',
                $context[Context::REQUEST]
            )
        )->shouldBeCalled();

        $this->errorHandler->handle($e, $context);
    }

    /**
     * @test
     */
    public function it_includes_the_request_and_the_parameters_in_the_notice_under_the_same_key()
    {
        $e = $this->getException();

        $context = [
            Context::PARAMETERS => [
                'important_parameter' => 1234,
            ],
            Context::REQUEST => [
                'page' => 1,
            ],
        ];

        $this->notifier->sendNotice(
            Argument::withEntry(
                'params',
                array_merge($context[Context::PARAMETERS], $context[Context::REQUEST])
            )
        )->shouldBeCalled();

        $this->errorHandler->handle($e, $context);
    }

    /**
     * Creates an exception with clean values.
     *
     * Note: this is necessary because this file has the string 'Error' in its name which leads to false positive checks.
     */
    private function getException(): \Exception
    {
        return new class('Message') extends \Exception {
            protected $file = 'file';
            protected $line = 1;
        };
    }
}
