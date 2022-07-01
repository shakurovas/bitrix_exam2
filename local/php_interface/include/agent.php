<?php

function CheckUserCount()
{
    $date = new DateTime();
    $date = \Bitrix\Main\Type\DateTime::createFromTimestamp($date->getTimestamp());

    // опция последнего запуска агента
    $lastCheck = COption::GetOptionString("main", "last_date_agent_checkUserCount");

    // фильтр пользователей, у кого дата регистрации позже даты последнего запуска агента
    if ($lastCheck) {
        $arFilter = array("DATE_REGISTER_1" => $lastCheck);
    } else {
        $arFilter = array();
    }

    // получение пользователей по фильтру
    $rsUser = CUser::GetList(
        $by = 'DATE_REGISTER',
        $order = 'ASC',
        $arFilter
    );

    // массив пользователей, у которых дата регистрации позже последнего запуска агента
    $arUsers = array();
    while ($user = $rsUser->Fetch()) {
        $arUsers[] = $user;
    }

    // если ещё агент ни разу не запускался
    if (!$lastCheck) {
        $lastCheck = $arUsers[0]['DATE_REGISTER'];
    }

    // разница между датой последнего запуска агента и текущей датой
    $diff = intval(strtotime($lastCheck) - strtotime($date->toString()));
    $days = round($diff / 3600 / 24);
    // print_r($days);

    // количество пользователей, соответствующих условию
    $users = count($arUsers);
    // print_r($users);

    // получение админов
    $rsAdmin = CUser::GetList(
        $by = 'ID',
        $order = 'ASC',
        array("GROUPS_ID" => 1)
    );

    // отправка письма админам
    while ($admin = $rsAdmin->Fetch()) {
        print_r($admin);
        print_r( array(
            "EMAIL_TO" => $admin['EMAIL'],
            "COUNT" => $users,
            "DAYS" => $days,
        ));

        $eventSendFields = array(
			"EVENT_NAME" => "CANJKNJSNJNGS",
            'MESSAGE_ID' => 34,
			"C_FIELDS" =>  array(
                "EMAIL_TO" => $admin['EMAIL'],
                "COUNT" => $users,
                "DAYS" => $days,
            ),
			"LID" => "s1",
		);
        \Bitrix\Main\Mail\Event::send($eventSendFields);
    }

    // заполняем опцию времени выполнения агента
    COption::SetOptionString('main', 'last_date_agent_checkUserCount', $date->toString());

    return "CheckUserCount();";
}