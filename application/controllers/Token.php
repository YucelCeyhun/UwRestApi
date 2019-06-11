<?php
class Token extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->responseheader->CreateResponseHeader();
        $this->load->library("jwt");
        $this->load->library("auth");
    }

    public function GetAll()
    {
        if (empty($this->input->cookie("USER_TOKEN"))) {

            $userData = (object)[
                "ID" => 1,
                "Name" => "Jayhoon",
                "Auth" => 0
            ];

            $jwt = $this->jwt->GetJwt($userData);
            $this->auth->CreateAuthCookie($jwt);

            $token = [
                "USER_TOKEN" =>  $jwt
            ];
        } else {

            $token = [
                "USER_TOKEN" =>  $this->input->cookie("USER_TOKEN")
            ];
        }

        echo json_encode($token);
    }
}