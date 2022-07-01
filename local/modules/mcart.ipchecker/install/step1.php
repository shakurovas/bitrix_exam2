<?

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (!check_bitrix_sessid()) {
    return;
}

global $APPLICATION;

if ($errorException = $APPLICATION->getException()) {
// ошибка при установке модуля
    CAdminMessage::showMessage(
        Loc::getMessage('MCART_IPCHECKER_INSTALL_ERROR').': '.$errorException->GetString()
    );
} else {
// модуль успешно установлен
    CAdminMessage::showNote(
        Loc::getMessage('MCART_IPCHECKER_INSTALL_SUCCESS')
    );
}
?>

<form action="<?= $APPLICATION->getCurPage(); ?>"> <!-- Кнопка возврата к списку модулей -->
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>" />
    <input type="submit" value="<?= Loc::getMessage('MCART_IPCHECKER_RETURN_MODULES'); ?>">
</form>
