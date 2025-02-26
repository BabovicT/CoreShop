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

namespace CoreShop\Component\Taxation\Model;

use CoreShop\Component\Resource\Model\ResourceInterface;

interface TaxItemInterface extends ResourceInterface
{
    public function getName(): ?string;

    public function setName(?string $name);

    public function getRate(): ?float;

    public function setRate(?float $rate);

    public function getAmount(): int;

    public function setAmount(int $amount);

    public function getTaxRate(): ?TaxRateInterface;

    public function setTaxRate(TaxRateInterface $taxRate);
}
