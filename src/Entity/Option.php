<?php

namespace Sulu\Bundle\ProductBundle\Entity;

use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

class Option extends \Sylius\Component\Product\Model\ProductOption implements AuditableInterface
{
    use AuditableTrait;
}
