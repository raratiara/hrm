<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends MY_Model
{
	/* Module */
 	protected $folder_name	= "api/api";
    protected $table 		= "user";

	function __construct()
	{
		parent::__construct();
	}
 
    public function register($data)
    {
		$query = $this->db->insert($this->table, $data);

        return $query;
    }
 
    public function cek_login($username)
    {
        $query = $this->db->where('username', $username)
				->get($this->table)
                ->num_rows();
 
        if($query >  0){
            $hasil = $this->db->where('username', $username)
                    ->limit(1)
                    ->get($this->table)
                    ->row_array();
        } else {
            $hasil = array(); 
        }

        return $hasil;
    }

    public function cek_company($url)
    {
        $table = "companies";

        $query = $this->db->where('website', $url)
                ->get($table)
                ->num_rows();
 
        if($query >  0){
            $hasil = $this->db->where('website', $url)
                    ->limit(1)
                    ->get($table)
                    ->row_array();
        } else {
            $hasil = array(); 
        }

        return $hasil;
    }

    public function cek_employee($employee)
    {
        $table = "employees";

        $query = $this->db->where('id', $employee)
                ->get($table)
                ->num_rows();
 
        if($query >  0){
            $hasil = $this->db->where('id', $employee)
                    ->limit(1)
                    ->get($table)
                    ->row_array();
        } else {
            $hasil = array(); 
        }

        return $hasil;
    }

}
