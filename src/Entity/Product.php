<?php

namespace Sulu\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Product extends \Sylius\Component\Product\Model\Product
{
    /**
     * @var Collection
     */
    private $medias;

    public function __construct()
    {
        parent::__construct();

        $this->medias = new ArrayCollection();
    }

    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function setMedias(Collection $medias): void
    {
        $this->medias = $medias;
    }
}
