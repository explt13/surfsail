<?php
namespace nosmi;

class Cache
{
    use SingletonTrait;

    public function set($key, $data, $seconds = 3600)
    {
        if ($seconds) {
            $content['data'] = $data;
            $content['exp'] = time() + $seconds;
            if (file_put_contents(CACHE . "/" . md5($key) . '.txt', serialize($content))) {
                return true;
            }
            return false;
        }
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