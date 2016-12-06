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

namespace Core42\Stdlib;

use League\Flysystem\FilesystemInterface;
use League\Flysystem\PluginInterface;
use Zend\Console\Console;

class DeleteSymlink implements PluginInterface
{
    /**
     * FilesystemInterface instance.
     *
     * @var FilesystemInterface
     */
    protected $filesystem;

    /**
     * Sets the Filesystem instance.
     *
     * @param FilesystemInterface $filesystem
     */
    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Gets the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'deleteSymlink';
    }

    /**
     * Method logic.
     *
     * Deletes a symlink.
     *
     * @param   string  $symlink    Symlink name.
     * @return  boolean             True on success. False on failure.
     */
    public function handle($symlink)
    {
        $symlink = $this->filesystem->getAdapter()->applyPathPrefix($symlink);

        if (!is_link($symlink)) {
            return false;
        }

        if (Console::isWindows() && is_dir($symlink)) {
            return rmdir($symlink);
        } else {
            return unlink($symlink);
        }
    }
}
