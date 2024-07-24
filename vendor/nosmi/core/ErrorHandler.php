<?php
namespace nosmi;

class ErrorHandler
{
    public function __construct()
    {
        if (DEBUG) {
            error_reporting(-1);
        } else {
            error_reporting(0);
        }
        set_exception_handler([$this, 'exceptionHandler']);
    }

    public function exceptionHandler(\Error | \Exception $e)
    {
        $this->logErrors($e->getMessage(), $e->getFile(), $e->getLine());
        $this->displayError($e::class, $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
    }

    private function logErrors(string $message = '', $file = '', $line = '')
    {
        error_log("[" . date("Y-m-d H:i:s") . "] Error: {$message} | File: {$file} | Line: {$line}\n--------------------------------------\n",
    3, ROOT . '/tmp/errors.log');
    }
    
    private function displayError($errno, $err_message, $err_file, $err_line, int $err_response = 404)
    {
        http_response_code($err_response);
        if ($err_response === 404 && !DEBUG) {
            require WWW . "/errors/404.php";
            die;
        }
        if (DEBUG){
            require WWW . "/errors/dev.php";
        } else {
            require WWW . "/errors/prod.php";
        }
        die;
    }
}