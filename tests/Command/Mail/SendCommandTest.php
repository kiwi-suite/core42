<?php
namespace Core42Test\Command\Mail;

use Core42\Command\Mail\SendCommand;
use Core42\Mail\Transport\Factory;
use Core42\View\Model\MailModel;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\View\HelperPluginManager;
use Zend\View\Resolver\TemplateMapResolver;

class SendCommandTest extends TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $this->serviceManager = new ServiceManager();

        $this->serviceManager->setService(
            'Core42\Mail\Transport',
            Factory::create(['type' => 'null', 'options' => []])
        );

        $templateMaoResolver = new TemplateMapResolver([
            'mail' => __DIR__ . '/../../__files/view/mail.phtml'
        ]);

        $this->serviceManager->setService('ViewResolver', $templateMaoResolver);
        $this->serviceManager->setService('ViewHelperManager', new HelperPluginManager($this->serviceManager));
    }

    /*public function testSuccess()
    {
        $this->serviceManager->setService("config", $this->config);

        $sendCommand = new SendCommand($this->serviceManager);
        $sendCommand->setEnableProjectDefaults(true);
        $sendCommand->setEnableSubjectPrefix(true);

        $layoutModel = new MailModel();
        $layoutModel->setPlainTemplate("mail");
        $sendCommand->setLayout($layoutModel);

        $bodyModel = new MailModel();
        $bodyModel->setPlainTemplate("mail");
        $sendCommand->setBody($bodyModel);

        $sendCommand->setSubject("test");
        $sendCommand->setFrom("kiwi@raum42.at");
        $sendCommand->addTo("kiwi@raum42.at");
        $sendCommand->addCc("kiwi@raum42.at");
        $sendCommand->addBcc("kiwi@raum42.at");
        $sendCommand->addReplyTo("kiwi@raum42.at");
        $sendCommand->addAttachment(__FILE__);
        $sendCommand->addAttachment([
            'content' => 'test',
            'filename' => 'test.txt',
            'type' => 'text/plain',
            'id' => 'kiwi@raum42.at',
        ]);

        $sendCommand->run();

        $this->assertFalse($sendCommand->hasErrors());
    }*/

    /**
     * @dataProvider configProvider
     */
    public function testSetSubject($config, $enableProjectDefaults)
    {
        $this->serviceManager->setService("config", $config);
        $sendCommand = new SendCommand($this->serviceManager);
        $sendCommand->setEnableProjectDefaults($enableProjectDefaults);

        $layoutModel = new MailModel();
        $layoutModel->setPlainTemplate("mail");
        $sendCommand->setLayout($layoutModel);

        $bodyModel = new MailModel();
        $bodyModel->setPlainTemplate("mail");
        $sendCommand->setBody($bodyModel);

        $sendCommand->setSubject("test");
        if ($enableProjectDefaults === true) {
            $sendCommand->setFrom("kiwi@raum42.at");
        } else {
            $sendCommand->addFrom("kiwi@raum42.at");
        }

        $sendCommand->addTo("kiwi@raum42.at");
        $sendCommand->addCc("kiwi@raum42.at");
        $sendCommand->addBcc("kiwi@raum42.at");
        $sendCommand->addReplyTo("kiwi@raum42.at");

        $result = $sendCommand->run();
        $this->assertFalse($sendCommand->hasErrors());
        if ($enableProjectDefaults === true) {
            $this->assertSame($config["project"]["email_subject_prefix"] . "test", $result->getSubject());
        } else {
            $this->assertSame("test", $result->getSubject());
        }

        $this->assertSame($bodyModel, $result->getBody());
        $this->assertSame($layoutModel, $result->getLayout());
        $this->assertSame("kiwi@raum42.at", $result->getFrom());
        $this->assertSame(["kiwi@raum42.at"], $result->getTo());
        $this->assertSame(["kiwi@raum42.at"], $result->getCc());
        $this->assertSame(["kiwi@raum42.at"], $result->getBcc());
        $this->assertSame(["kiwi@raum42.at"], $result->getReplyTo());

        $sendCommand = new SendCommand($this->serviceManager);
        $sendCommand->setEnableProjectDefaults($enableProjectDefaults);
        $sendCommand->setEnableSubjectPrefix(false);

        $layoutModel = new MailModel();
        $layoutModel->setPlainTemplate("mail");
        $sendCommand->setLayout($layoutModel);

        $bodyModel = new MailModel();
        $bodyModel->setPlainTemplate("mail");
        $sendCommand->setBody($bodyModel);

        $sendCommand->setSubject("test");
        $sendCommand->setFrom("kiwi@raum42.at");
        $sendCommand->addTo("payer@raum42.at");

        $result = $sendCommand->run();
        $this->assertFalse($sendCommand->hasErrors());
        $this->assertSame("test", $result->getSubject());
    }

    public function testAttachments()
    {
        $this->serviceManager->setService("config", ['project' => []]);

        $sendCommand = new SendCommand($this->serviceManager);

        $layoutModel = new MailModel();
        $layoutModel->setPlainTemplate("mail");
        $sendCommand->setLayout($layoutModel);

        $bodyModel = new MailModel();
        $bodyModel->setPlainTemplate("mail");
        $sendCommand->setBody($bodyModel);

        $sendCommand->setFrom("kiwi@raum42.at");
        $sendCommand->addTo("kiwi@raum42.at");

        $sendCommand->addAttachment(__FILE__);
        $sendCommand->addAttachment([
            'content' => 'test',
            'filename' => 'test.txt',
            'type' => 'text/plain',
            'id' => 'kiwi@raum42.at',
        ]);

        $result = $sendCommand->run();
        $this->assertSame([
            __FILE__,
            [
                'content' => 'test',
                'filename' => 'test.txt',
                'type' => 'text/plain',
                'id' => 'kiwi@raum42.at',
            ]
        ], $result->getAttachments());

        $sendCommand = new SendCommand($this->serviceManager);

        $layoutModel = new MailModel();
        $layoutModel->setPlainTemplate("mail");
        $sendCommand->setLayout($layoutModel);

        $bodyModel = new MailModel();
        $bodyModel->setPlainTemplate("mail");
        $sendCommand->setBody($bodyModel);

        $sendCommand->setFrom("kiwi@raum42.at");
        $sendCommand->addTo("kiwi@raum42.at");

        $sendCommand->setAttachments([
            __FILE__,
            [
                'content' => 'test',
                'filename' => 'test.txt',
                'type' => 'text/plain',
                'id' => 'kiwi@raum42.at',
            ]
        ]);
        $result = $sendCommand->run();

        $this->assertSame([
            __FILE__,
            [
                'content' => 'test',
                'filename' => 'test.txt',
                'type' => 'text/plain',
                'id' => 'kiwi@raum42.at',
            ]
        ], $result->getAttachments());
    }

    public function testError()
    {
        $this->serviceManager->setService("config", ['project' => []]);

        $sendCommand = new SendCommand($this->serviceManager);
        $sendCommand->run();
        $this->assertArrayHasKey("layout", $sendCommand->getErrors());
        $this->assertArrayHasKey("body", $sendCommand->getErrors());
        $this->assertArrayHasKey("from", $sendCommand->getErrors());
        $this->assertArrayHasKey("to", $sendCommand->getErrors());
    }

    public function configProvider()
    {
        $config1 = [
            'project' => [
                'email_subject_prefix'  => 'Prefix ',
                'email_from'            => 'kiwi@raum42.at',
                'email_layout_html'     => 'mail',
                'email_layout_plain'    => 'mail',
            ],
        ];

        $config2 = [
            'project' => [
                'email_subject_prefix'  => '',
                'email_from'            => '',
                'email_layout_html'     => '',
                'email_layout_plain'    => '',
            ],
        ];

        return [
            [$config1, true],
            [$config1, false],
            [$config2, true],
            [$config2, false],
        ];
    }
}
