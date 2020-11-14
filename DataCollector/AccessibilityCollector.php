<?php

declare(strict_types=1);

namespace Elao\Bundle\SEOTool\DataCollector;

use Elao\Bundle\SEOTool\Checker\AccessibilityChecker;
use Elao\Bundle\SEOTool\Checker\BrokenLinkChecker;
use Elao\Bundle\SEOTool\Checker\ImageChecker;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class AccessibilityCollector extends DataCollector
{
    /** @var ImageChecker */
    public $imageChecker;

    /** @var AccessibilityChecker */
    public $accessbilityChecker;

    /** @var BrokenLinkChecker */
    public $brokenLinkChecker;

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $this->imageChecker = new ImageChecker(new Crawler((string) $response->getContent(), $request->getUri(), $request->getBaseUrl()));
        $this->accessbilityChecker = new AccessibilityChecker(new Crawler((string) $response->getContent(), $request->getUri(), $request->getBaseUrl()));
        $this->brokenLinkChecker = new BrokenLinkChecker(new Crawler((string) $response->getContent(), $request->getUri(), $request->getBaseUrl()), $request->getUri());

        $this->data = [
            'response' => $response,
            'countAllImages' => $this->imageChecker->countAllImages(),
            'countAltFromImages' => $this->imageChecker->countAltFromImages(),
            'listMissingAltFromImages' => $this->imageChecker->listImagesWhithoutAlt(),
            'listNonExplicitIcons' => $this->imageChecker->listNonExplicitIcons(),
            'countAllIcons' => $this->imageChecker->countIcons(),
            'countAllExplicitIcons' => $this->imageChecker->countExplicitIcons(),
            'isForm' => $this->accessbilityChecker->isForm(),
            'missingAssociatedLabelForInput' => $this->accessbilityChecker->getListMissingForLabelsInForm(),
            'countMissingTextInButtons' => $this->accessbilityChecker->countNonExplicitButtons(),
            'brokenLinks' => $this->brokenLinkChecker->getExternalBrokenLinks(),
        ];
    }

    public function reset(): void
    {
        $this->data = [];
    }

    public function getBrokenLinks(): array
    {
        return $this->data['brokenLinks'];
    }

    public function isForm(): bool
    {
        return $this->data['isForm'];
    }

    public function getCountMissingTextInButtons(): int
    {
        return $this->data['countMissingTextInButtons'];
    }

    public function getMissingAssociatedLabelForInput(): array
    {
        return $this->data['missingAssociatedLabelForInput'];
    }

    public function listMissingAltFromImages(): array
    {
        return $this->data['listMissingAltFromImages'];
    }

    public function listNonExplicitIcons(): array
    {
        return $this->data['listNonExplicitIcons'];
    }

    public function getCountAllIcons(): int
    {
        return $this->data['countAllIcons'];
    }

    public function getCountAllExplicitIcons(): int
    {
        return $this->data['countAllExplicitIcons'];
    }

    public function getTitle(): ?string
    {
        return $this->data['title'];
    }

    public function getH1(): ?string
    {
        return $this->data['h1'];
    }

    public function getCountAllImages(): int
    {
        return $this->data['countAllImages'];
    }

    public function getCountAltFromImages(): int
    {
        return $this->data['countAltFromImages'];
    }

    public function getName(): string
    {
        return 'app.accessibility_collector';
    }
}