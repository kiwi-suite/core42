<?php
namespace Core42\Mail\Transport;

class FileTransport implements \Swift_Transport
{
    /** The event dispatcher from the plugin API */
    private $_eventDispatcher;

    /**
     * @var string
     */
    protected $path;

    /**
     * Create a new SendmailTransport with $buf for I/O.
     *
     * @param \Swift_Events_EventDispatcher $dispatcher
     */
    public function __construct(\Swift_Events_EventDispatcher $dispatcher)
    {
        $this->_eventDispatcher = $dispatcher;
    }

    /**
     * @inheritDoc
     */
    public function isStarted()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function start()
    {
        // not used
    }

    /**
     * @inheritDoc
     */
    public function stop()
    {
        // not used
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Send the given Message.
     *
     * @param \Swift_Mime_Message $message
     * @param string[]           $failedRecipients An array of failures by-reference
     * @throws \Swift_TransportException
     * @return int
     */
    public function send(\Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $failedRecipients = (array) $failedRecipients;

        if ($evt = $this->_eventDispatcher->createSendEvent($this, $message)) {
            $this->_eventDispatcher->dispatchEvent($evt, 'beforeSendPerformed');
            if ($evt->bubbleCancelled()) {
                return 0;
            }
        }

        $count = (
            count((array) $message->getTo())
            + count((array) $message->getCc())
            + count((array) $message->getBcc())
        );

        $filename = 'Mail_' . time() . '_' . mt_rand() . '.eml';
        $file     = $this->path . '/' . $filename;
        $email    = $message->toString();

        if (false === file_put_contents($file, $email)) {
            throw new \Swift_TransportException(sprintf(
                'Unable to write mail to file (directory "%s")',
                $this->path
            ));
        }

        if ($evt) {
            $evt->setResult(\Swift_Events_SendEvent::RESULT_SUCCESS);
            $evt->setFailedRecipients($failedRecipients);
            $this->_eventDispatcher->dispatchEvent($evt, 'sendPerformed');
        }

        return $count;
    }

    /**
     * Register a plugin.
     *
     * @param \Swift_Events_EventListener $plugin
     */
    public function registerPlugin(\Swift_Events_EventListener $plugin)
    {
        $this->_eventDispatcher->bindEventListener($plugin);
    }
}
