<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{
    /**
     * Hangi http eylemleri yapılacaksa o işimde method tanımlanır
     * methodun gövdesine gene aynı adı tasıyan,libraryden method çekilir; $this->responsebody->Get($id);
     * $id sayfadaki id yi temsil eder buda tablo idsine karşılık gelir
     */
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