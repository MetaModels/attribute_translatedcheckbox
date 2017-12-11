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
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2012-2017 The MetaModels team.
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
];
