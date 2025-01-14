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

namespace CoreShop\Component\Product\Calculator;

use CoreShop\Component\Product\Exception\NoDiscountPriceFoundException;
use CoreShop\Component\Product\Exception\NoRetailPriceFoundException;
use CoreShop\Component\Product\Model\ProductInterface;

final class ProductPriceCalculator implements ProductPriceCalculatorInterface
{
    public function __construct(private ProductRetailPriceCalculatorInterface $retailPriceCalculator, private ProductDiscountPriceCalculatorInterface $discountPriceCalculator, private ProductDiscountCalculatorInterface $discountCalculator)
    {
    }

    public function getPrice(ProductInterface $product, array $context, bool $withDiscount = false): int
    {
        $retailPrice = $this->getRetailPrice($product, $context);
        $price = $retailPrice;

        $discountPrice = $this->getDiscountPrice($product, $context);

        if ($discountPrice > 0 && $discountPrice < $price) {
            $price = $discountPrice;
        }

        if ($withDiscount) {
            $price -= $this->getDiscount($product, $context, $price);
        }

        return $price;
    }

    public function getRetailPrice(ProductInterface $product, array $context): int
    {
        try {
            return $this->retailPriceCalculator->getRetailPrice($product, $context);
        } catch (NoRetailPriceFoundException) {
        }

        return 0;
    }

    public function getDiscountPrice(ProductInterface $product, array $context): int
    {
        try {
            return $this->discountPriceCalculator->getDiscountPrice($product, $context);
        } catch (NoDiscountPriceFoundException) {
        }

        return 0;
    }

    public function getDiscount(ProductInterface $product, array $context, int $price): int
    {
        return $this->discountCalculator->getDiscount($product, $context, $price);
    }
}
