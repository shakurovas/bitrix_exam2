<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"NEWS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP2_EXAM2_NEWS_IBLOCK_ID"),
            "PARENT" => "BASE",
			"TYPE" => "STRING",
		),
        "AUTHOR_PROPERTY" => array(
            "NAME" => GetMessage("SIMPLECOMP2_EXAM2_AUTHOR_PROPERTY_CODE"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
        ),
        "AUTHOR_TYPE_PROPERTY" => array(
            "NAME" => GetMessage("SIMPLECOMP2_EXAM2_AUTHOR_TYPE_PROPERTY_CODE"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
        ),
        "CACHE_TIME" => array(
            "DEFAULT" => 36000000
        ),
        "CACHE_GROUPS" => array(
            "NAME" => GetMessage("CONSIDER_CACHE_GROUPS"),
            "PARENT" => "CACHE_SETTINGS",
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y"
        ),
	),
);