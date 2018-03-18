<?php

namespace Sulu\Bundle\ProductBundle\Content;

use Sulu\Component\Content\SimpleContentType;

class ProductAttributesContentType extends SimpleContentType
{
    public function __construct()
    {
        parent::__construct('product_attributes', null);
    }

    public function getTemplate()
    {
        return null;
    }
}
