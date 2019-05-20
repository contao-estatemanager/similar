<?php
/**
 * This file is part of Oveleon ImmoManager.
 *
 * @link      https://github.com/oveleon/contao-immo-manager-bundle
 * @copyright Copyright (c) 2018-2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://github.com/oveleon/contao-immo-manager-bundle/blob/master/LICENSE
 */

// IMMOMANAGER
$GLOBALS['TL_IMMOMANAGER_ADDONS'][] = array('Oveleon\\ContaoImmoManagerSimilarBundle', 'AddonManager');

if(Oveleon\ContaoImmoManagerSimilarBundle\AddonManager::valid()) {
    // Add expose module
    array_insert($GLOBALS['FE_EXPOSE_MOD']['miscellaneous'], -1, array
    (
        'similar' => '\\Oveleon\\ContaoImmoManagerSimilarBundle\\ExposeModuleSimilar',
    ));
}