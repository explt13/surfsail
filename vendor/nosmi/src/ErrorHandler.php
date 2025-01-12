<?php
namespace nosmi;

class ErrorHandler
{
    use SingletonTrait;

    private function __construct()
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
        $this->logError($e->getMessage(), $e->getFile(), $e->getLine());
        $this->render($e::class, $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
    }

    private function logError(string $message = '', $file = '', $line = '')
    {
        error_log("[" . date("Y-m-d H:i:s") . "] Error: {$message} | File: {$file} | Line: {$line}
                    \n--------------------------------------\n", 3, ROOT . '/tmp/errors.log');
    }
    
    private function render($err_type, $err_message, $err_file, $err_line, $err_response = 500)
    {
        if (!($err_type === 'PDOException')){
            http_response_code($err_response);
        }
        if (isAjax()) {
            echo json_encode(["message" => $err_message, "response_code" => $err_response]);
            die;
        }

        $view_path = APP . '/views/errors';
        if (!DEBUG) {
            switch ($err_response) {
                case 404:
                    require_once $view_path . "/404.php";
                    break;
                
                default:
                    require_once $view_path . "/500.php";
            };
        } else {
            require_once $view_path . "/dev.php";
        }
        die;
    }
}