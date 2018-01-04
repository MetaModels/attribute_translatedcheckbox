<?php

/**
 * This file is part of MetaModels/attribute_translatedcheckbox.
 *
 * (c) 2012-2017 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels
 * @subpackage AttributeTranslatedCheckbox
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2012-2017 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcheckbox/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\AttributeTranslatedCheckboxBundle\EventListener;

use ContaoCommunityAlliance\DcGeneral\Contao\DataDefinition\Definition\Contao2BackendViewDefinition;
use ContaoCommunityAlliance\DcGeneral\Contao\DataDefinition\Definition\Contao2BackendViewDefinitionInterface;
use ContaoCommunityAlliance\DcGeneral\DataDefinition\Definition\View\CommandCollectionInterface;
use ContaoCommunityAlliance\DcGeneral\DataDefinition\Definition\View\TranslatedToggleCommand;
use MetaModels\Attribute\IAttribute;
use MetaModels\AttributeTranslatedCheckboxBundle\Attribute\TranslatedCheckbox;
use MetaModels\DcGeneral\Events\MetaModel\BuildMetaModelOperationsEvent;

/**
 * This class creates the default instances for property conditions when generating input screens.
 */
class BuildMetaModelOperationsListener
{

    /**
     * Generate the toggle command information.
     *
     * @param CommandCollectionInterface $commands    The already existing commands.
     *
     * @param IAttribute                 $attribute   The attribute.
     *
     * @param string                     $commandName The name of the new command.
     *
     * @param string                     $class       The name of the CSS class for the command.
     *
     * @param string                     $language    The language name.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function generateToggleCommand($commands, $attribute, $commandName, $class, $language)
    {
        if (!$commands->hasCommandNamed($commandName)) {
            $toggle = new TranslatedToggleCommand();
            $toggle
                ->setLanguage($language)
                ->setToggleProperty($attribute->getColName())
                ->setName($commandName)
                ->setLabel($GLOBALS['TL_LANG']['MSC']['metamodelattribute_translatedcheckbox']['toggle'][0])
                ->setDescription(
                    sprintf(
                        $GLOBALS['TL_LANG']['MSC']['metamodelattribute_translatedcheckbox']['toggle'][1],
                        $attribute->getName(),
                        $language
                    )
                );

            $extra          = $toggle->getExtra();
            $extra['icon']  = 'visible.svg';
            $extra['class'] = $class;

            if ($commands->hasCommandNamed('show')) {
                $info = $commands->getCommandNamed('show');
            } else {
                $info = null;
            }
            $commands->addCommand($toggle, $info);
        }
    }

    /**
     * Build a attribute toggle operation for all languages of the MetaModel.
     *
     * @param TranslatedCheckbox         $attribute The checkbox attribute.
     *
     * @param CommandCollectionInterface $commands  The already existing commands.
     *
     * @return void
     */
    protected function buildCommandsFor($attribute, $commands)
    {
        $activeLanguage = $attribute->getMetaModel()->getActiveLanguage();
        $commandName    = 'publishtranslatedcheckboxtoggle_' . $attribute->getColName();

        $this->generateToggleCommand(
            $commands,
            $attribute,
            $commandName . '_' . $activeLanguage,
            'contextmenu',
            $activeLanguage
        );

        foreach (array_diff($attribute->getMetaModel()->getAvailableLanguages(), array($activeLanguage)) as $langCode) {
            $this->generateToggleCommand(
                $commands,
                $attribute,
                $commandName . '_' . $langCode,
                'edit-header',
                $langCode
            );
        }
    }

    /**
     * Create the property conditions.
     *
     * @param BuildMetaModelOperationsEvent $event The event.
     *
     * @return void
     *
     * @throws \RuntimeException When no MetaModel is attached to the event or any other important information could
     *                           not be retrieved.
     */
    public function handle(BuildMetaModelOperationsEvent $event)
    {
        foreach ($event->getMetaModel()->getAttributes() as $attribute) {
            if (($attribute instanceof TranslatedCheckbox) && ($attribute->get('check_publish') == 1)) {
                $container = $event->getContainer();
                if ($container->hasDefinition(Contao2BackendViewDefinitionInterface::NAME)) {
                    $view = $container->getDefinition(Contao2BackendViewDefinitionInterface::NAME);
                } else {
                    $view = new Contao2BackendViewDefinition();
                    $container->setDefinition(Contao2BackendViewDefinitionInterface::NAME, $view);
                }
                $this->buildCommandsFor($attribute, $view->getModelCommands());
            }
        }
    }
}
