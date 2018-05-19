<?php

namespace App;

/*
 * This file is part of Sulu.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Sulu\Component\HttpKernel\SuluKernel;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**
 * The abstract kernel holds everything that is common between
 * AdminKernel and WebsiteKernel.
 */
abstract class AbstractKernel extends SuluKernel
{
    use MicroKernelTrait;

    const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $context = $this->getContext();
        $contents = require $this->getProjectDir().'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if (isset($envs['all']) || isset($envs[$this->environment]) || isset($envs[$context])) {
                yield new $class();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $container->addResource(new FileResource($this->getProjectDir().'/config/bundles.php'));
        // Feel free to remove the "container.autowiring.strict_mode" parameter
        // if you are using symfony/dependency-injection 4.0+ as it's the default behavior
        $container->setParameter('container.autowiring.strict_mode', true);
        $container->setParameter('container.dumper.inline_class_loader', true);
        $confDir = $this->getProjectDir().'/config';

        $loader->load($confDir.'/{'.$this->getContext().'}/config_'.$this->environment.self::CONFIG_EXTS, 'glob');

        $loader->load($confDir.'/{services}'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}_'.$this->environment.self::CONFIG_EXTS, 'glob');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $context = $this->getContext();
        $confDir = $this->getProjectDir().'/config';

        $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}/' . $context . '/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}/' . $context . '/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}'.self::CONFIG_EXTS, '/', 'glob');
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return dirname($this->rootDir) . DIRECTORY_SEPARATOR
        . 'var' . DIRECTORY_SEPARATOR
        . 'cache' . DIRECTORY_SEPARATOR
        . $this->getContext() . DIRECTORY_SEPARATOR
        . $this->environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return dirname($this->rootDir) . DIRECTORY_SEPARATOR
        . 'var' . DIRECTORY_SEPARATOR
        . 'logs' . DIRECTORY_SEPARATOR
        . $this->getContext();
    }

    protected function getKernelParameters()
    {
        return array_merge(
            parent::getKernelParameters(),
            [
                'kernel.var_dir' => dirname($this->rootDir) . DIRECTORY_SEPARATOR . 'var'
            ]
        );
    }
}
