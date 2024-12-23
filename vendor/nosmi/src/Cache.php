<?php
namespace nosmi;

class Cache implements CacheInterface
{
    use SingletonTrait;

    public function set($key, $data, $seconds = 3600)
    {
        if ($seconds > 0) {
            $content['data'] = $data;
            $content['exp'] = time() + $seconds;
            if (file_put_contents(CACHE . "/" . md5($key) . '.txt', serialize($content))) {
                return true;
            }
            $err_handler = ErrorHandler::getInstance();
            $err_handler->logError("Cannot set cache $key");
            return false;
        }
        throw new \Exception("Cannot set cache $key with $seconds seconds");
    }
    
    public function get($key)
    {
        $fileName = CACHE . '/' . md5($key) . '.txt';
        if (file_exists($fileName)) {
            $content = unserialize(file_get_contents($fileName));
            if ($content['exp'] < time()) { // if cache expired
                unlink($fileName);
                return false;
            }
            return $content['data'];
        }
    }

    public function delete($key)
    {
        $fileName = CACHE . '/' . md5($key) . '.txt';
        if (file_exists($fileName)) {
            unlink($fileName);
        }
    }
}