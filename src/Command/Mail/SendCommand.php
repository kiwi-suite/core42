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
use Core42\Model\Mail;
use Core42\View\Model\MailModel;
use Zend\View\Renderer\PhpRenderer;

class SendCommand extends AbstractCommand
{
    /**
     * @var \Swift_Message
     */
    protected $mailMessage;

    /**
     * @var array
     */
    protected $parts = [];

    /**
     * @var bool
     */
    protected $transaction = false;

    /**
     * @var bool
     */
    protected $enableSubjectPrefix = true;

    /**
     * @var bool
     */
    protected $enableProjectDefaults = true;

    /**
     * @var Mail
     */
    protected $mailModel;

    /**
     *
     */
    protected function init()
    {
        $this->mailModel = new Mail();
    }

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

        $config = $this->getServiceManager()->get("config")['project'];

        $this->mailModel->normalizeData(
            $config,
            $this->enableProjectDefaults,
            $this->enableSubjectPrefix
        );
    }

    /**
     * @param MailModel $layout
     * @return $this
     */
    public function setLayout(MailModel $layout)
    {
        $this->mailModel->setLayout($layout);

        return $this;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->mailModel->setSubject($subject);

        return $this;
    }

    /**
     * @param bool $enableSubjectPrefix
     * @return $this
     */
    public function setEnableSubjectPrefix($enableSubjectPrefix)
    {
        $this->enableSubjectPrefix = (bool) $enableSubjectPrefix;

        return $this;
    }

    /**
     * @param $enableProjectDefaults
     * @return $this
     */
    public function setEnableProjectDefaults($enableProjectDefaults)
    {
        $this->enableProjectDefaults = (bool) $enableProjectDefaults;

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
        $this->mailModel->setFrom($address);

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
        $this->mailModel->addTo($address);

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
        $this->mailModel->addCc($address);

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
        $this->mailModel->addBcc($address);

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
        $this->mailModel->addReplyTo($address);

        return $this;
    }

    /**
     * @param MailModel $body
     * @return $this
     */
    public function setBody(MailModel $body)
    {
        $this->mailModel->setBody($body);

        return $this;
    }

    /**
     * @param array $attachments
     * @return $this
     */
    public function setAttachments(array $attachments)
    {
        $this->mailModel->setAttachments($attachments);

        return $this;
    }

    /**
     * @param $attachment
     * @return $this
     */
    public function addAttachment($attachment)
    {
        $this->mailModel->addAttachment($attachment);

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if ($this->mailModel->getLayout() === null) {
            $this->addError("layout", "invalid layout");
        }

        if ($this->mailModel->getBody() === null) {
            $this->addError("body", "invalid body");
        }

        if (empty($this->mailModel->getFrom())) {
            $this->addError("from", "invalid from");
        }

        if (empty($this->mailModel->getTo())) {
            $this->addError("to", "invalid to");
        }
    }

    /**
     * @throws \Exception
     * @return Mail|null
     */
    protected function execute()
    {
        $this->mailMessage->setTo($this->mailModel->getTo());
        $this->mailMessage->setFrom($this->mailModel->getFrom());
        $this->mailMessage->setSubject($this->mailModel->getSubject());
        if (!empty($this->mailModel->getCc())) {
            $this->mailMessage->setCc($this->mailModel->getCc());
        }
        if (!empty($this->mailModel->getBcc())) {
            $this->mailMessage->setBcc($this->mailModel->getBcc());
        }
        if (!empty($this->mailModel->getReplyTo())) {
            $this->mailMessage->setReplyTo($this->mailModel->getReplyTo());
        }

        $viewResolver = $this->getServiceManager()->get('ViewResolver');

        $phpRenderer = new PhpRenderer();
        $phpRenderer->setResolver($viewResolver);
        $phpRenderer->setHelperPluginManager($this->getServiceManager()->get('ViewHelperManager'));

        foreach ($this->parts as $type => $options) {
            if (!$this->mailModel->getBody()->hasTemplate($type)) {
                continue;
            }
            $this->mailModel->getBody()->useTemplate($type);
            $this->mailModel->getLayout()->useTemplate($type);
            $this
                ->mailModel
                ->getLayout()
                ->setVariable('content', $phpRenderer->render($this->mailModel->getBody()));
            $this
                ->mailMessage
                ->addPart($phpRenderer->render($this->mailModel->getLayout()), $options['type']);
        }

        foreach ($this->mailModel->getAttachments() as $attachment) {
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

                $this->mailMessage->embed($embeded);
            }
        }

        $transport = $this->getServiceManager()->get('Core42\Mail\Transport');
        $mailer = \Swift_Mailer::newInstance($transport);
        $sent = $mailer->send($this->mailMessage);

        if ($sent === 0) {
            $this->addError("send", "mail sending failed");
            return null;
        }

        return $this->mailModel;
    }
}
