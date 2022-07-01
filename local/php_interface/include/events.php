<?php
IncludeModuleLangFile(__FILE__);
AddEventHandler("main", "OnBeforeEventAdd", array("Ex2", "OnBeforeEventAddHandler"));
AddEventHandler("main", "OnBuildGlobalMenu", array("Ex2", "OnBuildGlobalMenuHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", array("Ex2", "OnBeforeIBlockElementUpdateHandler"));

class Ex2
{

    function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {
        if ($arFields['IBLOCK_ID'] == 2) {
            if ($arFields['ACTIVE'] == 'N') {

                $res = CIBlockElement::GetList(
                    array(),
                    array(
                        "IBLOCK_ID" => 2,
                        "ID" => $arFields['ID']
                    ),
                    false,
                    false,
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "NAME",
                        "SHOW_COUNTER"
                    )
                );

                $arItems = $res->Fetch();
                // global $APPLICATION;
                // echo '<pre>';
                // print_r($arItems);
                // echo '</pre>';
                // $APPLICATION->throwException('Error');
                // return false;

                if ($arItems['SHOW_COUNTER'] > 2) {
                    global $APPLICATION;
                    $text = GetMessage('ERROR_MESSAGE', array('#COUNT#' => $arItems['SHOW_COUNTER']));
                    $APPLICATION->throwException($text);
                    return false;
                }
            }
    }
}

    function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {
        if ($event = 'FEEDBACK_FORM') {
            global $USER;
            if ($USER->isAuthorized()) {
                $arFields['AUTHOR'] = GetMessage('MESS_FOR_AUTHORIZED_USER', array(
                    '#ID#' => $USER->GetID(),
                    '#LOGIN#' => $USER->GetLogin(),
                    '#NAME#' => $USER->GetFullName(),
                    '#NAME_FORM#' => $arFields['AUTHOR'],
                    )
                );
            } else {
                $arFields['AUTHOR'] = GetMessage('MESS_FOR_NOT_AUTHORIZED_USER', array(
                    '#NAME_FORM#' => $arFields['AUTHOR'],
                    )
                );

            }
            CEventLog::Add(array(
                "SEVERITY" => "SECURITY",
                "AUDIT_TYPE_ID" => GetMessage('CHANGING_DATA_IN_MAIL'),
                "MODULE_ID" => "main",
                "ITEM_ID" => $event,
                "DESCRIPTION" => GetMessage('CHANGING_DATA_IN_MAIL') . ' – ' . $arFields['AUTHOR'],
            ));
        }
    }

    function OnBuildGlobalMenuHandler(&$aGlobalMenu, &$aModuleMenu)
    {
        $isManager = false;
        $isAdmin = false;

//        echo '<pre>';
//        print_r($aModuleMenu);
//        echo '</pre>';

        global $USER;
        $usersGroup = CUser::GetUserGroupList($USER->GetId());
        $contentEditorGroupID = CGroup::GetList(
            $by = 'c_sort',
            $order = "asc",
            array("STRING_ID" => "content_editor")
        )->Fetch()["ID"];

        while ($group = $usersGroup->Fetch()) {
            if ($group["GROUP_ID"] == 1) {
                $isAdmin = true;
            }
            if ($group["GROUP_ID"] == $contentEditorGroupID) {
                $isManager = true;
            }
        }

        // если пользователь принадлежит группе контент-менеджеров, но не админов, убираем пункты меню
        if (!$isAdmin && $isManager) {
            foreach ($aModuleMenu as $key => $value) {
                if ($value["items_id"] == "menu_iblock_/news") {
                    $aModuleMenu = [$value];
                    foreach ($value["items"] as $childItem) {
                        if ($childItem["items_id"] == "menu_iblock_/news/1") {
                            $aModuleMenu[0]["items"] = [$childItem];
                            break;
                        }
                    }
                    break;
                }
            }
            $aGlobalMenu = ["global_menu_content" => $aGlobalMenu["global_menu_content"]];
        }
    }
}
