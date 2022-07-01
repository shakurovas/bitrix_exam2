<?php
if (isset($arResult["CANONICAL_LINK"])) {
    $APPLICATION->SetPageProperty('canonical', $arResult['CANONICAL_LINK']);
}

CJSCore::Init();
if ($_GET['TYPE'] == 'REPORT_RESULT') {
    if ($_GET['ID']) {
        // Формирование строки с результатом, вывод его в "ajax-report-text"
        echo '
            <script>
                var textElem = document.getElementById("ajax-report-text");
                textElem.innerText = "Ваше мнение учтено, №' . $_GET['ID'] . '";
                window.history.pushState(null, null, "' . $APPLICATION->GetCurPage() . '");
            </script>
        ';
    } else {
        echo '
            <script>
                var textElem = document.getElementById("ajax-report-text");
                textElem.innerText = "Ошибка!";
                window.history.pushState(null, null, "' . $APPLICATION->GetCurPage() . '");
            </script>
        ';
    }
        
} else if (isset($_GET['ID'])) {
    
    $jsonObject = array();

        if (CModule::IncludeModule('iblock')) {

            $sUser = '';

            if ($USER->IsAuthorized()) {
                // ID, Логин, ФИО пользователя
                $arUser = $USER->GetID() . " (" . $USER->GetLogin() . ") " . $USER->GetFullName();
            } else {
                $arUser = "Не авторизован";
            }

            $arField = array(
                // ИБ "Жалобы на новости"
                'IBLOCK_ID'       => 7,
                'NAME'            => 'Новость ' . $_GET['ID'],
                'ACTIVE_FROM'     => \Bitrix\Main\Type\DateTime::createFromTimestamp(time()),
                'PROPERTY_VALUES' => array(
                    'USERS' => $arUser,
                    'NEWS' => $_GET['ID'],
                ),
            );

            $element = new CIBlockElement(false);

            if ($elId = $element->Add($arField)) {
                $jsonObject['ID'] = $elId;

                if ($_GET['TYPE'] == 'REPORT_AJAX') {
                    $APPLICATION->RestartBuffer();
                    echo json_encode($jsonObject);
                    die();
                } else if ($_GET['TYPE'] == 'REPORT_GET') {
                    LocalRedirect($APPLICATION->GetCurPage() . "?TYPE=REPORT_RESULT&ID=" . $jsonObject['ID']);
                }
            } else {
                LocalRedirect($APPLICATION->GetCurPage() . "?TYPE=REPORT_RESULT");
            }
    }
}