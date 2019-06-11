<?php
abstract class HttpVerbs
{
    protected function GetAll()
    { }
    /**
     * @param int $id tek bir obje için benzersiz numara
     */
    protected function Get($id)
    { }

    protected function Post()
    { }
    /**
     * @param int $id tek bir obje için benzersiz numara
     */
    protected function Delete($id)
    { }
    /**
     * @param int $id tek bir obje için benzersiz numara
     */
    protected function Patch($id, $data)
    { }

    protected function Put($data)
    { }
}