<?php
namespace Core42\View\Helper\Service;

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
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $assetUrl = $container->get('Config')['asset_url'];
        if ($assetUrl === null) {
            $request = $container->get('Request');

            if (is_callable([$request, 'getBasePath'])) {
                $assetUrl = $request->getBasePath();
            }
        }

        if (empty($assetUrl)) {
            $assetUrl = "";
        }

        return new AssetUrl(
            $assetUrl
        );
    }
}
