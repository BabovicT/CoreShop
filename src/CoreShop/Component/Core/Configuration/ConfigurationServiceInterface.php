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

namespace CoreShop\Component\Core\Configuration;

use CoreShop\Component\Configuration\Service\ConfigurationServiceInterface as BaseConfigurationServiceInterface;
use CoreShop\Component\Core\Model\ConfigurationInterface;
use CoreShop\Component\Store\Model\StoreInterface;

interface ConfigurationServiceInterface extends BaseConfigurationServiceInterface
{
    /**
     * @return ConfigurationInterface|mixed|null
     */
    public function getForStore(string $key, StoreInterface $store = null, bool $returnObject = false): mixed;

    public function setForStore(string $key, mixed $data, StoreInterface $store = null): ConfigurationInterface;

    public function removeForStore(string $key, StoreInterface $store = null): void;
}
