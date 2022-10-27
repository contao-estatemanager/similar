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

namespace ContaoEstateManager\Similar;

use Contao\BackendTemplate;
use Contao\Model\Collection;
use Contao\PageModel;
use ContaoEstateManager\ExposeModule;
use ContaoEstateManager\RealEstateModel;

/**
 * Expose module "similar".
 *
 * @author Daniele Sciannimanica <daniele@oveleon.de>
 */
class ExposeModuleSimilar extends ExposeModule
{
    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'expose_mod_similar';

    /**
     * Do not display the module if there are no real estates.
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE === 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### '.mb_strtoupper($GLOBALS['TL_LANG']['FMD']['similar'][0], 'UTF-8').' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=expose_module&amp;act=edit&amp;id='.$this->id;

            return $objTemplate->parse();
        }

        $strBuffer = parent::generate();

        return $this->isEmpty && (bool) $this->hideOnEmpty ? '' : $strBuffer;
    }

    /**
     * Generate the module.
     */
    protected function compile(): void
    {
        $this->parseRealEstateList();
    }

    /**
     * Count the total matching items.
     */
    protected function countItems(): int
    {
        $arrFilterOptions = $this->getFilterOptions();

        if (null === $arrFilterOptions)
        {
            return 0;
        }

        [$arrColumns, $arrValues, $arrOptions] = $arrFilterOptions;

        return RealEstateModel::countPublishedBy($arrColumns, $arrValues, $arrOptions);
    }

    /**
     * Fetch the matching items.
     *
     * @param int $limit
     * @param int $offset
     *
     * @return Collection|RealEstateModel|null
     */
    protected function fetchItems($limit, $offset): ?object
    {
        $arrFilterOptions = $this->getFilterOptions();

        if (null === $arrFilterOptions)
        {
            return null;
        }

        [$arrColumns, $arrValues, $arrOptions] = $arrFilterOptions;

        $arrOptions['limit'] = $limit;
        $arrOptions['offset'] = $offset;

        return RealEstateModel::findPublishedBy($arrColumns, $arrValues, $arrOptions);
    }

    /**
     * Collect necessary filter parameter for similar real estates and return it as array.
     *
     * @return array
     */
    protected function getFilterOptions(): ?array
    {
        $t = 'tl_real_estate';

        $arrColumns = [];
        $arrValues = [];
        $arrOptions = [];

        /** @var PageModel $objPage */
        global $objPage;

        $pageDetails = $objPage->loadDetails();
        $objRootPage = PageModel::findByPk($pageDetails->rootId);

        if ($objRootPage->realEstateQueryLanguage)
        {
            $arrColumns[] = "$t.sprache=?";
            $arrValues[] = $objRootPage->realEstateQueryLanguage;
        }

        $arrColumns[] = "$t.id!=?";
        $arrValues[] = $this->realEstate->id;

        $objType = $this->realEstate->getType();

        if (null === $objType)
        {
            return null;
        }

        if ('kauf_erbpacht' === $objType->vermarktungsart)
        {
            $arrColumns[] = "($t.vermarktungsartKauf='1' OR $t.vermarktungsartErbpacht='1')";
        }
        elseif ('miete_leasing' === $objType->vermarktungsart)
        {
            $arrColumns[] = "($t.vermarktungsartMietePacht='1' OR $t.vermarktungsartLeasing='1')";
        }

        if (!empty($objType->nutzungsart))
        {
            $arrColumns[] = "$t.nutzungsart=?";
            $arrValues[] = $objType->nutzungsart;
        }

        if (!empty($objType->objektart))
        {
            $arrColumns[] = "$t.objektart=?";
            $arrValues[] = $objType->objektart;
        }

        if ($_SESSION['FILTER_DATA']['price_from'] ?? null)
        {
            $priceFrom = (int) ($_SESSION['FILTER_DATA']['price_from']);
        }
        elseif ($this->realEstate->{$objType->price})
        {
            $priceFrom = $this->realEstate->{$objType->price};
        }

        if ($priceFrom ?? null)
        {
            $arrColumns[] = "$t.".$objType->price.'>=?';
            $arrValues[] = $priceFrom - ($priceFrom * $this->filterCoarse / 100) > 0 ?: 0;
        }

        if ($_SESSION['FILTER_DATA']['price_to'] ?? null)
        {
            $priceTo = (int) ($_SESSION['FILTER_DATA']['price_to']);
        }
        elseif ($this->realEstate->{$objType->price})
        {
            $priceTo = $this->realEstate->{$objType->price};
        }

        if ($priceTo ?? null)
        {
            $arrColumns[] = "$t.".$objType->price.'<=?';
            $arrValues[] = $priceTo + ($priceTo * $this->filterCoarse / 100);
        }

        if ($_SESSION['FILTER_DATA']['room_from'] ?? null)
        {
            $roomFrom = (int) ($_SESSION['FILTER_DATA']['room_from']);
        }
        elseif ($this->realEstate->anzahlZimmer)
        {
            $roomFrom = $this->realEstate->anzahlZimmer;
        }

        if ($roomFrom ?? null)
        {
            $arrColumns[] = "$t.anzahlZimmer>=?";
            $arrValues[] = floor($roomFrom - ($roomFrom * $this->filterCoarse / 100)) > 0 ?: 0;
        }

        if ($_SESSION['FILTER_DATA']['room_to'] ?? null)
        {
            $roomTo = (int) ($_SESSION['FILTER_DATA']['room_to']);
        }
        elseif ($this->realEstate->anzahlZimmer)
        {
            $roomTo = $this->realEstate->anzahlZimmer;
        }

        if ($roomTo ?? null)
        {
            $arrColumns[] = "$t.anzahlZimmer<=?";
            $arrValues[] = ceil($roomTo + ($roomTo * $this->filterCoarse / 100));
        }

        if ($_SESSION['FILTER_DATA']['area_from'] ?? null)
        {
            $areaFrom = (int) ($_SESSION['FILTER_DATA']['area_from']);
        }
        elseif ($this->realEstate->{$objType->area})
        {
            $areaFrom = $this->realEstate->{$objType->area};
        }

        if ($areaFrom ?? null)
        {
            $arrColumns[] = "$t.".$objType->area.'>=?';
            $arrValues[] = $areaFrom - ($areaFrom * $this->filterCoarse / 100) > 0 ?: 0;
        }

        if ($_SESSION['FILTER_DATA']['area_to'] ?? null)
        {
            $areaTo = (int) ($_SESSION['FILTER_DATA']['area_to']);
        }
        elseif ($this->realEstate->{$objType->area})
        {
            $areaTo = $this->realEstate->{$objType->area};
        }

        if ($areaTo ?? null)
        {
            $arrColumns[] = "$t.".$objType->area.'<=?';
            $arrValues[] = floor($areaTo + ($areaTo * $this->filterCoarse / 100));
        }

        if ($this->similarDistance > 0)
        {
            $arrColumns[] = "(6371*acos(cos(radians(?))*cos(radians($t.breitengrad))*cos(radians($t.laengengrad)-radians(?))+sin(radians(?))*sin(radians($t.breitengrad)))) <= ?";
            $arrValues[] = $this->realEstate->breitengrad;
            $arrValues[] = $this->realEstate->laengengrad;
            $arrValues[] = $this->realEstate->breitengrad;
            $arrValues[] = $this->similarDistance;
        }

        // HOOK: custom filter
        if (isset($GLOBALS['TL_HOOKS']['getSimilarFilterOptions']) && \is_array($GLOBALS['TL_HOOKS']['getSimilarFilterOptions']))
        {
            foreach ($GLOBALS['TL_HOOKS']['getSimilarFilterOptions'] as $callback)
            {
                $this->import($callback[0]);
                $this->{$callback[0]}->{$callback[1]}($arrColumns, $arrValues, $arrOptions, $this->realEstate);
            }
        }

        return [$arrColumns, $arrValues, $arrOptions];
    }
}
