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

use MetaModels\AttributeTranslatedCheckboxBundle\Attribute\AttributeTypeFactory;
use MetaModels\AttributeTranslatedCheckboxBundle\Attribute\TranslatedCheckbox;
use MetaModels\AttributeTranslatedCheckboxBundle\FilterSetting\Published\TranslatedCheckbox as PublishedFilter;
use MetaModels\AttributeTranslatedCheckboxBundle\FilterSetting\Published\TranslatedCheckboxFilterSettingTypeFactory;

// This hack is to load the "old locations" of the classes.
spl_autoload_register(
    function ($class) {
        static $classes = [
            'MetaModels\Attribute\TranslatedCheckbox\TranslatedCheckbox'   => TranslatedCheckbox::class,
            'MetaModels\Attribute\TranslatedCheckbox\AttributeTypeFactory' => AttributeTypeFactory::class,
            'MetaModels\Filter\Setting\Published\TranslatedCheckbox'       => PublishedFilter::class,
            // @codingStandardsIgnoreStart Line exceed 120 characters
            'MetaModels\Filter\Setting\Published\TranslatedCheckboxFilterSettingTypeFactory' => TranslatedCheckboxFilterSettingTypeFactory::class,
            // @codingStandardsIgnoreEnd
        ];

        if (isset($classes[$class])) {
            // @codingStandardsIgnoreStart Silencing errors is discouraged
            @trigger_error('Class "' . $class . '" has been renamed to "' . $classes[$class] . '"', E_USER_DEPRECATED);
            // @codingStandardsIgnoreEnd

            if (!class_exists($classes[$class])) {
                spl_autoload_call($class);
            }

            class_alias($classes[$class], $class);
        }
    }
);
