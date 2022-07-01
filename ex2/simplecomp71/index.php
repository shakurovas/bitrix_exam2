<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент 71");
?><?$APPLICATION->IncludeComponent(
	"exam2:simplecomp.exam71", 
	".default", 
	array(
		"PRODUCTS_IBLOCK_ID" => "2",
		"COMPONENT_TEMPLATE" => ".default",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"CLASS_IBLOCK_ID" => "8",
		"DETAIL_URL_TEMPLATE" => "",
		"GOODS_CLASS_BINDING" => "FIRM"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>