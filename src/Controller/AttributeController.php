<?php

namespace Sulu\Bundle\ProductBundle\Controller;

use FOS\RestBundle\Routing\ClassResourceInterface;
use Sulu\Bundle\ProductBundle\Entity\Attribute;
use Sulu\Component\Rest\ListBuilder\FieldDescriptorInterface;
use Sulu\Component\Rest\ListBuilder\ListRepresentation;
use Sulu\Component\Rest\RequestParametersTrait;
use Sulu\Component\Rest\RestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AttributeController extends RestController implements ClassResourceInterface
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

        $listBuilder = $factory->create($this->getParameter('sylius.model.product_attribute.class'));
        $restHelper->initializeListBuilder($listBuilder, $this->getFieldDescriptors($locale));

        $idsParameter = $request->get('ids');
        $ids = array_filter(explode(',', $idsParameter));
        if (null !== $idsParameter && 0 === count($ids)) {
            return [];
        }

        if (null !== $idsParameter) {
            $listBuilder->in($this->fieldDescriptors['id'], $ids);
        }
        $listBuilder->addSelectField($this->fieldDescriptors['configuration']);

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
                    'attributes',
                    'get_attributes',
                    $request->query->all(),
                    $listBuilder->getCurrentPage(),
                    $listBuilder->getLimit(),
                    $listBuilder->count()
                )
            )
        );
    }

    public function getAction(Request $request, string $code): Response
    {
        $repository = $this->get('sylius.repository.product_attribute');

        /** @var Attribute $attribute */
        $attribute = $repository->findOneBy(['code' => $code]);
        $attribute->setCurrentLocale($this->getRequestParameter($request, 'locale', true));

        return $this->handleView(
            $this->view(
                [
                    'id' => $attribute->getId(),
                    'code' => $attribute->getCode(),
                    'name' => $attribute->getName(),
                    'configuration' => $attribute->getConfiguration(),
                ]
            )
        );
    }

    private function getFieldDescriptors(string $locale): array
    {
        $factory = $this->get('sulu_core.list_builder.field_descriptor_factory');

        return $this->fieldDescriptors = $factory->getFieldDescriptorForClass(
            $this->getParameter('sylius.model.product_attribute.class'),
            ['locale' => $locale]
        );
    }
}
