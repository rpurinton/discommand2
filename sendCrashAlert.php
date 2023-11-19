<?php

// sendCrashAlert.php - Sends an email alert when a Brain service crashes

$brainName = $argv[1] ?? 'unknown';
$subject = "Discommand Brain Restart: {$brainName}";
$message = "The Discommand Brain service for '{$brainName}' has restarted.\n\n";

mail('russell.purinton@gmail.com', $subject, $message, 'From: root@discommand.com');
