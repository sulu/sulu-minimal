<?php

namespace Sulu\Bundle\ProductBundle\Admin;

use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Admin\Routing\Route;
use Sulu\Bundle\AdminBundle\Navigation\Navigation;
use Sulu\Bundle\AdminBundle\Navigation\NavigationItem;

class SyliusProductAdmin extends Admin
{
    public function __construct($title)
    {
        $this->setNavigation(new Navigation(new NavigationItem($title)));
    }

    public function getRoutes(): array
    {
        return [
            (new Route('sulu_sylius.product_list', '/products/:locale', 'sulu_admin.list'))
                ->addOption('title', 'sulu_sylius.products')
                ->addOption('adapters', ['table'])
                ->addOption('resourceKey', 'products')
                ->addOption('locales', ['de', 'en'])
                ->addAttributeDefault('locale', 'en')
                ->addOption('addRoute', 'sulu_sylius.product_add_form.detail')
                ->addOption('editRoute', 'sulu_sylius.product_edit_form.detail'),
            (new Route('sulu_sylius.product_add_form', '/products/:locale/add', 'sulu_admin.resource_tabs'))
                ->addOption('resourceKey', 'products')
                ->addOption('locales', ['de', 'en']),
            (new Route('sulu_sylius.product_add_form.detail', '/details', 'sulu_admin.form'))
                ->addOption('tabTitle', 'sulu_sylius.product')
                ->addOption('backRoute', 'sulu_sylius.product_list')
                ->addOption('editRoute', 'sulu_sylius.product_edit_form.detail')
                ->setParent('sulu_sylius.product_add_form'),
            (new Route('sulu_sylius.product_edit_form', '/products/:locale/:id', 'sulu_admin.resource_tabs'))
                ->addOption('resourceKey', 'products')
                ->addOption('locales', ['de', 'en']),
            (new Route('sulu_sylius.product_edit_form.detail', '/details', 'sulu_admin.form'))
                ->addOption('tabTitle', 'sulu_sylius.product')
                ->addOption('backRoute', 'sulu_sylius.product_list')
                ->addOption('editRoute', 'sulu_sylius.product_form.detail')
                ->setParent('sulu_sylius.product_edit_form'),
            (new Route('sulu_sylius.attribute_list', '/attributes/:locale', 'sulu_admin.list'))
                ->addOption('title', 'sulu_sylius.attributes')
                ->addOption('adapters', ['table'])
                ->addOption('resourceKey', 'attributes')
                ->addOption('locales', ['de', 'en'])
                ->addAttributeDefault('locale', 'en'),
        ];
    }
}
