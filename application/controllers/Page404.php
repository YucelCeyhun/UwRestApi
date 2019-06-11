<?php
class Page404 extends CI_Controller
{
   private const STATUS_CODE = 404;

   public function index()
   {
      $arr["status"] = GetStatus(self::STATUS_CODE);
      $this->responseheader->CreateResponseHeader($arr);
      echo json_encode($arr);
   }
}