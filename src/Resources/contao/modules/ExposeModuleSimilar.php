<?php
/**
 * This file is part of Oveleon ImmoManager.
 *
 * @link      https://github.com/oveleon/contao-immo-manager-bundle
 * @copyright Copyright (c) 2018-2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://github.com/oveleon/contao-immo-manager-bundle/blob/master/LICENSE
 */

namespace Oveleon\ContaoImmoManagerSimilarBundle;

use Oveleon\ContaoImmoManagerBundle\ExposeModule;
use Oveleon\ContaoImmoManagerBundle\RealEstateModel;

/**
 * Expose module "similar".
 *
 * @author Daniele Sciannimanica <daniele@oveleon.de>
 */
class ExposeModuleSimilar extends ExposeModule
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'expose_mod_similar';

    /**
     * Template item
     * @var string
     */
    protected $strSimilarItemTemplate = 'real_estate_default';

    /**
     * Template
     * @var string
     */
    protected $strTable = 'tl_real_estate';

    /**
     * Do not display the module if there are no real etates
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['similar'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=expose_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        $strBuffer = parent::generate();
        return $this->isEmpty && !!$this->hideOnEmpty ? '' : $strBuffer;
    }

    /**
     * Generate the module
     */
    protected function compile()
    {
        $this->parseRealEstateList();
    }

    /**
     * Count the total matching items
     *
     * @return integer
     */
    protected function countItems()
    {
        list($arrColumns, $arrValues, $arrOptions) = $this->getFilterOptions();

        return RealEstateModel::countBy($arrColumns, $arrValues, $arrOptions);
    }

    /**
     * Fetch the matching items
     *
     * @param integer $limit
     * @param integer $offset
     *
     * @return \Model\Collection|RealEstateModel|null
     */
    protected function fetchItems($limit, $offset)
    {
        list($arrColumns, $arrValues, $arrOptions) = $this->getFilterOptions();

        $arrOptions['limit']  = $limit;
        $arrOptions['offset'] = $offset;

        return RealEstateModel::findBy($arrColumns, $arrValues, $arrOptions);
    }

    /**
     * Collect necessary filter parameter for similar real estates and return it as array
     *
     * @return array
     */
    protected function getFilterOptions()
    {
        $t = $this->strTable;

        $arrColumns = array("$t.published='1'");
        $arrValues = array();
        $arrOptions = array();

        $arrColumns[] = "$t.id!=?";
        $arrValues[] = $this->realEstate->getId();
        
        $objType = $this->realEstate->getType();

        if (!empty($objType->nutzungsart))
        {
            $arrColumns[] = "$t.nutzungsart=?";
            $arrValues[] = $objType->nutzungsart;
        }
        if ($objType->vermarktungsart === 'kauf_erbpacht')
        {
            $arrColumns[] = "($t.vermarktungsartKauf='1' OR $t.vermarktungsartErbpacht='1')";
        }
        if ($objType->vermarktungsart === 'miete_leasing')
        {
            $arrColumns[] = "($t.vermarktungsartMietePacht='1' OR $t.vermarktungsartLeasing='1')";
        }
        if (!empty($objType->objektart))
        {
            $arrColumns[] = "$t.objektart=?";
            $arrValues[] = $objType->objektart;
        }

        if ($_SESSION['FILTER_DATA']['price_from'])
        {
            $arrColumns[] = "$t.".$objType->price.">=?";
            $arrValues[] = $_SESSION['FILTER_DATA']['price_from'];
        }
        if ($_SESSION['FILTER_DATA']['price_to'])
        {
            $arrColumns[] = "$t.".$objType->price."<=?";
            $arrValues[] = $_SESSION['FILTER_DATA']['price_to'];
        }
        if ($_SESSION['FILTER_DATA']['room_from'])
        {
            $arrColumns[] = "$t.anzahlZimmer>=?";
            $arrValues[] = $_SESSION['FILTER_DATA']['room_from'];
        }
        if ($_SESSION['FILTER_DATA']['room_to'])
        {
            $arrColumns[] = "$t.anzahlZimmer<=?";
            $arrValues[] = $_SESSION['FILTER_DATA']['room_to'];
        }
        if ($_SESSION['FILTER_DATA']['area_from'])
        {
            $arrColumns[] = "$t.".$objType->area.">=?";
            $arrValues[] = $_SESSION['FILTER_DATA']['area_from'];
        }
        if ($_SESSION['FILTER_DATA']['area_to'])
        {
            $arrColumns[] = "$t.".$objType->area."<=?";
            $arrValues[] = $_SESSION['FILTER_DATA']['area_to'];
        }

        return array($arrColumns, $arrValues, $arrOptions);
    }
}