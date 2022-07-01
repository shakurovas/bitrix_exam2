<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"PRODUCTS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID"),
			"PARENT" => "BASE",
			"TYPE" => "STRING",
		),
		"CLASS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CLASS_IBLOCK_ID"),
			"PARENT" => "BASE",
			"TYPE" => "STRING",
		),
		"DETAIL_URL_TEMPLATE" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_DETAIL_URL_TEMPLATE"),
			"PARENT" => "BASE",
			"TYPE" => "STRING",
		),
		"GOODS_CLASS_BINDING" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_GOODS_CLASS_BINDING"),
			"TYPE" => "STRING",
		),
		"CACHE_TIME"  =>  array("DEFAULT"=>36000000),
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BNL_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	),
);