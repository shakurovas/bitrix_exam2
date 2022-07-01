<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = 'mcart.ipchecker';

Loader::includeModule($module_id);
//CJSCore::Init(array("jquery","date"));
//CUtil::InitJSCore();

$aTabs  = [
    [
        'DIV'     => 'urlSettings',
        'TAB'     => Loc::getMessage('URL_SETTINGS_TAB_TITLE'),
        'TITLE'   => Loc::getMessage('URL_SETTINGS_TAB_TITLE'),
        'OPTIONS' => [
            [
                'OUTER_URLS_FOR_CHECKING',
                Loc::getMessage('URL_FOR_CHECKING'),
                '',
                ['text', 80]
            ],
            [
                'INNER_URLS_FOR_CHECKING',
                Loc::getMessage('URL_FOR_CHECKING'),
                '',
                ['text', 80]
            ],
        ]
    ],
    [
        'DIV'     => 'notificationsSettings',
        'TAB'     => Loc::getMessage('NOTIFICATIONS_SETTINGS_TAB_TITLE'),
        'TITLE'   => Loc::getMessage('NOTIFICATIONS_SETTINGS_TAB_TITLE'),
        'OPTIONS' => [
            [
                'EMAIL_WHERE_SEND_TO',
                Loc::getMessage('EMAIL'),
                '',
                ['text', 80]
            ],
            [
                'SEND_ONLY_ERRORS',
                Loc::getMessage('SEND_ONLY_ERRORS'),
                '',
                ['checkbox']
            ],
        ]
    ],
];

$tabControl = new CAdminTabControl('tabControl', $aTabs);
$tabControl->begin();
$arrOptionValue = Option::getForModule($module_id);?>

<form action="<?= $APPLICATION->getCurPage(); ?>?mid=<?=$module_id; ?>&lang=<?= LANGUAGE_ID; ?>" method="post" name="url_for_checking_form">
    <?= bitrix_sessid_post(); ?>

    <?php $tabControl->beginNextTab();?>
    
    <tr class="heading">
        <td colspan="2"><?=GetMessage('BITRIX_SERVICES');?></td>
    </tr>
    <?php
    $outerInputValues = explode(',', $arrOptionValue['OUTER_URLS_FOR_CHECKING']);
    for ($i=0; $i<count($outerInputValues); $i++) {?>
        <tr>
            <td>
                <label style="width: 30%; display: inline-block;"><?=GetMessage('URL_FOR_CHECKING');?></label>
                <input type="text" name="OUTER_URLS_FOR_CHECKING[]"
                    value="<?=$outerInputValues[$i] ? $outerInputValues[$i] : '' ?>" size="40">
            </td>
        </tr>
    <?}?>

    <tr>
	    <td colspan="2">
            <script type="text/javascript">
                function settingsAddHost(a)
                {
                    var row = jsUtils.FindParentObject(a, "tr");
                    var tbl = row.parentNode;

                    var tableRow = tbl.rows[row.rowIndex-1].cloneNode(true);
                    tbl.insertBefore(tableRow, row);

                    var sel = jsUtils.FindChildObject(tableRow.cells[1], "select");
                    sel.name = "";
                    sel.selectedIndex = 0;

                    var div = jsUtils.FindNextSibling(sel, "div");
                    div.style.display = "none";
                    sel = jsUtils.FindChildObject(div, "select");
                    sel.name = "";
                    sel.selectedIndex = -1;

                    sel = jsUtils.FindChildObject(tableRow.cells[0], "select");
                    sel.selectedIndex = 0;
                }
            </script>
            <a href="javascript:void(0)" onclick="settingsAddHost(this)" hidefocus="true" class="bx-action-href"><?echo GetMessage("ADD_HOST")?></a>
        </td>
    </tr>

    <tr class="heading">
        <td colspan="2"><?=GetMessage('INNER_SERVICES');?></td>
    </tr>
    <?php
    $innerInputValues = explode(',', $arrOptionValue['INNER_URLS_FOR_CHECKING']);
    for ($i=0; $i<count($innerInputValues); $i++) {?>
        <tr>
            <td>
                <label style="width: 30%; display: inline-block;"><?=GetMessage('URL_FOR_CHECKING');?></label>
                <input type="text" name="INNER_URLS_FOR_CHECKING[]"
                    value="<?=$innerInputValues[$i] ? $innerInputValues[$i] : '' ?>" size="40">
            </td>
        </tr>
    <?}?>

    <tr>
	    <td colspan="2">
            <script type="text/javascript">
                function settingsAddHost(a)
                {
                    var row = jsUtils.FindParentObject(a, "tr");
                    var tbl = row.parentNode;

                    var tableRow = tbl.rows[row.rowIndex-1].cloneNode(true);
                    tbl.insertBefore(tableRow, row);

                    var sel = jsUtils.FindChildObject(tableRow.cells[1], "select");
                    sel.name = "";
                    sel.selectedIndex = 0;

                    var div = jsUtils.FindNextSibling(sel, "div");
                    div.style.display = "none";
                    sel = jsUtils.FindChildObject(div, "select");
                    sel.name = "";
                    sel.selectedIndex = -1;

                    sel = jsUtils.FindChildObject(tableRow.cells[0], "select");
                    sel.selectedIndex = 0;
                }
            </script>
            <a href="javascript:void(0)" onclick="settingsAddHost(this)" hidefocus="true" class="bx-action-href"><?echo GetMessage("ADD_HOST")?></a>
        </td>
    </tr>


    <?php $tabControl->beginNextTab();?>

    <tr class="heading">
        <td colspan="2"><?=GetMessage('EMAIL');?></td>
    </tr>

    <tr>
        <td>
            <?php __AdmSettingsDrawRow($module_id, $aTabs[1]['OPTIONS'][0]);?>
        </td>
    </tr>

    <tr class="heading">
        <td colspan="2"><?=GetMessage('SEND_ONLY_ERRORS');?></td>
    </tr>

    <tr>
        <td>
            <?php __AdmSettingsDrawRow($module_id, $aTabs[1]['OPTIONS'][1]);?>
        </td>
    </tr>

    <?$tabControl->buttons();?>
    <input type="submit" name="apply"
           value="<?=Loc::getMessage('SAVE_BUTTON')?>" class="adm-btn-save" />
    <input type="submit" name="default"
           value="<?=Loc::getMessage('RESET_BUTTON')?>" />
</form>

<?php
$tabControl->end();

if($request->isPost() && check_bitrix_sessid()) {

    foreach($aTabs as $aTab){

        foreach($aTab["OPTIONS"] as $arOption){
            if (!is_array($arOption)) {
                continue;
            }

            if ($arOption["note"]) {
                continue;
            }

            if ($request["apply"]) {
                   
                    $optionValue = $request->getPost($arOption[0]);
                    Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(",", $optionValue) : $optionValue);

            } elseif ($request["default"]) {
                Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
    }

    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . $module_id . "&lang=" . LANG);
}
    
