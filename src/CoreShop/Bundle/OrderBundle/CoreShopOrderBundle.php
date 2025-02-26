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

namespace CoreShop\Bundle\OrderBundle;

use CoreShop\Bundle\CurrencyBundle\CoreShopCurrencyBundle;
use CoreShop\Bundle\CustomerBundle\CoreShopCustomerBundle;
use CoreShop\Bundle\MoneyBundle\CoreShopMoneyBundle;
use CoreShop\Bundle\OrderBundle\DependencyInjection\Compiler\CartPriceRuleActionPass;
use CoreShop\Bundle\OrderBundle\DependencyInjection\Compiler\CartPriceRuleConditionPass;
use CoreShop\Bundle\OrderBundle\DependencyInjection\Compiler\PurchasableDiscountCalculatorsPass;
use CoreShop\Bundle\OrderBundle\DependencyInjection\Compiler\PurchasableDiscountPriceCalculatorsPass;
use CoreShop\Bundle\OrderBundle\DependencyInjection\Compiler\PurchasablePriceCalculatorsPass;
use CoreShop\Bundle\OrderBundle\DependencyInjection\Compiler\PurchasableRetailPriceCalculatorsPass;
use CoreShop\Bundle\OrderBundle\DependencyInjection\Compiler\PurchasableWholesalePriceCalculatorsPass;
use CoreShop\Bundle\OrderBundle\DependencyInjection\Compiler\RegisterCartContextsPass;
use CoreShop\Bundle\OrderBundle\DependencyInjection\Compiler\RegisterCartProcessorPass;
use CoreShop\Bundle\OrderBundle\DependencyInjection\Compiler\RegisterWorkflowValidatorPass;
use CoreShop\Bundle\PaymentBundle\CoreShopPaymentBundle;
use CoreShop\Bundle\ResourceBundle\AbstractResourceBundle;
use CoreShop\Bundle\ResourceBundle\CoreShopResourceBundle;
use CoreShop\Bundle\RuleBundle\CoreShopRuleBundle;
use CoreShop\Bundle\StoreBundle\CoreShopStoreBundle;
use CoreShop\Bundle\WorkflowBundle\CoreShopWorkflowBundle;
use Pimcore\HttpKernel\BundleCollection\BundleCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class CoreShopOrderBundle extends AbstractResourceBundle
{
    public function getSupportedDrivers(): array
    {
        return [
            CoreShopResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new CartPriceRuleActionPass());
        $container->addCompilerPass(new CartPriceRuleConditionPass());
        $container->addCompilerPass(new RegisterWorkflowValidatorPass());
        $container->addCompilerPass(new RegisterCartProcessorPass());
        $container->addCompilerPass(new PurchasablePriceCalculatorsPass());
        $container->addCompilerPass(new PurchasableDiscountCalculatorsPass());
        $container->addCompilerPass(new RegisterCartContextsPass());
        $container->addCompilerPass(new PurchasableDiscountPriceCalculatorsPass());
        $container->addCompilerPass(new PurchasableRetailPriceCalculatorsPass());
        $container->addCompilerPass(new PurchasableWholesalePriceCalculatorsPass());
    }

    public static function registerDependentBundles(BundleCollection $collection): void
    {
        parent::registerDependentBundles($collection);

        $collection->addBundle(new CoreShopWorkflowBundle(), 3600);
        $collection->addBundle(new CoreShopRuleBundle(), 3500);
        $collection->addBundle(new CoreShopCustomerBundle(), 3100);
        $collection->addBundle(new CoreShopCurrencyBundle(), 2700);
        $collection->addBundle(new CoreShopStoreBundle(), 2500);
        $collection->addBundle(new CoreShopPaymentBundle(), 2200);
        $collection->addBundle(new CoreShopMoneyBundle(), 1550);
    }

    protected function getModelNamespace(): string
    {
        return 'CoreShop\Component\Order\Model';
    }
}
