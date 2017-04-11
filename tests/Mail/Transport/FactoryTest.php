<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 29/03/2017
 * Time: 22:41
 */

namespace Core42Test\Mail\Transport;

use Core42\Mail\Transport\Factory;
use Core42\Mail\Transport\FileTransport;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @dataProvider configProvider
     */
    public function testNullMailer($class, $config)
    {
        $this->assertInstanceOf($class, Factory::create($config));
    }

    public function configProvider()
    {
        return [
            [\Swift_NullTransport::class, ['type' => 'null', 'options' => []]],
            [\Swift_NullTransport::class, ['type' => 'null']],
            [\Swift_NullTransport::class, []],
            [\Swift_SmtpTransport::class, ['type' =>
                'smtp', 'options' => [
                'host'              => '',
                'port'              => 25,
                'encryption'        => '',
                'username'          => '',
                'password'          => '',
                'auth_mode'         => '',

            ]]],
            [\Swift_MailTransport::class, ['type' => 'mail',
                'options' => [
                    'extra'             => '',
                ]]],
            [\Swift_SendmailTransport::class, [
                'type' => 'sendmail',
                'options' => [
                    'command'           => '',
                ]]
            ],
            [\Swift_SendmailTransport::class, [
                'type' => 'sendmail',
                'options' => []
            ]],
            [FileTransport::class, ['type' => 'file', 'options' => ['path' => 'test']]]
        ];
    }
}
