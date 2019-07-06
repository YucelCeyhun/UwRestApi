<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Responseheader
{
    private $status, $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function CreateResponseHeader($param = [])
    {
        if ($param) {
            $objParam = (object)$param;
            $this->status = $objParam->status;
        }

        $this->CI->output->set_header('Access-Control-Allow-Origin: http://localhost:3000');
        $this->CI->output->set_header('Access-Control-Allow-Methods:GET, PUT, POST, DELETE, HEAD, PATCH');
        $this->CI->output->set_header('Access-Control-Allow-Headers: X-Requested-With');
        $this->CI->output->set_header('Access-Control-Allow-Credentials: true');
        $this->CI->output->set_header('Content-Type: application/json;charset=utf-8');
        $this->CI->output->set_header('Host: ' . $_SERVER["SERVER_NAME"]);
        $this->CI->output->set_header('Accept-Encoding: ' . 'gzip');
        $this->status ? $this->CI->output->set_status_header($this->status->statusCode) : null;
        $this->CI->output->set_header('Date: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    }

    public function GetResponseHeader()
    {
        $headerResponseObj = (object)array(
            "Host" => $this->CI->output->get_header("Host"),
            "Content-Type" => $this->CI->output->get_header("Content-Type"),
            "Content-Length" => $this->CI->output->get_header("Content-Length"),
            "Date" => $this->CI->output->get_header("Date"),
            "Connection" => $this->CI->output->get_header("Connection"),
            "Accept-Encoding" => $this->CI->output->get_header("Accept-Encoding")
        );

        return json_encode($headerResponseObj);
    }
}