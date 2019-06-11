<?php
defined('BASEPATH') or exit('No direct script access allowed');
include 'HttpVerbs.php';
class Responsebody extends HttpVerbs
{
   private $general;
   public function __construct($config)
   {
      $CI = &get_instance();
      $CI->load->model("general");
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
      $status = ["delete" => $delete];
      echo json_encode($status);
   }

   public function Patch($id, $data)
   {
      $update = false;
      if ($id)
         $update = $this->general->Patch($id, $data);
      $status = ["update" => $update];
      echo json_encode($status);
   }

   public function Put($data)
   {
      $insert = false;
      $insert = $this->general->Put($data);
      $status = ["insert" => $insert];
      echo json_encode($status);
   }
}