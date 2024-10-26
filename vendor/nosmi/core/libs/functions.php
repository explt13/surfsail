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

    header("Location: $redirect");
    die;
}