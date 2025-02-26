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

namespace CoreShop\Component\Core\Repository;

use CoreShop\Component\Configuration\Repository\ConfigurationRepositoryInterface as BaseConfigurationRepositoryInterface;
use CoreShop\Component\Core\Model\ConfigurationInterface;
use CoreShop\Component\Store\Model\StoreInterface;

interface ConfigurationRepositoryInterface extends BaseConfigurationRepositoryInterface
{
    public function findForKeyAndStore(string $key, StoreInterface $store): ?ConfigurationInterface;
}
