<?php
namespace Core42\Command\Migration;

use Core42\Command\AbstractCommand;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\I18n\Filter\Alnum;

class MakeCommand extends AbstractCommand
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $migrationDirectory;

    /**
     * @var string
     */
    private $filename;

    /**
     * @throws \Exception
     */
    protected function preExecute()
    {
        $config = $this->getServiceManager()->get("Config");
        if (!is_dir($config['migration']['migration_dir'])) {
            mkdir($config['migration']['migration_dir'], 0777, true);
        }
        $this->migrationDirectory = rtrim($config['migration']['migration_dir'], "/") . "/";

        $date = date("YmdHis");

        $this->className = 'Migration' . $date;
        $this->name = $date;
        if (file_exists($this->migrationDirectory . $this->name)) {
            throw new \Exception("migration file already exists");
        }

        $this->filename = $this->migrationDirectory . $this->name . '.php';
    }

    /**
     *
     */
    protected function execute()
    {
        $classGenerator = new ClassGenerator($this->className, "Migrations");
        $classGenerator->addMethod("up", array(
            new ParameterGenerator("migration", '\Core42\Migration\Migration'),
        ));
        $classGenerator->addMethod("down", array(
            new ParameterGenerator("migration", '\Core42\Migration\Migration'),
        ));

        file_put_contents($this->migrationDirectory . $this->name .'.php', "<?php\n" . $classGenerator->generate());
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }
}
