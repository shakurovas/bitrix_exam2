<?

use \Bitrix\Main\Config;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

$emailForSendingInfo = \Bitrix\Main\Config\Option::get("mcart.ipchecker", "EMAIL_WHERE_SEND_TO");

Class mcart_ipchecker extends CModule
{
    public $MODULE_ID = "mcart.ipchecker";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;

    function __construct()
    {
        $this->MODULE_NAME = Loc::getMessage('MCART_IPCHECKER_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MCART_IPCHECKER_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('MCART_IPCHECKER_MODULE_PARTNER_NAME');

        if (is_file(__DIR__.'/version.php')) {
            include_once(__DIR__ . '/version.php');

            if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
                $this->MODULE_VERSION = $arModuleVersion['VERSION'];
                $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
            }
        }
        else
        {
            $this->MODULE_VERSION = '0.0.1';
            $this->MODULE_VERSION_DATE = '2022-05-27 10:00:00';
        }
    }

    function DoInstall()
    {
        global $APPLICATION;

        // мы используем функционал нового ядра D7 — поддерживает ли его система?
        if (CheckVersion(ModuleManager::getVersion('main'), '14.00.00')) {

            // создаем таблицы БД, необходимые для работы модуля
            $this->installDB();
            // регистрируем модуль в системе
            ModuleManager::RegisterModule($this->MODULE_ID);

            CEventType::Add(array(
                "LID"           => 'ru',
                "EVENT_NAME"    => 'MCART_IPCHECKER_SENDING_INFO_ABOUT_HOSTS',
                "NAME"          => 'Отправка информации о состоянии хостов',
                "DESCRIPTION"   => 
            "#EMAIL_TO# - Email получателя письма
            #TASK_ID# - ID задачи
            #TASK_TITLE# - Заголовок задачи
            #COMMENT_ID# - ID комментария
            #RECIPIENT_ID# - ID получателя
            #USER_ID# - ID пользователя для проверки прав на задачу
            #URL# - URL страницы задачи
            #SUBJECT# - Заголовок письма"
                )); // создаём тип почтового события
            
            CEventMessage::Add(array(
                'ACTIVE' => 'Y',
                'EVENT_NAME' => 'MCART_IPCHECKER_SENDING_INFO_ABOUT_HOSTS',
                'LID' => 's1',
                'EMAIL_FROM' => 'поле "From" ("Откуда")',
                'EMAIL_TO' => $emailForSendingInfo,
                'BCC' => 'поле "BCC" ("Скрытая копия")',
                'SUBJECT' => 'Проверка хостов',
                'BODY_TYPE' =>"text",
                'MESSAGE' => 'тело почтового сообщения',
            )); // создаём почтовый шаблон
        } else {
            CAdminMessage::showMessage(Loc::getMessage('MCART_IPCHECKER_INSTALL_ERROR'));
        }

        $APPLICATION->includeAdminFile(
            Loc::getMessage('MCART_IPCHECKER_INSTALL_TITLE') . ' «' . Loc::getMessage('MCART_IPCHECKER_MODULE_NAME') . '»',
            __DIR__ . '/step1.php'
        );

        // агенты
       \CAgent::AddAgent(
           "\MCart\Exchange\CorpStructure::getWorkers();",
           "mcart.ipchecker",
           "N",
           86400,
           "",// когда проверить первый запуск (сейчас)
           "Y",
           "" // начиная с какой даты проверять
       );
    }

    function installDB() {
        return;
    }

    function DoUninstall()
    {
        global $APPLICATION;

        // $this->uninstallDB();

        CEventMessage::Delete(
            CEventMessage::GetList(
            $by="id",
            $order="desc",
            array(
                "TYPE_ID" => 'MCART_IPCHECKER_SENDING_INFO_ABOUT_HOSTS',
            ))->Fetch()['ID']
        ); // удаляем почтовый шаблон

        CEventType::Delete('MCART_IPCHECKER_SENDING_INFO_ABOUT_HOSTS'); // удаляем тип почтового события

        Option::delete($this->MODULE_ID); // удаляем настройки

        ModuleManager::unRegisterModule($this->MODULE_ID); // убираем модуль

        $APPLICATION->includeAdminFile(
            Loc::getMessage('MCART_IPCHECKER_UNINSTALL_TITLE').' «'.Loc::getMessage('MCART_IPCHECKER_MODULE_NAME').'»',
            __DIR__.'/unstep1.php'
        );
    }

    public function uninstallDB() {
        return;
    }


}
?>
