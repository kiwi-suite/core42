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


namespace Core42\Command\Mail;

use Core42\Command\AbstractCommand;
use Core42\View\Model\MailModel;
use Zend\View\Renderer\PhpRenderer;

class SendCommand extends AbstractCommand
{
    /**
     * @var \Swift_Message
     */
    protected $mailMessage;

    /**
     * @var MailModel
     */
    protected $layout;

    /**
     * @var MailModel
     */
    protected $body;

    /**
     * @var array
     */
    protected $attachments = [];

    /**
     * @var array
     */
    protected $parts = [];

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var array
     */
    protected $from = [];

    /**
     * @var array
     */
    protected $to = [];

    /**
     * @var array
     */
    protected $cc = [];

    /**
     * @var array
     */
    protected $bcc = [];

    /**
     * @var array
     */
    protected $replyTo = [];

    /**
     * @var bool
     */
    protected $transaction = false;

    /**
     *
     */
    protected function configure()
    {
        $this->mailMessage = \Swift_Message::newInstance();

        $this->parts = [
            'plain' => [
                'type' => 'text/plain',
            ],
            'html' => [
                'type' => 'text/html',
            ],
        ];
    }

    /**
     * @param MailModel $layout
     * @return $this
     */
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
    public function setFrom($email, $name = null)
    {
        $address = ($name == null) ? $email : [$email => $name];
        $this->from = $address;

        return $this;
    }

    /**
     * @param string $email
     * @param null|string $name
     * @return $this
     */
    public function addFrom($email, $name = null)
    {
        return $this->setFrom($email, $name);
    }

    /**
     * @param string $email
     * @param null|string $name
     * @return $this
     */
    public function addTo($email, $name = null)
    {
        $address = ($name == null) ? $email : [$email => $name];
        $this->to[] = $address;

        return $this;
    }

    /**
     * @param string $email
     * @param null|string $name
     * @return $this
     */
    public function addCc($email, $name = null)
    {
        $address = ($name == null) ? $email : [$email => $name];
        $this->cc[] = $address;

        return $this;
    }

    /**
     * @param string $email
     * @param null|string $name
     * @return $this
     */
    public function addBcc($email, $name = null)
    {
        $address = ($name == null) ? $email : [$email => $name];
        $this->bcc[] = $address;

        return $this;
    }

    /**
     * @param string $email
     * @param null|string $name
     * @return $this
     */
    public function addReplyTo($email, $name = null)
    {
        $address = ($name == null) ? $email : [$email => $name];
        $this->replyTo[] = $address;

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
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * @param $attachment
     * @return $this
     */
    public function addAttachment($attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (!($this->body instanceof MailModel)) {
            $this->addError('body', 'invalid body');

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
            $this->addError('layout', 'either html or plain layout must be specified');

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

        if (empty($this->from)) {
            if (!empty($projectConfig['email_from'])) {
                $this->from = $projectConfig['email_from'];
            } else {
                $this->addError('from', 'no from specified');
            }
        }

        $this->mailMessage->setFrom($this->from);
        $this->mailMessage->setTo($this->to);
        if (!empty($this->cc)) {
            $this->mailMessage->setCc($this->cc);
        }
        if (!empty($this->bcc)) {
            $this->mailMessage->setBcc($this->bcc);
        }
        if (!empty($this->replyTo)) {
            $this->mailMessage->setReplyTo($this->replyTo);
        }
    }

    /**
     * @throws \Exception
     * @return void
     */
    protected function execute()
    {
        $viewResolver = $this->getServiceManager()->get('ViewResolver');

        $phpRenderer = new PhpRenderer();
        $phpRenderer->setResolver($viewResolver);
        $phpRenderer->setHelperPluginManager($this->getServiceManager()->get('ViewHelperManager'));

        foreach ($this->parts as $type => $options) {
            if (!$this->body->hasTemplate($type)) {
                continue;
            }
            $this->body->useTemplate($type);
            $this->layout->useTemplate($type);
            $this->layout->setVariable('content', $phpRenderer->render($this->body));
            $this->mailMessage->addPart($phpRenderer->render($this->layout), $options['type']);
        }

        foreach ($this->attachments as $attachment) {
            if (\is_string($attachment)) {
                $this->mailMessage->attach(\Swift_Attachment::fromPath($attachment));
            } else {
                $embeded = \Swift_EmbeddedFile::newInstance(
                    $attachment['content'],
                    $attachment['filename'],
                    $attachment['type']
                );
                if (isset($attachment['id'])) {
                    $embeded->setId($attachment['id']);
                }

                $cid = $this->mailMessage->embed($embeded);
            }
        }

        $transport = $this->getServiceManager()->get('Core42\Mail\Transport');
        $mailer = \Swift_Mailer::newInstance($transport);
        $mailer->send($this->mailMessage);
    }
}
