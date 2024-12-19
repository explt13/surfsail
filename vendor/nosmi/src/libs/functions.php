<?php
function debug(mixed $arr)
{
    echo "<pre>" . print_r($arr, 1) . "</pre>";
}

function debugIncludedFile()
{
    $backtrace = debug_backtrace();
    foreach ($backtrace as $trace) {
        if (isset($trace['file'])) {
            echo "<div style='color: green; font-size: 28px'>Included in {$trace['file']} on line {$trace['line']}</div><br/>";
        }
    }
}

function redirect($path = null)
{
    if ($path) {
        $redirect = PATH . $path;
    } else {
        $redirect = $_SERVER['HTTP_REFERER'] ?? PATH;
    }

    if (isAjax()) {
        header('Content-Type: application/json');
        echo json_encode(['redirect' => $redirect]);
        http_response_code(401);
        die;
    }

    header("Location: $redirect");
    die;
}

function isAjax()
{
    return (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest');
}


function cors() {
    $allowed_origins = ['http://surfsail.com'];
    if (in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)){
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }
}
