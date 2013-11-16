<?php
namespace Core42\Command\Seeding;

use Core42\Command\AbstractCommand;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\I18n\Validator\Alnum;

class MakeCommand extends AbstractCommand
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $className;

    /**
     * @param $name
     * @return \Core42\Command\Seeding\MakeCommand
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    protected function preExecute()
    {
        if (empty($this->name)) {
            throw new \Exception("invalid name");
        }
        $alumnValidator = new Alnum();
        if (!$alumnValidator->isValid($this->name)) {
            throw new \Exception("invalid name");
        }

        $config = $this->getServiceManager()->get("Config");
        if (!is_dir($config['seeding']['seeding_dir'])) {
            mkdir($config['seeding']['seeding_dir'], 0777, true);
        }

        $this->filename = rtrim($config['seeding']['seeding_dir'], "/") . "/" . $this->name .".php";

        if (file_exists($this->filename)) {
            throw new \Exception("file {$this->filename} already exists");
        }

        $this->className = 'Seeding' . ucfirst($this->name);
    }

    protected function execute()
    {
        $classGenerator = new ClassGenerator($this->className, "Seeding");
        $classGenerator->addMethod("seed", array(
            new ParameterGenerator("serviceManager", '\Zend\ServiceManager\ServiceManager')
        ));
        $classGenerator->addMethod("reset", array(
            new ParameterGenerator("serviceManager", '\Zend\ServiceManager\ServiceManager')
        ));

        file_put_contents($this->filename, "<?php\n" . $classGenerator->generate());
    }
}
