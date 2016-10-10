<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42\Error;

use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Whoops\Util\Misc;

class ErrorHandler
{
    /**
     * @var \Throwable|\Exception
     */
    protected $e;

    /**
     * @var string
     */
    protected static $template;

    public static function setErrorTemplate($template)
    {
        self::$template = $template;
    }

    public static function registerShutdown()
    {
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error && Misc::isLevelFatal($error['type'])) {
                $exception = new \ErrorException(
                    $error['message'],
                    $error['type'],
                    $error['type'],
                    $error['file'],
                    $error['line']
                );
                $self = new self($exception);

                return $self();
            }
        });
    }

    public static function init($e)
    {
        if (!($e instanceof \Throwable) && !($e instanceof \Exception)) {
            return;
        }

        $self = new self($e);

        return $self();
    }

    protected function __construct($e)
    {
        $this->e = $e;
    }

    public function __invoke()
    {
        if (!empty($_SERVER)) {
            foreach (['application/json', 'text/json', 'application/x-json'] as $check) {
                if (strpos($_SERVER['HTTP_ACCEPT'], $check) !== false) {
                    $this->getJsonErrors();

                    return;
                }
            }
        }

        if (DEVELOPMENT_MODE === true) {
            $this->getDisplayErrors();

            return;
        }

        $this->getDisplayTemplate();
    }

    /**
     * @return string
     */
    protected function getDisplayErrors()
    {
        $method = Run::EXCEPTION_HANDLER;
        $whoops = new Run();

        $handler = new PrettyPageHandler();
        $handler->setPageTitle('Error 500');

        $whoops->pushHandler($handler);
        $whoops->$method($this->e);
    }

    /**
     * @return string
     */
    protected function getDisplayTemplate()
    {
        ob_clean();
        $error = $this->e;
        include_once self::$template;
    }

    protected function getJsonErrors()
    {
        if (DEVELOPMENT_MODE === true) {
            $method = Run::EXCEPTION_HANDLER;
            $whoops = new Run();

            $handler = new JsonResponseHandler();
            $handler->addTraceToOutput(true);

            $whoops->pushHandler($handler);
            $whoops->$method($this->e);

            return;
        }

        header('Content-Type: application/json');
        http_response_code(500);

        echo '[]';
    }
}
