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
	return array(
		"Date.UTC(".date("Y", strtotime($xAxisDateString)).", ".(date("m", strtotime($xAxisDateString)) - 1).", ".date("d", strtotime($xAxisDateString)).")", 
		$value);
}


function fillResultSet($country, &$result, $value) {
	if (array_key_exists($country, $result)) {
		if (array_key_exists($value[0], $result[$country]["data"])) {
			$result[$country]["data"][$value[0]][1] += $value[1];
		} else {
			//array_push($result[$country]["data"], $value);
			$result[$country]["data"][$value[0]] = $value;
		}
		
	} else {
		$result[$country] = array(
			"name" => $country,
			"data" => array($value[0] => $value)
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
//echo date("Y-m-d H:i:s", $now) . "<br>";
$dateStart = strtotime($dateStartString);
$datediff = $now - $dateStart;
$dateChangeFormatString = "2020-03-22";
$dateChangeFormat = strtotime($dateChangeFormatString);

$numDays = round($datediff / (60 * 60 * 24));
$resultConfirmed = array();
$resultDeaths = array();
$resultRecovered = array();

$numConfirmedCases = 0;
$numDeathsCases = 0;
$numRecoveredCases = 0;

$countries = [];
$numRealCountries = 0;

$totalDays = ($numDays - 1);
//$totalDays = 5;
for ($i=0; $i < $totalDays ; $i++) { 
	$iterTimeStamp = strtotime($dateStartString . ' +'. $i .' day');
	$d = date('m-d-Y', $iterTimeStamp);

	//echo $baseUrl . $d . ".csv<br>";
	$csv = file_get_contents($baseUrl . $d . ".csv");
	$data = split("\n", $csv);
	for ($j = 1 ; $j < count($data) - 1; $j ++) {
		$arrayAux = str_getcsv($data[$j]);

		$state = "";
		$valueConfirmed = 0;
		$valueDeaths = 0;
		$valueRecovered = 0;
		$indexArraySwift = 0;
		$indexArraySwiftNumbers = 0;

		if ($iterTimeStamp >= $dateChangeFormat) {
			$indexArraySwift = 2;
			$indexArraySwiftNumbers = $indexArraySwift + 2;
		}

		$country = $arrayAux[$indexArraySwift + 1];
		$country = trim(addslashes(str_replace("Mainland ", "", $country)));
		$xAxisDateString = $arrayAux[$indexArraySwift + 2];
		
		$countries[] = $country;

		if (!empty($arrayAux[$indexArraySwift + 0])) {
			//$country = $arrayAux[$indexArraySwift + 0] . "-" . $country;
			$state = addslashes($arrayAux[$indexArraySwift + 0]);
		}

		if (preg_match("/([1-9]{1}\/[1-9]{1,2}\/[0-9]{4}) ([0-9]{1,2}:[0-9]{1,2})/i", $xAxisDateString, $matches)) {
			$a = explode("/", $matches[1]);
			$xAxisDateString = $a[2]."/".$a[0]."/".$a[1]." " . $matches[2] ;
		} elseif (preg_match("/([1-9]{1}\/[1-9]{1,2}\/[0-9]{2}) ([0-9]{1,2}:[0-9]{1,2})/i", $xAxisDateString, $matches)) {
			$a = explode("/", $matches[1]);
			$xAxisDateString = "20" . $a[2]."/".$a[0]."/".$a[1]." " . $matches[2] ;
		}
		
		//var_dump($arrayAux);
		if (array_key_exists($indexArraySwiftNumbers + 3, $arrayAux) && !empty($arrayAux[$indexArraySwiftNumbers + 3])) {
			$valueConfirmed = $arrayAux[$indexArraySwiftNumbers + 3];
		}
		if (array_key_exists($indexArraySwiftNumbers + 4, $arrayAux) && !empty($arrayAux[$indexArraySwiftNumbers + 4])) {
			$valueDeaths = $arrayAux[$indexArraySwiftNumbers + 4];
		}
		if (array_key_exists($indexArraySwiftNumbers + 5, $arrayAux) && !empty($arrayAux[$indexArraySwiftNumbers + 5])) {
			$valueRecovered = $arrayAux[$indexArraySwiftNumbers + 5];
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

$countries = array_unique($countries);
echo "<pre>";
//print_r($countries);
//print ($numConfirmedCases);
//print ($numDeathsCases);
// print ($numRecoveredCases);
print_r($resultConfirmed);
echo "</pre>";

$phpString = "<?php \$numConfirmedCases = $numConfirmedCases; \$numDeathsCases = $numDeathsCases; \$numRecoveredCases = $numRecoveredCases; ";
$phpString .= "\$resultConfirmed = ".var_export($resultConfirmed, true)."; \$resultDeaths = ".var_export($resultDeaths, true)."; \$resultRecovered = ".var_export($resultRecovered, true)."; ";
$phpString .= "\$numCountries = " . count($countries) . ";";
$jsString = getVarStringJavascript("resultConfirmed", $resultConfirmed);
$jsString .= getVarStringJavascript("resultDeaths", $resultDeaths);
$jsString .= getVarStringJavascript("resultRecovered", $resultRecovered);
//echo $jsString;
file_put_contents("result.js", $jsString);
file_put_contents("vars.php", $phpString);
?>