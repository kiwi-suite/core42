<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Assets;

use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use Symfony\Component\Filesystem\Filesystem;
use ZF\Console\Route;

class AssetsCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

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
        $this->copy = (boolean) $copy;

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
        $filesystem = new Filesystem();

        foreach ($this->assetConfig as $name => $config) {
            if ($this->copy === true) {
                $filesystem->mirror($config['source'], $config['target'], null, [
                    'override'          => true,
                    'copy_on_windows'   => true,
                ]);
                $this->consoleOutput("created directory for '{$config['source']}'");

                continue;
            }
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                if ($filesystem->isAbsolutePath($config['target'])) {
                    $source = $config['target'];
                } else {
                    $source = getcwd() . DIRECTORY_SEPARATOR . $config['source'];
                }
            } else {
                $source = $filesystem->makePathRelative(
                    $config['source'],
                    substr($config['target'], 0, strrpos($config['target'], '/'))
                );
            }

            $filesystem->symlink($source, $config['target']);
            $this->consoleOutput("created symlink for '{$config['source']}'");
        }
    }

    /**
     * @param Route $route
     */
    public function consoleSetup(Route $route)
    {
        $this->setCopy($route->getMatchedParam("copy") || $route->getMatchedParam("c"));
    }
}
