<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (isset($arResult['MAX_PRICE']) && isset($arResult['MIN_PRICE'])) {
    $infoTemplates = '<div style="color:red; margin: 34px 15px 35px 15px">Min price: ' . $arResult['MIN_PRICE'] . '<br>Max price: ' . $arResult['MAX_PRICE'] . '</div>';
//    $sText = ;
    $APPLICATION->AddViewContent('min_max_prices', $infoTemplates);
}