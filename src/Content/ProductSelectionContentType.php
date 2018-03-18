<?php

namespace Sulu\Bundle\ProductBundle\Content;

use Sulu\Component\Content\SimpleContentType;

class ProductSelectionContentType extends SimpleContentType
{
    public function __construct()
    {
        parent::__construct('product_aelection', []);
    }

    public function getTemplate()
    {
        return null;
    }
}
