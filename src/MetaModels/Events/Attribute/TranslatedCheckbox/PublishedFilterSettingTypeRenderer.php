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

namespace MetaModels\Events\Attribute\TranslatedCheckbox;

use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;
use ContaoCommunityAlliance\DcGeneral\EnvironmentInterface;
use MetaModels\DcGeneral\Events\Table\FilterSetting\FilterSettingTypeRenderer;

/**
 * Handles rendering of model from tl_metamodel_filtersetting.
 */
class PublishedFilterSettingTypeRenderer extends FilterSettingTypeRenderer
{
    /**
     * {@inheritdoc}
     */
    protected function getTypes()
    {
        return ['translatedcheckbox_published'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getLabelParameters(EnvironmentInterface $environment, ModelInterface $model)
    {
        return $this->getLabelParametersWithAttributeAndUrlParam($environment, $model);
    }
}
