<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 28/03/2017
 * Time: 12:08
 */

namespace Core42Test\Model;

use Core42\Model\Mail;
use Core42\View\Model\MailModel;
use PHPUnit\Framework\TestCase;

class MailTest extends TestCase
{
    public function testAddTo()
    {
        $mail = new Mail();
        $mail->addTo("test");
        $this->assertSame(["test"], $mail->getTo());

        $mail = new Mail();
        $mail->setTo("test");
        $mail->addTo("test");
        $this->assertSame(["test"], $mail->getTo());
    }

    public function testAddCc()
    {
        $mail = new Mail();
        $mail->addCc("test");
        $this->assertSame(["test"], $mail->getCc());

        $mail = new Mail();
        $mail->setCc("test");
        $mail->addCc("test");
        $this->assertSame(["test"], $mail->getCc());
    }

    public function testAddBcc()
    {
        $mail = new Mail();
        $mail->addBcc("test");
        $this->assertSame(["test"], $mail->getBcc());

        $mail = new Mail();
        $mail->setBcc("test");
        $mail->addBcc("test");
        $this->assertSame(["test"], $mail->getBcc());
    }

    public function testAddReplyTo()
    {
        $mail = new Mail();
        $mail->addReplyTo("test");
        $this->assertSame(["test"], $mail->getReplyTo());

        $mail = new Mail();
        $mail->setReplyTo("test");
        $mail->addReplyTo("test");
        $this->assertSame(["test"], $mail->getReplyTo());
    }

    public function testAddAttachment()
    {
        $mail = new Mail();
        $mail->addAttachment("test");
        $this->assertSame(["test"], $mail->getAttachments());

        $mail = new Mail();
        $mail->setAttachments("test");
        $mail->addAttachment("test");
        $this->assertSame(["test"], $mail->getAttachments());
    }

    public function testNormalizeSubject()
    {
        $mail = new Mail();
        $mail->setSubject("Test");
        $mail->normalizeData(
            [
                'email_subject_prefix'  => 'kiwi42: ',
            ],
            true,
            true
        );
        $this->assertSame("kiwi42: Test", $mail->getSubject());

        $mail = new Mail();
        $mail->setSubject("Test");
        $mail->normalizeData(
            [
                'email_subject_prefix'  => 'kiwi42: ',
            ],
            true,
            false
        );
        $this->assertSame("Test", $mail->getSubject());

        $mail = new Mail();
        $mail->setSubject("Test");
        $mail->normalizeData(
            [
                'email_subject_prefix'  => 'kiwi42: ',
            ],
            false,
            true
        );
        $this->assertSame("Test", $mail->getSubject());

        $mail = new Mail();
        $mail->setSubject(new \stdClass());
        $mail->normalizeData([], false, false);
        $this->assertSame("", $mail->getSubject());
    }

    public function testNormalizeLayout()
    {
        $mail = new Mail();
        $mail->normalizeData(
            [
                'email_layout_html'     => 'test',
                'email_layout_plain'    => 'test',
            ],
            true,
            false
        );
        $this->assertTrue($mail->getLayout()->hasHtmlTemplate());
        $this->assertTrue($mail->getLayout()->hasPlainTemplate());

        $mail = new Mail();
        $mail->normalizeData(
            [
                'email_layout_html'     => '',
                'email_layout_plain'    => '',
            ],
            true,
            false
        );
        $this->assertNull($mail->getLayout());

        $mail = new Mail();
        $mail->normalizeData(
            [
                'email_layout_html'     => 'test',
                'email_layout_plain'    => 'test',
            ],
            false,
            false
        );
        $this->assertNull($mail->getLayout());

        $layout = new MailModel();
        $mail = new Mail();
        $mail->setLayout($layout);
        $mail->normalizeData(
            [
                'email_layout_html'     => 'test',
                'email_layout_plain'    => 'test',
            ],
            true,
            false
        );
        $this->assertSame($layout, $mail->getLayout());
    }

    public function testNormalizeBody()
    {
        $mail = new Mail();
        $mail->setBody("test");
        $mail->normalizeData([], false, false);
        $this->assertNull($mail->getBody());

        $mailModel = new MailModel();
        $mail = new Mail();
        $mail->setBody($mailModel);
        $mail->normalizeData([], false, false);
        $this->assertSame($mailModel, $mail->getBody());
    }

    public function testNormalizeFrom()
    {
        $mail = new Mail();
        $mail->normalizeData(
            [
                'email_from'            => 'dev@kiwi-suite.com',
            ],
            true,
            false
        );
        $this->assertSame("dev@kiwi-suite.com", $mail->getFrom());

        $mail = new Mail();
        $mail->normalizeData(
            [
                'email_from'            => 'dev@kiwi-suite.com',
            ],
            false,
            false
        );
        $this->assertSame([], $mail->getFrom());

        $mail = new Mail();
        $mail->setFrom("dev@kiwi-suite.com");
        $mail->normalizeData(
            [
                'email_from'            => 'test@kiwi-suite.com',
            ],
            true,
            false
        );
        $this->assertSame("dev@kiwi-suite.com", $mail->getFrom());

        $mail = new Mail();
        $mail->setFrom(["dev@kiwi-suite.com" => "Thomas"]);
        $mail->normalizeData(
            [
                'email_from'            => 'test@kiwi-suite.com',
            ],
            true,
            false
        );
        $this->assertSame(["dev@kiwi-suite.com" => "Thomas"], $mail->getFrom());
    }

    public function testNormalizeTo()
    {
        $mail = new Mail();
        $mail->addTo(["dev@kiwi-suite.com" => "Thomas"]);
        $mail->normalizeData([], false, false);
        $this->assertSame([["dev@kiwi-suite.com" => "Thomas"]], $mail->getTo());

        $mail = new Mail();
        $mail->addTo("dev@kiwi-suite.com");
        $mail->normalizeData([], false, false);
        $this->assertSame(["dev@kiwi-suite.com"], $mail->getTo());

        $mail = new Mail();
        $mail->addTo(new \stdClass());
        $mail->normalizeData([], false, false);
        $this->assertSame([], $mail->getTo());

        $mail = new Mail();
        $mail->setTo(new \stdClass());
        $mail->normalizeData([], false, false);
        $this->assertSame([], $mail->getTo());
    }

    public function testNormalizeCc()
    {
        $mail = new Mail();
        $mail->addCc(["dev@kiwi-suite.com" => "Thomas"]);
        $mail->normalizeData([], false, false);
        $this->assertSame([["dev@kiwi-suite.com" => "Thomas"]], $mail->getCc());

        $mail = new Mail();
        $mail->addCc("dev@kiwi-suite.com");
        $mail->normalizeData([], false, false);
        $this->assertSame(["dev@kiwi-suite.com"], $mail->getCc());

        $mail = new Mail();
        $mail->addCc(new \stdClass());
        $mail->normalizeData([], false, false);
        $this->assertSame([], $mail->getCc());

        $mail = new Mail();
        $mail->setCc(new \stdClass());
        $mail->normalizeData([], false, false);
        $this->assertSame([], $mail->getCc());
    }

    public function testNormalizeBcc()
    {
        $mail = new Mail();
        $mail->addBcc(["dev@kiwi-suite.com" => "Thomas"]);
        $mail->normalizeData([], false, false);
        $this->assertSame([["dev@kiwi-suite.com" => "Thomas"]], $mail->getBcc());

        $mail = new Mail();
        $mail->addBcc("dev@kiwi-suite.com");
        $mail->normalizeData([], false, false);
        $this->assertSame(["dev@kiwi-suite.com"], $mail->getBcc());

        $mail = new Mail();
        $mail->addBcc(new \stdClass());
        $mail->normalizeData([], false, false);
        $this->assertSame([], $mail->getBcc());

        $mail = new Mail();
        $mail->setBcc(new \stdClass());
        $mail->normalizeData([], false, false);
        $this->assertSame([], $mail->getBcc());
    }

    public function testNormalizeReplyTo()
    {
        $mail = new Mail();
        $mail->addReplyTo(["dev@kiwi-suite.com" => "Thomas"]);
        $mail->normalizeData([], false, false);
        $this->assertSame([["dev@kiwi-suite.com" => "Thomas"]], $mail->getReplyTo());

        $mail = new Mail();
        $mail->addReplyTo("dev@kiwi-suite.com");
        $mail->normalizeData([], false, false);
        $this->assertSame(["dev@kiwi-suite.com"], $mail->getReplyTo());

        $mail = new Mail();
        $mail->addReplyTo(new \stdClass());
        $mail->normalizeData([], false, false);
        $this->assertSame([], $mail->getReplyTo());

        $mail = new Mail();
        $mail->setReplyTo(new \stdClass());
        $mail->normalizeData([], false, false);
        $this->assertSame([], $mail->getReplyTo());
    }

    public function testNormalizeAttachments()
    {
        $mail = new Mail();
        $mail->addAttachment(__FILE__);
        $mail->normalizeData([], false, false);
        $this->assertSame([__FILE__], $mail->getAttachments());

        $mail = new Mail();
        $mail->addAttachment("doesnt exist");
        $mail->normalizeData([], false, false);
        $this->assertSame([], $mail->getAttachments());

        $mail = new Mail();
        $mail->addAttachment(["content" => "incomplete array"]);
        $mail->normalizeData([], false, false);
        $this->assertSame([], $mail->getAttachments());

        $mail = new Mail();
        $mail->addAttachment(["type" => "incomplete array"]);
        $mail->normalizeData([], false, false);
        $this->assertSame([], $mail->getAttachments());

        $mail = new Mail();
        $mail->addAttachment(["filename" => "incomplete array"]);
        $mail->normalizeData([], false, false);
        $this->assertSame([], $mail->getAttachments());

        $mail = new Mail();
        $mail->addAttachment(["content" => "content", "filename" => "filename", "type" => "type"]);
        $mail->normalizeData([], false, false);
        $this->assertSame([["content" => "content", "filename" => "filename", "type" => "type"]], $mail->getAttachments());

        $mail = new Mail();
        $mail->setAttachments("test");
        $mail->normalizeData([], false, false);
        $this->assertSame([], $mail->getAttachments());
    }
}
