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

namespace CoreShop\Bundle\TaxationBundle\Form\Type;

use CoreShop\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TaxRuleGroupType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('active', CheckboxType::class)
            ->add(
                'taxRules',
                CollectionType::class,
                [
                    'entry_type' => TaxRuleType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            );
    }

    public function getBlockPrefix(): string
    {
        return 'coreshop_taxation_tax_rule_group';
    }
}
