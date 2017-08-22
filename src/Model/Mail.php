<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
 */

namespace Core42\Model;

use Core42\View\Model\MailModel;
use Zend\Stdlib\ArrayUtils;

/**
 * @method Mail setSubject() setSubject(string $subject)
 * @method string getSubject() getSubject()
 * @method Mail setLayout() setLayout(MailModel $layout)
 * @method MailModel getLayout() getLayout()
 * @method Mail setBody() setBody(MailModel $body)
 * @method MailModel getBody() getBody()
 * @method Mail setFrom() setFrom(string|array $from)
 * @method string|array getFrom() getFrom()
 * @method Mail setTo() setTo(array $to)
 * @method array getTo() getTo()
 *  @method Mail setCc() setCc(array $cc)
 * @method array getCc() getCc()
 * @method Mail setBcc() setBcc(array $bcc)
 * @method array getBcc() getBcc()
 * @method Mail setReplyTo() setReplyTo(array $replyTo)
 * @method array getReplyTo() getReplyTo()
 * @method Mail setAttachments() setAttachments(array $attachments)
 * @method array getAttachments() getAttachments()
 */
class Mail extends AbstractModel
{
    /**
     * @var array
     */
    protected $properties = [
        'subject',
        'layout',
        'body',
        'from',
        'to',
        'cc',
        'bcc',
        'replyTo',
        'attachments',
    ];

    /**
     * @var array
     */
    protected $data = [
        'subject' => '',
        'layout' => null,
        'body' => null,
        'from' => [],
        'to' => [],
        'cc' => [],
        'bcc' => [],
        'replyTo' => [],
        'attachments' => [],
    ];

    /**
     * @param array|string $address
     * @return $this
     */
    public function addTo($address)
    {
        if (!\is_array($this->data['to'])) {
            $this->data['to'] = [];
        }
        $this->data['to'][] = $address;

        return $this;
    }

    /**
     * @param array|string $address
     * @return $this
     */
    public function addCc($address)
    {
        if (!\is_array($this->data['cc'])) {
            $this->data['cc'] = [];
        }
        $this->data['cc'][] = $address;

        return $this;
    }

    /**
     * @param array|string $address
     * @return $this
     */
    public function addBcc($address)
    {
        if (!\is_array($this->data['bcc'])) {
            $this->data['bcc'] = [];
        }
        $this->data['bcc'][] = $address;

        return $this;
    }

    /**
     * @param array|string $address
     * @return $this
     */
    public function addReplyTo($address)
    {
        if (!\is_array($this->data['replyTo'])) {
            $this->data['replyTo'] = [];
        }
        $this->data['replyTo'][] = $address;

        return $this;
    }

    /**
     * @param array|string $attachment
     * @return $this
     */
    public function addAttachment($attachment)
    {
        if (!\is_array($this->data['attachments'])) {
            $this->data['attachments'] = [];
        }
        $this->data['attachments'][] = $attachment;

        return $this;
    }

    /**
     * @param array $config
     * @param bool $enableProjectDefaults
     * @param bool $enableSubjectPrefix
     */
    public function normalizeData(array $config, $enableProjectDefaults, $enableSubjectPrefix)
    {
        $config['email_subject_prefix'] = (\array_key_exists("email_subject_prefix", $config)) ? $config['email_subject_prefix'] : "";
        $config['email_from'] = (\array_key_exists("email_from", $config)) ? $config['email_from'] : "";
        $config['email_layout_html'] = (\array_key_exists("email_layout_html", $config)) ? $config['email_layout_html'] : "";
        $config['email_layout_plain'] = (\array_key_exists("email_layout_plain", $config)) ? $config['email_layout_plain'] : "";
        $config['project_base_url'] = (\array_key_exists("project_base_url", $config)) ? $config['project_base_url'] : "";
        $config['project_name'] = (\array_key_exists("project_name", $config)) ? $config['project_name'] : "";

        $this->normalizeSubject($enableProjectDefaults, $enableSubjectPrefix, $config['email_subject_prefix']);
        $this->normalizeFrom($enableProjectDefaults, $config['email_from']);
        $this->normalizeLayout($enableProjectDefaults, $config);
        $this->normalizeBody($config);
        $this->normalizeTo();
        $this->normalizeCc();
        $this->normalizeBcc();
        $this->normalizeReplyTo();
        $this->normalizeAttachments();
    }

    /**
     *
     * @param mixed $enableProjectDefaults
     * @param mixed $enableSubjectPrefix
     * @param mixed $emailSubjectPrefix
     */
    protected function normalizeSubject($enableProjectDefaults, $enableSubjectPrefix, $emailSubjectPrefix)
    {
        if (!\is_string($this->getSubject())) {
            $this->setSubject("");
        }

        if ($enableProjectDefaults === true && $enableSubjectPrefix === true && !empty($emailSubjectPrefix)) {
            $this->setSubject($emailSubjectPrefix . $this->getSubject());
        }
    }

    /**
     * @param bool $enableProjectDefaults
     * @param array $config
     */
    protected function normalizeLayout($enableProjectDefaults, array $config)
    {
        $emailHtmlTemplate = $config['email_layout_html'];
        $emailPlainTemplate = $config['email_layout_plain'];
        $layout = $this->getLayout();

        if ($enableProjectDefaults === true
            && !($layout instanceof MailModel)
            && !empty($emailHtmlTemplate)
            && !empty($emailPlainTemplate)
        ) {
            $layout = new MailModel();
            if (!empty($emailHtmlTemplate)) {
                $layout->setHtmlTemplate($emailHtmlTemplate);
            }
            if (!empty($emailPlainTemplate)) {
                $layout->setPlainTemplate($emailPlainTemplate);
            }
        }

        if (!($layout instanceof MailModel)) {
            $layout = null;
        } else {
            $layout->setVariables([
                'projectBaseUrl' => $config['project_base_url'],
                'projectName' => $config['project_name'],
            ]);
        }

        $this->setLayout($layout);
    }

    /**
     * @param array $config
     */
    protected function normalizeBody(array $config)
    {
        $body = $this->getBody();

        if (!($body instanceof MailModel)) {
            $body = null;
        } else {
            $body->setVariables([
                'projectBaseUrl' => $config['project_base_url'],
                'projectName' => $config['project_name'],
            ]);
        }

        $this->setBody($body);
    }


    /**
     * @param bool $enableProjectDefaults
     * @param array|string $emailFrom
     */
    protected function normalizeFrom($enableProjectDefaults, $emailFrom)
    {
        if ($enableProjectDefaults === true && empty($this->getFrom())) {
            $this->setFrom($emailFrom);
        }

        if (\is_string($this->getFrom())) {
            return;
        }

        if (\is_array($this->getFrom()) && ArrayUtils::hasStringKeys($this->getFrom())) {
            return;
        }

        $this->setFrom([]);
    }

    /**
     *
     */
    protected function normalizeTo()
    {
        $to = $this->getTo();
        if (!\is_array($to)) {
            $to = [];
        }

        foreach ($to as $key => $toPart) {
            if (\is_string($toPart)) {
                continue;
            }

            if (\is_array($toPart) && ArrayUtils::hasStringKeys($toPart)) {
                continue;
            }

            unset($to[$key]);
        }

        $this->setTo($to);
    }

    /**
     *
     */
    protected function normalizeCc()
    {
        $cc = $this->getCc();
        if (!\is_array($cc)) {
            $cc = [];
        }

        foreach ($cc as $key => $ccPart) {
            if (\is_string($ccPart)) {
                continue;
            }

            if (\is_array($ccPart) && ArrayUtils::hasStringKeys($ccPart)) {
                continue;
            }

            unset($cc[$key]);
        }

        $this->setCc($cc);
    }

    /**
     *
     */
    protected function normalizeBcc()
    {
        $bcc = $this->getBcc();
        if (!\is_array($bcc)) {
            $bcc = [];
        }

        foreach ($bcc as $key => $bccPart) {
            if (\is_string($bccPart)) {
                continue;
            }

            if (\is_array($bccPart) && ArrayUtils::hasStringKeys($bccPart)) {
                continue;
            }

            unset($bcc[$key]);
        }

        $this->setBcc($bcc);
    }

    /**
     *
     */
    protected function normalizeReplyTo()
    {
        $replyTo = $this->getReplyTo();
        if (!\is_array($replyTo)) {
            $replyTo = [];
        }

        foreach ($replyTo as $key => $replyToPart) {
            if (\is_string($replyToPart)) {
                continue;
            }

            if (\is_array($replyToPart) && ArrayUtils::hasStringKeys($replyToPart)) {
                continue;
            }

            unset($replyTo[$key]);
        }

        $this->setReplyTo($replyTo);
    }

    /**
     *
     */
    protected function normalizeAttachments()
    {
        $attachments = $this->getAttachments();
        if (!\is_array($attachments)) {
            $attachments = [];
        }

        foreach ($attachments as $key => $attachment) {
            if (\is_string($attachment) && \file_exists($attachment)) {
                continue;
            }

            if (\is_array($attachment)
                && \array_key_exists('content', $attachment)
                && \array_key_exists('filename', $attachment)
                && \array_key_exists('type', $attachment)
            ) {
                continue;
            }

            unset($attachments[$key]);
        }

        $this->setAttachments($attachments);
    }
}
