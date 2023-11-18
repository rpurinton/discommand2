<?php

// sendCrashAlert.php - Sends an email alert when a Brain service crashes

$brainName = $argv[1] ?? 'unknown';
$subject = "Discommand Brain Service Crash Alert: {$brainName}";
$message = "The Discommand Brain service for '{$brainName}' has crashed and failed to restart. Please investigate the issue.";

mail('russell.purinton@gmail.com', $subject, $message, 'From: root@discommand.com');
