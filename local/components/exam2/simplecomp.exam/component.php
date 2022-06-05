<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}
//
//echo '<pre>';
//print_r($arParams);
//echo '</pre>';

if(!isset($arParams['CACHE_TIME'])){
    $arParams['CACHE_TIME'] = 36000000;
}

if(!isset($arParams['PRODUCTS_IBLOCK_ID'])){
    $arParams['PRODUCTS_IBLOCK_ID'] = 0;
}

if(!isset($arParams['NEWS_IBLOCK_ID'])){
    $arParams['NEWS_IBLOCK_ID'] = 0;
}

if($this->startResultCache()){
    $arNews = array();
    $arNewsID = array();
    $obNews = CIBlockElement::GetList(
        array(),
        array(
            "IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
            "ACTIVE" => "Y",
        ),
        false,
        false,
        array(
            "NAME",
            "ACTIVE_FROM",
            "ID"
        )
    );

    while ($newsElements = $obNews->Fetch()) {
        $arNewsID[] = $newsElements['ID'];
        $arNews[$newsElements['ID']] = $newsElements;
    }

    $arSections =  array();
    $arSectionsID =  array();
//    $arSectionCatalog = array();
//    $arSectionCatalogID = array();

    $obSection = CIBlockSection::GetList(
        array(),
        array(
            "IBLOCK_ID" => $arParams['PRODUCTS_IBLOCK_ID'],
            "ACTIVE",
            $arParams['PRODUCTS_IBLOCK_ID_PROPERTY'] => $arNewsID
        ),
        false,
        array(
            "NAME",
            "IBLOCK_ID",
            "ID",
            $arParams['PRODUCTS_IBLOCK_ID_PROPERTY'],
        ),
        false,
    );

    while ($arSectionCatalog = $obSection->Fetch()) {
        $arSectionsID[] = $arSectionCatalog['ID'];
        $arSections[$arSectionCatalog['ID']] = $arSectionCatalog;
    }

    $arNewsList = array();

    $obProduct = CIBlockElement::GetList(
        array(),
        array(
            "IBLOCK_ID" => $arParams['PRODUCTS_IBLOCK_ID'],
            "ACTIVE" => 'Y',
            "SECTION_ID" => $arSectionsID,
            ),
        false,
        false,
        array(
            "NAME",
            "IBLOCK_SECTION_ID",
            "ID",
            "IBLOCK_ID",
            "PROPERTY_ARTNUMBER",
            "PROPERTY_MATERIAL",
            "PROPERTY_PRICE",
        )
    );

    $arResult['PRODUCT_COUNT'] = 0;

    while ($arProduct = $obProduct->Fetch()) {
        $arResult['PRODUCT_COUNT'] += 1;
        foreach($arSections[$arProduct["IBLOCK_SECTION_ID"]][$arParams['PRODUCTS_IBLOCK_ID_PROPERTY']] as $newsId){
            $arNews[$newsId]['PRODUCTS'][] = $arProduct;
        }
    }

    foreach ($arSections as $arSection) {
        foreach ($arSection[$arParams['PRODUCTS_IBLOCK_ID_PROPERTY']] as $newId) {
            $arNews[$newId]['SECTIONS'][] = $arSection['NAME'];
        }
    }

    $arResult['NEWS'] = $arNews;
    $this->SetResultCacheKeys(array('PRODUCT_COUNT'));
    $this->includeComponentTemplate();

//    echo '<pre>';
//    print_r($arNews);
//    echo '</pre>';
} else {
    $this->abortResultCache();
}
$APPLICATION->SetTitle(GetMessage('AMOUNT') . $arResult['PRODUCT_COUNT']);
?>