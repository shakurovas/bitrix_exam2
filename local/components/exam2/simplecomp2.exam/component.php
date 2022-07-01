<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}
if (empty($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TIME'] = 36000000;
}

if (empty($arParams['NEWS_IBLOCK_ID'])) {
    $arParams['NEWS_IBLOCK_ID'] = 0;
}

$arParams['AUTHOR_TYPE_PROPERTY'] = trim($arParams['AUTHOR_TYPE_PROPERTY']);
$arParams['AUTHOR_PROPERTY'] = trim($arParams['AUTHOR_PROPERTY']);

global $USER;

if ($USER->IsAuthorized()) {
    $arResult['AMOUNT'] = 0;
    $currentUserId = $USER->GetID();
    $currentUserType = CUser::GetList(
        $by = "id",
        $order = "asc",
        array("ID" => $currentUserId),
        array("SELECT" => array($arParams['AUTHOR_TYPE_PROPERTY'])),
    )->Fetch()[$arParams['AUTHOR_TYPE_PROPERTY']];
//    print_r($currentUserId);
//    print_r($currentUserType);
}

if ($USER->IsAuthorized()) {
    $arButtons = CIBlock::GetPanelButtons($arParams['PRODUCTS_IBLOCK_ID']);
//    echo '<pre>';
//    print_r($arButtons);
//    echo '</pre>';
    $this->AddIncludeAreaIcons(
        array(
            array(
                'ID' => 'ib_in_admin',
                'TITLE' => GetMessage('IB_IN_ADMIN'),
                'URL' => $arButtons['submenu']['element_list']['ACTION_URL'],
                'IN_PARAMS_MENU' => true,
            )
        )
    );
}

if ($this->StartResultCache(false, array($currentUserId, $currentUserType))) {

    $rsUsers = CUser::GetList(
        $by = "id",
        $order = "desc",
        array(
            $arParams['AUTHOR_TYPE_PROPERTY'] => $currentUserType,
//            "!ID" => $currentUserId,
        ),
        array(
            "SELECT" => array("LOGIN", "ID"),
        ),
    ); // выбираем пользователей

    while($arUser = $rsUsers->Fetch())
    {
        $userListId[] = $arUser['ID'];
        $userList[$arUser['ID']] = array("LOGIN" => $arUser['LOGIN']);
    }

    $arNewsAuthor = array();
    $arNewsList = array();

    $rsElements = CIBlockElement::GetList(
        array(),
        array(
            "IBLOCK_ID" => $arParams['NEWS_IBLOCK_ID'],
            "PROPERTY_" . $arParams['AUTHOR_PROPERTY'] => $userListId,
        ),
        false,
        false,
        array(
            "NAME",
            "ACTIVE_FROM",
            "ID",
            "IBLOCK_ID",
            "PROPERTY_" . $arParams['AUTHOR_PROPERTY']
        )
    );

//    echo '<pre>';
//    print_r($rsElements);
//    echo '</pre>';

    $arNwsId = array();
    while($arElement = $rsElements->Fetch())
    {
        $arNewsAuthor[$arElement['ID']][] = $arElement["PROPERTY_" . $arParams['AUTHOR_PROPERTY'] . "_VALUE"];

        if (empty($arNewsList[$arElement['ID']])) {
            $arNewsList[$arElement['ID']] = $arElement;
        }

        if ($arElement["PROPERTY_" . $arParams['AUTHOR_PROPERTY'] . "_VALUE"] != $currentUserId) {
            $arNewsList[$arElement["ID"]]["AUTHORS"][] = $arElement["PROPERTY_" . $arParams['AUTHOR_PROPERTY'] . "_VALUE"];
        }
    }

    foreach ($arNewsList as $key => $value) {
        if (in_array($currentUserId, $arNewsAuthor[$value['ID']])) {
            continue;
        }
        foreach ($value['AUTHORS'] as $authorId) {
            $userList[$authorId]['NEWS'][] = $value;
            $arNewsId[$value['ID']] = $value['ID'];
        }
    }

    unset($userList[$currentUserId]);

    $arResult['AUTHORS'] = $userList;
    $arResult['AMOUNT'] = count($arNewsId);
    $this->SetResultCacheKeys('AMOUNT');
    $this->includeComponentTemplate();

//    echo '<pre>';
//    print_r($arResult);
//    echo '</pre>';
//
//    echo '<pre>';
//    print_r($arParams);
//    echo '</pre>';

} else {
    $this->AbortResultCache();
}

$APPLICATION->SetTitle(GetMessage('AMOUNT') . $arResult['AMOUNT']);

?>