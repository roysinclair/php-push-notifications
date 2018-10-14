<?php
/**
 * User: Roy Sinclair
 * Date: 2016/07/07
 * Time: 1:46 PM
 */

require("config.php");
ob_start();

$inputJSON = file_get_contents("php://input");
$input = json_decode($inputJSON); //convert JSON into array
$notificationsServer = $input->{"request"};

if (isset($notificationsServer) && $notificationsServer == "iOS") {

    // set json string to php variables
    $pushMessage = $input->{"message"};
    $pushCustomIdentifier = gen_uuid();

    // Connect to the Apple Push Notification Service
    $push->connect();

    $sentCount = 0;

    $pdo = $DB->query("SELECT deviceToken FROM push_notifications WHERE appOS = 'iOS'");
    $pdo->execute();
    $results = $pdo->fetchAll();

    if ($results == false) {

        $response = array(
            "success" => false,
            "message" => "No iOS users found"
        );
        echo json_encode($response);
        exit();

    } else {

        foreach ($results as $row) {

            // Instantiate a new Message with a single recipient
            $message = new ApnsPHP_Message($row["deviceToken"]);

            // Set a custom identifier. To get back this identifier use the getCustomIdentifier() method
            // over a ApnsPHP_Message object retrieved with the getErrors() message.
            $message->setCustomIdentifier($pushCustomIdentifier);

            // Set a simple welcome text
            $message->setText($pushMessage);

            // Play the default sound
            $message->setSound();

            // Set the expiry value to 60 seconds
            $message->setExpiry(60);

            // Add the message to the message queue
            $push->add($message);

            $sentCount++;

        }

        // Send all messages in the message queue
        $push->send();

        // Disconnect from the Apple Push Notification Service
        $push->disconnect();

        // Examine the error message container
        $aErrorQueue = $push->getErrors();
        if (!empty($aErrorQueue)) {

            $delCount = 0;

            foreach ($aErrorQueue as $row) {
                $delCount++;
            }
        }
    }
}


if (isset($notificationsServer) && $notificationsServer == "Android") {

    // set json string to php variables
    $gcmTitle = $input->{"title"};
    $pushMessage = $input->{"message"};

    $time_start = microtime(true);
    $pushCustomIdentifier = gen_uuid();

    /* ============== ============== NEW DB PUSH TO ANDROID ============== ============== */
    $pdo = $DB->query("SELECT deviceToken FROM push_notifications WHERE appOS = 'ANDROID'");
    $pdo->execute();
    $new_DB_results = $pdo->fetchAll();

    if ($new_DB_results == false) {

        $response = array(
            "success" => false,
            "message" => "No Android users found"
        );
        echo json_encode($response);
        exit();

    } else {

        // NEW DB ARRAY
        $new_DB_Array = [];

        foreach ($new_DB_results as $row) {
            $new_DB_Array[] = $row["deviceToken"];
        }

        /* MERGE DEVICE TOKENS INTO ONE ARRAY */
        $deviceToken = array_merge($new_DB_Array);

        $package = '{                    
                      "title"		: "' . $gcmTitle . '",
                      "alert"		: "' . $pushMessage . '",
                      "sound"		: "default",                    
                      "vibrate"		: true,     
                      "tag"         : "MYAPP",               
                      "id"          : "' . $pushCustomIdentifier . '",         
                }';

        $fields = array(
            "registration_ids" => $deviceToken,
            "data" => array("data" => $package),
        );

        //print_r($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: key=$googleKey", "Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        echo curl_exec($ch);
        $curl_result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($curl_result, true);
    }
}

if (isset($notificationsServer) && $notificationsServer == "All") {

    // set json string to php variables
    $pushMessage = $input->{"message"};
    $pushCustomIdentifier = gen_uuid();

    // Connect to the Apple Push Notification Service
    $push->connect();

    $sentCount = 0;

    $pdo = $DB->query("SELECT deviceToken FROM push_notifications WHERE appOS = 'iOS'");
    $pdo->execute();
    $results = $pdo->fetchAll();

    if ($results == false) {

        $response = array(
            "success" => false,
            "message" => "No iOS users found"
        );
        echo json_encode($response);

    } else {

        foreach ($results as $row) {

            // Instantiate a new Message with a single recipient
            $message = new ApnsPHP_Message($row["deviceToken"]);

            // Set a custom identifier. To get back this identifier use the getCustomIdentifier() method
            // over a ApnsPHP_Message object retrieved with the getErrors() message.
            $message->setCustomIdentifier($pushCustomIdentifier);

            // Set a simple welcome text
            $message->setText($pushMessage);

            // Play the default sound
            $message->setSound();

            // Set the expiry value to 60 seconds
            $message->setExpiry(60);

            // Add the message to the message queue
            $push->add($message);

            $sentCount++;

        }

        // Send all messages in the message queue
        $push->send();

        // Disconnect from the Apple Push Notification Service
        $push->disconnect();

        // Examine the error message container
        $aErrorQueue = $push->getErrors();
        if (!empty($aErrorQueue)) {

            $delCount = 0;

            foreach ($aErrorQueue as $row) {
                $delCount++;
            }
        }
    }

    // set json string to php variables
    $gcmTitle = $input->{"title"};
    $pushMessage = $input->{"message"};

    $time_start = microtime(true);
    $pushCustomIdentifier = gen_uuid();

    /* ============== ============== NEW DB PUSH TO ANDROID ============== ============== */
    $pdo = $DB->query("SELECT deviceToken FROM push_notifications WHERE appOS = 'ANDROID'");
    $pdo->execute();
    $new_DB_results = $pdo->fetchAll();

    if ($new_DB_results == false) {

        $response = array(
            "success" => false,
            "message" => "No Android users found"
        );
        echo json_encode($response);

    } else {

        // NEW DB ARRAY
        $new_DB_Array = [];

        foreach ($new_DB_results as $row) {
            $new_DB_Array[] = $row["deviceToken"];
        }

        /* MERGE DEVICE TOKENS INTO ONE ARRAY */
        $deviceToken = array_merge($new_DB_Array);

        $package = '{                    
                      "title"		: "' . $gcmTitle . '",
                      "alert"		: "' . $pushMessage . '",
                      "sound"		: "default",                    
                      "vibrate"		: true,     
                      "tag"         : "MYAPP",               
                      "id"          : "' . $pushCustomIdentifier . '",         
                }';

        $fields = array(
            "registration_ids" => $deviceToken,
            "data" => array("data" => $package),
        );

        //print_r($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: key=$googleKey", "Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        echo curl_exec($ch);
        $curl_result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($curl_result, true);
    }

}

// Ref: https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
function gen_uuid() {
    return sprintf("%04x%04x-%04x-%04x-%04x-%04x%04x%04x",
        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}