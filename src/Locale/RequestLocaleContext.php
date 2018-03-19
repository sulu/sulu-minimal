<?php

namespace Sulu\Bundle\ProductBundle\Locale;

use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestLocaleContext implements LocaleContextInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getLocaleCode(): string
    {
        $request = $this->requestStack->getMasterRequest();
        if (null === $request) {
            return 'en';
        }

        $localeCode = $request->query->get('locale');
        if (null === $localeCode) {
            return 'en';
        }

        return $localeCode;
    }
}
