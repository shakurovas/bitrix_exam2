<?php
AddEventHandler("main", "OnBeforeEventAdd", array("Ex2", "OnBeforeEventAddHandler"));
AddEventHandler("main", "OnBuildGlobalMenu", array("Ex2", "OnBuildGlobalMenuHandler"));

class Ex2
{
    function OnBeforeEventAddHandler(&$event, &$lid, &$arFields){
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

    function OnBuildGlobalMenuHandler(&$aGlobalMenu, &$aModuleMenu){
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
