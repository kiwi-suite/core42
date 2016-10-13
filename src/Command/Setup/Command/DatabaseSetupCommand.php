<?php
namespace Core42\Command\Setup\Command;

use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\ValueGenerator;
use Zend\Console\Prompt\Line;
use Zend\Db\Adapter\Adapter;
use ZF\Console\Route;

class DatabaseSetupCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @return mixed
     */
    protected function execute()
    {
        $this->consoleOutput("<info>Database Setup</info>");

        do {
            try {
                $config = $this->ask();
                $adapter = new Adapter($config);
                $adapter->getCurrentSchema();
                $hasInfos = true;
            } catch (\Exception $e) {
                $this->consoleOutput("<error>Can't connect to database. Please try again</error>");
                $this->consoleOutput("");

                $hasInfos = false;
            }
        } while (!$hasInfos);

        $this->getServiceManager()->setAllowOverride(true);
        $this->getServiceManager()->setService('Db\Master', $adapter);
        $this->getServiceManager()->setAllowOverride(false);

        $config = [
            'db' => [
                'adapters' => [
                    'Db\Master' => [
                        'database'  => $config['database'],
                        'username'  => $config['username'],
                        'password'  => $config['password'],
                        'hostname'  => $config['hostname'],
                    ],
                ],
            ],
        ];

        $valueGenerator = new ValueGenerator();
        $valueGenerator->setValue($config);
        $valueGenerator->setType(ValueGenerator::TYPE_ARRAY_SHORT);

        $filegenerator = new FileGenerator();
        $filegenerator->setBody("return " . $valueGenerator->generate() .  ";" . PHP_EOL);

        $this->consoleOutput("<info>config written to 'config/autoload/local.database.config.php'</info>");
        file_put_contents("config/autoload/local.database.config.php", $filegenerator->generate());
    }

    protected function ask()
    {
        $hostname = Line::prompt(
            'MySQL hostname: ',
            false,
            100
        );

        $username = Line::prompt(
            'MySQL username: ',
            false,
            100
        );

        $password = Line::prompt(
            'MySQL password: ',
            true,
            100
        );

        $database = Line::prompt(
            'MySQL database name: ',
            false,
            100
        );

        $config = [
            'driver'    => 'pdo_mysql',
            'database'  => $database,
            'username'  => $username,
            'password'  => $password,
            'hostname'  => $hostname,
            'options'   => [
                'buffer_results' => false
            ],
            'charset'   => 'utf8',
        ];

        return $config;
    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {
        // TODO: Implement consoleSetup() method.
    }
}
