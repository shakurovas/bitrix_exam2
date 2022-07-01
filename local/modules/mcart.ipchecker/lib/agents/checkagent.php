<?php
namespace Mcart\Ipchecker\Agents;

use Bitrix\Main\Loader;
Loader::IncludeModule('mcart.ipchecker');

use \Bitrix\Main\Config;
use \Mcart\Avisma\HighloadHelper;
use \MCArt\Exchange\Helper\UserHelper;

class CheckAgent
{
    public static function checkHostsAgent()
    {
        $options = \Bitrix\Main\Config\Option::getForModule('mcart.ipchecker');
        $hosts_to_check = array_merge(explode(',', $options['OUTER_URLS_FOR_CHECKING']), explode(',', $options['INNER_URLS_FOR_CHECKING']));

        $connectors = array(
            'https://ords-server.vm.vsmpo.ru:8443/ords/is001/ws/exp/absence?person_id=',
            'https://ords-server.vm.vsmpo.ru:8443/%D1%88/is001/ws/exp/food_respond?person_id',
            'https://ords-server.vm.vsmpo.ru:8443/ords/is001/ws/exp/sport_respond?person_id',
            'https://ords-server.vm.vsmpo.ru:8443/ords/is002/ordstest/SIZ/SIZTMP/?person_id=',
            'https://esb.vsmpo.ru:8094/adapters/request_response/in/request?timeout=10',
            'http://esb-dev:8090/adapters/portal/out/',
            'http://esb-dev:8090/adapters/portal/in/',
        );
        $result = array();

        $send_only_errors = \Bitrix\Main\Config\Option::get("mcart.ipchecker", "SEND_ONLY_ERRORS");

        function check_url($url) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
            curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_TIMEOUT,10);
            $output = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $httpcode;
        }

        for ($i=0; $i<count($hosts_to_check); $i++) {
            if (strpos($hosts_to_check[$i], 'bitrix24') || (strpos($hosts_to_check[$i], 'www') !== false)) {

                $httpsUrl = 'https://' . $hosts_to_check[$i];
                $httpcode = self::check_url($httpsUrl);

                if ($httpcode == 0) {
                    $httpUrl = 'http://' . $hosts_to_check[$i];
                    $httpcode = self::check_url($httpUrl);
                }

                if ($send_only_errors) {
                    if (($httpcode < 200 || $httpcode >= 400) && ($httpcode != 0)) {
                        $result[] = $hosts_to_check[$i] . ' is unavailable' . ' HTTP code: ' . $httpcode;
                    }
                } else {
                    if (($httpcode < 200 || $httpcode >= 400) && ($httpcode != 0)) {
                        $result[] = $hosts_to_check[$i] . ' is unavailable' . ' HTTP code: ' . $httpcode;
                    } else if ($httpcode >= 200 && $httpcode < 400) {
                        $result[] = $hosts_to_check[$i] . ' is ok' . ' HTTP code: ' . $httpcode;
                    }

                }

            } else {
                $isSuccess = true;
                for ($j=0; $j<count($connectors); $j++) {

                    $httpsUrl = 'https://' . $hosts_to_check[$i] . '/' . $connectors[$j];
                    $httpcode = check_url($httpsUrl);

                    if ($send_only_errors) {
                        if (($httpcode < 200 || $httpcode >= 400) && ($httpcode != 0)) {
                            $result[] = $hosts_to_check[$i] . ' is unavailable' . ' HTTP code: ' . $httpcode;
                            $isSuccess = false;
                        }
                        break;
                    } else {
                        if (($httpcode < 200 || $httpcode >= 400) && ($httpcode != 0)) {
                            $result[] = $hosts_to_check[$i] . ' is unavailable' . ' HTTP code: ' . $httpcode;
                            $isSuccess = false;
                            break;
                        } else if ($httpcode >= 200 && $httpcode < 400) {
                            $isSuccess = $isSuccess && true;
                        } else {

                        }
                    }

                }
                if (true) {
                    if ($isSuccess) {
                        $result[] = $hosts_to_check[$i] . ' is ok' . ' HTTP code: ' . $httpcode;
                    }
                }
            }

        }

        $emailForSendingInfo = \Bitrix\Main\Config\Option::get("mcart.ipchecker", "EMAIL_WHERE_SEND_TO");

        $eventSendFields = array(
			"EVENT_NAME" => "HOSTS_CHECKED",
			"C_FIELDS" =>  array(
                "EMAIL_TO" => $emailForSendingInfo,
                "HOSTS_INFO" => $result,
            ),
			"LID" => "s1",
		);
        \Bitrix\Main\Mail\Event::send($eventSendFields);

        return "\Mcart\Ipchecker\Agents\CheckAgent::checkHostsAgent();";
    }
}

