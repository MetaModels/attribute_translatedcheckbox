<?php

/**
 * This file is part of MetaModels/attribute_translatedcheckbox.
 *
 * (c) 2012-2024 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/attribute_translatedcheckbox
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Andreas Isaak <info@andreas-isaak.de>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2024 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcheckbox/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeTranslatedCheckboxBundle\FilterSetting\Published;

use Contao\System;
use MetaModels\Filter\IFilter as IMetaModelFilter;
use MetaModels\Filter\Rules\SearchAttribute;
use MetaModels\Filter\Rules\StaticIdList as MetaModelFilterRuleStaticIdList;
use MetaModels\Filter\Setting\Simple as MetaModelFilterSetting;

/**
 * Filter setting to filter for translated checkboxes.
 *
 * @covers \MetaModels\AttributeTranslatedCheckboxBundle\Attribute\TranslatedCheckbox
 */
class TranslatedCheckbox extends MetaModelFilterSetting
{
    /**
     * {@inheritDoc}
     */
    public function prepareRules(IMetaModelFilter $objFilter, $arrFilterUrl)
    {
        if ((bool) $this->get('check_ignorepublished') && $arrFilterUrl['ignore_published' . $this->get('id')]) {
            return;
        }

        // Skip filter when in front end preview.
        if (
            (bool) $this->get('check_allowpreview')
            && System::getContainer()->get('contao.security.token_checker')?->isPreviewMode()
        ) {
            return;
        }

        $objAttribute = $this->getMetaModel()->getAttributeById((int) $this->get('attr_id'));
        if ($objAttribute) {
            /** @psalm-suppress DeprecatedMethod */
            $language      = $this->getMetaModel()->getActiveLanguage();
            $objFilterRule = new SearchAttribute($objAttribute, '1', '' !== $language ? $language : 'en');
            $objFilter->addFilterRule($objFilterRule);

            return;
        }
        // Attribute not found, do not return anything to prevent leaking of items.
        $objFilter->addFilterRule(new MetaModelFilterRuleStaticIdList([]));
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return ($this->get('check_ignorepublished')) ? ['ignore_published' . $this->get('id')] : [];
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function getParameterDCA()
    {
        if (!$this->get('check_ignorepublished')) {
            return [];
        }

        if (null === $objAttribute = $this->getMetaModel()->getAttributeById($this->get('attr_id'))) {
            return [];
        }

        $arrLabel = [];
        foreach ($GLOBALS['TL_LANG']['MSC']['metamodel_filtersetting']['ignore_published'] as $strLabel) {
            $arrLabel[] = \sprintf($strLabel, $objAttribute->getName());
        }

        return [
            'ignore_published' . $this->get('id') =>
                [
                    'label'     => $arrLabel,
                    'inputType' => 'checkbox',
                ]
        ];
    }
}
