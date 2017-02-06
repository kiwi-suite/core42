<?php
namespace Core42\Security\Csp;

use Zend\Filter\Word\DashToCamelCase;
use Zend\Http\PhpEnvironment\Response;

class Csp
{
    /**
     * @var CspOptions
     */
    protected $cspOptions;

    /**
     * @var string
     */
    protected $nonce;

    /**
     * @var array
     */
    protected $types = [
        'font-src',
        'img-src',
        'media-src',
        'object-src',
        'script-src',
        'style-src',
        'default-src',
        'form-action',
        'form-ancestors',
        'plugin-types',
        'child-src',
        'frame-src',
        'frame-ancestors',
    ];

    /**
     * Csp constructor.
     * @param CspOptions $cspOptions
     */
    public function __construct(CspOptions $cspOptions)
    {
        $this->cspOptions = $cspOptions;
    }

    /**
     * @return CspOptions
     */
    public function getCspOptions()
    {
        return $this->cspOptions;
    }

    /**
     * @return string
     */
    public function getNonce()
    {
        if (empty($this->nonce)) {
            $this->generateNonce();
        }

        return $this->nonce;
    }

    /**
     *
     */
    protected function generateNonce()
    {
        $this->nonce = base64_encode(random_bytes(16));
    }

    public function writeHeaders(Response $response)
    {
        $header = $this->compile();
        if (empty($header)) {
            return;
        }

        $response->getHeaders()->addHeaderLine('Content-Security-Policy', $header);
    }

    /**
     * @return string
     */
    protected function compile()
    {
        if ($this->getCspOptions()->getEnable() === false) {
            return "";
        }

        $headers = [];
        $dashToCamelCase = new DashToCamelCase();
        foreach ($this->types as $type) {
            $method = 'get' . ucfirst($dashToCamelCase->filter($type));

            $options = $this->getCspOptions()->{$method}();
            if ($options === false) {
                continue;
            }

            if (!is_array($options)) {
                continue;
            }

            if ($this->getCspOptions()->getNonce() === true && !in_array("'unsafe-inline'", $options)) {
                $options[] = "'nonce-" . $this->getNonce() . "'";
            }

            $headers[] = $type . ' ' . implode(" ", $options);
        }

        return implode("; ", $headers);
    }
}
