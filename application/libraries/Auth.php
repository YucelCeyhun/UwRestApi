<?php
class Auth
{
    private const XSS = FALSE;
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library("jwt");
    }

    public function CreateAuthCookie($jwt)
    {
        $cookie = array(
            'name'   => 'TOKEN',
            'value'  => $jwt,
            'expire' => '3600',
            'domain' => $_SERVER["SERVER_NAME"],
            'path'   => '/',
            'prefix' => 'USER_',
            'secure' => self::XSS
        );

        $this->CI->input->set_cookie($cookie);
    }

    private function setHeader($status = 200)
    {
        $arr["status"] = GetStatus($status);
        $this->CI->responseheader->CreateResponseHeader($arr);
    }

    public function CheckAuth()
    {

        $userToken = $this->CI->input->cookie("USER_TOKEN");

        if (empty($userToken)) {
            $this->setHeader(403);
            exit;
        }

        if (!$this->CI->jwt->CheckJwt($userToken)) {
            $this->setHeader(401);
            $this->CI->load->helper('cookie');
            delete_cookie("USER_TOKEN");
            exit;
        }

        $this->setHeader();
    }
}