<?php
if (isset($arResult["CANONICAL_LINK"])) {
    $APPLICATION->SetPageProperty('canonical', $arResult['CANONICAL_LINK']);
}