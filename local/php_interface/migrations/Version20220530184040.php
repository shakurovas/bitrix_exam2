<?php

namespace Sprint\Migration;

//use \Bitrix\Main;

class Version20220530184040 extends Version
{
    protected $description = "Добавлены права на просмотр профилей пользователей для группы тех поддержки";

    protected $moduleVersion = "3.30.1";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();

        $helper->UserGroup()->saveGroup('SUPPORT',array (
            'ACTIVE' => 'Y',
            'C_SORT' => '10',
            'ANONYMOUS' => 'N',
            'NAME' => 'Техподдержка',
            'DESCRIPTION' => 'Сотрудники IT отдела, обеспечивающие техническую поддержку.',
            'SECURITY_POLICY' =>
                array (
                    'PASSWORD_CHANGE_DAYS' => '0',
                ),
        ));

        global $APPLICATION;
        $APPLICATION->SetFileAccessPermission("/bitrix/admin", array($helper->UserGroup()->getGroupId('SUPPORT') => "R"));

//       CGroup::SetTasks($helper->UserGroup()->getGroupId('SUPPORT', 3));
//       $ID - ID группы, для которой задаются уровни доступа
//       $arTasks - ID уровня доступа

        $APPLICATION->SetGroupRight('main', $helper->UserGroup()->getGroupId('SUPPORT'), 'W');
        \CGroup::SetModulePermission($helper->UserGroup()->getGroupId('SUPPORT'),"main", "R");
        \CGroup::SetModulePermission($helper->UserGroup()->getGroupId('SUPPORT'), "socialnetwork", "R");
        \CGroup::SetModulePermission($helper->UserGroup()->getGroupId('SUPPORT'), "support", "W");


    }

    public function down()
    {
        //your code ...
    }
}
