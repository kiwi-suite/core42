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


namespace Core42\Stdlib;

use League\Flysystem\FilesystemInterface;
use League\Flysystem\PluginInterface;

/**
 * Local Symlink plugin.
 *
 * Implements a symlink($symlink, $target) method for Filesystem instances using LocalAdapter.
 */
class Symlink implements PluginInterface
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
        return 'symlink';
    }

    /**
     * Method logic.
     *
     * Creates a symlink.
     *
     * @see http://php.net/manual/en/function.symlink.php Documentation of symlink().
     *
     * @param   string  $target     symlink target
     * @param   string  $symlink    symlink name
     * @return  bool             True on success. False on failure.
     */
    public function handle($target, $symlink)
    {
        $target = $this->filesystem->getAdapter()->applyPathPrefix($target);
        $symlink = $this->filesystem->getAdapter()->applyPathPrefix($symlink);

        return \symlink($target, $symlink);
    }
}
