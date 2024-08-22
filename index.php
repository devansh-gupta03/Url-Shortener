<?php

function generateShortURL($url) {
    return substr(md5($url . time()), 0, 6);
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $original_url = filter_var($_POST['url'], FILTER_SANITIZE_URL);

    
    if (filter_var($original_url, FILTER_VALIDATE_URL)) {
        $short_code = generateShortURL($original_url);

        
        file_put_contents('urls.txt', "$short_code $original_url\n", FILE_APPEND);

      
        $short_url = "http://localhost/url-shortener/$short_code";
        echo json_encode(["short_url" => $short_url]);
    } else {
        echo json_encode(["error" => "Invalid URL"]);
    }
    exit;
}

if (isset($_GET['code'])) {
    $short_code = $_GET['code'];

    
    $lines = file('urls.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        list($code, $url) = explode(' ', $line, 2);

        
        if ($code === $short_code) {
            
            header("Location: $url");
            exit;
        }
    }

    
    header("HTTP/1.0 404 Not Found");
    echo "URL not found! Please check the short code.";
    exit;
}


echo "No short code provided!";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: #fff;
        }
        .container {
            margin-top: 100px;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .shorten-btn {
            background-color: #28a745;
            border-color: #28a745;
        }
        .shorten-btn:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h1 class="mb-4">URL Shortener</h1>
        <form id="urlForm">
            <input type="url" id="urlInput" class="form-control" placeholder="Enter your long URL here" required>
            <button type="submit" class="btn shorten-btn mt-3">Shorten</button>
        </form>
        <div id="result" class="mt-4"></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
