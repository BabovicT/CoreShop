<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) CoreShop GmbH (https://www.coreshop.org)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

declare(strict_types=1);

namespace CoreShop\Component\SEO;

use CoreShop\Component\Registry\PrioritizedServiceRegistryInterface;
use CoreShop\Component\SEO\Extractor\ExtractorInterface;
use CoreShop\Component\SEO\Model\SEOMetadata;
use Pimcore\Twig\Extension\Templating\HeadMeta;
use Pimcore\Twig\Extension\Templating\HeadTitle;

class SEOPresentation implements SEOPresentationInterface
{
    public function __construct(protected HeadMeta $headMeta, protected HeadTitle $headTitle, protected PrioritizedServiceRegistryInterface $extractorRegistry)
    {
    }

    public function updateSeoMetadata($object): void
    {
        $seoMetadata = $this->extractSeoMetaData($object);

        if ($extraProperties = $seoMetadata->getExtraProperties()) {
            foreach ($extraProperties as $key => $value) {
                $this->headMeta->appendProperty($key, $value);
            }
        }

        if ($extraNames = $seoMetadata->getExtraNames()) {
            foreach ($extraNames as $key => $value) {
                $this->headMeta->appendName($key, $value);
            }
        }

        if ($extraHttp = $seoMetadata->getExtraHttp()) {
            foreach ($extraHttp as $key => $value) {
                $this->headMeta->appendHttpEquiv($key, $value);
            }
        }

        if ($seoMetadata->getTitle()) {
            $this->headTitle->set($seoMetadata->getTitle());
        }

        if ($seoMetadata->getMetaDescription()) {
            $this->headMeta->setDescription($seoMetadata->getMetaDescription());
        }
    }

    protected function extractSeoMetaData($object): SEOMetadata
    {
        $seoMetadata = new SEOMetadata();

        /**
         * @var ExtractorInterface $extractor
         */
        foreach ($this->extractorRegistry->all() as $extractor) {
            if ($extractor->supports($object)) {
                $extractor->updateMetadata($object, $seoMetadata);
            }
        }

        return $seoMetadata;
    }
}
