<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 21/03/2017
 * Time: 09:27
 */

namespace Core42Test\View\Model;


use Core42\View\Model\MailModel;
use PHPUnit\Framework\TestCase;


class MailModelTest extends TestCase
{
    public function testSetHtmlTemplate()
    {
        $mailModel = new MailModel();
        $this->assertSame($mailModel, $mailModel->setHtmlTemplate("test"));
    }

    public function testSetPlainTemplate()
    {
        $mailModel = new MailModel();
        $this->assertSame($mailModel, $mailModel->setPlainTemplate("test"));
    }

    public function testHasTemplateException()
    {
        $this->expectException(\Exception::class);

        $mailModel = new MailModel();
        $mailModel->hasTemplate("test");
    }

    public function testHasTemplate()
    {
        $mailModel = new MailModel();
        $this->assertFalse($mailModel->hasTemplate(MailModel::TYPE_HTML));
    }

    public function testHasHtmlTemplate()
    {
        $mailModel = new MailModel();
        $mailModel->setHtmlTemplate("test");
        $this->assertTrue($mailModel->hasHtmlTemplate());

        $mailModel = new MailModel();
        $this->assertFalse($mailModel->hasHtmlTemplate());
    }

    public function testHasPlainTemplate()
    {
        $mailModel = new MailModel();
        $mailModel->setPlainTemplate("test");
        $this->assertTrue($mailModel->hasPlainTemplate());

        $mailModel = new MailModel();
        $this->assertFalse($mailModel->hasPlainTemplate());
    }

    public function testUseTemplateException()
    {
        $this->expectException(\Exception::class);

        $mailModel = new MailModel();
        $mailModel->useTemplate("test");
    }

    public function testUseHtmlTemplate()
    {
        $mailModel = new MailModel();
        $mailModel->setHtmlTemplate("mail");
        $mailModel->useHtmlTemplate();
        $this->assertSame("mail", $mailModel->getTemplate());
    }

    public function testUsePlainTemplate()
    {
        $mailModel = new MailModel();
        $mailModel->setPlainTemplate("mail");
        $mailModel->usePlainTemplate();
        $this->assertSame("mail", $mailModel->getTemplate());
    }

    public function testUseTemplate()
    {
        $mailModel = new MailModel();
        $mailModel->setPlainTemplate("plain");
        $mailModel->setHtmlTemplate("html");
        $mailModel->useTemplate(MailModel::TYPE_PLAIN);
        $this->assertSame("plain", $mailModel->getTemplate());
        $mailModel->useTemplate(MailModel::TYPE_HTML);
        $this->assertSame("html", $mailModel->getTemplate());
    }
}
