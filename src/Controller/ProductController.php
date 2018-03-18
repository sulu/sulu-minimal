<?php

namespace Sulu\Bundle\ProductBundle\Controller;

use Ferrandini\Urlizer;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sulu\Bundle\ProductBundle\Entity\Product;
use Sulu\Component\Rest\ListBuilder\FieldDescriptorInterface;
use Sulu\Component\Rest\ListBuilder\ListRepresentation;
use Sulu\Component\Rest\RequestParametersTrait;
use Sulu\Component\Rest\RestController;
use Sylius\Component\Attribute\Model\AttributeValue;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends RestController implements ClassResourceInterface
{
    use RequestParametersTrait;

    /**
     * @var FieldDescriptorInterface[]
     */
    private $fieldDescriptors;

    public function cgetAction(Request $request)
    {
        $locale = $this->getRequestParameter($request, 'locale', true);

        $restHelper = $this->get('sulu_core.doctrine_rest_helper');
        $factory = $this->get('sulu_core.doctrine_list_builder_factory');

        $listBuilder = $factory->create($this->getParameter('sylius.model.product.class'));
        $restHelper->initializeListBuilder($listBuilder, $this->getFieldDescriptors($locale));

        $idsParameter = $request->get('ids');
        $ids = array_filter(explode(',', $idsParameter));
        if (null !== $idsParameter && 0 === count($ids)) {
            return [];
        }

        if (null !== $idsParameter) {
            $listBuilder->in($this->fieldDescriptors['id'], $ids);
        }

        $listResponse = $listBuilder->execute();

        if (null !== $idsParameter) {
            $comparator = $this->get('sulu_contact.util.index_comparator');
            // the @ is necessary in case of a PHP bug https://bugs.php.net/bug.php?id=50688
            @usort(
                $listResponse,
                function ($a, $b) use ($comparator, $ids) {
                    return $comparator->compare($a['id'], $b['id'], $ids);
                }
            );
        }

        return $this->handleView(
            $this->view(
                new ListRepresentation(
                    $listResponse,
                    'products',
                    'get_products',
                    $request->query->all(),
                    $listBuilder->getCurrentPage(),
                    $listBuilder->getLimit(),
                    $listBuilder->count()
                )
            )
        );
    }

    public function getAction(int $id, Request $request)
    {
        $locale = $this->getRequestParameter($request, 'locale', true);

        $repository = $this->get('sylius.repository.product');

        /** @var Product $product */
        $product = $repository->find($id);
        $product->setCurrentLocale($locale);
        $product->setFallbackLocale('xxx'); // FIXME to disable fallback-locale

        return $this->handleView($this->view($this->serialize($locale, $product)));
    }

    public function postAction(Request $request)
    {
        $locale = $this->getRequestParameter($request, 'locale', true);

        $factory = $this->get('sylius.factory.product');
        $manager = $this->get('sylius.manager.product');

        /** @var Product $product */
        $product = $factory->createNew();
        $product->setCurrentLocale($locale);
        $product->setFallbackLocale('xxx'); // FIXME to disable fallback-locale

        $this->deserialize($request, $product);

        $manager->persist($product);
        $manager->flush();

        return $this->handleView($this->view($this->serialize($locale, $product)));
    }

    public function putAction(int $id, Request $request)
    {
        $locale = $this->getRequestParameter($request, 'locale', true);

        $manager = $this->get('sylius.manager.product');
        $repository = $this->get('sylius.repository.product');

        /** @var Product $product */
        $product = $repository->find($id);
        $product->setCurrentLocale($locale);
        $product->setFallbackLocale('xxx'); // FIXME to disable fallback-locale

        $this->deserialize($request, $product);

        $manager->persist($product);
        $manager->flush();

        return $this->handleView($this->view($this->serialize($locale, $product)));
    }

    public function deleteAction(int $id): Response
    {
        $manager = $this->get('sylius.manager.product');
        $repository = $this->get('sylius.repository.product');

        /** @var Product $product */
        $product = $repository->find($id);

        $manager->remove($product);
        $manager->flush();

        return $this->handleView($this->view([]));
    }

    private function deserialize(Request $request, ProductInterface $product): ProductInterface
    {
        $product->setCode($this->getRequestParameter($request, 'code', true));
        $product->setName($this->getRequestParameter($request, 'name', true));
        $product->setDescription($this->getRequestParameter($request, 'description', false, ''));
        $product->setSlug(Urlizer::urlize($product->getName()));

        foreach ($this->getRequestParameter($request, 'attributes', false, []) as $item) {
            $attribute = $product->getAttributeByCodeAndLocale($item['code']);
            if (!$attribute) {
                $factory = $this->get('sylius.factory.product_attribute_value');
                $repository = $this->get('sylius.repository.product_attribute');

                /** @var AttributeValue $attribute */
                $attribute = $factory->createNew();
                $attribute->setLocaleCode($this->getRequestParameter($request, 'locale'));
                $attribute->setAttribute($repository->find($item['id']));
                $product->addAttribute($attribute);
            }

            if (array_key_exists('value', $item)) {
                $attribute->setValue($item['value']);
            }
        }

        return $product;
    }

    private function serialize(string $locale, ProductInterface $product): array
    {
        $attributes = [];
        foreach ($product->getAttributes() as $attribute) {
            $attributes[] = [
                'code' => $attribute->getCode(),
                'name' => $attribute->getName(),
                'type' => $attribute->getType(),
                'value' => $attribute->getValue(),
                'configuration' => $attribute->getAttribute()->getConfiguration(),
            ];
        }

        return [
            'id' => $product->getId(),
            'locale' => $locale,
            'code' => $product->getCode(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'slug' => $product->getSlug(),
            'attributes' => $attributes,
        ];
    }

    private function getFieldDescriptors(string $locale): array
    {
        $factory = $this->get('sulu_core.list_builder.field_descriptor_factory');

        return $this->fieldDescriptors = $factory->getFieldDescriptorForClass(
            $this->getParameter('sylius.model.product.class'),
            ['locale' => $locale]
        );
    }
}