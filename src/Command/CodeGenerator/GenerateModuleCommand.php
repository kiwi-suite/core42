<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\CodeGenerator;

use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use Zend\Filter\Word\CamelCaseToDash;
use ZF\Console\Route;

class GenerateModuleCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     *
     */
    protected function execute()
    {
        $camelcaseToDashFilter = new CamelCaseToDash();
        $moduleDirectory = 'module/' . strtolower($camelcaseToDashFilter->filter($this->name));

        mkdir($moduleDirectory);
        mkdir($moduleDirectory . '/config');
        mkdir($moduleDirectory . '/data');
        mkdir($moduleDirectory . '/data/migrations');
        mkdir($moduleDirectory . '/data/language');
        mkdir($moduleDirectory . '/view');
        mkdir($moduleDirectory . '/view/' . strtolower($camelcaseToDashFilter->filter($this->name)));
        mkdir($moduleDirectory . '/src');

        file_put_contents(
            $moduleDirectory . '/config/module.config.php',
            <<<EOT
<?php
namespace {$this->name};

return [
    'migration' => [
        'directory'     => [
            __NAMESPACE__ => __DIR__ . '/../data/migrations'
        ],
    ],
];

EOT
        );

        file_put_contents(
            $moduleDirectory . '/src//Module.php',
            <<<EOT
<?php
namespace {$this->name};

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return array_merge(
            require_once __DIR__ . '/../config/module.config.php'
        );
    }
}

EOT
        );

    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {
        $this->setName($route->getMatchedParam("name"));
    }
}
