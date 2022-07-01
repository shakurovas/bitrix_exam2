<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"PRODUCTS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID"),
            "PARENT" => "BASE",
			"TYPE" => "STRING",
		),
        "NEWS_IBLOCK_ID" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
        ),
        "PRODUCTS_IBLOCK_ID_PROPERTY" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_PROPERTY_IBLOCK_ID"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
        ),
        "DETAIL_LINK" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_DETAIL_LINK"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
            "DEFAULT" => 'catalog_exam/#SECTION_ID#/#ELEMENT_CODE#',
        ),
        "ELEMENTS_PER_PAGE" => array(
            "NAME" => GetMessage("ELEMENTS_PER_PAGE"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
            "DEFAULT" => 2
        ),
        "CACHE_TIME" => array(
            "DEAFAULT" => 36000000,
        )
	),
);