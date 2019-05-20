<?php
/**
 * This file is part of Oveleon ImmoManager.
 *
 * @link      https://github.com/oveleon/contao-immo-manager-bundle
 * @copyright Copyright (c) 2018-2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://github.com/oveleon/contao-immo-manager-bundle/blob/master/LICENSE
 */

if(Oveleon\ContaoImmoManagerSimilarBundle\AddonManager::valid()) {
    // Add field
    array_insert($GLOBALS['TL_DCA']['tl_expose_module']['palettes'], -1, array
    (
        'similar'  => '{title_legend},name,headline,type;{settings_legend},jumpTo,numberOfItems,perPage,hideOnEmpty;{image_legend:hide},imgSize;{template_legend:hide},customTpl,realEstateTemplate;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID'
    ));

    // Add fields
    array_insert($GLOBALS['TL_DCA']['tl_expose_module']['fields'], -1, array(
        'realEstateTemplate' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_module']['realEstateTemplate'],
            'default'                 => 'real_estate_default',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => array('tl_module_immo_manager_similar', 'getRealEstateTemplates'),
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),
    ));
}

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Daniele Sciannimanica <daniele@oveleon.de>
 */
class tl_module_immo_manager_similar extends \Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * Return all real estate list templates as array
     *
     * @return array
     */
    public function getRealEstateTemplates()
    {
        return $this->getTemplateGroup('real_estate_');
    }
}