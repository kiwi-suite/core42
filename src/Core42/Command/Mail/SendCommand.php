<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2016 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Mail;

use Core42\Command\AbstractCommand;
use Core42\View\Model\MailModel;
use Zend\Mail\Message;
use Zend\Mime\Mime;
use Zend\Mime\Part;
use Zend\Mime\Message as MimeMessage;
use Zend\View\Renderer\PhpRenderer;

class SendCommand extends AbstractCommand
{
    /**
     * @var Message
     */
    private $mailMessage;

    /**
     * @var MailModel
     */
    private $layout;

    /**
     * @var MailModel
     */
    private $body;

    /**
     * @var array
     */
    protected $attachments = [];

    /**
     * @var array
     */
    private $parts = [];

    /**
     * @var string
     */
    private $subject;

    /**
     *
     */
    protected function init()
    {
        $this->mailMessage = new Message();

        $this->parts = [
            'plain' => [
                'type' => Mime::TYPE_TEXT,
            ],
            'html' => [
                'type' => Mime::TYPE_HTML,
            ],
        ];
    }

    public function setLayout(MailModel $layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param string $email
     * @param null|string $name
     * @return $this
     */
    public function addFrom($email, $name = null)
    {
        $this->mailMessage->addFrom($email, $name);
        return $this;
    }

    /**
     * @param string $email
     * @param null|string $name
     * @return $this
     */
    public function addTo($email, $name = null)
    {
        $this->mailMessage->addTo($email, $name);
        return $this;
    }

    /**
     * @param string $email
     * @param null|string $name
     * @return $this
     */
    public function addCc($email, $name = null)
    {
        $this->mailMessage->addCc($email, $name);
        return $this;
    }

    /**
     * @param string $email
     * @param null|string $name
     * @return $this
     */
    public function addBcc($email, $name = null)
    {
        $this->mailMessage->addBcc($email, $name);
        return $this;
    }

    /**
     * @param string $email
     * @param null|string $name
     * @return $this
     */
    public function addReplyTo($email, $name = null)
    {
        $this->mailMessage->addReplyTo($email, $name);
        return $this;
    }

    /**
     * @param MailModel $body
     * @return $this
     */
    public function setBody(MailModel $body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param array $attachments
     * @return $this
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (!($this->body instanceof MailModel)) {
            $this->addError("body", "invalid body");
            return;
        }

        $config = $this->getServiceManager()->get('config');
        $projectConfig = $config['project'];

        if ($this->layout === null) {
            $this->layout = new MailModel();
            if (!empty($projectConfig['email_layout_html'])) {
                $this->layout->setHtmlTemplate($projectConfig['email_layout_html']);
            }
            if (!empty($projectConfig['email_layout_plain'])) {
                $this->layout->setPlainTemplate($projectConfig['email_layout_plain']);
            }
        }

        if (!$this->layout->hasHtmlTemplate() && !$this->layout->hasPlainTemplate()) {
            $this->addError("layout", "either html or plain layout must be specified");
            return;
        }

        $this->body->setVariables([
            'projectBaseUrl' => $projectConfig['project_base_url'],
            'projectName' => $projectConfig['project_name'],
        ]);

        $this->layout->setVariables([
            'projectBaseUrl' => $projectConfig['project_base_url'],
            'projectName' => $projectConfig['project_name'],
            'subject' => $this->subject,
        ]);

        $this->subject = $projectConfig['email_subject_prefix'] . $this->subject;
        $this->mailMessage->setSubject($this->subject);

        if ($this->mailMessage->getFrom()->count() == 0) {
            if (!empty($projectConfig['email_from'])) {
                $this->mailMessage->addFrom($projectConfig['email_from']);
            } else {
                $this->addError("from", "no from specified");
            }
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function execute()
    {
        $viewResolver = $this->getServiceManager()->get('ViewResolver');

        $phpRenderer = new PhpRenderer();
        $phpRenderer->setResolver($viewResolver);
        $phpRenderer->setHelperPluginManager($this->getServiceManager()->get("ViewHelperManager"));

        $body = new \Zend\Mime\Message();

        $parts = [];
        foreach ($this->parts as $type => $options) {
            if (!$this->body->hasTemplate($type)) {
                continue;
            }
            $this->body->useTemplate($type);
            $this->layout->useTemplate($type);
            $this->layout->setVariable('content', $phpRenderer->render($this->body));

            $part = new Part($phpRenderer->render($this->layout));
            $part->type = $options['type'];
            $part->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
            $part->charset = "UTF-8";
            $parts[$type] = $part;
        }

        $inlineImages = [];
        $attachments = [];

        if (count($this->attachments) > 0) {

            // Attachments
            foreach ($this->attachments as $curAttachment) {

                $isInline = false;

                $attachment = new Part();
                $attachment->encoding = Mime::ENCODING_BASE64;
                $attachment->setDisposition(Mime::DISPOSITION_ATTACHMENT);

                if (is_array($curAttachment)) {
                    if (!empty($curAttachment['content'])) {
                        $attachment->setContent($curAttachment['content']);
                    } elseif (!empty($curAttachment['file'])) {
                        $attachment->setContent(file_get_contents($curAttachment['file']));
                    } else {
                        continue;
                    }

                    $attachment->type = Mime::TYPE_OCTETSTREAM;
                    if (!empty($curAttachment['type'])) {
                        $attachment->type = $curAttachment['type'];
                    }

                    if (!empty($curAttachment['filename'])) {
                        $attachment->filename = $curAttachment['filename'];
                    } else {
                        $attachment->filename = basename($curAttachment['file']);
                    }

                    if (!empty($curAttachment['inline']) && $curAttachment['inline'] === true) {
                        $attachment->setDisposition(Mime::DISPOSITION_INLINE);
                        $isInline = true;
                    }

                    if (!empty($curAttachment['id'])) {
                        $attachment->setId($curAttachment['id']);
                    }

                } else {
                    $attachment->setContent(file_get_contents($curAttachment));
                    $attachment->type = Mime::TYPE_OCTETSTREAM;
                    $attachment->filename = basename($curAttachment);
                }

                if ($isInline) {
                    $inlineImages[] = $attachment;
                } else {
                    $attachments[] = $attachment;
                }
            }
        }

        // wrap html in part in related part if inline images are used
        if (!empty($parts['html']) && count($inlineImages) > 0) {

            $mimeMessage = new MimeMessage();
            $relatedPart = new Part();
            $relatedPart->type = "multipart/related;\n boundary=\"".$mimeMessage->getMime()->boundary()."\"";

            $mimeMessage->addPart($parts['html']);
            foreach ($inlineImages as $image) {
                $mimeMessage->addPart($image);
            }

            $relatedPart->setContent($mimeMessage->generateMessage());
            $parts['html'] = $relatedPart;
        }

        $contentType = null;

        if (count($attachments) > 0) {

            $contentType = Mime::MULTIPART_MIXED;

            // Create separate alternative parts object
            $alternatives = new MimeMessage();
            $alternatives->setParts($parts);
            $alternativesPart = new Part($alternatives->generateMessage());
            $alternativesPart->type = "multipart/alternative;\n boundary=\"".$alternatives->getMime()->boundary()."\"";

            $body->addPart($alternativesPart);
            foreach ($attachments as $attachment) {
                $body->addPart($attachment);
            }

        } else {

            foreach ($parts as $part) {
                $body->addPart($part);
            }

            if (count($parts) > 1) {
                $contentType = Mime::MULTIPART_ALTERNATIVE;
            }
        }

        if (count($body->getParts()) == 0) {
            return;
        }

        $this->mailMessage->setBody($body);
        $this->mailMessage->setEncoding('UTF-8');

        //content type must be overwritten after "setBody"
        if ($contentType !== null) {
            $this->mailMessage
                ->getHeaders()
                ->get('content-type')
                ->setType($contentType);
        }

        $transport = $this->getServiceManager()->get('Core42\Mail\Transport');
        $transport->send($this->mailMessage);
    }
}
