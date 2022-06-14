<?php
if (!empty($arParams['ID_IBLOCK_CANONICAL'])) {
    $res = CIBlockElement::GetList(
        array(),
        array(
            "IBLOCK_ID" => $arParams['ID_IBLOCK_CANONICAL'],
            "PROPERTY_NEWS" => $arResult['ID'],
            "ACTIVE" => 'Y',
        ),
        false,
        false,
        array(
            "ID",
            "IBLOCK_ID",
            "NAME",
            "PROPERTY_NEWS"
        )
    );
    if ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arResult['CANONICAL_LINK'] = $arFields['NAME'];
        $this->__component->SetResultCacheKeys(array('CANONICAL_LINK'));
    }
}

