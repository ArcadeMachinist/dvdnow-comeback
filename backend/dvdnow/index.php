<?php

require "global_funcs/dump.inc";

(new DumpHTTPRequestToFile)->execute('/data/dvdnow.txt');

// $endpoint = preg_replace('#^/api/#', '', $_SERVER['REQUEST_URI']);
$endpoint = $_SERVER['REQUEST_URI'];

switch ($endpoint) {

        case "/api/getIp":
                echo $_SERVER['HTTP_X_FORWARDED_FOR'];
                die();
	case "/api":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$function_name = isset($_POST['function_name']) ? $_POST['function_name'] : null;
                        $params = isset($_POST['params']) ? $_POST['params'] : null;

			if ($function_name) {
				switch ($function_name) {
					case "getCcInfoByCcNumber":
						// SAMPLE Data
						// Hardcoded for 1 card, but should be database
						// Form item: "params" = "{'kiosk_id': 'S250-A580', 'cc_number_sha1': '', 'cc_track2': '52291500388XXXXX=24XXXXXXXXXX', 'cc_track1': 'B52291500XXXXXXXX^NAME/NAME^240XXXXXXXXX', 'cc_type': 0, 
						//'cc_number': '52291500XXXXXXXXXX', 'cc_expdate': 'XXXX'
                                                if ($params) {
                                                 	$json_data = json_decode($params,TRUE);
							$zdata = new stdClass();
							$zdata->cc_id = 1000;
							$zdata->is_new = 1;
							$zdata->cc_type = 3; // CerePay
							$zdata->cc_name = "NAME/NAME";  // Your's here
							$zdata->cc_expdate = "XXX";
							$zdata->cc_track1 = "B5229150XXXXXXXX^NAME/NAME^240XXXXXXX";
							$zdata->cc_track2 = "522915XXXXXXXXX=2408XXXXXXXX";
							$zdata->cc_display = "Card1";

							$response = new stdClass();
							$response->result = "ok";
							$response->zdata = $zdata;
							header("Content-type: text/plain");
							die(jsonToPythonDict(json_encode($response)));
                                                }
						break;
					case "getCerePayCfgByAcctId":
						$zdata = new stdClass();
						$zdata->MERCHANTID = 1;
						$zdata->PASSWORD = "abcdeg";
						$zdata->CURRENCY = "USD";
						$response = new stdClass();
						$response->result = "ok";
                                                $response->zdata = $zdata;
                                                header("Content-type: text/plain");
                                                die(jsonToPythonDict(json_encode($response)));
						break;
					case "getCerePayUserInfo":
                                                $zdata = new stdClass();
						$zdata->status = 0;
						$zdata->name = "ArcadeMachinist";
						$zdata->needTrsPasswd = false;
						$zdata->passwd = "AAAAA";
						$zdata->number = "1005";
						$zdata->errCode = 0;
						$zdata->holdingAmt = 0;
						$zdata->currency = "USD";
						$zdata->email = "null@void.com";
						$zdata->numberList = "";
						$zdata->merchantId = 1;
						$zdata->balance = 500;
						$zdata->id = 1000;
						$zdata->errMsg = "";
						$zdata->trsPasswd = "";
                                                $response = new stdClass();
                                                $response->result = "ok";
                                                $response->zdata = $zdata;
                                                header("Content-type: text/plain");
                                                die(jsonToPythonDict(json_encode($response)));
                                                break;
					case "getInfoForKioskByCc":
					// Host: ums.dvdnow
					// params=%7B%27card_number%27%3A+%275229150XXXXXXXXXXXXX%27%2C+%27cc_id%27%3A+1000%7D&function_name=getInfoForKioskByCc
						$zdata = new stdClass();
                                                $zdata->email = "null@void.com";

						$cerepay = new stdClass();
                                                $cerepay->status = 0;
                                                $cerepay->name = "ArcadeMachinist";
                                                $cerepay->needTrsPasswd = false;
                                                $cerepay->passwd = "AAAAA";
                                                $cerepay->number = "1005";
                                                $cerepay->errCode = 0;
                                                $cerepay->holdingAmt = 0;
                                                $cerepay->currency = "USD";
                                                $cerepay->email = "null@void.com";
                                                $cerepay->numberList = "";
                                                $cerepay->merchantId = 1;
                                                $cerepay->balance = 500;
                                                $cerepay->id = 1000;
                                                $cerepay->errMsg = "";
                                                $cerepay->trsPasswd = "";

						$zdata->member_id = 1000;
						$zdata->cerepay = $cerepay;
						$zdata->first_name = "Arcade";
						$zdata->last_name = "Machinist";
						$zdata->birth_year = 1980;
						$zdata->member_rating = 5; // not actually used
						$zdata->gender = "male";
						$zdata->month_subs = "";
						// Month subscription
/*
            totalCount = int(subscptInfo.get('total_count', '0'))
            usedCount = int(subscptInfo.get('used_count', '0'))
            customer.msID = subscptInfo.get('ms_id', '')
            customer.msKeepDays = int(subscptInfo.get('keep_days', ''))
            customer.msMaxKeepDiscs = int(subscptInfo.get('keep_count', ''))
            customer.msCount = totalCount - usedCount
            customer.msDiscType = subscptInfo.get('apply_disc_types', '')
*/
					        $response = new stdClass();
                                                $response->result = "ok";
                                                $response->zdata = $zdata;
                                                header("Content-type: text/plain");
                                                die(jsonToPythonDict(json_encode($response)));
						break;
				}
			}
		}
	case "/upg/agent/upgAgent":
		// cardExpDate=2408&trsComment=S250-A580&oid=&track1=B52291500XXXXXXXX%5ENAME%2FNAME%5E24082XXXXXX&amount=26.75&track2=52291XXXXXXXXXXXXX%3D24XXXXXXXXXXX&cardNum=52291XXXXXXXXXX&trsType=PREAUTH&acctId=2645&ignore_bl=0&trsMode=LIVE&nameOnCard=NAME%2FNAME
		//
		// returns CODE|MESSAGE|OID
                header("Content-type: text/plain");
                die("0||39929201");
		break;
        default:
                die("Go away!!!1");
}

die("Go away!");

function jsonToPythonDict($json) {
    $array = json_decode($json, true);

    if ($array === null) {
        return "None";  // Return Python's None if JSON decoding fails
    }

    return convertToPythonDict($array);
}

function convertToPythonDict($array) {
    if (is_null($array)) {
        return "None";
    } elseif (is_bool($array)) {
        return $array ? "True" : "False";
    } elseif (is_numeric($array)) {
        return $array;
    } elseif (is_string($array)) {
        return "'" . addslashes($array) . "'";  // Ensure proper escaping
    } elseif (is_array($array)) {
        if (array_keys($array) === range(0, count($array) - 1)) {
            // List (Python uses [])
            return "[" . implode(", ", array_map('convertToPythonDict', $array)) . "]";
        } else {
            // Dictionary (Python uses {})
            $items = [];
            foreach ($array as $key => $value) {
                $items[] = "'" . addslashes($key) . "': " . convertToPythonDict($value);
            }
            return "{" . implode(", ", $items) . "}";
        }
    }
    return "None";  // Fallback for unexpected types
}

?>
