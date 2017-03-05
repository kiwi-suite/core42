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

class IsSymlink implements PluginInterface
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
        return 'isSymlink';
    }

    /**
     * Method logic.
     *
     * Tells whether the specified $filename exists and is a symlink.
     *
     * @param   string  $filename   filename
     * @return  bool             True if $filename is a symlink. Else false.
     */
    public function handle($filename)
    {
        $filename = $this->filesystem->getAdapter()->applyPathPrefix($filename);

        return \is_link($filename);
    }
}
