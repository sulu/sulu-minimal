<?php

namespace Sulu\Bundle\ProductBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ProductExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container)
    {
        if ($container->hasExtension('sulu_admin')) {
            $container->prependExtensionConfig(
                'sulu_admin',
                [
                    'resources' => [
                        'products' => [
                            'form' => ['@ProductBundle/Resources/forms/products.xml'],
                            'list' => '%sylius.model.product.class%',
                        ],
                        'attributes' => [
                            'form' => ['@ProductBundle/Resources/forms/attributes.xml'],
                            'list' => '%sylius.model.product_attribute.class%',
                        ],
                        'options' => [
                            'form' => ['@ProductBundle/Resources/forms/options.xml'],
                            'list' => '%sylius.model.product_option.class%',
                        ],
                    ],
                ]
            );
        }
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.xml');
    }
}
