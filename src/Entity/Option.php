<?php

namespace Sulu\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;
use Sylius\Component\Product\Model\ProductOptionValue;

class Option extends \Sylius\Component\Product\Model\ProductOption implements AuditableInterface
{
    use AuditableTrait;

    public function findOrCreateValue(string $code): ProductOptionValue
    {
        foreach ($this->getValues() as $value) {
            if ($value->getCode() === $code) {
                return $value;
            }
        }

        $value = new ProductOptionValue();
        $value->setCode($code);
        $value->setOption($this);
        $this->addValue($value);

        return $value;
    }

    public function setValues(Collection $values)
    {
        $this->values = $values;
    }
}
