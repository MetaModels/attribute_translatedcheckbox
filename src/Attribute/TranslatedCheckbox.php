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
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2022 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcheckbox/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeTranslatedCheckboxBundle\Attribute;

use Doctrine\DBAL\Connection;
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
        return $this->get('check_publish') == 1;
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
        foreach (array_diff($arrIds, array_keys($arrReturn)) as $itemId) {
            $arrReturn[$itemId] = $this->widgetToValue(false, $itemId);
        }

        return $arrReturn;
    }

    /**
     * {@inheritdoc}
     */
    public function searchForInLanguages($pattern, $languages = [])
    {
        $optionizer = $this->getOptionizer();
        $procedure  = 't.' . $optionizer['value'] . ' LIKE :pattern';
        $pattern    = str_replace(['*', '?'], ['%', '_'], $pattern);

        // If option inverse on.
        if ($this->get('tcheck_inverse')) {
            $queryBuilderSub = $this->connection->createQueryBuilder()
                ->select('DISTINCT t.item_id')
                ->from($this->getValueTable(), 't')
                ->andWhere($procedure);

            $this->buildWhere($queryBuilderSub, null, $languages);

            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder->select('t2.id')
                ->from($this->getMetaModel()->getTableName(), 't2')
                ->where($queryBuilder->expr()->notIn('t2.id', $queryBuilderSub->getSQL()))
                ->setParameter('pattern', $pattern);
            foreach ($queryBuilderSub->getParameters() as $name => $value) {
                $queryBuilder->setParameter($name, $value, $queryBuilderSub->getParameterType($name));
            }

            $filterRuleInverse = SimpleQuery::createFromQueryBuilder($queryBuilder, 'id');

            return $filterRuleInverse->getMatchingIds();
        }

        // If option inverse off.
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('DISTINCT t.item_id')
            ->from($this->getValueTable(), 't')
            ->andWhere($procedure)
            ->setParameter('pattern', $pattern);

        $this->buildWhere($queryBuilder, null, $languages);

        $filterRule = SimpleQuery::createFromQueryBuilder($queryBuilder, 'item_id');

        return $filterRule->getMatchingIds();
    }

    /**
     * Build a where clause for the given id(s) and language code.
     *
     * @param QueryBuilder         $queryBuilder The query builder for the query  being build.
     * @param string[]|string|null $mixIds       One, none or many ids to use.
     * @param string|string[]      $mixLangCode  The language code/s to use, optional.
     *
     * @return void
     */
    private function buildWhere(QueryBuilder $queryBuilder, $mixIds, $mixLangCode = '')
    {
        $alias = '';

        if (null !== ($firstFrom = ($queryBuilder->getQueryPart('from')[0] ?? null))) {
            $alias = $firstFrom['alias'] . '.';
        }

        $queryBuilder
            ->andWhere($alias . 'att_id = :att_id')
            ->setParameter('att_id', $this->get('id'));

        if (!empty($mixIds)) {
            if (is_array($mixIds)) {
                $queryBuilder
                    ->andWhere($alias . 'item_id IN (:item_ids)')
                    ->setParameter('item_ids', $mixIds, Connection::PARAM_STR_ARRAY);
            } else {
                $queryBuilder
                    ->andWhere($alias . 'item_id = :item_id')
                    ->setParameter('item_id', $mixIds);
            }
        }

        if (!empty($mixLangCode)) {
            if (is_array($mixLangCode)) {
                $queryBuilder
                    ->andWhere($alias . 'langcode IN (:langcode)')
                    ->setParameter('langcode', $mixLangCode, Connection::PARAM_STR_ARRAY);
            } else {
                $queryBuilder
                    ->andWhere($alias . 'langcode = :langcode')
                    ->setParameter('langcode', $mixLangCode);
            }
        }
    }
}
