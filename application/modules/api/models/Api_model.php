<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends MY_Model
{
	/* Module */
 	protected $folder_name	= "api/api";
    protected $table 		= "users";

	function __construct()
	{
		parent::__construct();
	}
 
    public function register($data)
    {
		$query = $this->db->insert($this->table, $data);

        return $query;
    }
 
    /*public function cek_login_old($username)
    {
        $query = $this->db->where('name', $username)
				->get($this->table)
                ->num_rows();
 
        if($query >  0){
            $hasil = $this->db->where('name', $username)
                    ->limit(1)
                    ->get($this->table)
                    ->row_array();
        } else {
            $hasil = array(); 
        }

        return $hasil;
    }*/


    public function cek_login($username, $password)
    {
        $sql = "select * from user where username = '".$username."' AND passwd = '".md5($password)."' AND isaktif = 2 ORDER BY date_insert DESC LIMIT 1";
        $user = $this->db->query($sql)->row();

        if($user){
            return $user;
        }else return null;
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

    public function dayCount($from, $to) {
        $first_date = strtotime($from);
        $second_date = strtotime($to);
        $days_diff = $second_date - $first_date;
        return date('d',$days_diff);
    }

    public function get_data_sisa_cuti($empid, $startdate, $enddate){ 

        $cek_start_date = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$empid."' and status = 1 and ( ('".$startdate."' >= period_start and '".$startdate."' <= period_end) or ('".$startdate."' >= period_start and '".$startdate."' <= expired_date) )")->result(); 

        $cek_end_date = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$empid."' and status = 1 and ( ('".$enddate."' >= period_start and '".$enddate."' <= period_end) or ('".$enddate."' >= period_start and '".$enddate."' <= expired_date) )")->result(); 


        // cek apakah startdate & enddate masuk dalam periode available cuti
        if(!empty($cek_start_date) && !empty($cek_end_date)){
            $rs = $this->db->query("select sum(sisa_cuti) as ttl_sisa_cuti from total_cuti_karyawan where employee_id = '".$empid."' and status = 1")->result(); 

            return $rs;
        }else return 0;

    }

}
