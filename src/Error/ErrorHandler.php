<?php
namespace Core42\Error;

use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Whoops\Util\Misc;
use Zend\Http\PhpEnvironment\Request;

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
     * @var Request
     */
    protected $request;

    public static function setErrorTemplate($template)
    {
        self::$template = $template;
    }

    public static function registerShutdown(Request $request = null)
    {
        register_shutdown_function(function() use ($request){
            $error = error_get_last();
            if ($error && Misc::isLevelFatal($error['type'])) {

                $exception = new \ErrorException(
                    $error['message'],
                    $error['type'],
                    $error['type'],
                    $error['file'],
                    $error['line']
                );
                $self = new self($exception, $request);
                return $self();
            }
        });
    }

    public static function init($e, Request $request = null)
    {
        if (!($e instanceof \Throwable) && !($e instanceof \Exception)) {
            return;
        }

        $self = new self($e, $request);
        return $self();
    }

    protected function __construct($e, Request $request = null)
    {
        $this->e = $e;
        $this->request = $request;
    }

    public function __invoke()
    {
        if ($this->request instanceof Request) {
            $acceptTypes = $this->request->getHeader('accept')->getFieldValue();
            foreach (['application/json', 'text/json', 'application/x-json'] as $check) {
                if (strpos($acceptTypes, $check) !== false) {
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
        $handler->setPageTitle("Error 500");

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
        return;
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

        return;
    }
}
