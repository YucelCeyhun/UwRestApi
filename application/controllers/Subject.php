<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subject extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $config["table"] = "subjects";
        $this->load->library("responsebody", $config);
    }

    function GetAll()
    {
        $this->responsebody->GetAll();
    }

    function Get($id)
    {
        $this->responsebody->Get($id);
    }

    function Delete($id = NULL)
    {
        $this->responsebody->Delete($id);
    }

    function Patch($id)
    {
        $data = $this->input->input_stream();
        $this->responsebody->Patch($id, $data);
    }

    function Put()
    {
        $data = $this->input->input_stream();
        $this->responsebody->Put($data);
    }
}