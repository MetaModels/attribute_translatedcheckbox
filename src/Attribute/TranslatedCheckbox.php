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
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2024 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcheckbox/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeTranslatedCheckboxBundle\Attribute;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Query\QueryBuilder;
use MetaModels\Attribute\TranslatedReference;
use MetaModels\Filter\Rules\SimpleQuery;

/**
 * This is the MetaModelAttribute class for handling translated checkbox fields.
 */
class TranslatedCheckbox extends TranslatedReference
{
    /**
     * Check if the attribute is a published field.
     *
     * @return bool
     */
    public function isPublishedField()
    {
        return $this->get('check_publish') === 1;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributeSettingNames()
    {
        return \array_merge(
            parent::getAttributeSettingNames(),
            [
                'mandatory',
                'check_publish',
                'tcheck_inverse',
                'filterable',
                'searchable',
                'submitOnChange',
                'tcheck_listview',
                'tcheck_listviewicon_fields'
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function getValueTable()
    {
        return 'tl_metamodel_translatedcheckbox';
    }

    /**
     * {@inheritDoc}
     */
    public function getFieldDefinition($arrOverrides = [])
    {
        $arrFieldDef              = parent::getFieldDefinition($arrOverrides);
        $arrFieldDef['inputType'] = 'checkbox';

        return $arrFieldDef;
    }

    /**
     * {@inheritDoc}
     */
    public function getTranslatedDataFor($arrIds, $strLangCode)
    {
        $arrReturn = parent::getTranslatedDataFor($arrIds, $strLangCode);

        // Per definition:
        // - all values that are not contained are defaulting to false in the fallback language.
        // - all values in published not contained are defaulting to false.
        foreach (\array_diff($arrIds, \array_keys($arrReturn)) as $itemId) {
            $arrReturn[$itemId] = $this->widgetToValue(false, $itemId);
        }

        return $arrReturn;
    }

    /**
     * {@inheritdoc}
     */
    public function searchForInLanguages($strPattern, $arrLanguages = [])
    {
        $optionizer = $this->getOptionizer();
        $procedure  = 't.' . $optionizer['value'] . ' LIKE :pattern';
        $strPattern = \str_replace(['*', '?'], ['%', '_'], $strPattern);

        // If option inverse on.
        if ($this->get('tcheck_inverse')) {
            $queryBuilderSub = $this->connection->createQueryBuilder()
                ->select('DISTINCT t.item_id')
                ->from($this->getValueTable(), 't')
                ->andWhere($procedure);

            $this->buildWhere($queryBuilderSub, null, $arrLanguages, 't');

            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder->select('t2.id')
                ->from($this->getMetaModel()->getTableName(), 't2')
                ->where($queryBuilder->expr()->notIn('t2.id', $queryBuilderSub->getSQL()))
                ->setParameter('pattern', $strPattern);
            foreach ($queryBuilderSub->getParameters() as $name => $value) {
                $queryBuilder->setParameter($name, $value, $queryBuilderSub->getParameterType($name));
            }

            return SimpleQuery::createFromQueryBuilder($queryBuilder, 'id')->getMatchingIds();
        }

        // If option inverse off.
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('DISTINCT t.item_id')
            ->from($this->getValueTable(), 't')
            ->andWhere($procedure)
            ->setParameter('pattern', $strPattern);

        $this->buildWhere($queryBuilder, null, $arrLanguages, 't');

        return SimpleQuery::createFromQueryBuilder($queryBuilder, 'item_id')->getMatchingIds();
    }

    /**
     * Build a where clause for the given id(s) and language code.
     *
     * @param QueryBuilder             $queryBuilder The query builder for the query  being build.
     * @param null|list<string>|string $mixIds       One, none or many ids to use.
     * @param array                    $arrLangCode  The language code/s to use, optional.
     * @param string                   $alias        The table alias, optional.
     *
     * @return void
     */
    private function buildWhere(
        QueryBuilder $queryBuilder,
        array|string|null $mixIds,
        array $arrLangCode,
        string $alias = ''
    ): void {
        if ('' !== $alias) {
            $alias .= '.';
        }

        $queryBuilder
            ->andWhere($alias . 'att_id = :att_id')
            ->setParameter('att_id', $this->get('id'));

        if ([] !== $arrLangCode) {
            $queryBuilder
                ->andWhere($alias . 'langcode IN (:langcode)')
                ->setParameter('langcode', $arrLangCode, ArrayParameterType::STRING);
        }

        if (null === $mixIds) {
            return;
        }
        if (\is_array($mixIds)) {
            if ([] === $mixIds) {
                return;
            }

            $queryBuilder
                ->andWhere($alias . 'item_id IN (:item_ids)')
                ->setParameter('item_ids', $mixIds, ArrayParameterType::STRING);

            return;
        }
        $queryBuilder
            ->andWhere($alias . 'item_id = :item_id')
            ->setParameter('item_id', $mixIds);
    }
}
