<?php
$file_path = "file.txt";
$file_lines = [];// Массив для хранения строк файла

$file = fopen($file_path, "r");

if ($file) {
    while (($line = fgets($file)) !== false)
    {
        $file_lines[] = $line;
    }
    fclose($file);
} else {
    echo "Ошибка при открытии файла.";
}

$url = isset($_GET['get']) ? trim($_GET['get']) : null;
$url = str_replace('https:/', 'https://', $url);
echo $url;
echo "<br>";
if ($url != null)
{
    foreach ($file_lines as $line) {
        $parts = explode(",", $line);
        // Проверяем, что есть оба элемента в $parts
        if (count($parts) >= 2) {
            $original_url = trim($parts[0]);
            $short_url = trim($parts[1]);
            if ($url == $short_url)
            {
                header("Location: $original_url");
                exit;
            }
//            echo "Short" . mb_strlen($short_url);
//            echo "<br>";
//            echo "URL" . mb_strlen($url);
//            echo "<br>";
        }

    }
} else {
    echo "Короткая ссылка не передана.";
}
