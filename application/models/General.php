<?php
defined('BASEPATH') or exit('No direct script access allowed');
class General extends CI_Model
{

    private $table;

    public function SetTable($table)
    {
        $this->table = $table;
    }
	
	public function Post($data){
		return $this->db->where($data)->get($this->table)->first_row();
	}

    public function GetAll()
    {
        return $this->db->get($this->table)->result();
    }

    public function Get($id)
    {
        return $this->db->where("id", $id)->get($this->table)->row();
    }

    public function Delete($id)
    {
        return $this->db->where("id", $id)->delete($this->table);
    }

    public function Patch($id, $data)
    {
        return $this->db->where("id", $id)->update($this->table, $data);
    }

    public function Put($data)
    {
        return $this->db->insert($this->table, $data);
    }
	
}