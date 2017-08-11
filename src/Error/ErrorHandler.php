<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */


namespace Core42\Error;

use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Whoops\Util\Misc;
use Zend\ServiceManager\ServiceManager;

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

    /**
     * @var ServiceManager
     */
    protected static $serviceManager;

    /**
     * @param $template
     */
    public static function setErrorTemplate($template)
    {
        self::$template = $template;
    }

    /**
     *
     */
    public static function registerShutdown()
    {
        \register_shutdown_function(function () {
            $error = \error_get_last();
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

    /**
     * @param $e
     */
    public static function init($e)
    {
        if (!($e instanceof \Throwable) && !($e instanceof \Exception)) {
            return;
        }

        $self = new self($e);

        return $self();
    }

    /**
     * @param ServiceManager $serviceManager
     */
    public static function setServiceManager(ServiceManager $serviceManager)
    {
        self::$serviceManager = $serviceManager;
    }

    /**
     * ErrorHandler constructor.
     * @param $e
     */
    protected function __construct($e)
    {
        $this->e = $e;
    }

    /**
     *
     */
    public function __invoke()
    {
        try {
            $this->logErrors();
        } catch (\Throwable $e) {
        } catch (\Exception $e) {
        }

        if (!empty($_SERVER)) {
            foreach (['application/json', 'text/json', 'application/x-json'] as $check) {
                if (!empty($_SERVER['HTTP_ACCEPT']) && \mb_strpos($_SERVER['HTTP_ACCEPT'], $check) !== false) {
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
        \ob_clean();
        \http_response_code(500);
        $error = $this->e;
        include_once self::$template;
    }

    /**
     *
     */
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

        \header('Content-Type: application/json');
        \http_response_code(500);

        echo '[]';
    }

    protected function logErrors()
    {
        if (!(self::$serviceManager instanceof ServiceManager)) {
            return;
        }

        try {
            $logger = self::$serviceManager->get('Logger')->get('error');
            $logger->error($this->e->getMessage() . ' in ' . $this->e->getFile() . ':' . $this->e->getLine());
        } catch (\Exception $e) {
        }
    }
}
