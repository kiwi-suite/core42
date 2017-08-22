<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
 */

namespace Core42\Command\Setup\Command;

use Core42\Command\AbstractCommand;
use Core42\Command\Assets\AssetsCommand;
use Core42\Command\ConsoleAwareTrait;
use Core42\Stdlib\Symlink;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\ValueGenerator;
use ZF\Console\Route;

class AssetSetupCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @return mixed
     */
    protected function execute()
    {
        $this->getCommand(AssetsCommand::class)->setForce(true)->run();


        $config = [
            'assets' => [
                'asset_path'         => '/assets',
            ],
        ];

        $valueGenerator = new ValueGenerator();
        $valueGenerator->setValue($config);
        $valueGenerator->setType(ValueGenerator::TYPE_ARRAY_SHORT);

        $filegenerator = new FileGenerator();
        $filegenerator->setBody("return " . $valueGenerator->generate() . ";" . PHP_EOL);

        $this->consoleOutput("<info>config written to 'config/autoload/local.assets.config.php'</info>");
        \file_put_contents("config/autoload/local.assets.config.php", $filegenerator->generate());

        $filesystem = new Filesystem(new Local(\getcwd()));
        $filesystem->addPlugin(new Symlink());

        if (!\file_exists('public/assets')) {
            $filesystem->symlink('resources/assets', 'public/assets');
        }
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
