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

$cFilter = false;
if (isset($_REQUEST['F'])) {
    $cFilter = true;
}

// echo '<pre>';
// print_r($arParams['DETAIL_LINK']);
// echo '</pre>';

$arNavigation = CDBResult::GetNavParams($arNavParams);
//print_r($arNavigation);

global $CACHE_MANAGER;

if($this->startResultCache(false, array($arNavigation, $cFilter), 'servicesIblock')){

    $CACHE_MANAGER->RegisterTag('iblock_id_3');
    $arNews = array();
    $arNewsID = array();
    $obNews = CIBlockElement::GetList(
        array(),
        array(
            "IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
            "ACTIVE" => "Y",
        ),
        false,
        array(
            "nPageSize" => $arParams['ELEMENTS_PER_PAGE'],
            "bShowAll" => true,
        ),
        array(
            "NAME",
            "ACTIVE_FROM",
            "ID"
        )
    );

    $arResult['NAV_STRING'] = $obNews->GetPageNavString(GetMessage('PAGE_TITLE'));

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

    $arFilterElements = array(
        "IBLOCK_ID" => $arParams['PRODUCTS_IBLOCK_ID'],
        "ACTIVE" => 'Y',
        "SECTION_ID" => $arSectionsID
    );

    if ($cFilter) {
        $arFilterElements[] = array(
            array("<=PROPERTY_PRICE" => 1700, "PROPERTY_MATERIAL" => 'Дерево, ткань'),
            array("<PROPERTY_PRICE" => 1500, "PROPERTY_MATERIAL" => 'Металл, пластик'),
            "LOGIC" => "OR"
        );
        $this->AbortResultCache();
    }

    $arNewsList = array();

    $obProduct = CIBlockElement::GetList(
        array(
            "NAME" => 'asc',
            "SORT" => 'asc'
        ),
        // array(
        //     "IBLOCK_ID" => $arParams['PRODUCTS_IBLOCK_ID'],
        //     "ACTIVE" => 'Y',
        //     "SECTION_ID" => $arSectionsID,
        //     ),
        $arFilterElements,
        false,
        false,
        array(
            "NAME",
            "IBLOCK_SECTION_ID",
            "ID",
            "CODE",
            "IBLOCK_ID",
            "PROPERTY_ARTNUMBER",
            "PROPERTY_MATERIAL",
            "PROPERTY_PRICE",
        )
    );

    $arResult['PRODUCT_COUNT'] = 0;

    while ($arProduct = $obProduct->Fetch()) {
        $arResult['PRODUCT_COUNT'] += 1;

        $arProduct['DETAIL_PAGE_URL'] = str_replace(
            array(
                "#SECTION_ID#",
                "#ELEMENT_CODE#"
            ),
            array(
                $arProduct['IBLOCK_SECTION_ID'],
                $arProduct['CODE']
            ),
            $arParams['DETAIL_LINK']
        );
        // $arButtons = CIBlock::GetPanelButtons(
        //     $arParams['PRODUCTS_IBLOCK_ID'],
        //     $arProduct['ID'],
        //     0,
        //     array("SECTION_BUTTONS" => false, "SESSID" => false),
        // );
        // $arProduct['EDIT_LINK'] = $arButtons['edit']['edit_element']['ACTION_URL'];
        // $arProduct['DELETE_LINK'] = $arButtons['edit']['delete_element']['ACTION_URL'];

        // $arResult['ADD_LINK'] = $arButtons['edit']['add_element']['ACTION_URL'];
        // $arResult['IBLOCK_ID'] = $arParams['PRODUCTS_IBLOCK_ID'];

        foreach($arSections[$arProduct["IBLOCK_SECTION_ID"]][$arParams['PRODUCTS_IBLOCK_ID_PROPERTY']] as $newsId){
            if (isset($arNews[$newsId])) {
                $arNews[$newsId]['PRODUCTS'][] = $arProduct;
            }
        }
    }

    foreach ($arSections as $arSection) {
        foreach ($arSection[$arParams['PRODUCTS_IBLOCK_ID_PROPERTY']] as $newId) {
            if (isset($arNews[$newId])) {
                $arNews[$newId]['SECTIONS'][] = $arSection['NAME'];
            }
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
