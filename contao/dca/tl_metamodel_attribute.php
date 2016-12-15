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
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2016 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcheckbox/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

/**
 * Table tl_metamodel_attribute
 */

/**
 * Add palette configuration.
 */
$GLOBALS['TL_DCA']['tl_metamodel_attribute']['metapalettes']['translatedcheckbox extends _simpleattribute_'] = array
(
    '-advanced' => array('isunique'),
    '+advanced' => array('check_publish')
);

/**
 * Add data provider.
 */
$GLOBALS['TL_DCA']['tl_metamodel_attribute']['dca_config']['data_provider']['tl_metamodel_translatedcheckbox'] = array
(
    'source' => 'tl_metamodel_translatedcheckbox'
);

/**
 * Add child condition.
 */
$GLOBALS['TL_DCA']['tl_metamodel_attribute']['dca_config']['childCondition'][] = array
(
    'from'   => 'tl_metamodel_attribute',
    'to'     => 'tl_metamodel_translatedcheckbox',
    'setOn'  => array
    (
        array
        (
            'to_field'   => 'att_id',
            'from_field' => 'id',
        ),
    ),
    'filter' => array
    (
        array
        (
            'local'     => 'att_id',
            'remote'    => 'id',
            'operation' => '=',
        ),
    )
);

/**
 * Add field configuration.
 */
$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['check_publish'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['check_publish'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => array('tl_class' => 'w50'),
);
