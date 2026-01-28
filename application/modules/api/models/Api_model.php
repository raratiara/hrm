
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends MY_Model
{
	/* Module */
 	protected $folder_name	= "api/api";
    protected $table 		= "users";

    /* upload */
    protected $attachment_folder    = "./uploads/absensi";
    protected $allow_type           = "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
    protected $allow_size           = "0"; // 0 for limit by default php conf (in Kb)


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
        /*$sql = "select * from user where username = '".$username."' AND passwd = '".md5($password)."' AND isaktif = 2 ORDER BY date_insert DESC LIMIT 1";*/

        $sql = "select a.*, b.emp_code, c.logo, c.name, b.id as emp_id, b.emp_source, b.cust_id, b.work_location
                from user a left join employees b on b.id = a.id_karyawan
                left join companies c on c.id = b.company_id
                where (a.username = '".$username."' or b.emp_code = '".$username."') AND a.passwd = '".md5($password)."'
                AND a.isaktif = 2 ORDER BY a.date_insert DESC LIMIT 1";

        $user = $this->db->query($sql)->row();

        if($user){
            return $user;
        }else return null;
    }

    public function get_user_by_employee_id($employee_id) 
    {
        return $this->db 
            ->where('id_karyawan', $employee_id)
            ->where('isaktif', 2)
            ->get('user')
            ->row();
    }

    public function update_password_by_user_id($user_id, $password)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->update('user', [
                'passwd' => $password
            ]);
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


    // Upload file
    public function upload_file($id = "", $fieldname= "", $replace=FALSE, $oldfilename= "", $array=FALSE, $i=0) { 
        $data = array();
        $data['status'] = FALSE; 
        if(!empty($id) && !empty($fieldname)){ 
            // handling multiple upload (as array field)

            if($array){ 
                // Define new $_FILES array - $_FILES['file']
                $_FILES['file']['name'] = $_FILES[$fieldname]['name'];
                $_FILES['file']['type'] = $_FILES[$fieldname]['type'];
                $_FILES['file']['tmp_name'] = $_FILES[$fieldname]['tmp_name'];
                $_FILES['file']['error'] = $_FILES[$fieldname]['error'];
                $_FILES['file']['size'] = $_FILES[$fieldname]['size']; 
                // override field
                //$fieldname = 'document';

            } 
            // handling regular upload (as one field)
            if(isset($_FILES[$fieldname]) && !empty($_FILES[$fieldname]['name']))
            { 
                /*$dir = $this->attachment_folder.'/'.$id;
                if(!is_dir($dir)) {
                    mkdir($dir);
                }
                if($replace){
                    $this->remove_file($id, $oldfilename);
                }*/
                $config['upload_path']   = $this->attachment_folder;
                $config['allowed_types'] = $this->allow_type;
                $config['max_size']      = $this->allow_size;
                
                $this->load->library('upload', $config); 
                
                if(!$this->upload->do_upload($fieldname)){ 
                    $err_msg = $this->upload->display_errors(); 
                    $data['error_warning'] = strip_tags($err_msg);              
                    $data['status'] = FALSE;
                } else { 
                    $fileData = $this->upload->data();
                    $data['upload_file'] = $fileData['file_name'];
                    $data['status'] = TRUE;
                }
            }
        }

        
        
        return $data;
    }


    public function query_db($sql)
    { 
       

        /*$host   = "localhost";
        $dbname = "hrm";
        $user   = "root";
        $pass   = "";*/

        /*$host   = "172.30.5.202";
        $dbname = $nama_db;
        $user   = $username_db;
        $pass   = $password_db;*/


        /*$host   = "localhost";
        $dbname = "u1647144_hrm";
        $user   = "u1647144_hrm";
        $pass   = "HRM_NBID@2025!";*/

        /// Nathabuana
        /*$host   = "localhost";
        $dbname = "hrm";
        $user   = "hrm";
        $pass   = "HRM_NBID@2025!";*/


        /// Nathabuana
        /*$host   = "172.30.2.50";
        $dbname = "hrm";
        $user   = "hrm";
        $pass   = "HRM_NBID@2025!";*/

        
        /// MAS
        $host   = "172.30.2.93";
        $dbname = "hrm";
        $user   = "hrm";
        $pass   = "Nbid@2025!";
        

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
            // Enable error exceptions
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully<br>";

            $stmt = $pdo->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            //die("Connection failed: " . $e->getMessage());
            $row='failed';
        }


        
        return $row;

    }


    public function cek_user_device($user_id, $type)
    { 
        
        $sql = "select * from user_devices where user_id = '".$user_id."' and type = '".$type."' and is_active = 1 ORDER BY id DESC LIMIT 1";

        $user = $this->db->query($sql)->row();

        if($user){
            return $user;
        }else return null;
    }

    
    public function get_active_login_banner()
    {
        return $this->db
            ->select('name, photo')
            ->from('login_banners')
            ->where('is_active', 1)
            ->order_by('created_at', 'DESC')
            ->limit(1)
            ->get()
            ->row();
    }

    public function deactivate_all_login_banner()
    {
        return $this->db->update('login_banners', ['is_active' => 0]);
    }

    public function insert_login_banner($data)
    {
        return $this->db->insert('login_banners', $data);
    }

}

