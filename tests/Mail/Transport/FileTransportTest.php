<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 29/03/2017
 * Time: 12:43
 */

namespace Core42Test\Mail\Transport;

use Core42\Mail\Transport\FileTransport;
use PHPUnit\Framework\TestCase;

class FileTransportTest extends TestCase
{
    protected $mailDir = __DIR__ . '/../../../data/maillog/';
    protected $mailMessage;

    public function setUp()
    {
        @mkdir($this->mailDir);

        $this->mailMessage = new \Swift_Message();
        $this->mailMessage->setFrom("dev@kiwi-suite.com");
        $this->mailMessage->setTo("dev@kiwi-suite.com");
        $this->mailMessage->setSubject("test");
        $this->mailMessage->setBody("Test");
    }

    public function testSend()
    {
        $fileTransport = new FileTransport(new \Swift_Events_SimpleEventDispatcher());
        $fileTransport->setPath($this->mailDir);
        $failedRecipients = [];

        $count = $fileTransport->send($this->mailMessage, $failedRecipients);

        $this->assertSame(1, $count);
    }

    public function testFailToWriteFile()
    {
        $this->expectException(\Swift_TransportException::class);

        $fileTransport = new FileTransport(new \Swift_Events_SimpleEventDispatcher());
        $fileTransport->setPath("doesntexists");
        $failedRecipients = [];

        $fileTransport->send($this->mailMessage, $failedRecipients);
    }

    public function testEmptyMethods()
    {
        $fileTransport = new FileTransport(new \Swift_Events_SimpleEventDispatcher());
        $fileTransport->start();
        $fileTransport->stop();

        $this->assertFalse($fileTransport->isStarted());
    }



    public function tearDown()
    {
        $dir = dir($this->mailDir);
        while (false !== ($entry = $dir->read())) {

            if (is_file($this->mailDir . '/' . $entry)) {
                @unlink($this->mailDir . '/' . $entry);
            }
        }
        @rmdir($this->mailDir);
    }
}
