<?php

/**
 * This file is part of MetaModels/attribute_translatedcheckbox.
 *
 * (c) 2012-2019 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/attribute_translatedcheckbox
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcheckbox/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeTranslatedCheckboxBundle\EventListener;

use ContaoCommunityAlliance\DcGeneral\DataDefinition\Palette\Condition\Property\PropertyFalseCondition;
use ContaoCommunityAlliance\DcGeneral\DataDefinition\Palette\Condition\Property\PropertyTrueCondition;
use MetaModels\AttributeTranslatedCheckboxBundle\Attribute\TranslatedCheckbox;
use MetaModels\Events\CreatePropertyConditionEvent;

/**
 * Class CreatePropertyConditionListener.
 */
class CreatePropertyConditionListener
{
    /**
     * Create property condition for the translated checkbox.
     *
     * @param CreatePropertyConditionEvent $event The property condition event.
     *
     * @return void
     */
    public function createCondition(CreatePropertyConditionEvent $event)
    {
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
    }
}
