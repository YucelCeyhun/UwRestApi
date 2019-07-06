<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $config["table"] = "users";
        $this->load->library("responsebody", $config);
        $this->load->helper("utilities");
    }


    function Post()
    {
        $data = $this->input->input_stream();

        $array = array(
            'username' => ["kullanıcı adı", "trim|required|min_length[5]"],
            "password" => ["şifre", "trim|required"]
        );
       $result = Validation($array);
       if($result["status"]) {
           echo $this->responsebody->Post($data);
       }else{
           echo $result["message"];
       }

    }
}
