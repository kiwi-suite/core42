<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */


namespace Core42\Command\Assets;

use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use Core42\Stdlib\DeleteSymlink;
use Core42\Stdlib\IsSymlink;
use Core42\Stdlib\Symlink;
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
     * @var bool
     */
    private $force = false;

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
     * @param bool $force
     * @return $this
     */
    public function setForce($force)
    {
        $this->force = (bool) $force;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        $config = $this->getServiceManager()->get('config');
        $this->assetConfig = $config['assets']['directories'];
        foreach ($this->assetConfig as $name => $config) {
            if (empty($config['target'])) {
                $this->addError('target', "target doesn't exist for asset key '{$name}'");
                continue;
            }
            if (empty($config['source'])) {
                $this->addError('source', "source doesn't exist for asset key '{$name}'");
                continue;
            }

            if (!\is_dir($config['source'])) {
                $this->addError('source', "source directory '{$config['source']}' doesn't exists");
            }
        }

        if ($this->force == false && \file_exists('resources/assets')) {
            $this->consoleOutput(\sprintf("<error>'%s' already exists</error>", 'data/assets'));

            return;
        }


        if (!\is_dir('resources/assets')) {
            $created = \mkdir('resources/assets', 0777, true);
            if ($created === false) {
                $this->addError('directory', "directory 'resources/assets' can't be created");

                return;
            }
        }

        if (!\is_writable('resources/assets')) {
            $this->addError('directory', "directory 'resources/assets' isn't writable");

            return;
        }
    }

    /**
     *
     */
    protected function execute()
    {
        $filesystem = new Filesystem(new Local(\getcwd(), LOCK_EX, Local::SKIP_LINKS));
        $filesystem->addPlugin(new Symlink());
        $filesystem->addPlugin(new ListPaths());
        $filesystem->addPlugin(new ListFiles());
        $filesystem->addPlugin(new EmptyDir());
        $filesystem->addPlugin(new IsSymlink());
        $filesystem->addPlugin(new DeleteSymlink());

        $filesystem->emptyDir('resources/assets');

        foreach ($this->assetConfig as $config) {
            $source = \trim($config['source'], '/');
            $target = 'resources/assets/' . \trim($config['target'], '/');

            if (!\is_dir(\dirname($target))) {
                $filesystem->createDir(\dirname($target));
            }

            if ($this->copy === true) {
                $files = $filesystem->listFiles($source, true);
                foreach ($files as $fileData) {
                    if ($fileData['type'] == 'dir') {
                        continue;
                    }

                    $dirname = $target . '/' . \str_replace($source, '', $fileData['dirname']);
                    $filesystem->createDir($dirname);

                    $filename = $target . '/' . \str_replace($source, '', $fileData['path']);
                    $filesystem->copy($fileData['path'], $filename);
                }
                $this->consoleOutput("created directory for '{$source}'");

                continue;
            }

            if ($filesystem->isSymlink($target)) {
                $filesystem->deleteSymlink($target);
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
        $this->setForce($route->getMatchedParam('force') || $route->getMatchedParam('f'));
    }
}
