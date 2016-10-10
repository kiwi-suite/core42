<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42\Command\Assets;

use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use Falc\Flysystem\Plugin\Symlink\Local\Symlink;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\Plugin\EmptyDir;
use League\Flysystem\Plugin\ListFiles;
use League\Flysystem\Plugin\ListPaths;
use ZF\Console\Route;

class AssetsCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @var bool
     */
    protected $transaction = false;

    /**
     * @var bool
     */
    private $copy = false;

    /**
     * @var array|null
     */
    private $assetConfig;

    /**
     * @param bool $copy
     * @return $this
     */
    public function setCopy($copy)
    {
        $this->copy = (bool) $copy;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        $config = $this->getServiceManager()->get('config');
        $this->assetConfig = $config['assets'];
        foreach ($this->assetConfig as $name => $config) {
            if (empty($config['target'])) {
                $this->addError('target', "target doesn't exist for asset key '{$name}'");
                continue;
            }
            if (empty($config['source'])) {
                $this->addError('source', "source doesn't exist for asset key '{$name}'");
                continue;
            }

            if (!is_dir($config['source'])) {
                $this->addError('source', "source directory '{$config['source']}' doesn't exists");
            }
        }
    }

    /**
     *
     */
    protected function execute()
    {
        $filesystem = new Filesystem(new Local(getcwd()));
        $filesystem->addPlugin(new Symlink());
        $filesystem->addPlugin(new ListPaths());
        $filesystem->addPlugin(new ListFiles());
        $filesystem->addPlugin(new EmptyDir());

        foreach ($this->assetConfig as $config) {
            $source = $config['source'];
            $target = rtrim($config['target'], DIRECTORY_SEPARATOR);

            $filesystem->createDir(dirname($target));

            if ($this->copy === true) {
                $filesystem->emptyDir($target);

                $files = $filesystem->listFiles($source, true);
                foreach ($files as $fileData) {
                    if ($fileData['type'] == 'dir') {
                        continue;
                    }

                    $dirname = $target . DIRECTORY_SEPARATOR . str_replace($source, '', $fileData['dirname']);
                    $filesystem->createDir($dirname);

                    $filename = $target . DIRECTORY_SEPARATOR . str_replace($source, '', $fileData['path']);
                    $filesystem->copy($fileData['path'], $filename);
                }
                $this->consoleOutput("created directory for '{$source}'");

                continue;
            }

            $filesystem->symlink($source, $target);
            $this->consoleOutput("created symlink for '{$source}' (target '{$target}')");
        }
    }

    /**
     * @param Route $route
     */
    public function consoleSetup(Route $route)
    {
        $this->setCopy($route->getMatchedParam('copy') || $route->getMatchedParam('c'));
    }
}
