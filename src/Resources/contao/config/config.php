<?php

declare(strict_types=1);

/*
 * This file is part of Contao EstateManager.
 *
 * @see        https://www.contao-estatemanager.com/
 * @source     https://github.com/contao-estatemanager/similar
 * @copyright  Copyright (c) 2021 Oveleon GbR (https://www.oveleon.de)
 * @license    https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

use ContaoEstateManager\Similar\AddonManager;

// ESTATEMANAGER
$GLOBALS['TL_ESTATEMANAGER_ADDONS'][] = ['ContaoEstateManager\Similar', 'AddonManager'];

if (AddonManager::valid())
{
    // Add expose module
    $GLOBALS['FE_EXPOSE_MOD']['miscellaneous']['similar'] = 'ContaoEstateManager\Similar\ExposeModuleSimilar';
}
