<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}

// echo '<pre>';
// print_r($arParams);
// echo '</pre>';

if (empty($arParams['CACHE_TIME'])) {
	$arParams['CACHE_TIME'] = 36000000;
}

if (empty($arParams['PRODUCTS_IBLOCK_ID'])) {
	$arParams['PRODUCTS_IBLOCK_ID'] = 0;
}

if (empty($arParams['CLASS_IBLOCK_ID'])) {
	$arParams['CLASS_IBLOCK_ID'] = 0;
}

$arParams['GOODS_CLASS_BINDING'] = trim($arParams['GOODS_CLASS_BINDING']);

global $USER;

if ($this->startResultCache(false, $USER->GetGroups())) {

	$arClass = array();
	$arClassId = array();
	$arResult['COUNT'] = 0;

	$arSelectElems = array (
		"ID",
		"IBLOCK_ID",
		"NAME"
	);
	$arFilterElems = array (
		"IBLOCK_ID" => $arParams["CLASS_IBLOCK_ID"],
		"CHECK_PERMISSIONS" => $arParams['CACHE_GROUPS'],
		"ACTIVE" => "Y"
	);
	$rsElements = CIBlockElement::GetList(array(), $arFilterElems, false, false, $arSelectElems);
	while($arElement = $rsElements->GetNext())
	{
		$arClass[$arElement['ID']] = $arElement;
		$arClassId[] = $arElement['ID'];

	}

	$arResult['COUNT'] = count($arClassId);

	// echo '<pre>';
	// print_r($arClass);
	// echo '</pre>';

	$arSelectElemsCatalog = array (
		"ID",
		"IBLOCK_ID",
		"IBLOCK_SECTION_ID",
		"NAME",
		"DETAIL_PAGE_URL",
	);
	$arFilterElemsCatalog = array (
		"IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
		"CHECK_PERMISSIONS" => $arParams['CACHE_GROUPS'],
		"PROPERTY_" . $arParams['GOODS_CLASS_BINDING'] => $arClassId,
		"ACTIVE" => "Y"
	);

	$arResult['ELEMENTS'] = array();
	$rsElements = CIBlockElement::GetList(array(), $arFilterElemsCatalog, false, false, $arSelectElemsCatalog);
	while($rsEl = $rsElements->GetNextElement())
	{
		$arField = $rsEl->GetFields();
		$arField['PROPERTY'] = $rsEl->GetProperties();

		foreach ($arField['PROPERTY']['FIRM']['VALUE'] as $value) {
			$arClass[$value]["ELEMENTS"][$arField['ID']] = $arField;

		}
		// $arResult['ELEMENTS'][$arField['ID']] = $arField; 
	}
	$arResult['CLASSIFIER'] = $arClass;

	// echo '<pre>';
	// print_r($arResult);
	// echo '</pre>';

	$this->SetResultCacheKeys(array("COUNT"));
	$this->includeComponentTemplate();
	
} else {
	$this->abortResultCache();
}

if(intval($arParams["PRODUCTS_IBLOCK_ID"]) > 0)
{
	
	//iblock elements
	$arSelectElems = array (
		"ID",
		"IBLOCK_ID",
		"NAME",
	);
	$arFilterElems = array (
		"IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
		"ACTIVE" => "Y"
	);
	$arSortElems = array (
			"NAME" => "ASC"
	);
	
	$arResult["ELEMENTS"] = array();
	$rsElements = CIBlockElement::GetList($arSortElems, $arFilterElems, false, false, $arSelectElems);
	while($arElement = $rsElements->GetNext())
	{
		$arResult["ELEMENTS"][] = $arElement;
	}
	
	//iblock sections
	$arSelectSect = array (
			"ID",
			"IBLOCK_ID",
			"NAME",
	);
	$arFilterSect = array (
			"IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
			"ACTIVE" => "Y"
	);
	$arSortSect = array (
			"NAME" => "ASC"
	);
	
	$arResult["SECTIONS"] = array();
	$rsSections = CIBlockSection::GetList($arSortSect, $arFilterSect, false, $arSelectSect, false);
	while($arSection = $rsSections->GetNext())
	{
		$arResult["SECTIONS"][] = $arSection;
	}
		
	// user
	$arOrderUser = array("id");
	$sortOrder = "asc";
	$arFilterUser = array(
		"ACTIVE" => "Y"
	);
	
	$arResult["USERS"] = array();
	$rsUsers = CUser::GetList($arOrderUser, $sortOrder, $arFilterUser); // выбираем пользователей
	while($arUser = $rsUsers->GetNext())
	{
		$arResult["USERS"][] = $arUser;
	}	
	
	
}
// $this->includeComponentTemplate();
$APPLICATION->SetTitle(GetMessage('SIMPLECOMP_EXAM2_COUNT') . $arResult['COUNT']);
?>