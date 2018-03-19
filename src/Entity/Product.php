<?php

namespace Sulu\Bundle\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

class Product extends \Sylius\Component\Product\Model\Product implements AuditableInterface
{
    use AuditableTrait;

    /**
     * @var Collection
     */
    private $similarProducts;

    /**
     * @var Collection
     */
    private $medias;

    /**
     * @var Collection
     */
    private $tags;

    /**
     * @var Collection
     */
    private $categories;

    public function __construct()
    {
        parent::__construct();

        $this->products = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getSimilarProducts(): Collection
    {
        return $this->similarProducts;
    }

    public function setSimilarProducts(Collection $similarProducts): void
    {
        $this->similarProducts = $similarProducts;
    }

    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function setMedias(Collection $medias): void
    {
        $this->medias = $medias;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setCategories(Collection $categories): void
    {
        $this->categories = $categories;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags(Collection $tags): void
    {
        $this->tags = $tags;
    }
}
