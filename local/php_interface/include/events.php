<?php
AddEventHandler("main", "OnBeforeEventAdd", array("Ex2", "OnBeforeEventAddHandler"));

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
}
