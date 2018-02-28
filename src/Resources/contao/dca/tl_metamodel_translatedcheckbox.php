<?php

/**
 * This file is part of MetaModels/attribute_translatedcheckbox.
 *
 * (c) 2012-2018 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels
 * @subpackage AttributeTranslatedCheckbox
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2012-2018 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcheckbox/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

$GLOBALS['TL_DCA']['tl_metamodel_translatedcheckbox'] = [
    'config'                      => [
        'sql'                     => [
            'keys'                => [
                'id'              => 'primary',
                'att_id,value'    => 'index',
                'att_id,langcode' => 'index',
                'att_id,item_id'  => 'index',
            ],
        ],
    ],
    'fields'                      => [
        'id'                      => [
            'sql'                 => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp'                  => [
            'sql'                 => 'int(10) unsigned NOT NULL default \'0\'',
        ],
        'att_id'                  => [
            'sql'                 => 'int(10) unsigned NOT NULL default \'0\'',
        ],
        'item_id'                 => [
            'sql'                 => 'int(10) unsigned NOT NULL default \'0\'',
        ],
        'langcode'                => [
            'sql'                 => 'varchar(5) NOT NULL default \'\'',
        ],
        'value'                   => [
            'sql'                 => 'char(1) NOT NULL default \'\'',
        ],
    ],
];
