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

namespace CoreShop\Component\Pimcore\DataObject;

use Pimcore\Model\DataObject\Concrete;

class UnpublishedHelper
{
    /**
     * This function enables usage of unpublished/published in Pimcore and resets the state hideUnpublished
     * after your functions is finished.
     *
     *
     * @return mixed
     */
    public static function hideUnpublished(\Closure $function, bool $hide = false)
    {
        $backup = Concrete::getHideUnpublished();

        Concrete::setHideUnpublished($hide);

        $result = $function();

        Concrete::setHideUnpublished($backup);

        return $result;
    }
}
