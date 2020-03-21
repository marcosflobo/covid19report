<?php

if (!array_key_exists("token", $_GET)) {
	exit();
}

$inputToken = htmlspecialchars($_GET['token'], ENT_QUOTES);
$verifiedToken = "demo";

if ($inputToken !== $verifiedToken) {
	exit();
}

function getValueForhighcharts($xAxisDateString, $value) {
	return array("Date.UTC(".date("Y", strtotime($xAxisDateString)).", ".date("m", strtotime($xAxisDateString)).", ".date("d", strtotime($xAxisDateString)).")", $value);
}


function fillResultSet($country, &$result, $value) {
	if (array_key_exists($country, $result)) {
		array_push($result[$country]["data"], $value);
	} else {
		$result[$country] = array(
			"name" => $country,
			"data" => array($value)
		);
	}
}


function getVarStringJavascript($varName, $arrayResult) {
	$jsString = "var ". $varName ." = [";
	foreach ($arrayResult as $key => $value) {
		$dataString = "[";
		foreach ($value['data'] as $j => $number) {
			$dataString .= "[".implode(",", $number)."], ";
		}
		$dataString .= "]";
		$jsString .= "{name: '" . $key . "', data: " . $dataString . "},";
	}
	$jsString .= "];";
	return $jsString;
}

$baseUrl = 'https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/csse_covid_19_daily_reports/';
$dateStartString = "2020-01-22";
$now = time();
$dateStart = strtotime($dateStartString);
$datediff = $now - $dateStart;

$numDays = round($datediff / (60 * 60 * 24));
$resultConfirmed = array();
$resultDeaths = array();
$resultRecovered = array();

$numConfirmedCases = 0;
$numDeathsCases = 0;
$numRecoveredCases = 0;

$totalDays = ($numDays - 1);
for ($i=0; $i < $totalDays; $i++) { 
	$d = date('m-d-Y', strtotime($dateStartString . ' +'. $i .' day'));

	//echo $baseUrl . $d . ".csv<br>";
	$csv = file_get_contents($baseUrl . $d . ".csv");
	$data = split("\n", $csv);
	for ($j = 1 ; $j < count($data) - 1; $j ++) {
		$arrayAux = str_getcsv($data[$j]);

		$country = $arrayAux[1];
		$xAxisDateString = $arrayAux[2];
		$valueConfirmed = 0;
		$valueDeaths = 0;
		$valueRecovered = 0;

		if (preg_match("/([1-9]{1}\/[1-9]{1}\/[0-9]{4}) ([0-9]{1,2}:[0-9]{1,2})/i", $xAxisDateString, $matches)) {
			$a = explode("/", $matches[1]);
			$xAxisDateString = $a[2]."/".$a[0]."/".$a[1]." " . $matches[2] ;
		}
		
		if (!empty($arrayAux[0])) {
			$country = $arrayAux[0] . "-" . $country;
		}
		$country = addslashes($country);
		
		if (array_key_exists(3, $arrayAux) && !empty($arrayAux[3])) {
			$valueConfirmed = $arrayAux[3];
		}
		if (array_key_exists(4, $arrayAux) && !empty($arrayAux[4])) {
			$valueDeaths = $arrayAux[4];
		}
		if (array_key_exists(5, $arrayAux) && !empty($arrayAux[5])) {
			$valueRecovered = $arrayAux[5];
		}

		if (($i + 1) == $totalDays) {
			$numConfirmedCases += $valueConfirmed;
			$numDeathsCases += $valueDeaths;
			$numRecoveredCases += $valueRecovered;
		}

		$valueConfirmed = getValueForhighcharts($xAxisDateString, $valueConfirmed);
		$valueDeaths = getValueForhighcharts($xAxisDateString, $valueDeaths);
		$valueRecovered = getValueForhighcharts($xAxisDateString, $valueRecovered);
		fillResultSet($country, $resultConfirmed, $valueConfirmed);
		fillResultSet($country, $resultDeaths, $valueDeaths);
		fillResultSet($country, $resultRecovered, $valueRecovered);
	}
}


// echo "<pre>";
// print ($numConfirmedCases);
// print ($numDeathsCases);
// print ($numRecoveredCases);
// print_r($resultDeaths);
// echo "</pre>";
$phpString = "<?php \$numConfirmedCases = $numConfirmedCases; \$numDeathsCases = $numDeathsCases; \$numRecoveredCases = $numRecoveredCases; ";
$jsString = getVarStringJavascript("resultConfirmed", $resultConfirmed);
$jsString .= getVarStringJavascript("resultDeaths", $resultDeaths);
$jsString .= getVarStringJavascript("resultRecovered", $resultRecovered);
//echo $jsString;
file_put_contents("result.js", $jsString);
file_put_contents("vars.php", $phpString);
?>