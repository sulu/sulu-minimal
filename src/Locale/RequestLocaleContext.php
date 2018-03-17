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

    /**
     * @var LocaleProviderInterface
     */
    private $localeProvider;

    public function __construct(RequestStack $requestStack, LocaleProviderInterface $localeProvider)
    {
        $this->requestStack = $requestStack;
        $this->localeProvider = $localeProvider;
    }

    public function getLocaleCode(): string
    {
        $request = $this->requestStack->getMasterRequest();
        if (null === $request) {
            return 'de';
        }

        $localeCode = $request->query->get('locale');
        if (null === $localeCode) {
            return 'de';
        }

        $availableLocalesCodes = $this->localeProvider->getAvailableLocalesCodes();
        if (!in_array($localeCode, $availableLocalesCodes, true)) {
            throw LocaleNotFoundException::notAvailable($localeCode, $availableLocalesCodes);
        }

        return $localeCode;
    }
}
