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

namespace Core42\View\Helper\Service;

use Core42\View\Helper\AssetUrl;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Json\Json;
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
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $assetUrl = $container->get('Config')['assets']['asset_url'];
        if (empty($assetUrl)) {
            $assetUrl = '/';
        }

        $prependBasePath = $container->get('Config')['assets']['prepend_base_path'];
        if ($prependBasePath === true) {
            $request = $container->get('Request');

            if (is_callable([$request, 'getBasePath'])) {
                $assetUrl = rtrim($assetUrl, '/');
                $assetUrl .= $request->getBasePath();
            }
        }

        $assetPath = $container->get('Config')['assets']['asset_path'];
        if (!empty($assetPath)) {
            $assetUrl = rtrim($assetUrl, '/');
            $assetUrl .= $assetPath;
        }

        $prependCommit = $container->get('Config')['assets']['prepend_commit'];
        if ($prependCommit === true) {
            $assetUrl = $this->appendCommitHash($assetUrl);
        }

        $directories = [];
        $assetDirectories = $container->get('Config')['assets']['directories'];
        foreach ($assetDirectories as $name => $dir) {
            $directories[$name] = $dir['target'];
        }

        return new AssetUrl(
            rtrim($assetUrl, '/'),
            $directories
        );
    }

    /**
     * @param string $assetUrl
     * @return string
     */
    protected function appendCommitHash($assetUrl)
    {
        if (!file_exists('resources/version/revision.json')) {
            return $assetUrl;
        }

        try {
            $revision = Json::decode(file_get_contents('resources/version/revision.json'), Json::TYPE_ARRAY);
        } catch (\Exception $e) {
            return $assetUrl;
        }

        if (empty($revision['revision_hash_short'])) {
            return $assetUrl;
        }

        $assetUrl = rtrim($assetUrl, '/');
        return  $assetUrl . "/v-" . rawurlencode($revision['revision_hash_short']);
    }
}
