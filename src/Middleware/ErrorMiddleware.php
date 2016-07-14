<?php
namespace Core42\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Stratigility\ErrorMiddlewareInterface;
use Zend\Stratigility\Http\ResponseInterface;

class ErrorMiddleware implements ErrorMiddlewareInterface
{
    /**
     * @var string
     */
    protected $errorTemplate;

    /**
     * @param string $errorTemplate
     * @return $this
     */
    public function setErrorTemplate($errorTemplate)
    {
        $this->errorTemplate = $errorTemplate;

        return $this;
    }

    /**
     * Process an incoming error, along with associated request and response.
     *
     * Accepts an error, a server-side request, and a response instance, and
     * does something with them; if further processing can be done, it can
     * delegate to `$out`.
     *
     * @see MiddlewareInterface
     * @param mixed $error
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke($error, Request $request, Response $response, callable $out = null)
    {
        if (DEVELOPMENT_MODE === true) {
            return $this->showErrors($error, $request);
        }

        return $this->showTemplate($error);
    }

    /**
     * @return ResponseInterface
     */
    protected function showErrors($error, Request $request)
    {
        $acceptTypes = $request->getHeader('accept');
        if (count($acceptTypes) > 0) {
            $acceptType = $acceptTypes[0];
            foreach (['application/json', 'text/json', 'application/x-json'] as $check) {
                if (strpos($acceptType, $check) !== false) {
                    return $this->showJson($error);
                }
            }
        }

        $method = Run::EXCEPTION_HANDLER;
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->allowQuit(false);
        ob_start();
        $whoops->$method($error);
        $response = ob_get_clean();
        return new HtmlResponse($response, 500);
    }

    /**
     * @param $error
     * @return JsonResponse
     */
    protected function showJson(\Exception $error)
    {
        $data = [
            'success' => false,
            'exceptions' => [],
        ];
        $data['exceptions'][] = [
            'exception' => get_class($error),
            'file' => $error->getFile() .':'.$error->getLine(),
            'message' => $error->getFile() .':'.$error->getMessage(),
            'trace' => $error->getFile() .':'.$error->getTraceAsString(),
        ];

        $e = $error->getPrevious();
        while($e) {
            $data['exceptions'][] = [
                'exception' => get_class($e),
                'file' => $error->getFile() .':'.$e->getLine(),
                'message' => $error->getFile() .':'.$e->getMessage(),
                'trace' => $error->getFile() .':'.$e->getTraceAsString(),
            ];

            $e = $e->getPrevious();
        }

        return new JsonResponse($data, 500);
    }

    /**
     * @return ResponseInterface
     */
    protected function showTemplate($error)
    {
        ob_start();
        include_once $this->errorTemplate;
        $response = ob_get_clean();

        return new HtmlResponse($response, 500);
    }
}
