<?php
/**
 * This file is part of Contao EstateManager.
 *
 * @link      https://www.contao-estatemanager.com/
 * @source    https://github.com/contao-estatemanager/similar
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

// ESTATEMANAGER
$GLOBALS['TL_ESTATEMANAGER_ADDONS'][] = array('ContaoEstateManager\\Similar', 'AddonManager');

if(ContaoEstateManager\Similar\AddonManager::valid()) {
    // Add expose module
    $GLOBALS['FE_EXPOSE_MOD']['miscellaneous']['similar'] = '\\ContaoEstateManager\\Similar\\ExposeModuleSimilar';
}
