<?php

namespace nosmi;

class Request
{
    protected array $query_params;
    protected array $post_data;
    protected array $headers;

    public function __construct()
    {
        $this->headers = getallheaders();
        $this->query_params = $_GET;
        $this->setPostData();
    }

    protected function setPostData(): void
    {
        if (!isset($this->headers['Content-Type']) || $this->headers['Content-Type'] === 'application/json') {
            $this->post_data = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Invalid JSON received: " . json_last_error_msg());
            }
        } else if (($this->headers['Content-Type'] === 'application/x-www-form-urlencoded') || 
        (preg_match("#multipartform-data; boundary=.*#", $this->headers['Content-Type']))) {
            $this->post_data = $_POST;
        }
    }

    /**
     * if not Content Type header present -
     * JSON format will be used by default.
     */

    public function getPostData(string $key, $default = null): mixed
    {
        return $this->post_data[$key] ?? $default;
    }

    public function getQueryParams(string $key, $default = null): mixed
    {
        return $this->query_params[$key] ?? $default;
    }
}