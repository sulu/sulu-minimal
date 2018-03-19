<?php

namespace Sulu\Bundle\ProductBundle\Controller;

use FOS\RestBundle\Routing\ClassResourceInterface;
use Sulu\Bundle\ProductBundle\Entity\Option;
use Sulu\Component\Rest\ListBuilder\FieldDescriptorInterface;
use Sulu\Component\Rest\ListBuilder\ListRepresentation;
use Sulu\Component\Rest\RequestParametersTrait;
use Sulu\Component\Rest\RestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OptionController extends RestController implements ClassResourceInterface
{
    use RequestParametersTrait;

    /**
     * @var FieldDescriptorInterface[]
     */
    private $fieldDescriptors;

    public function cgetAction(Request $request): Response
    {
        $locale = $this->getRequestParameter($request, 'locale', true);

        $restHelper = $this->get('sulu_core.doctrine_rest_helper');
        $factory = $this->get('sulu_core.doctrine_list_builder_factory');

        $listBuilder = $factory->create($this->getParameter('sylius.model.product_option.class'));
        $restHelper->initializeListBuilder($listBuilder, $this->getFieldDescriptors($locale));

        $idsParameter = $request->get('ids');
        $ids = array_filter(explode(',', $idsParameter));
        if (null !== $idsParameter && 0 === count($ids)) {
            return [];
        }

        if (null !== $idsParameter) {
            $listBuilder->in($this->fieldDescriptors['id'], $ids);
        }

        if ($search = $request->get('search')) {
            $listBuilder->search($search);
            $listBuilder->addSearchField($this->fieldDescriptors['code']);
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
                    'options',
                    'get_options',
                    $request->query->all(),
                    $listBuilder->getCurrentPage(),
                    $listBuilder->getLimit(),
                    $listBuilder->count()
                )
            )
        );
    }

    public function postAction(Request $request): Response
    {
        $locale = $this->getRequestParameter($request, 'locale', true);

        $factory = $this->get('sylius.factory.product_option');
        $repository = $this->get('sylius.repository.product_option');

        /** @var Option $option */
        $option = $factory->createNew();
        $option->setCurrentLocale($locale);
        $option->setFallbackLocale('xxx'); // FIXME deactivate fallback
        $this->deserialize($request, $option);

        $repository->add($option);

        return $this->handleView($this->view($this->serialize($option)));
    }

    public function getAction(int $id, Request $request): Response
    {
        $locale = $this->getRequestParameter($request, 'locale', true);

        $repository = $this->get('sylius.repository.product_option');

        /** @var Option $option */
        $option = $repository->find($id);
        $option->setCurrentLocale($locale);
        $option->setFallbackLocale('xxx'); // FIXME deactivate fallback

        return $this->handleView($this->view($this->serialize($option)));
    }

    public function putAction(int $id, Request $request): Response
    {
        $locale = $this->getRequestParameter($request, 'locale', true);

        $repository = $this->get('sylius.repository.product_option');

        /** @var Option $option */
        $option = $repository->find($id);
        $option->setCurrentLocale($locale);
        $option->setFallbackLocale('xxx'); // FIXME deactivate fallback
        $this->deserialize($request, $option);

        $repository->add($option);

        return $this->handleView($this->view($this->serialize($option)));
    }

    public function deleteAction(int $id): Response
    {
        $repository = $this->get('sylius.repository.product_option');

        /** @var Option $product */
        $product = $repository->find($id);
        $repository->remove($product);

        return $this->handleView($this->view([]));
    }

    private function deserialize(Request $request, Option $option)
    {
        $option->setCode($this->getRequestParameter($request, 'code', true));
        $option->setName($this->getRequestParameter($request, 'name', true));
        $option->setPosition(0);
    }

    private function serialize(Option $option)
    {
        return [
            'id' => $option->getId(),
            'code' => $option->getCode(),
            'name' => $option->getName(),
        ];
    }

    private function getFieldDescriptors(string $locale): array
    {
        $factory = $this->get('sulu_core.list_builder.field_descriptor_factory');

        return $this->fieldDescriptors = $factory->getFieldDescriptorForClass(
            $this->getParameter('sylius.model.product_option.class'),
            ['locale' => $locale]
        );
    }
}
