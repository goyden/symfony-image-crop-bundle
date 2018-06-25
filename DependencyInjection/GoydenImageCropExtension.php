<?php


namespace App\Goyden\ImageCropBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class GoydenImageCropExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $resourcesDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources';
        $configDir = $resourcesDir . DIRECTORY_SEPARATOR . 'config';

        $loader = new YamlFileLoader(
            $container, new FileLocator($configDir)
        );

        $loader->load('services.yaml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $container->getDefinition('goyden_image_crop')->setArgument('$thumbnails', $config['thumbnail']);
    }
}