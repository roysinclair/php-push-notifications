<?php
/**
 * User: Roy Sinclair
 * Date: 2017/03/09
 * Time: 9:32 AM
 */

require("config.php");

$inputJSON = file_get_contents("php://input");
$input = json_decode($inputJSON); //convert JSON into array
$request = $input->{"request"};

// REGISTER DEVICE
if (isset($request) && $request == "register") {

    // set json string to php variables
    $deviceToken = $input->{"deviceToken"};
    $appVersion = $input->{"appVersion"};
    $appOS = $input->{"appOS"};

    if (!isset($deviceToken)) {

        $response = array(
            "success" => false,
            "message" => "Missing core data"
        );
        echo json_encode($response);
        exit();
    }

    // If duplicate deviceToken is detected update the database instead of inserting a new entry
    $pdo = $DB->prepare(
        "INSERT INTO push_notifications
        (deviceToken,appVersion,appOS)
        VALUES
        (:deviceToken,:appVersion,:appOS)
        ON DUPLICATE KEY UPDATE appVersion = :appVersion, appOS = :appOS
    ");

    $pdo->execute(array(
        ":deviceToken" => $deviceToken, ":appVersion" => $appVersion, ":appOS" => $appOS
    ));

    $affected_rows = $pdo->rowCount();

    if ($affected_rows >= 1) {

        $response = array(
            "success" => true,
            "message" => "Device registered"
        );

        echo json_encode($response);
        exit();

    } else {

        $response = array(
            "success" => false,
            "message" => "Could not register your device at this time"
        );

        echo json_encode($response);
        exit();

    }

}

// UN-REGISTER DEVICE
if (isset($request) && $request == "unregister") {

    // set json string to php variables
    $userId = $input->{"userId"};
    $deviceToken = $input->{"deviceToken"};

    if (!isset($userId) OR !isset($deviceToken)) {

        $response = array(
            "success" => false,
            "message" => "Missing core data"
        );
        echo json_encode($response);
        exit();
    }

    $pdo = $DB->prepare("DELETE FROM push_notifications WHERE deviceToken = :deviceToken");

    $pdo->execute(array(
        ":deviceToken" => $deviceToken
    ));

    $affected_rows = $pdo->rowCount();

    if ($affected_rows >= 1) {

        $response = array(
            "success" => true,
            "message" => "Device removed"
        );

        echo json_encode($response);
        exit();

    } else {

        $response = array(
            "success" => false,
            "message" => "Please try again later"
        );

        echo json_encode($response);
        exit();

    }


}
