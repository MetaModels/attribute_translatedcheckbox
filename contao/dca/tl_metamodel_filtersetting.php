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
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcheckbox/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

/**
 * Table tl_metamodel_filtersetting
 */
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['translatedcheckbox_published extends _attribute_']['+config'] =
[
    'check_ignorepublished',
    'check_allowpreview'
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['check_ignorepublished'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['check_ignorepublished'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => [
        'alwaysSave'          => true,
        'tl_class'            => 'w50 m12',
    ],
    'sql'                     => 'char(1) NOT NULL default \'\''
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['check_allowpreview'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['check_allowpreview'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => [
        'alwaysSave'          => true,
        'tl_class'            => 'w50 m12',
    ],
    'sql'                     => 'char(1) NOT NULL default \'\''
];
