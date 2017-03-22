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


namespace Core42\View\Helper\Service;

use Core42\Asset\Hash\CommitHashInterface;
use Core42\View\Helper\AssetUrl;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class AssetUrlFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @throws ServiceNotFoundException if unable to resolve the service
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service
     * @throws ContainerException if any other error occurs
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $assetUrl = $container->get('config')['assets']['asset_url'];
        if (empty($assetUrl)) {
            $assetUrl = '/';
        }

        $prependBasePath = $container->get('config')['assets']['prepend_base_path'];
        if ($prependBasePath === true) {
            $request = $container->get('Request');

            if (\is_callable([$request, 'getBasePath'])) {
                $assetUrl = \rtrim($assetUrl, '/');
                $assetUrl .= $request->getBasePath();
            }
        }

        $assetPath = $container->get('config')['assets']['asset_path'];
        if (!empty($assetPath)) {
            $assetUrl = \rtrim($assetUrl, '/');
            $assetUrl .= $assetPath;
        }

        $prependCommit = $container->get('config')['assets']['prepend_commit'];
        if ($prependCommit === true) {
            $assetUrl = $this->appendCommitHash(
                $assetUrl,
                $container->get($container->get('config')['assets']['commit_strategy'])
            );
        }

        $directories = [];
        $assetDirectories = $container->get('config')['assets']['directories'];
        foreach ($assetDirectories as $name => $dir) {
            $directories[$name] = $dir['target'];
        }

        return new AssetUrl(
            \rtrim($assetUrl, '/'),
            $directories
        );
    }

    /**
     * @param string $assetUrl
     * @param CommitHashInterface $commitHash
     * @return string
     */
    protected function appendCommitHash($assetUrl, CommitHashInterface $commitHash)
    {
        $commitHash = $commitHash->getHash();

        if (empty($commitHash)) {
            return $assetUrl;
        }

        $commitHash = '/' . \trim($commitHash, '/');
        $assetUrl = \rtrim($assetUrl, '/');
        return  $assetUrl . $commitHash;
    }
}
