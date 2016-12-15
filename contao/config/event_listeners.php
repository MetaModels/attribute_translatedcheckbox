<?php

/**
 * This file is part of MetaModels/attribute_translatedcheckbox.
 *
 * (c) 2012-2016 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels
 * @subpackage AttributeTranslatedCheckbox
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2012-2016 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcheckbox/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

use ContaoCommunityAlliance\DcGeneral\DataDefinition\Palette\Condition\Property\PropertyFalseCondition;
use ContaoCommunityAlliance\DcGeneral\DataDefinition\Palette\Condition\Property\PropertyTrueCondition;
use MetaModels\Attribute\Events\CreateAttributeFactoryEvent;
use MetaModels\Attribute\TranslatedCheckbox\AttributeTypeFactory;
use MetaModels\Attribute\TranslatedCheckbox\TranslatedCheckbox;
use MetaModels\Events\Attribute\TranslatedCheckbox\Listener;
use MetaModels\Events\Attribute\TranslatedCheckbox\PublishedFilterSettingTypeRenderer;
use MetaModels\Events\CreatePropertyConditionEvent;
use MetaModels\Events\MetaModelsBootEvent;
use MetaModels\Filter\Setting\Events\CreateFilterSettingFactoryEvent;
use MetaModels\Filter\Setting\Published\TranslatedCheckboxFilterSettingTypeFactory;
use MetaModels\MetaModelsEvents;

return [
    MetaModelsEvents::SUBSYSTEM_BOOT_BACKEND => [
        function (MetaModelsBootEvent $event) {
            new Listener($event->getServiceContainer());
            new PublishedFilterSettingTypeRenderer($event->getServiceContainer());
        }
    ],
    MetaModelsEvents::ATTRIBUTE_FACTORY_CREATE => [
        function (CreateAttributeFactoryEvent $event) {
            $factory = $event->getFactory();
            $factory->addTypeFactory(new AttributeTypeFactory());
        }
    ],
    MetaModelsEvents::FILTER_SETTING_FACTORY_CREATE => [
        function (CreateFilterSettingFactoryEvent $event) {
            $factory = $event->getFactory();
            $factory->addTypeFactory(new TranslatedCheckboxFilterSettingTypeFactory());
        }
    ],
    CreatePropertyConditionEvent::NAME => [[
        function (CreatePropertyConditionEvent $event) {
            $meta = $event->getData();

            if ('conditionpropertyvalueis' !== $meta['type']) {
                return;
            }

            $metaModel = $event->getMetaModel();
            $attribute = $metaModel->getAttributeById($meta['attr_id']);
            if (!($attribute instanceof TranslatedCheckbox)) {
                return;
            }

            if ((bool) $meta['value']) {
                $event->setInstance(new PropertyTrueCondition($attribute->getColName()));
                return;
            }
            $event->setInstance(new PropertyFalseCondition($attribute->getColName()));
        },
        -10
    ]]
];
