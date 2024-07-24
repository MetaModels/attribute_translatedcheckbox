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
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2024 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcheckbox/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

declare(strict_types=1);

namespace MetaModels\AttributeTranslatedCheckboxBundle\Translator;

use MetaModels\Attribute\IAttribute;
use MetaModels\AttributeTranslatedCheckboxBundle\Attribute\TranslatedCheckbox;
use MetaModels\CoreBundle\Translator\LoaderInterface;
use MetaModels\IMetaModel;
use MetaModels\ITranslatedMetaModel;
use MetaModels\ViewCombination\InputScreenInformationBuilder;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\TranslatorBagInterface;

use function array_map;
use function in_array;
use function is_array;

final class TranslationLoader implements LoaderInterface
{
    public function __construct(
        private readonly TranslatorBagInterface $baseTranslator,
        private readonly InputScreenInformationBuilder $builder,
    ) {
    }

    public function load(IMetaModel $metaModel, string $locale): MessageCatalogue
    {
        $catalog = new MessageCatalogue($locale);
        /**
         * @psalm-suppress DeprecatedMethod
         * @psalm-suppress TooManyArguments
         */
        if ($metaModel instanceof ITranslatedMetaModel) {
            $metaModel->selectLanguage($locale);
            $mainLanguage = $metaModel->getMainLanguage();
            $allLocales   = $metaModel->getLanguages();
        } elseif ($metaModel->isTranslated(false)) {
            $mainLanguage = $metaModel->getFallbackLanguage() ?? 'en';
            $allLocales   = $metaModel->getAvailableLanguages() ?? ['en'];
        } else {
            // Untranslated MetaModel.
            return $catalog;
        }
        $domain = $metaModel->getTableName();

        foreach ($this->builder->fetchAllInputScreensForTable($domain) as $inputScreen) {
            foreach (
                $this->loadInputScreen($metaModel, $inputScreen, $locale, $mainLanguage, $domain, $allLocales)
                     ->all($domain) as $key => $value
            ) {
                $catalog->set($key, $value, $domain);
            }
        }

        return $catalog;
    }

    private function loadInputScreen(
        IMetaModel $metaModel,
        array $inputScreen,
        string $locale,
        string $mainLanguage,
        string $domain,
        array $allLocales
    ): MessageCatalogue {
        $allProps   = $inputScreen['properties'];
        $properties = array_map(
            static fn(array $property): ?string => ($property['col_name'] ?? null),
            $allProps
        );
        $catalog    = new MessageCatalogue($locale);

        // Load tl_metamodel_item catalogue.
        $base = $this->baseTranslator->getCatalogue($locale);

        $prefix = 'inputscreen.' . $inputScreen['meta']['id'] . '.';
        foreach ($metaModel->getAttributes() as $attribute) {
            if (!$this->wantToAdd($attribute, $properties)) {
                continue;
            }

            foreach ($allLocales as $langCode) {
                $commandName =
                    $prefix . 'publishtranslatedcheckboxtoggle_' . $attribute->getColName() . '_' . $langCode;
                $values      = ['%name%' => $this->getName($attribute, $locale, $mainLanguage), '%lang%' => $langCode];
                $catalog->set(
                    $commandName . '.label',
                    strtr($base->get('toggle.label', 'metamodel_translatedcheckbox'), $values),
                    $domain
                );
                $catalog->set(
                    $commandName . '.description',
                    strtr($base->get('toggle.description', 'metamodel_translatedcheckbox'), $values),
                    $domain
                );
            }
        }

        return $catalog;
    }

    /**
     * Test if we want to add an operation for the attribute.
     *
     * @param IAttribute $attribute  The attribute to test.
     * @param array      $properties The property names in the input screen.
     *
     * @return bool
     */
    private function wantToAdd(IAttribute $attribute, array $properties): bool
    {
        return ($attribute instanceof TranslatedCheckbox)
               && (($attribute->get('check_publish') === '1') || ($attribute->get('check_listview') === '1'))
               && (in_array($attribute->getColName(), $properties, true));
    }

    private function getName(IAttribute $attribute, string $locale, string $mainLanguage): string
    {
        $colName = $attribute->getColName();
        $name    = $attribute->get('name') ?? $colName;

        return $this->extractLangString($name, $locale, $mainLanguage) ?? $colName;
    }

    /**
     * @param string|array<string, string> $value      The value.
     * @param string                       $locale     The locale.
     * @param string                       $mainLocale The fallback language.
     *
     * @return string|null
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function extractLangString(string|array $value, string $locale, string $mainLocale): ?string
    {
        if (!is_array($value)) {
            return $value;
        }

        $fallback = null;
        foreach ($value as $langCode => $string) {
            if ($locale === $langCode && '' !== $string) {
                return $string;
            }
            if ($mainLocale === $langCode && '' !== $string) {
                $fallback = $string;
            }
        }

        if ('en' === $locale && null === $fallback && (null !== $default = ($value['default'] ?? $value[''] ?? null))) {
            return $default;
        }

        return $fallback;
    }
}
