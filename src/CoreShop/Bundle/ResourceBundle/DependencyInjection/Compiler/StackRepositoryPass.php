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

namespace CoreShop\Bundle\ResourceBundle\DependencyInjection\Compiler;

use CoreShop\Bundle\ResourceBundle\CoreShopResourceBundle;
use CoreShop\Bundle\ResourceBundle\Pimcore\Repository\StackRepository;
use CoreShop\Component\Resource\Metadata\Metadata;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class StackRepositoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('coreshop.all.stack.pimcore_class_names') ||
            !$container->hasParameter('coreshop.all.stack')) {
            return;
        }

        /**
         * @var array $stackConfig
         */
        $stackConfig = $container->getParameter('coreshop.all.stack');

        /**
         * @var array $fqcns
         */
        $fqcns = $container->getParameter('coreshop.all.stack.fqcns');

        foreach ($fqcns as $alias => $classes) {
            [$applicationName, $name] = explode('.', $alias);

            $definition = new Definition(Metadata::class);
            $definition
                ->setFactory([Metadata::class, 'fromAliasAndConfiguration'])
                ->setArguments([$alias, ['driver' => CoreShopResourceBundle::DRIVER_PIMCORE]]);

            $repositoryDefinition = new Definition(StackRepository::class);
            $repositoryDefinition->setArguments([
                $definition,
                new Reference('doctrine.dbal.default_connection'),
                $stackConfig[$alias],
                $classes,
            ]);

            $container->setDefinition(sprintf('%s.repository.stack.%s', $applicationName, $name), $repositoryDefinition)
                ->setPublic(true);
        }
    }
}
