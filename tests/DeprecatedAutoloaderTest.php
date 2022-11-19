<?php

/**
 * This file is part of MetaModels/attribute_translatedcheckbox.
 *
 * (c) 2012-2022 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/attribute_translatedcheckbox
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2022 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcheckbox/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeTranslatedCheckboxBundle\Test;

use MetaModels\AttributeTranslatedCheckboxBundle\Attribute\AttributeTypeFactory;
use MetaModels\AttributeTranslatedCheckboxBundle\Attribute\TranslatedCheckbox;
use MetaModels\AttributeTranslatedCheckboxBundle\FilterSetting\Published\TranslatedCheckbox as PublishedTranslatedCheckbox;
use MetaModels\AttributeTranslatedCheckboxBundle\FilterSetting\Published\TranslatedCheckboxFilterSettingTypeFactory;

use PHPUnit\Framework\TestCase;

/**
 * This class tests if the deprecated autoloader works.
 *
 * @covers \MetaModels\AttributeTranslatedCheckboxBundle\DeprecatedAutoloader
 */
class DeprecatedAutoloaderTest extends TestCase
{
    /**
     * TranslatedTextes of old classes to the new one.
     *
     * @var array
     */
    private static $classes = [
        'MetaModels\Attribute\TranslatedCheckbox\TranslatedCheckbox'                     => TranslatedCheckbox::class,
        'MetaModels\Attribute\TranslatedCheckbox\AttributeTypeFactory'                   => AttributeTypeFactory::class,
        'MetaModels\Filter\Setting\Published\TranslatedCheckbox'                         => PublishedTranslatedCheckbox::class,
        'MetaModels\Filter\Setting\Published\TranslatedCheckboxFilterSettingTypeFactory' => TranslatedCheckboxFilterSettingTypeFactory::class,
    ];

    /**
     * Provide the alias class map.
     *
     * @return array
     */
    public function provideAliasClassMap()
    {
        $values = [];

        foreach (static::$classes as $translatedText => $class) {
            $values[] = [$translatedText, $class];
        }

        return $values;
    }

    /**
     * Test if the deprecated classes are aliased to the new one.
     *
     * @param string $oldClass Old class name.
     * @param string $newClass New class name.
     *
     * @dataProvider provideAliasClassMap
     */
    public function testDeprecatedClassesAreAliased($oldClass, $newClass)
    {
        $this->assertTrue(\class_exists($oldClass), \sprintf('Class TranslatedTExt "%s" is not found.', $oldClass));

        $oldClassReflection = new \ReflectionClass($oldClass);
        $newClassReflection = new \ReflectionClass($newClass);

        $this->assertSame($newClassReflection->getFileName(), $oldClassReflection->getFileName());
    }
}
