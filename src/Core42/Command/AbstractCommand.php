<?php
namespace Core42\Command;

abstract class AbstractCommand
{
    
    public static function createCommand()
    {
        $className = get_called_class();
        return new $className;
    }
    
    final protected function __construct(){
        $this->init();
    }
    
    protected function init(){}
    
    final public function run()
    {
        $this->validate();
        $this->preExecute();
        $this->execute();
        $this->postExecute();
    
        return $this;
    }
    
    protected function validate(){}
    
    protected function preExecute(){}
    
    abstract protected function execute();
    
    protected function postExecute(){}
}
