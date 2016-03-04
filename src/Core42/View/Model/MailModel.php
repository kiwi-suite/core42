<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\View\Model;

use Zend\View\Model\ViewModel;

class MailModel extends ViewModel
{
    const TYPE_PLAIN = 'plain';
    const TYPE_HTML = 'html';

    /**
     * @var string
     */
    protected $htmlTemplate;

    /**
     * @var string
     */
    protected $plainTemplate;

    /**
     * @param string $template
     * @return $this;
     */
    public function setHtmlTemplate($template)
    {
        $this->htmlTemplate = $template;
        return $this;
    }

    /**
     * @param string $template
     * @return $this;
     */
    public function setPlainTemplate($template)
    {
        $this->plainTemplate = $template;
        return $this;
    }

    /**
     * @param string $type
     * @throws \Exception
     * @return bool
     */
    public function hasTemplate($type)
    {
        $type = strtolower($type);
        if (!in_array($type, [self::TYPE_HTML, self::TYPE_PLAIN])) {
            throw new \Exception("invalid type '{$type}'");
        }
        $method = "has" . ucfirst($type) . 'Template';
        return $this->{$method}();
    }

    /**
     * @return bool
     */
    public function hasHtmlTemplate()
    {
        return !empty($this->htmlTemplate);
    }

    /**
     * @return bool
     */
    public function hasPlainTemplate()
    {
        return !empty($this->plainTemplate);
    }

    /**
     * @param string $type
     * @throws \Exception
     */
    public function useTemplate($type)
    {
        $type = strtolower($type);
        if (!in_array($type, [self::TYPE_HTML, self::TYPE_PLAIN])) {
            throw new \Exception("invalid type '{$type}'");
        }
        $method = "use" . ucfirst($type) . 'Template';
        $this->{$method}();
    }

    /**
     *
     */
    public function useHtmlTemplate()
    {
        $this->setTemplate($this->htmlTemplate);
    }

    /**
     *
     */
    public function usePlainTemplate()
    {
        $this->setTemplate($this->plainTemplate);
    }
}
