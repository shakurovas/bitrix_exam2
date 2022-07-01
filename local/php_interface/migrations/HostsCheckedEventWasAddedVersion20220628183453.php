<?php

namespace Sprint\Migration;


class HostsCheckedEventWasAddedVersion20220628183453 extends Version
{
    protected $description = "Добавлено событие HOSTS_CHECKED для отправки информации о состоянии хостов";

    protected $moduleVersion = "4.0.2";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->Event()->saveEventType('HOSTS_CHECKED', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Проверка хостов',
  'DESCRIPTION' => '#EMAIL_TO#
#HOSTS_INFO#',
  'SORT' => '150',
));
            $helper->Event()->saveEventMessage('HOSTS_CHECKED', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
  'EMAIL_TO' => '#EMAIL_TO#',
  'SUBJECT' => 'Проверка хостов',
  'MESSAGE' => 'Были осуществлена проверка хостов

#HOSTS_INFO#',
  'BODY_TYPE' => 'text',
  'BCC' => '',
  'REPLY_TO' => '',
  'CC' => '',
  'IN_REPLY_TO' => '',
  'PRIORITY' => '',
  'FIELD1_NAME' => '',
  'FIELD1_VALUE' => '',
  'FIELD2_NAME' => '',
  'FIELD2_VALUE' => '',
  'SITE_TEMPLATE_ID' => '',
  'ADDITIONAL_FIELD' => 
  array (
  ),
  'LANGUAGE_ID' => 'ru',
  'EVENT_TYPE' => '[ HOSTS_CHECKED ] Проверка хостов',
));
        }

    public function down()
    {
        //your code ...
    }
}
