<?php
function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        if (trim($line) === '') {
            continue;
        }

        $equalsPos = strpos($line, '=');
        if ($equalsPos !== false) {
            $name = trim(substr($line, 0, $equalsPos));
            $value = trim(substr($line, $equalsPos + 1));
            $value = trim($value, '"\'');
            putenv("$name=$value");
        }
    }
}

loadEnv(__DIR__ . '/.env');
?>