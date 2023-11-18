<?php

$brainName = $argv[1] ?? null;
if (!$brainName) {
    echo "Usage: php newBrain.php <BrainName>\n";
    exit(1);
}

// Create Linux user and group
exec("sudo useradd -m -d /home/{$brainName} -s /bin/bash {$brainName}");
exec("sudo groupadd {$brainName}");
exec("sudo usermod -a -G {$brainName} {$brainName}");

// Create MySQL database and user
exec("mysql -e 'CREATE DATABASE {$brainName};'");
exec("mysql -e \"GRANT ALL PRIVILEGES ON {$brainName}.* TO '{$brainName}'@'localhost' IDENTIFIED BY '{$brainName}'; FLUSH PRIVILEGES;\"");

// Enable and start the systemd service
exec("sudo systemctl enable discommand-brain@{$brainName}.service");
exec("sudo systemctl start discommand-brain@{$brainName}.service");

// Insert record into discommand2 main database
exec("mysql discommand2 -e \"INSERT INTO brains (brain_name, linux_username, database_name) VALUES ('{$brainName}', '{$brainName}', '{$brainName}');\"");

echo "Brain {$brainName} has been successfully provisioned.\n";
