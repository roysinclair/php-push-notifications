<?php
/**
 * @file
 * sample_feedback.php
 *
 * Feedback demo
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://code.google.com/p/apns-php/wiki/License
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to aldo.armiento@gmail.com so we can send you a copy immediately.
 *
 * @author (C) 2010 Aldo Armiento (aldo.armiento@gmail.com)
 * @version $Id$
 */


require("../../config.php");

// Connect to the Apple Push Notification Feedback Service
$push->connect();

$aDeviceTokens = $push->receive();
if (!empty($aDeviceTokens)) {

	var_dump($aDeviceTokens);

	foreach ($aDeviceTokens as $k => $deviceToken) {

		// code to delete record from database
		$pdo = $DB->prepare("DELETE FROM push_notifications WHERE deviceToken = :deviceToken");

		$pdo->execute(array(
			":deviceToken" => $deviceToken["deviceToken"]
		));

		$affected_rows = $pdo->rowCount();
	}

}
// Disconnect from the Apple Push Notification Feedback Service
$push->disconnect();
