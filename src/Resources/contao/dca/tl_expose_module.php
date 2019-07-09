<?php
/**
 * This file is part of Contao EstateManager.
 *
 * @link      https://www.contao-estatemanager.com/
 * @source    https://github.com/contao-estatemanager/similar
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

if(ContaoEstateManager\Similar\AddonManager::valid()) {
    // Add field
    array_insert($GLOBALS['TL_DCA']['tl_expose_module']['palettes'], -1, array
    (
        'similar'  => '{title_legend},name,headline,type;{settings_legend},jumpTo,numberOfItems,perPage,filterCoarse,hideOnEmpty;{image_legend:hide},imgSize;{template_legend:hide},customTpl,realEstateTemplate;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID'
    ));

    // Add fields
    array_insert($GLOBALS['TL_DCA']['tl_expose_module']['fields'], -1, array(
        'filterCoarse' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_expose_module']['filterCoarse'],
            'default'                 => 25,
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "smallint(5) NOT NULL default '0'",
        ),
    ));
}
