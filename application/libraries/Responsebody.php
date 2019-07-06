<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once 'HttpVerbs.php';
class Responsebody extends HttpVerbs
{
   private $general;
   public function __construct($config)
   {
      $CI = &get_instance();
      $CI->load->model("general");
      $CI->load->helper("utilities");
      $this->general = $CI->general;
      $this->general->SetTable($config["table"]);
   }

   public function GetAll()
   {
      $result = $this->general->GetAll();
      echo json_encode($result, JSON_UNESCAPED_UNICODE);
   }

   public function Get($id)
   {
      $row = $this->general->Get($id);
      echo json_encode($row, JSON_UNESCAPED_UNICODE);
   }

   public function Delete($id)
   {
      $delete = false;
      if ($id)
         $delete = $this->general->Delete($id);
      $status = array("delete" => $delete);
      echo json_encode($status);
   }

   public function Patch($id, $data)
   {
      $update = false;
      if ($id)
         $update = $this->general->Patch($id, $data);
      $status = array("update" => $update);
      echo json_encode($status);
   }

   public function Put($data)
   {
      $insert = false;
      $data = StripValues($data);
      $insert = $this->general->Put($data);
      $status = array("insert" => $insert);
      echo json_encode($status);
   }

    /**
     * @param $data
     */
    public function Post($data = array())
   {

      $data = StripValues($data);
      $row =  $this->general->Post($data);
      $check = isset($row);
      $status = array("check" => $check);
      $array = $check ? array("status" => $status, "data" => $row) : $status;

      return json_encode($array, JSON_UNESCAPED_UNICODE);
    }
}
