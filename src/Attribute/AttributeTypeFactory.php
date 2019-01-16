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
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcheckbox/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeTranslatedCheckboxBundle\Attribute;

use Doctrine\DBAL\Connection;
use MetaModels\Attribute\IAttributeTypeFactory;

/**
 * Attribute type factory for checkbox attributes.
 */
class AttributeTypeFactory implements IAttributeTypeFactory
{
    /**
     * Database connection.
     *
     * @var Connection
     */
    private $connection;

    /**
     * Create instance.
     *
     * @param Connection $connection Database connection.
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function createInstance($information, $metaModel)
    {
        return new TranslatedCheckbox($metaModel, $information, $this->connection);
    }

    /**
     * Return the type name - this is the internal type name used by MetaModels.
     *
     * @return string
     */
    public function getTypeName()
    {
        return 'translatedcheckbox';
    }

    /**
     * Retrieve the (relative to TL_ROOT) path to a icon for the type.
     *
     * @return string
     */
    public function getTypeIcon()
    {
        return 'bundles/metamodelsattributetranslatedcheckbox/checkbox.png';
    }

    /**
     * Check if the type is translated.
     *
     * @return bool
     */
    public function isTranslatedType()
    {
        return true;
    }

    /**
     * Check if the type is of simple nature.
     *
     * @return bool
     */
    public function isSimpleType()
    {
        return false;
    }

    /**
     * Check if the type is of complex nature.
     *
     * @return bool
     */
    public function isComplexType()
    {
        return true;
    }
}
