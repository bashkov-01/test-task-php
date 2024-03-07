<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Short URL Generator</title>
</head>
<body>

<form id="urlForm">
    <input type="text" id="name-url" placeholder="Enter URL">
    <button type="submit">Generate Short URL</button>
</form>

<div id="shortUrlContainer"></div>

<script>
    document.getElementById('urlForm').addEventListener('submit', function (event) {
        event.preventDefault();
        var urlInput = document.getElementById('name-url').value;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'handler.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var shortUrlContainer = document.getElementById('shortUrlContainer');
                    shortUrlContainer.innerHTML = xhr.responseText;
                } else {
                    alert('Error: ' + xhr.status);
                }
            }
        };
        xhr.send('name-url=' + encodeURIComponent(urlInput));
    });
</script>

<?php
if(isset($_POST['name-url']))
{
    $url = $_POST['name-url'];
    $array_url = explode('/', $url);
    $short_url = $array_url[2];
    if (str_contains($short_url, '.'))
    {
        $short_url = explode('.', $short_url);
        $short_url = $short_url[0];
    }

    $short_url2 = 'https://' . $short_url . '/abCdE';

    $is_unique = true;
    if (file_exists("file.txt")) {
        $file_lines = file("file.txt", FILE_IGNORE_NEW_LINES);
        foreach ($file_lines as $line) {
            $parts = explode(",", $line);
            if (count($parts) >= 2) {
                $existing_url = trim($parts[0]);
                if ($existing_url == $url) {
                    $is_unique = false;
                    break;
                }
            }
        }
    }

    if ($is_unique) {
        // Открываем файл для записи, добавляем данные и закрываем файл
        $file = fopen('file.txt', 'a');
        if ($file) {
            fwrite($file, $url . ',' . $short_url2 . PHP_EOL);
            fclose($file);
        } else {
            echo "Ошибка при открытии файла для записи.";
        }
    } else {
        // Генерируем случайный короткий URL
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random_string = '';
        $max_index = strlen($letters) - 1;
        for ($i = 0; $i < 10; $i++) {
            $random_string .= $letters[rand(0, $max_index)];
        }

        $short_url3 = 'https://' . $short_url . '/' . $random_string;

        // Открываем файл для записи, добавляем данные и закрываем файл
        $file = fopen('file.txt', 'a');
        if ($file) {
            fwrite($file, $url . ',' . $short_url3 . PHP_EOL);
            fclose($file);
        } else {
            echo "Ошибка при открытии файла для записи.";
        }
    }
}
?>
</body>
</html>