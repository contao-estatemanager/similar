<?php
/**
 * This file is part of Contao EstateManager.
 *
 * @link      https://www.contao-estatemanager.com/
 * @source    https://github.com/contao-estatemanager/similar
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

namespace ContaoEstateManager\Similar;

use Contao\Config;
use Contao\Environment;
use ContaoEstateManager\EstateManager;

class AddonManager
{
    /**
     * Bundle name
     * @var string
     */
    public static $bundle = 'EstateManagerSimilar';

    /**
     * Package
     * @var string
     */
    public static $package = 'contao-estatemanager/similar';

    /**
     * Addon config key
     * @var string
     */
    public static $key  = 'addon_similar_license';

    /**
     * Is initialized
     * @var boolean
     */
    public static $initialized  = false;

    /**
     * Is valid
     * @var boolean
     */
    public static $valid  = false;

    /**
     * Licenses
     * @var array
     */
    private static $licenses = [
        '00b972574c3d1d2851e1f8b11bce39d7',
        'ef00226a0f1d58c600a69fd1a80d4351',
        'c3172764bc27c4016df731e60a6e4a2a',
        '7d35125780c55bd0e272b0ed03a5803c',
        'ab44196a56217165b61193d03fa8f475',
        '488b0ae6f6606cc2188d558afeb7b159',
        '4b8a04f926da0886d14edafdb7855cc6',
        '593fa109c9852384efcac92de22ac5d1',
        'ef3e67ab106bda2aaef946fad3dcb4a1',
        'dc237cdc0eec8fbca71342446d8a55a6',
        'c2a1893e4423798c1631e6c1bf3f98c2',
        '98cc9134f87afc897c6ada5e3920f9a9',
        '708844f5fbaffa0cab7cdafdeb177494',
        '79e1c3d4dac21d6e5e2524f976746739',
        '37e74db3c69b3466e06205db1f5bda18',
        '80462fe91dfa394b35743ac89088e20f',
        '4964481ee0e735e2465aad9aa9b3868c',
        '00a0cf7b6f79845a0fc7a35803087421',
        'd6072896603afaadc8144e27a45da1c6',
        '6d2bbf22bc71882310c7a83da310a5c8',
        '0b4eddee95161108a4e0d2e1d2ed48b4',
        '4aca81ab614aab81f27425f76672d394',
        '6ef68c44fb739b4752dcd54308b15ae7',
        '832905a21a76fe77cbf23bf236eb52d1',
        '3d7142edd199020fe963fef001ba1feb',
        '743957fbbcb0fae52b5cfa2211060a8a',
        '87ca7bdc024d83a067cdd28a1d3716bc',
        'eb62277ecea56ba26faa45e14345e8a3',
        '845f854beaf28c93bb63134255bc09d2',
        'aaa4c2d11a8f4cacbb3ca61d23034cae',
        '629c71ce730b152f452a4567b17d9ede',
        '3884a89d9abd1253a09f06fe53bb6a81',
        '5b3f4a28d2a9175fc8dfcbca7c500092',
        '46b21f2097e84abba608e74b5bcd847b',
        '26d3668402b23beefceebdef4c53b64c',
        'a481b6e86d0911708672a8b84180e118',
        '18bc763089404303c5f678d0a7f12e7b',
        'a731f9ec99e179d5c8e004fd1cff3f31',
        '5d34fc6c7fd139ad4073d91170949845',
        'ecc31cb0b32a46de0087f67a0a8f5645',
        '1d9048c2017dbbe3b4f91cfe5ad8b903',
        '7ce6c16bc7744db4964761dbfd6ee0dc',
        'ca25eb1e6f43bd8e6d28f485aa40f938',
        '57e4e35365243b2f9556be1ff606b272',
        'd4e33bd9b8320e74b1e6c289b130697a',
        'c7bcbe394a5d10ca495846c83676c4fb',
        'd6b836c585b5f0e27b3b5a307969c261',
        'd8b4046600e147e386622cc53b613335',
        '4b82ed77424c1d5ab2bc9cc24a0aa0b4',
        'ecfb87f100e28014b57106cc4b3e8933'
    ];

    public static function getLicenses()
    {
        return static::$licenses;
    }

    public static function valid()
    {
        if(strpos(Environment::get('requestUri'), '/contao/install') !== false)
        {
            return true;
        }

        if (static::$initialized === false)
        {
            static::$valid = EstateManager::checkLicenses(Config::get(static::$key), static::$licenses, static::$key);
            static::$initialized = true;
        }

        return static::$valid;
    }

}
