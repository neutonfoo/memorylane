<?php
header('Content-Type: application/json');

// Yelp API Stuff
$API_KEY = "knlsO9IAMiUn5tskPXoEv3xnuIW-sucG8c3cK5iqAd2VGC7I1LLqork8g_QoQduwnrQ5uzTAqfGj7ZBvWITVNb5qAUg1nkEHsBbxyUVMKvkHXLZpvMLukicWkVLCW3Yx";
$API_HOST = "https://api.yelp.com";
$SEARCH_PATH = "/v3/businesses/search";

$database['serverAddress'] = "localhost";
$database['username'] = "salhacks_main";
$database['password'] = "eTZJ]5#riQ.P5kHH";
$database['databaseName'] = "salhacks_main";

function request($host, $path, $url_params = array()) {
    // Send Yelp API Call
    try {
        $curl = curl_init();
        if (FALSE === $curl)
            throw new Exception('Failed to initialize');
        $url = $host . $path . "?" . http_build_query($url_params);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,  // Capture response.
            CURLOPT_ENCODING => "",  // Accept gzip/deflate/whatever.
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $GLOBALS['API_KEY'],
                "cache-control: no-cache",
            ),
        ));
        $response = curl_exec($curl);
        if (FALSE === $response)
            throw new Exception(curl_error($curl), curl_errno($curl));
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (200 != $http_status)
            throw new Exception($response, $http_status);
        curl_close($curl);
    } catch(Exception $e) {
        trigger_error(sprintf(
            'Curl failed with error #%d: %s',
            $e->getCode(), $e->getMessage()),
            E_USER_ERROR);
    }
    return $response;
}

// Actual shit

$action = $_POST['action'];
// $action = "search";

if($action == 'search') {
	$url_params = array();

	// $url_params['term'] = "Korean";
	// $url_params['radius'] = 5000;
	// $url_params['price'] = 1;
	//
	// $url_params['latitude'] = 34.019559;
	// $url_params['longitude'] = -118.289529;

	$url_params['term'] = $_POST['term'];
	$url_params['radius'] = $_POST['radius'];
	$url_params['price'] = $_POST['pricePoint']; // 1, 2, 3, 4

	$url_params['longitude'] = $_POST['longitude'];
	$url_params['latitude'] = $_POST['latitude'];

	$url_params['open_now'] = TRUE;
	$url_params['limit'] = 10;

	$request = request($GLOBALS['API_HOST'], $GLOBALS['SEARCH_PATH'], $url_params);
	$requestObj = json_decode($request);

	// Open connection to DB
	$mysqli = new mysqli($database['serverAddress'], $database['username'], $database['password'], $database['databaseName']);
	if($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
	}

	$accessCodeExists = true;
	$accessCode = rand(1000, 9999);

	while($accessCodeExists) {
		if($stmt = $mysqli->prepare("SELECT COUNT(id) FROM wefood_surveyResults WHERE accessCode = ?")) {
			$stmt->bind_param("i", $accessCode);
			$stmt->execute();

			$stmt->bind_result($count);

			while ($stmt->fetch()) {
				if($count == 0) {
					$accessCodeExists = false;
					$requestObj->accessCode = $accessCode;
				} else {
					$accessCode = rand(1000, 9999);
				}
			}
		}
	}

	$restaurants = json_encode($requestObj->businesses);
	$request = json_encode($requestObj);

	if($stmt = $mysqli->prepare("INSERT INTO wefood_surveyResults (accessCode, choices, restaurants) VALUES (?, '0|0|0|0|0|0|0|0|0|0', ?)")) {
		$stmt->bind_param("is", $accessCode, $request);
		$stmt->execute();
	}
	echo $request;

	$mysqli->close();
} else if($action == 'join') {
	$joinAccessCode = $_POST['joinAccessCode'];

	$mysqli = new mysqli($database['serverAddress'], $database['username'], $database['password'], $database['databaseName']);
	if($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
	}

	if($stmt = $mysqli->prepare("SELECT restaurants FROM wefood_surveyResults WHERE accessCode = ? LIMIT 1")) {
		$stmt->bind_param("i", $joinAccessCode);
		$stmt->bind_result($restaurants);
		$stmt->execute();

		while($stmt->fetch()) {
			echo $restaurants;
		}
	}

	$mysqli->close();

} else if($action == 'vote') {
	$accessCode = $_POST['accessCode'];
	$resturantIndex = $_POST['resturantIndex'];

	$restaurantsVotes = [];

	$mysqli = new mysqli($database['serverAddress'], $database['username'], $database['password'], $database['databaseName']);
	if($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
	}

	if($stmt = $mysqli->prepare("SELECT choices FROM wefood_surveyResults WHERE accessCode = ? LIMIT 1")) {
		$stmt->bind_param("i", $accessCode);
		$stmt->bind_result($choices);
		$stmt->execute();

		while($stmt->fetch()) {
			$restaurantsVotes = explode('|', $choices);
		}
	}

	$restaurantsVotes[$resturantIndex]++;
	$restaurantsVotesString = implode('|', $restaurantsVotes);

	if($stmt = $mysqli->prepare("UPDATE wefood_surveyResults SET choices = ? WHERE accessCode = ?")) {
		$stmt->bind_param("si", $restaurantsVotesString, $accessCode);
		$stmt->execute();
	}

	echo json_encode(array());

  /*
  prepare("UPDATE wefood_surveyResults SET choiceId = choiceId + 1")
  */

} else if($action == 'getResults') {
	$accessCode = $_POST['accessCode'];

	$mysqli = new mysqli($database['serverAddress'], $database['username'], $database['password'], $database['databaseName']);
	if($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
	}

	if($stmt = $mysqli->prepare("SELECT choices, restaurants FROM wefood_surveyResults WHERE accessCode = ? LIMIT 1")) {
		$stmt->bind_param("i", $accessCode);
		$stmt->bind_result($choices, $restaurants);
		$stmt->execute();

		$response = new stdClass();

		while($stmt->fetch()) {
			$response->choices = $choices;
			$response->businesses = $restaurants;

		}
	}

	echo json_encode($response);
}
