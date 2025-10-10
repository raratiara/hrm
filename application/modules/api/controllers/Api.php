



<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends API_Controller
{
	/* Module */
 	//private $model_name				= "api_model";


 	/* upload */
 	//di LOCAL ->//protected $attachment_folder	= "./uploads/absensi"; 
 	/*protected $attachment_folder	= "hrm.sandboxxplore.com/uploads/absensi"; 
	protected $allow_type			= "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
	protected $allow_size			= "0";*/ // 0 for limit by default php conf (in Kb)


   	public function __construct()
	{
      	parent::__construct();

		//$this->load->model($this->model_name);
   	}

    public function index()
    {
        $response = [
            'message' => 'Access denied',
            "error" => 'Not allowed root access.'
            ];

		$this->render_json($response, 400);
		exit;
	}


	public function tes(){
		$j=28;
		$field = sprintf("%02d", $j);


		echo $field; die();


	}

	

	
	// register basic example
    public function register()
    {
		$valid_elem = ['first_name', 'last_name', 'email', 'password'];
		$params = elements($valid_elem, $this->mParams);
		if(!values_satisfied($params)){
			$firstname  = $params['first_name'];
			$lastname   = $params['last_name'];
			$email      = $params['email'];
			$password   = $params['password'];
		 
			$password_hash = password_hash($password, PASSWORD_BCRYPT);
		 
			$dataRegister = [
				'first_name' => $firstname,
				'last_name' => $lastname,
				'email' => $email,
				'password' => $password_hash
			];
		 
			$register = $this->api->register($dataRegister);
		 
			if($register == true){
				$response = [
					'status' => 201, // Created
					'message' => 'Registration Successful'
				];
			} else {
				$response = [
					'status' => 503, // Service Unavailable
					'message' => 'Registration Fail'
				];
			}
		} else {
			$response = [
				'status' => 400, // Bad Request
				'message' => 'Failed',
				'error' => 'Require not satisfied'
			];
		}
		
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
    }



    public function login_old()
    {
    	$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$username	= $_REQUEST['username'];
    	$password 	= $_REQUEST['password'];


		if($username != '' && $password != ''){
			
			$cek_login = $this->api->cek_login($username);	
			
			if(password_verify($password, isset($cek_login['password'])?$cek_login['password']:''))
			{ 
				$data = array(
					"id" 			=> $cek_login['id'],
					"name" 			=> $cek_login['name'],
					"email" 		=> $cek_login['email'],
					"employee_id" 	=> $cek_login['id_karyawan']
				);
	 
				$token = $this->genJWTdata($data);	 
				$response = [
					'status' 		=> 200,
					'message' 		=> 'Success',
					"token" 		=> $token[0],
					"expire" 		=> $token[1],
					"email" 		=> $cek_login['email'],
					"employee_id" 	=> $cek_login['id_karyawan'] 
				];
			} else { 
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Access credentials not match'
				];
			}

		} else {
			$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
		}
		
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
    }

    public function login()
    {
    	$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$username	= $_REQUEST['username'];
    	$password 	= $_REQUEST['password'];


		if($username != '' && $password != ''){
			
			$cek_login = $this->api->cek_login($username, $password);	
			
			if($cek_login != '')
			{ 
				$data = array(
					"id" 			=> $cek_login->user_id,
					"name" 			=> $cek_login->name,
					"email" 		=> $cek_login->email,
					"employee_id" 	=> $cek_login->id_karyawan
				);
	 
				$token = $this->genJWTdata($data);	 
				$response = [
					'status' 		=> 200,
					'message' 		=> 'Success',
					"token" 		=> $token[0],
					"expire" 		=> $token[1],
					"email" 		=> $cek_login->email,
					"employee_id" 	=> $cek_login->id_karyawan 
				];
			} else { 
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Access credentials not match'
				];
			}

		} else {
			$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
		}
		
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
    }


    public function sync()
    { 

    	
    	$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$url		= $_REQUEST['url'];
    	$username	= $_REQUEST['username'];
    	$password 	= $_REQUEST['password'];


    	//$cek_url = $this->db->query("select * from companies where website = '".$url."'")->result(); 
    	$sql = "select * from companies where website = '".$url."'";
    	//$nama_db="hrm"; $username_db="hrm"; $password_db="hrm@2025!";
    	$cek_url = $this->api->query_db($sql); 
    
    	if(!empty($cek_url)){ 
    		$url_app 			= $cek_url['url_app'];
    		
    		$cek_login = $this->api->cek_login($username, $password);
    	
    		if(!empty($cek_login)){ 
    			$logo 				= $cek_login->logo;
    			$nama_perusahaan 	= $cek_login->name;
    			$getversion = $this->db->query("select * from version order by id desc limit 1")->result();

				$version 	= $getversion[0]->version;
    			$urllogo 	= $url.'/uploads/logo/'.$logo;

				$data = array(
					"nama_perusahaan" => $nama_perusahaan,
					"logo_perusahaan" => $urllogo,
					"version" => $version,
					"url_app" => $url_app
				);
	 
				$response = [
					'status' 	=> 200,
					'message' 	=> 'Success',
					"data" 		=> $data
				];
    		}else{ 
    			$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'User not found'
				];
    		}
    		
    	}else{ 
    		$response = [
				'status' 	=> 401,
				'message' 	=> 'Failed',
				'error' 	=> 'URL not found x'
			];
    	}

		

		
		
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
    }


    public function sync_old()
    {
    	/*$bearer_token = 'jk43242kdnsd';

    	$headers = getallheaders();
    	if (substr($headers['Authorization'], 0, 7) !== 'Bearer ') {
		    echo json_encode(["error" => "Bearer keyword is missing"]);
		    exit;
		}else{
			$token = trim(substr($headers['Authorization'], 7));

			if($token != $bearer_token){
				echo json_encode(["error" => "Token not valid"]);
		    	exit;
			}

		}*/


    	$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
		$base_url = $protocol . $_SERVER['HTTP_HOST'] . '/';


    	$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$url	= $_REQUEST['url'];
    	$username	= $_REQUEST['username'];
    	$password 	= $_REQUEST['password'];


		$cek_login = $this->api->cek_login($username, $password);
		if($cek_login != '')
		{ 
			if($url != ''){
			
				$cek_data = $this->api->cek_company($url);	
				$getversion = $this->db->query("select * from version order by id desc limit 1")->result(); 
				$version 	= $getversion[0]->version;

				if($cek_data['id'] != '')
				{
					//in LOCAL => //$urllogo = $base_url.'_hrm/uploads/logo/'.$cek_data['logo'];
					$urllogo = $base_url.'uploads/logo/'.$cek_data['logo'];
					$data = array(
						"nama_perusahaan" => $cek_data['name'],
						"logo_perusahaan" => $urllogo,
						"version" => $version  
					);
		 
					$response = [
						'status' 	=> 200,
						'message' 	=> 'Success',
						"data" 		=> $data
					];
				} else {
					$response = [
						'status' 	=> 401,
						'message' 	=> 'Failed',
						'error' 	=> 'Company not found'
					];
				}
				
			} else {
				$response = [
					'status' 	=> 400, // Bad Request
					'message'	=>'Failed',
					'error'	 	=> 'Require not satisfied'
				];
			}
		}else{
			$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Access credentials not match'
				];
		}

		
		
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
    }


    public function absen_old()
    {
    	$this->verify_token();


		/*$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true); 
    	$_REQUEST = $data;

    	$employee	= $_REQUEST['employee'];
    	$tipe 		= $_REQUEST['tipe'];
    	$datetime	= $_REQUEST['datetime_attendance'];
    	$notes		= $_REQUEST['notes'];
    	$photo		= $_REQUEST['photo'];*/

    	$employee	= $_POST['employee'];
    	$tipe 		= $_POST['tipe'];
    	$datetime	= $_POST['datetime_attendance'];
    	$notes		= $_POST['notes'];
    	$photo		= $_FILES['photo'];

    	//print_r($photo); die();

		if($employee != '' && $tipe != '' && $datetime != ''){
			/*$date 	= date_format($datetime,"Y-m-d");
			$time 		= date_format($datetime,"H:i:s");*/

			$exp 			= explode(" ",$datetime);
			$date 			= $exp[0];
			$time 			= $exp[1];
			$timestamp_time = strtotime($time); 

			$cek_emp = $this->api->cek_employee($employee);	

			if($cek_emp['shift_type'] != '')
			{
				if($cek_emp['shift_type'] == 'Reguler'){
					$dt = $this->db->query("select * from master_shift_time where shift_type = 'Reguler' ")->result(); 
					$attendance_type 	= $dt[0]->name;
					$time_in 			= $dt[0]->time_in;
					$time_out 			= $dt[0]->time_out;
					$post_timein 		= strtotime($time_in);
					$post_timeout 		= strtotime($time_out);
				}

				$is_late=''; $is_leaving_office_early = '';
				if($timestamp_time > $post_timein){
					$is_late='Y';
				}
				if($timestamp_time < $post_timeout){
					$is_leaving_office_early = 'Y';
				}

				$cek_data = $this->db->query("select * from time_attendances where employee_id = '".$employee."' and date_attendance = '".$date."' ")->result();


				if(!empty($cek_data)){  
					if($cek_data[0]->id != ''){ //update
						if($tipe == 'checkin'){
							
							$response = [
								'status' 	=> 400, // Bad Request
								'message' 	=>'Failed',
								'error' 	=> 'Require not satisfied'
							];
							/*$data = [
								'attendance_type' 			=> $attendance_type,
								'time_in' 					=> $time_in,
								'time_out' 					=> $time_out,
								'date_attendance_in' 		=> $datetime,
								'is_late'					=> $is_late,
								'updated_at'				=> date("Y-m-d H:i:s")
							];
							$rs = $this->db->update("time_attendances", $data, "id='".$cek_data[0]->id."'");

							if($rs){
								$response = [
									'status' 	=> 200,
									'message' 	=> 'Success'
								];
							}else{
								$response = [
									'status' 	=> 401,
									'message' 	=> 'Failed',
									'error' 	=> 'Error update checkin'
								];
							}*/

						}else{ //checkout
							$f_datetime_in 			= $cek_data[0]->date_attendance_in;
							$f_datetime_out 		= $datetime;
							$timestamp1 			= strtotime($f_datetime_in); 
							$timestamp2 			= strtotime($f_datetime_out);
							$num_of_working_hours 	= abs($timestamp2 - $timestamp1)/(60)/(60); //jam


							//upload 
							$dataU = array();
	        				$dataU['status'] = FALSE; 
							$fieldname='photo';
							if(isset($_FILES[$fieldname]) && !empty($_FILES[$fieldname]['name']))
				            { 
				               
				                
				            	$config['upload_path']   = "uploads/absensi/";
				                $config['allowed_types'] = "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
				                $config['max_size']      = "0"; 
				                
				                $this->load->library('upload', $config); 
				                
				                if(!$this->upload->do_upload($fieldname)){ 
				                    $err_msg = $this->upload->display_errors(); 
				                    $dataU['error_warning'] = strip_tags($err_msg);              
				                    $dataU['status'] = FALSE;
				                } else { 
				                    $fileData = $this->upload->data();
				                    $dataU['upload_file'] = $fileData['file_name'];
				                    $dataU['status'] = TRUE;
				                }
				            }
				            $document = '';
							if($dataU['status']){ 
								$document = $dataU['upload_file'];
							} else if(isset($dataU['error_warning'])){ 
								//echo $dataU['error_warning']; exit;

								$document = 'ERROR : '.$dataU['error_warning'];
							}

				            //end upload

							$data = [
								'attendance_type' 			=> $attendance_type,
								'time_in' 					=> $time_in,
								'time_out' 					=> $time_out,
								'date_attendance_out' 		=> $datetime,
								'is_leaving_office_early'	=> $is_leaving_office_early,
								'num_of_working_hours'		=> $num_of_working_hours,
								'updated_at'				=> date("Y-m-d H:i:s"),
								'notes' => $notes,
								'photo' => $document
							];
							$rs = $this->db->update("time_attendances", $data, "id='".$cek_data[0]->id."'");

							if($rs){
								$response = [
									'status' 	=> 200,
									'message' 	=> 'Success'
								];
							}else{
								$response = [
									'status' 	=> 401,
									'message' 	=> 'Failed',
									'error' 	=> 'Error update checkout'
								];
							}
						}

					}else{
						$response = [
							'status' 	=> 400, // Bad Request
							'message' 	=>'Failed',
							'error' 	=> 'Require not satisfied'
						];
					}
				}else{ //insert
					if($tipe == 'checkin'){

						$data = [
							'date_attendance' 			=> $date,
							'employee_id' 				=> $employee,
							'attendance_type' 			=> $attendance_type,
							'time_in' 					=> $time_in,
							'time_out' 					=> $time_out,
							'date_attendance_in' 		=> $datetime,
							'is_late'					=> $is_late,
							'created_at'				=> date("Y-m-d H:i:s")
						];

						$rs = $this->db->insert("time_attendances", $data);

						if($rs){
							$response = [
								'status' 	=> 200,
								'message' 	=> 'Success'
							];
						}else{
							$response = [
								'status' 	=> 401,
								'message' 	=> 'Failed',
								'error' 	=> 'Error submit checkin'
							];
						}

					}else{ //checkout
						$response = [
							'status' 	=> 401,
							'message' 	=> 'Failed',
							'error' 	=> 'Please CheckIn first'
						];
					}
				}
				
			} else {
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Employee not found'
				];
			}
			
		} else {
			$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
		}
		
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
    }


    public function absen_checkin()
    {
    	$this->verify_token();

    	$employee	= $_POST['employee_id'];
    	$tipe 		= 'checkin';
    	$datetime	= $_POST['datetime_attendance'];
    	$latitude	= $_POST['latitude'];
    	$longitude	= $_POST['longitude'];
    	$work_location	= $_POST['work_location'];
    	$notes		= $_POST['notes'];
    	$photo		= $_FILES['photo'];
    	


		if($employee != '' && $datetime != ''){

			$exp 			= explode(" ",$datetime);
			$date 			= $exp[0];
			$time 			= $exp[1];
			$timestamp_time = strtotime($time); 
			$year = date("Y", strtotime($date));
			$month = date("m", strtotime($date));
			$timestamp_datetime = strtotime($datetime);
			$period = date("Y-m", strtotime($date));
			$tgl = date("d", strtotime($date));

			$cek_emp = $this->api->cek_employee($employee);	

			if($cek_emp['shift_type'] != '')
			{
				$emp_shift_type=1;
				if($cek_emp['shift_type'] == 'Reguler'){ 
					$dt = $this->db->query("select * from master_shift_time where shift_type = 'Reguler' ")->result(); 
					
				}else if($cek_emp['shift_type'] == 'Shift'){ 
					
					// $data_attendances = $this->db->query("select * from time_attendances where date_attendance = '".$date."' and employee_id = '".$employee."'")->result(); 
					// //jika sudah ada absen hari ini, maka akan cek shift besok, kalau dapet shift 3, maka bisa checkin. Karna shift 3 jadwalnya tengah malam, jadi bisa checkin di tgl sebelumnya.
					// if((!empty($data_attendances)) && $data_attendances[0]->date_attendance_in != null && $data_attendances[0]->date_attendance_in != '0000-00-00 00:00:00' && $data_attendances[0]->date_attendance_out != null && $data_attendances[0]->date_attendance_out != '0000-00-00 00:00:00'){

					// 	$dateTomorrow = date("Y-m-d", strtotime($date . " +1 day"));
					// 	$period  = date('Y-m', strtotime($dateTomorrow));
					// 	$tgl = date('d', strtotime($dateTomorrow));
					// }

					// $dt = $this->db->query("select a.*, b.periode
					// 		, b.`".$tgl."` as 'shift' 
					// 		, c.time_in, c.time_out, c.name 
					// 		from shift_schedule a
					// 		left join group_shift_schedule b on b.shift_schedule_id = a.id
					// 		left join master_shift_time c on c.shift_id = b.`".$tgl."`
					// 		where b.employee_id = '".$employee."' and a.period = '".$period."' ")->result(); 

					// if($dt[0]->shift != 3){ //bukan shift 3, tidak bisa checkin di tgl sebelumnya
					// 	//$emp_shift_type=0;
					// 	$period = date("Y-m", strtotime($date)); 
					// 	$tgl = date("d", strtotime($date));
					// 	$dt = $this->db->query("select a.*, b.periode, b.`".$tgl."` as 'shift', c.time_in, c.time_out, c.name 
					// 		from shift_schedule a left join group_shift_schedule b on b.shift_schedule_id = a.id 
					// 		left join master_shift_time c on c.shift_id = b.`".$tgl."`
					// 		where b.employee_id = '".$employee."' and a.period = '".$period."' ")->result();
					// }



					/// NEW SCRIPT
					$datetimemax_shift3 = $date.' 08:00:00';
					if($datetime < $datetimemax_shift3){ //brarti dia sdg checkin shift 3 di tgl sebelumnya (late)
						$dateYesterday = date("Y-m-d", strtotime($date . " -1 day"));
						$period  = date('Y-m', strtotime($dateYesterday));
					 	$tgl = date('d', strtotime($dateYesterday));
					 	$date = $dateYesterday;
					}


					$dt = $this->db->query("select 
					    a.*, 
					    b.periode, 
					    b.`".$tgl."` as 'shift', 
					    c.name,
					    case 
					        when c.shift_id = 3 then 
					            concat(date_add(str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d'), interval 1 day), ' ', c.time_in)
					        else 
					            concat(str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d'), ' ', c.time_in)
					    end as expected_checkin,
					    case 
					        when c.shift_id = 2 then 
					            concat(date_add(str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d'), interval 1 day), ' 00:00:00')
					        when c.shift_id = 3 then 
					            concat(date_add(str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d'), interval 1 day), ' ', c.time_out)
					        else 
					            concat(str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d'), ' ', c.time_out)
					    end as expected_checkout,
					    c.time_in, c.time_out, str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d') as date_attendance
					from shift_schedule a
					left join group_shift_schedule b on b.shift_schedule_id = a.id 
					left join master_shift_time c on c.shift_id = b.`".$tgl."`
					where b.employee_id = '".$employee."'
					and a.period = '".$period."'
					")->result(); 


					if($dt[0]->shift == ""){
						$emp_shift_type=0;
					}

					/// END NEW SCRIPT

				}else{ //tidak ada shift type
					$emp_shift_type=0;
				} 


				if($emp_shift_type == 1){ 
					$attendance_type 	= $dt[0]->name;
					$time_in 			= $dt[0]->time_in;
					$time_out 			= $dt[0]->time_out;
					//$post_timein 		= strtotime($time_in);
					//$post_timeout 		= strtotime($time_out);

					if($attendance_type == 'Shift 3'){
						$date2 = date("Y-m-d", strtotime($date . " +1 day"));
					}else{
						$date2 = $date;
					}

					$schedule 			= $date2.' '.$time_in;
					$post_timein 		= strtotime($schedule); 
					$schedule_out 		= $date2.' '.$time_out;
					$post_timeout 		= strtotime($schedule_out); 

					

					if($timestamp_time > $post_timeout){ //jika checkin di atas waktu checkout
						$response = [
							'status' 	=> 401,
							'message' 	=> 'Failed',
							'error' 	=> 'Check-in time has expired'
						];

					}else{

						$is_late=''; 
						if($timestamp_time > $post_timein){
							$is_late='Y';
						}

						$cek_data = $this->db->query("select * from time_attendances where employee_id = '".$employee."' and date_attendance = '".$date."' ")->result();


						if(!empty($cek_data) && $cek_emp['shift_type'] == 'Reguler'){  
							$response = [
								'status' 	=> 401,
								'message' 	=> 'Failed',
								'error' 	=> 'Cannot double checkin'
							];
						}else{ //insert
							$error=0; 
							if($cek_emp['shift_type'] == 'Shift'){ 
								if(!empty($cek_data)){  
									// $cek_data_shift = $this->db->query("select * from time_attendances where employee_id = '".$employee."' and date_attendance = '".$date."' and (date_attendance_in is not null and date_attendance_in != '0000-00-00') and (date_attendance_out is not null and date_attendance_out != '0000-00-00') ")->result();
									// if(!empty($cek_data_shift) && $attendance_type == 'Shift 3'){ //maka set bahwa absen yg akan dilakukan adalah absen utk hari besok (hanya utk shift 3)
								
									// 	$date = date("Y-m-d", strtotime($date . " +1 day"));

									// 	$cek_data_shift_besok = $this->db->query("select * from time_attendances where employee_id = '".$employee."' and date_attendance = '".$date."' ")->result();
									// 	if(!empty($cek_data_shift_besok)){ 
									// 		$error='Cannot double checkin';
									// 	}else{ 
											
									// 		$dt = $this->db->query("select a.*, b.periode
									// 				, b.`".$tgl."` as 'shift' 
									// 				, c.time_in, c.time_out, c.name 
									// 				from shift_schedule a
									// 				left join group_shift_schedule b on b.shift_schedule_id = a.id 
									// 				left join master_shift_time c on c.shift_id = b.`".$tgl."`
									// 				where b.employee_id = '".$employee."' and a.period = '".$period."' ")->result(); 

									// 		if(empty($dt)){
									// 			$error='Checkin Date not valid';
									// 		}else{
									// 			$attendance_type 	= $dt[0]->name;
									// 			$time_in 			= $dt[0]->time_in;
									// 			$time_out 			= $dt[0]->time_out;
									// 			$datetime_in 		= $date.' '.$time_in;
									// 			$post_datetimein 	= strtotime($datetime_in);
												

									// 			$is_late=''; 
									// 			if($timestamp_datetime > $post_datetimein){
									// 				$is_late='Y';
									// 			}
									// 		}
									// 	}

									// }else{ 
									// 	/*$error='Checkin Date not valid';*/
									// 	$error='Cannot double checkin';
									// }

									$error='Cannot double checkin';
								}else{ 
									$dt = $this->db->query("select a.*, b.periode
											, b.`".$tgl."` as 'shift' 
											, c.time_in, c.time_out, c.name 
											from shift_schedule a
											left join group_shift_schedule b on b.shift_schedule_id = a.id 
											left join master_shift_time c on c.shift_id = b.`".$tgl."`
											where b.employee_id = '".$employee."' and a.period = '".$period."' ")->result(); 
									if(empty($dt)){
										$error='Checkin Date not valid';
									}
								}
						
							}

							if($error==0){

								//upload 
								$dataU = array();
		        				$dataU['status'] = FALSE; 
								$fieldname='photo';
								if(isset($_FILES[$fieldname]) && !empty($_FILES[$fieldname]['name']))
					            { 
					               
					                
					            	$config['upload_path']   = "uploads/absensi/";
					                $config['allowed_types'] = "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
					                $config['max_size']      = "0"; 
					                
					                $this->load->library('upload', $config); 
					                
					                if(!$this->upload->do_upload($fieldname)){ 
					                    $err_msg = $this->upload->display_errors(); 
					                    $dataU['error_warning'] = strip_tags($err_msg);              
					                    $dataU['status'] = FALSE;
					                } else { 
					                    $fileData = $this->upload->data();
					                    $dataU['upload_file'] = $fileData['file_name'];
					                    $dataU['status'] = TRUE;
					                }
					            }
					            $document = '';
								if($dataU['status']){ 
									$document = $dataU['upload_file'];
								} else if(isset($dataU['error_warning'])){ 
									//echo $dataU['error_warning']; exit;

									$document = 'ERROR : '.$dataU['error_warning'];
								}
					            //end upload


								$data = [
									'date_attendance' 			=> $date,
									'employee_id' 				=> $employee,
									'attendance_type' 			=> $attendance_type,
									'time_in' 					=> $time_in,
									'time_out' 					=> $time_out,
									'date_attendance_in' 		=> $datetime,
									'is_late'					=> $is_late,
									'created_at'				=> date("Y-m-d H:i:s"),
									'lat_checkin' 				=> $latitude,
									'long_checkin' 				=> $longitude,
									'work_location' 			=> $work_location,
									'notes' 					=> $notes,
									'photo' 					=> $document
								];

								$rs = $this->db->insert("time_attendances", $data);

								if($rs){
									$upd_emp = [
										'last_lat' 				=> $latitude,
										'last_long' 			=> $longitude
									];
									$this->db->update("employees", $upd_emp, "id='".$employee."'");


									$response = [
										'status' 	=> 200,
										'message' 	=> 'Success'
									];
								}else{
									$response = [
										'status' 	=> 401,
										'message' 	=> 'Failed',
										'error' 	=> 'Error submit checkin'
									];
								}
							}else{
								$response = [
									'status' 	=> 401,
									'message' 	=> 'Failed',
									'error' 	=> $error
								];
							}

						}
					}

					
				}else{
					$response = [
						'status' 	=> 401,
						'message' 	=> 'Failed',
						'error' 	=> 'Data Shift not found'
					];
				}
				
			} else {
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Employee not found'
				];
			}
			
		} else {
			$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
		}
		
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
    }


    public function absen_checkout()
    {
    	$this->verify_token();

    	$employee	= $_POST['employee_id'];
    	$tipe 		= 'checkout';
    	$datetime	= $_POST['datetime_attendance'];
    	$notes		= $_POST['notes'];
    	$photo		= $_FILES['photo'];
    	$latitude	= $_POST['latitude'];
    	$longitude	= $_POST['longitude'];
    	$work_location	= $_POST['work_location'];


		if($employee != '' && $datetime != ''){

			$exp 			= explode(" ",$datetime);
			$date 			= $exp[0];
			$time 			= $exp[1];
			$timestamp_time = strtotime($time); 
			$year 			= date("Y", strtotime($date));
			$month 			= date("m", strtotime($date));
			$tgl 			= date("d", strtotime($date));
			$period 		= date("Y-m", strtotime($date));

			$cek_emp = $this->api->cek_employee($employee);	

			if($cek_emp['shift_type'] != '')
			{

				$emp_shift_type=1;
				if($cek_emp['shift_type'] == 'Reguler'){
					$dt = $this->db->query("select * from master_shift_time where shift_type = 'Reguler' ")->result(); 
					$datetime_out = $date.' '.$dt[0]->time_out;
				}else if($cek_emp['shift_type'] == 'Shift'){ 
					/*$dt = $this->db->query("select a.*, b.time_in, b.time_out, b.name from shift_schedule a
					left join master_shift_time b on b.id = a.master_shift_time_id
					where a.employee_id = '".$employee."' and a.year_periode = '".$year."' and a.month_periode = '".$month."' and date = '".$date."' ")->result(); */
					
					$dt = $this->db->query("select a.*, b.periode
							, b.`".$tgl."` as 'shift' 
							, c.time_in, c.time_out, c.name 
							from shift_schedule a
							left join group_shift_schedule b on b.shift_schedule_id = a.id 
							left join master_shift_time c on c.shift_id = b.`".$tgl."`
							where b.employee_id = '".$employee."' and a.period = '".$period."' ")->result(); 
					
					if($cek_emp[0]->attendance_type == 'Shift 2' || $cek_emp[0]->attendance_type == 'Shift 3'){
						$date_attendance = date("Y-m-d", strtotime($dt[0]->date . " +1 day"));
					}

					$datetime_out = $date_attendance.' '.$dt[0]->time_out;
				}else{ //tidak ada shift type
					$emp_shift_type=0;
				} 

				if($emp_shift_type == 1){
					/*$attendance_type 	= $dt[0]->name;
					$time_out 			= $dt[0]->time_out;
					$post_timeout 		= strtotime($time_out);*/

					$timestamp_datetime = strtotime($datetime);
					$post_datetimeout 	= strtotime($datetime_out);



					$is_leaving_office_early = '';
					if($timestamp_datetime < $post_datetimeout){
						$is_leaving_office_early = 'Y';
					}

					$cek_data = $this->db->query("select * from time_attendances where employee_id = '".$employee."' and date_attendance = '".$date."' ")->result();

					$err_checkout=0;
					if(empty($cek_data) && $cek_emp['shift_type'] == 'Reguler'){ 
						$err_checkout = 'Please CheckIn first';
					}else if($cek_emp['shift_type'] == 'Shift'){ 
						if(empty($cek_data)){ 
							$previousDay = date("Y-m-d", strtotime($date . " -1 day")); 
							
							/*$cek_data = $this->db->query("select * from time_attendances where employee_id = '".$employee."' and date_attendance = '".$previousDay."' and (date_attendance_out is null or date_attendance_out = '0000-00-00') ")->result();*/
							$cek_data = $this->db->query("select * from time_attendances where employee_id = '".$employee."' and date_attendance = '".$previousDay."' ")->result();
						}else{ 
							$cek_data = $this->db->query("select * from time_attendances where employee_id = '".$employee."' and date_attendance = '".$date."' ")->result();
							if(empty($cek_data)){
								$err_checkout='Checkout Date not valid';
							}
						}
					}


					if($err_checkout==0){  
						if($cek_data[0]->id != ''){ //update checkout
							
							$f_datetime_in 			= $cek_data[0]->date_attendance_in;
							$f_datetime_out 		= $datetime;
							$timestamp1 			= strtotime($f_datetime_in); 
							$timestamp2 			= strtotime($f_datetime_out);
							$num_of_working_hours 	= abs($timestamp2 - $timestamp1)/(60)/(60); //jam


							//upload 
							$dataU = array();
	        				$dataU['status'] = FALSE; 
							$fieldname='photo';
							if(isset($_FILES[$fieldname]) && !empty($_FILES[$fieldname]['name']))
				            { 
				               
				                
				            	$config['upload_path']   = "uploads/absensi/";
				                $config['allowed_types'] = "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
				                $config['max_size']      = "0"; 
				                
				                $this->load->library('upload', $config); 
				                
				                if(!$this->upload->do_upload($fieldname)){ 
				                    $err_msg = $this->upload->display_errors(); 
				                    $dataU['error_warning'] = strip_tags($err_msg);              
				                    $dataU['status'] = FALSE;
				                } else { 
				                    $fileData = $this->upload->data();
				                    $dataU['upload_file'] = $fileData['file_name'];
				                    $dataU['status'] = TRUE;
				                }
				            }
				            $document = '';
							if($dataU['status']){ 
								$document = $dataU['upload_file'];
							} else if(isset($dataU['error_warning'])){ 
								//echo $dataU['error_warning']; exit;

								$document = 'ERROR : '.$dataU['error_warning'];
							}
				            //end upload

				            $cektime = $this->db->query("select * from time_attendances where id = '".$cek_data[0]->id."'")->result();
				            if($notes == '' && $cektime[0]->notes != ''){
				            	$notes = $cektime[0]->notes;
				            }
				            if($document == '' && $cektime[0]->photo != ''){
				            	$document = $cektime[0]->photo;
				            }

				            if($cektime[0]->date_attendance_in < $datetime){
				            	$data = [
									'date_attendance_out' 		=> $datetime,
									'is_leaving_office_early'	=> $is_leaving_office_early,
									'num_of_working_hours'		=> $num_of_working_hours,
									'updated_at'				=> date("Y-m-d H:i:s"),
									'notes' 					=> $notes,
									'photo' 					=> $document,
									'lat_checkout' 				=> $latitude,
									'long_checkout' 			=> $longitude,
									'work_location' 			=> $work_location
								];
								$rs = $this->db->update("time_attendances", $data, "id='".$cek_data[0]->id."'");

								if($rs){
									$upd_emp = [
										'last_lat' 				=> $latitude,
										'last_long' 			=> $longitude
									];
									$this->db->update("employees", $upd_emp, "id='".$employee."'");


								
									$response = [
										'status' 	=> 200,
										'message' 	=> 'Success'
									];
								}else{
									$response = [
										'status' 	=> 401,
										'message' 	=> 'Failed',
										'error' 	=> 'Error update checkout'
									];
								}
				            }else{
				            	$response = [
									'status' 	=> 401,
									'message' 	=> 'Failed',
									'error' 	=> 'Checkout date is greater than checkin date'
								];
				            }
				           
						}else{
							$response = [
								'status' 	=> 400, // Bad Request
								'message' 	=>'Failed',
								'error' 	=> 'Require not satisfied'
							];
						}
					}else{ //insert
						$response = [
							'status' 	=> 401,
							'message' 	=> 'Failed',
							'error' 	=> $err_checkout
						];
					}
				}else{ //tidak ada shift type
					$response = [
						'status' 	=> 401,
						'message' 	=> 'Failed',
						'error' 	=> 'Data Shift not found'
					];
				}

			} else {
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Employee not found'
				];
			}
			
		} else {
			$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
		}
		
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
    }


    public function ijin()
    {
    	$this->verify_token();


		/*$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$employee	= $_REQUEST['employee'];
    	$leave_type = $_REQUEST['leave_type'];
    	$date_start	= $_REQUEST['date_start'];
    	$date_end 	= $_REQUEST['date_end'];
    	$reason		= $_REQUEST['reason'];
    	$method_type	= $_REQUEST['method_type']; //insert or update
    	$id 		= $_REQUEST['id'];*/


    	$employee	= $_POST['employee'];
    	$leave_type = $_POST['leave_type'];
    	$date_start	= $_POST['date_start'];
    	$date_end 	= $_POST['date_end'];
    	$reason		= $_POST['reason'];
    	$method_type	= $_POST['method_type']; //insert or update
    	$id 		= $_POST['id'];
    	/*$photo 		= $_FILES['photo'];*/
    	$photo 		= isset($_FILES['photo']) ? $_FILES['photo'] : null;

    	//$method_type = 'update';

		if($employee != '' && $leave_type != '' && $date_start != '' && $date_end != '' ){
			
			$cek_emp = $this->api->cek_employee($employee);	

			if($cek_emp['id'] != '')
			{
				if($method_type == 'insert'){
					$rs = $this->insert_ijin($employee, $leave_type, $date_start, $date_end, $reason, $photo);
				}else if($method_type == 'update'){
					$rs = $this->update_ijin($employee, $leave_type, $date_start, $date_end, $reason, $id);
				}
				

				if($rs == 1){
					$response = [
						'status' 	=> 200,
						'message' 	=> 'Success'
					];
				}
				else if($rs == 'sisa_cuti_tidak_cukup'){
					$response = [
						'status' 	=> 401,
						'message' 	=> 'Failed',
						'error' 	=> 'Sisa Cuti Tidak Cukup'
					];
				}
				else if($rs == 'cannot_edit_approved_leave'){
					$response = [
						'status' 	=> 401,
						'message' 	=> 'Failed',
						'error' 	=> 'Cannot Edit Approved Leave'
					];
				}
				else if($rs == 'lampirkan_file'){
					$response = [
						'status' 	=> 401,
						'message' 	=> 'Failed',
						'error' 	=> 'Please attach file'
					];
				}
				else if($rs == 'sisa_dayoff_tidak_cukup'){
					$response = [
						'status' 	=> 401,
						'message' 	=> 'Failed',
						'error' 	=> 'Sisa Dayoff tidak cukup'
					];
				}
				else if($rs == 'work_location_not_found'){
					$response = [
						'status' 	=> 401,
						'message' 	=> 'Failed',
						'error' 	=> 'Work Location not found'
					];
				}
				else{
					$response = [
						'status' 	=> 401,
						'message' 	=> 'Failed',
						'error' 	=> 'Error submit leave'
					];
				}
	
			} else {
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Employee not found'
				];
			}
			
		} else {
			$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
		}
		
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
    }


    public function cek_ttl_dayoff($employee_id){
		$overtimes = $this->db->query("select 
								    employee_id,
								    SUM(count_day - COALESCE(ttl_dayoff_used, 0)) AS total_sisa_dayoff
								FROM overtimes
								WHERE type = 2 
								  AND status_id = 2
								  AND employee_id = '".$employee_id."'
								GROUP BY employee_id;
								")->result(); 

		if(!empty($overtimes)){
			return $overtimes[0]->total_sisa_dayoff;
		}else return 0;
		
	}


	public function update_table_overtimes($employee, $diff_day){

		$tmp_ttl_pengajuan = $diff_day;
		$dataOvertimes = $this->db->query("select * from overtimes where type = 2 and employee_id = '".$employee."' and status_id = 2 and status_dayoff_available = 1")->result(); 

		if($tmp_ttl_pengajuan != 0){
			foreach($dataOvertimes as $rowOvertimes){
				$count_day 	= $rowOvertimes->count_day;
				$kuota 		= $rowOvertimes->count_day-$rowOvertimes->ttl_dayoff_used;
				
				if($tmp_ttl_pengajuan > $kuota){ 
					$ttl_dayoff_used = $kuota;
				}else{ 
					$ttl_dayoff_used = $tmp_ttl_pengajuan;
				}
				$sumdayoff = $rowOvertimes->ttl_dayoff_used+$ttl_dayoff_used;

				$status_dayoff_available='';
				if($count_day == $sumdayoff){
					$status_dayoff_available=0;
				}
				
				if($status_dayoff_available==0){
					$dataUpd = [
						'ttl_dayoff_used' 			=> $sumdayoff,
						'status_dayoff_available' 	=> $status_dayoff_available
					];
					$this->db->update('overtimes', $dataUpd, "id = '".$rowOvertimes->id."'");
				}else{
					$dataUpd = [
						'ttl_dayoff_used' => $sumdayoff
					];
					$this->db->update('overtimes', $dataUpd, "id = '".$rowOvertimes->id."'");
				}
				
				$tmp_ttl_pengajuan = $tmp_ttl_pengajuan-$ttl_dayoff_used;
				

			}
		}
		
	}


	public function pengembalian_jatah_dayoff($employee, $diff_day){

		$ttl_pengembalian = $diff_day;
		$dataOvertimes = $this->db->query("select * from overtimes where type = 2 and employee_id = '".$employee."' and status_id = 2 order by id desc")->result(); 
		foreach($dataOvertimes as $rowOvertimes){
			$kuota = $rowOvertimes->count_day;
			$curr_ttl_dayoff_used = $rowOvertimes->ttl_dayoff_used;
			
			if($ttl_pengembalian > $kuota){
				$yg_sudah_dikembalikan = $ttl_pengembalian-$kuota;
			}else{
				$yg_sudah_dikembalikan = $ttl_pengembalian;
			}
			$sisa_pengembalian = $ttl_pengembalian-$yg_sudah_dikembalikan;
			$ttl_dayoff_used = $curr_ttl_dayoff_used-$yg_sudah_dikembalikan;
			
			$dataUpd = [
				'ttl_dayoff_used' => $ttl_dayoff_used,
				'status_dayoff_available' => 1
			];
			$this->db->update('overtimes', $dataUpd, "id = '".$rowOvertimes->id."'");
			

			$ttl_pengembalian = $sisa_pengembalian;

		}
	}


	public function getApprovalMatrix($work_location_id, $approval_type_id, $leave_type_id='', $diff_day='', $trx_id){

		if($work_location_id != '' && $approval_type_id != ''){
			if($approval_type_id == 1){ ///Absence
				if($leave_type_id != ''){ 
					if($diff_day == ''){
						$diff_day=0;
					}
					
					$getmatrix = $this->db->query("select * from approval_matrix where approval_type_id = '".$approval_type_id."' and work_location_id = '".$work_location_id."' and leave_type_id = '".$leave_type_id."' and (
							(".$diff_day." >= min and ".$diff_day." <= max and min != '' and max != '') or
							(".$diff_day." >= min and min != '' and max = '') or
							(".$diff_day." <= max and max != '' and min = '')
						)  ")->result(); 

					
					if(!empty($getmatrix)){
						$approvalMatrixId = $getmatrix[0]->id;
						if($approvalMatrixId != ''){
							$dataApproval = [
								'approval_matrix_type_id' 	=> $approval_type_id, //Absence
								'trx_id' 					=> $trx_id,
								'approval_matrix_id' 		=> $approvalMatrixId,
								'current_approval_level' 	=> 1
							];
							$rs = $this->db->insert("approval_path", $dataApproval);
							$approval_path_id = $this->db->insert_id();
							if($rs){
								$dataApprovalDetail = [
									'approval_path_id' 	=> $approval_path_id, 
									'approval_level' 	=> 1
								];
								$this->db->insert("approval_path_detail", $dataApprovalDetail);
							}
						}
					}

				}
			}

		}

	}


    public function insert_ijin($employee, $leave_type, $date_start, $date_end, $reason, $photo){

    	if($employee != '' && $date_start != '' && $date_end != '' && $leave_type != ''){ 
    		$dataEmp = $this->db->query("select * from employees where id = '".$employee."'")->result(); 

    		if(!empty($dataEmp)){
    			if(!empty($dataEmp[0]->work_location)){

    				$cek_sisa_cuti 	= $this->api->get_data_sisa_cuti($employee, $date_start, $date_end);
					$sisa_cuti 		= $cek_sisa_cuti[0]->ttl_sisa_cuti;

					$diff_day		= $this->api->dayCount($date_start, $date_end);
					$diff_day 		= number_format($diff_day);

					//upload 
					$dataU = array();
					$dataU['status'] = FALSE; 
					$fieldname='photo';
					if(isset($_FILES[$fieldname]) && !empty($_FILES[$fieldname]['name']))
		            { 
		               
		                $config['upload_path']   = "uploads/ijin/";
		                $config['allowed_types'] = "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
		                $config['max_size']      = "0"; 
		                
		                $this->load->library('upload', $config); 
		                
		                if(!$this->upload->do_upload($fieldname)){ 
		                    $err_msg = $this->upload->display_errors(); 
		                    $dataU['error_warning'] = strip_tags($err_msg);              
		                    $dataU['status'] = FALSE;
		                } else { 
		                    $fileData = $this->upload->data();
		                    $dataU['upload_file'] = $fileData['file_name'];
		                    $dataU['status'] = TRUE;
		                }
		            }
		            $document = '';
					if($dataU['status']){ 
						$document = $dataU['upload_file'];
					} else if(isset($dataU['error_warning'])){ 
						//echo $dataU['error_warning']; exit;
						$document = 'ERROR : '.$dataU['error_warning'];
					}
		            //end upload


					if($leave_type == '24'){ //DAY OFF
						$ttl_dayoff = $this->cek_ttl_dayoff($employee);

						if($diff_day <= $ttl_dayoff){

							//insert table leave
							$data = [
								'employee_id' 				=> $employee,
								'date_leave_start' 			=> $date_start,
								'date_leave_end' 			=> $date_end,
								'masterleave_id' 			=> $leave_type,
								'reason' 					=> $reason,
								'total_leave' 				=> $diff_day,
								'status_approval' 			=> 1, //waiting approval
								'created_at'				=> date("Y-m-d H:i:s"),
								'photo' => $document
							];
							$rs = $this->db->insert("leave_absences", $data);
							$lastId = $this->db->insert_id();

							if($rs){
								///insert approval path
								$approval_type_id = 1; //Absence
								$this->getApprovalMatrix($dataEmp[0]->work_location, $approval_type_id, $leave_type, $diff_day, $lastId);

								//update table overtimes
								$this->update_table_overtimes($employee,$diff_day);

								return $rs;
							}else return null;

						}else{
							return 'sisa_dayoff_tidak_cukup';
						}

					}else{

						if($leave_type == '6'){ //Half day leave
							$diff_day = $diff_day*0.5;
						}
						if($leave_type == '5'){ //Sick Leave
							$diff_day = 0 ;
						}
						

						if($diff_day <= $sisa_cuti || $leave_type == '2'){ //unpaid leave gak ngecek sisa cuti

				            if($leave_type == 5 && ($document == '' || $document == null)){
				            	return 'lampirkan_file';
				            }else{
				            	$data = [
									'employee_id' 				=> $employee,
									'date_leave_start' 			=> $date_start,
									'date_leave_end' 			=> $date_end,
									'masterleave_id' 			=> $leave_type,
									'reason' 					=> $reason,
									'total_leave' 				=> $diff_day,
									'status_approval' 			=> 1, //waiting approval
									'created_at'				=> date("Y-m-d H:i:s"),
									'photo' => $document
								];
								$rs = $this->db->insert("leave_absences", $data);
								$lastId = $this->db->insert_id();

								if($rs){
									//update sisa jatah cuti
									/*if($leave_type != '2'){ //unpaid leave gak update sisa cuti
										$jatahcuti = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$employee."' and status = 1 order by period_start asc")->result(); 

										$is_update_jatah_selanjutnya=0;
										$sisa_cuti = $jatahcuti[0]->sisa_cuti-$diff_day;

										if($diff_day > $jatahcuti[0]->sisa_cuti){ 
											$is_update_jatah_selanjutnya=1;
											$sisa_cuti = 0;
											$diff_day2 = $diff_day-$jatahcuti[0]->sisa_cuti;
											$sisa_cuti2 = $jatahcuti[1]->sisa_cuti-$diff_day2;	
										}
										
										$data2 = [
											'sisa_cuti' 	=> $sisa_cuti,
											'updated_date'	=> date("Y-m-d H:i:s")
										];
										$this->db->update('total_cuti_karyawan', $data2, "id = '".$jatahcuti[0]->id."'");


										if($is_update_jatah_selanjutnya == 1){ 
											$data3 = [
												'sisa_cuti' 	=> $sisa_cuti2,
												'updated_date'	=> date("Y-m-d H:i:s")
											];
											$this->db->update('total_cuti_karyawan', $data3, "id = '".$jatahcuti[1]->id."'");
										}

									}*/

									///insert approval path
									$approval_type_id = 1; //Absence
									$this->getApprovalMatrix($dataEmp[0]->work_location, $approval_type_id, $leave_type, $diff_day, $lastId);



									return $rs;
								}else return null;
				            }

						}
						else return 'sisa_cuti_tidak_cukup';

					}
    			}else{
    				return 'work_location_not_found';
    			}
    		}

		}else return null;


    }


    public function update_ijin($employee, $leave_type, $date_start, $date_end, $reason, $id){

    	if(!empty($id)){ 

			if($date_start != '' && $date_end != '' && $leave_type != ''){ 
				$diff_day		= $this->api->dayCount($date_start, $date_end);
				$diff_day 		= number_format($diff_day);
				$getcurrLeave 	= $this->db->query("select * from leave_absences where id = '".$id."' ")->result(); 

				if($getcurrLeave[0]->status_approval == 1){ //waiting approval

					//upload 
					$dataU = array();
					$dataU['status'] = FALSE; 
					$fieldname='photo';
					if(isset($_FILES[$fieldname]) && !empty($_FILES[$fieldname]['name']))
		            { 
		               
		                $config['upload_path']   = "uploads/ijin/";
		                $config['allowed_types'] = "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
		                $config['max_size']      = "0"; 
		                
		                $this->load->library('upload', $config); 
		                
		                if(!$this->upload->do_upload($fieldname)){ 
		                    $err_msg = $this->upload->display_errors(); 
		                    $dataU['error_warning'] = strip_tags($err_msg);              
		                    $dataU['status'] = FALSE;
		                } else { 
		                    $fileData = $this->upload->data();
		                    $dataU['upload_file'] = $fileData['file_name'];
		                    $dataU['status'] = TRUE;
		                }
		            }
		            $document = '';
					if($dataU['status']){ 
						$document = $dataU['upload_file'];
					} else if(isset($dataU['error_warning'])){ 
						//echo $dataU['error_warning']; exit;
						$document = 'ERROR : '.$dataU['error_warning'];
					}
		            //end upload
		            if($document == '' && $getcurrLeave[0]->photo != ''){
		            	$document = $getcurrLeave[0]->photo;
		            }


		            if($leave_type == '24'){ //day off
		            	if($getcurrLeave[0]->masterleave_id != '24'){ //awalnya bukan day off
							$ttl_dayoff = $this->cek_ttl_dayoff($employee);

							if($diff_day <= $ttl_dayoff){
								$data = [
									'date_leave_start' 			=> $date_start,
									'date_leave_end' 			=> $date_end,
									'masterleave_id' 			=> $leave_type,
									'reason' 					=> $reason,
									'total_leave' 				=> $diff_day,
									'photo' 					=> $document,
									'updated_at'				=> date("Y-m-d H:i:s")
								];
								$rs = $this->db->update("leave_absences", $data, "id = '".	$id."'");
								if($rs){
									//update table overtimes
									$this->update_table_overtimes($employee,$diff_day);

									return $rs;
								}else return null;
							}else{
								return 'sisa_dayoff_tidak_cukup';
							}
							
						}else{ 
							$curr_diff_day = $getcurrLeave[0]->total_leave;
							if($curr_diff_day == $diff_day){ //kalo sama brarti gausah update data overtime, update data biasa aja yg leave_absences
								$data = [
									'date_leave_start' 			=> $date_start,
									'date_leave_end' 			=> $date_end,
									'masterleave_id' 			=> $leave_type,
									'reason' 					=> $reason,
									'total_leave' 				=> $diff_day,
									'photo' 					=> $document,
									'updated_at'				=> date("Y-m-d H:i:s")
								];
								$rs = $this->db->update("leave_absences", $data, "id = '".	$id."'");
								
								return $rs;
							}else{  //total lama tidak sama dengan total baru
								if($curr_diff_day < $diff_day){ //nambah dayoff

									$ttl_dayoff = $this->cek_ttl_dayoff($employee); 
									$selisih_diff_day = $diff_day-$curr_diff_day;
									if($selisih_diff_day <= $ttl_dayoff){
										$data = [
											'date_leave_start' 			=> $date_start,
											'date_leave_end' 			=> $date_end,
											'masterleave_id' 			=> $leave_type,
											'reason' 					=> $reason,
											'total_leave' 				=> $diff_day,
											'photo' 					=> $document,
											'updated_at'				=> date("Y-m-d H:i:s")
										]; 
										$rs = $this->db->update("leave_absences", $data, "id = '".	$id."'");
										if($rs){ 
											$this->update_table_overtimes($employee,$selisih_diff_day);
											return $rs;
										}else return null;
									}else{ 
										return 'sisa_dayoff_tidak_cukup';
									}
								}else{ //mengembalikan jatah dayoff 
									$data = [
										'date_leave_start' 			=> $date_start,
										'date_leave_end' 			=> $date_end,
										'masterleave_id' 			=> $leave_type,
										'reason' 					=> $reason,
										'total_leave' 				=> $diff_day,
										'photo' 					=> $document,
										'updated_at'				=> date("Y-m-d H:i:s")
									];
									$rs = $this->db->update("leave_absences", $data, "id = '".	$id."'");
									if($rs){
										$selisih_diff_day = $curr_diff_day-$diff_day;
										$this->pengembalian_jatah_dayoff($employee,$selisih_diff_day);

										return $rs;
									}else return null;
								}
							}
						}

		            }else{ 

		            	/*$getcurrTotalCuti =0;
						if($getcurrLeave[0]->masterleave_id != 2){ //data sebelumnya bukan unpaid leave, maka sisa cuti dibalikin
							$getcurrTotalCuti = $getcurrLeave[0]->total_leave;
						}
						$cek_sisa_cuti 	= $this->api->get_data_sisa_cuti($employee, $date_start, $date_end); 
						$sisa_cuti 		= $cek_sisa_cuti[0]->ttl_sisa_cuti+$getcurrTotalCuti;*/

						$cek_sisa_cuti 	= $this->api->get_data_sisa_cuti($employee, $date_start, $date_end);
						$sisa_cuti 		= $cek_sisa_cuti[0]->ttl_sisa_cuti;


						if($leave_type == '6'){ //Half day leave
							$diff_day = $diff_day*0.5;
						}
						if($leave_type == '5'){ //Sick Leave
							$diff_day = 0 ;
						}

						if($diff_day <= $sisa_cuti || $leave_type == '2'){ //unpaid leave gak ngecek sisa cuti

				            if($leave_type == 5 && ($document == '' || $document == null)){
				            	return 'lampirkan_file';
				            }else{
				            	$data = [
									'date_leave_start' 			=> $date_start,
									'date_leave_end' 			=> $date_end,
									'masterleave_id' 			=> $leave_type,
									'reason' 					=> $reason,
									'total_leave' 				=> $diff_day,
									'photo' 					=> $document,
									'updated_at'				=> date("Y-m-d H:i:s")
								];

								$rs = $this->db->update("leave_absences", $data, "id = '".	$id."'");

								//update sisa jatah cuti
								if($rs){

									if($getcurrLeave[0]->masterleave_id != '24'){ //dr tipe day off
										$this->pengembalian_jatah_dayoff($employee,$getcurrLeave[0]->total_leave);
									}

									/*$update_jatah_cuti=1;
									if($getcurrLeave[0]->masterleave_id == 2 && $leave_type == 2){ //tidak ada perubahan jika data sebelumnya dan data skrg sama2 unpaid leave
										$update_jatah_cuti=0;
										return $rs; 
									}

									if($update_jatah_cuti == 1){

										if($leave_type == 2){
											$diff_day=0;
										}

										$jml_tambahan_cuti =  $getcurrTotalCuti-$diff_day;

										if($jml_tambahan_cuti != 0){
											$jatahcuti = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$employee."' and status = 1 order by period_start asc")->result(); 

											if($jml_tambahan_cuti > 0){ // metode tambahin cuti
											
												$sisa_cuti_1 = $jatahcuti[0]->sisa_cuti+$jml_tambahan_cuti;

												$tambah_selanjutnya=0;
												if($sisa_cuti_1 > 12){
													$tambah_selanjutnya =1;
													$slot_tambah = 12- $jatahcuti[0]->sisa_cuti;
													$sisa_slot_tambah = $jml_tambahan_cuti-$slot_tambah;
													$sisa_cuti_1 =12;
												}
												$data2 = [
													'sisa_cuti' 	=> $sisa_cuti_1,
													'updated_date'	=> date("Y-m-d H:i:s")
												];
												$this->db->update('total_cuti_karyawan', $data2, "id = '".$jatahcuti[0]->id."'");

												if($tambah_selanjutnya == 1){
													$sisa_cuti_2 = $jatahcuti[1]->sisa_cuti+$sisa_slot_tambah;
													if($sisa_cuti_2 > 12){
														$sisa_cuti_2 = 12;
													}

													$data3 = [
														'sisa_cuti' 	=> $sisa_cuti_2,
														'updated_date'	=> date("Y-m-d H:i:s")
													];
													$this->db->update('total_cuti_karyawan', $data3, "id = '".$jatahcuti[1]->id."'");
												}

											}else{ //metode kurangi cuti

												$jml_kurang_cuti = $diff_day-$getcurrTotalCuti;
												$sisa_cuti_1 = $jatahcuti[0]->sisa_cuti-$jml_kurang_cuti;

												$kurang_selanjutnya=0;
												if($sisa_cuti_1 < 0){
													$kurang_selanjutnya = 1;

													if($jatahcuti[0]->sisa_cuti == 0){
														$slot_kurang =0;
													}else{
														$slot_kurang = $jml_kurang_cuti-$jatahcuti[0]->sisa_cuti;
													}
													
													$sisa_slot_kurang = $jml_kurang_cuti-$slot_kurang;
													$sisa_cuti_1 = 0;
												}
												$data2 = [
													'sisa_cuti' 	=> $sisa_cuti_1,
													'updated_date'	=> date("Y-m-d H:i:s")
												];
												$this->db->update('total_cuti_karyawan', $data2, "id = '".$jatahcuti[0]->id."'");

												if($kurang_selanjutnya == 1){
													$sisa_cuti_2 = $jatahcuti[1]->sisa_cuti-$sisa_slot_kurang;
													if($sisa_cuti_2 < 0){
														$sisa_cuti_2 = 0;
													}
													$data3 = [
														'sisa_cuti' 	=> $sisa_cuti_2,
														'updated_date'	=> date("Y-m-d H:i:s")
													];
													$this->db->update('total_cuti_karyawan', $data3, "id = '".$jatahcuti[1]->id."'");
												}

											}
										}
										
									}*/
									
									return  $rs;
								}else return null;
				            }

						}else return 'sisa_cuti_tidak_cukup'; // cuti gak cukup

		            }

				}else{
					return 'cannot_edit_approved_leave';
				}

			}
			else return null;

		} else return null;

    }


    public function get_data_ijin()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$islogin_employee	= $_REQUEST['islogin_employee'];
    	$employee			= $_REQUEST['employee']; //filter employee


    	if($islogin_employee != ''){

    		$where=""; 
	    	if($employee != ''){
	    		/*$where = " and a.employee_id = '".$employee."' ";*/
	    		$where = " and ao.employee_id = '".$employee."' ";
	    	}

	    	/*$dataijin = $this->db->query("select a.id, b.full_name, a.date_leave_start, a.date_leave_end, c.name as 			leave_name, a.reason, a.total_leave, 
							(case
							when a.status_approval = 1 then 'Waiting Approval'
							when a.status_approval = 2 then 'Approved'
							when a.status_approval = 3 then 'Rejected'
							 end) as status, b.direct_id
						from leave_absences a left join employees b on b.id = a.employee_id
						left join master_leaves c on c.id = a.masterleave_id
						where (a.employee_id = '".$islogin_employee."' or b.direct_id = '".$islogin_employee."') 
	                    ".$where." ")->result();  */


	        /*$dataijin = $this->db->query('select ao.* from (SELECT 
							a.*, 
							b.full_name, 
							c.name AS leave_name,
							CASE
								WHEN a.status_approval = 1 THEN "Waiting Approval"
								WHEN a.status_approval = 2 THEN "Approved"
								WHEN a.status_approval = 3 THEN "Rejected"
							END AS status,
							b.direct_id,
							d.current_approval_level,
							h.role_id as current_role_id,
							i.role_name as current_role_name,
							GROUP_CONCAT(g.employee_id) AS all_employeeid_approver,
							if(i.role_name = "Direct",b.direct_id,(select GROUP_CONCAT(employee_id) from approval_matrix_role_pic where approval_matrix_role_id = h.role_id)) as current_employeeid_approver,
							CASE 
								WHEN FIND_IN_SET('.$islogin_employee.', GROUP_CONCAT(g.employee_id)) > 0 THEN 1 
								ELSE 0 
							END AS is_approver_view,
					        CASE 
								WHEN FIND_IN_SET('.$islogin_employee.', (select GROUP_CONCAT(employee_id) from approval_matrix_role_pic where approval_matrix_role_id = h.role_id)) > 0 THEN 1
								when i.role_name = "Direct" and b.direct_id = '.$islogin_employee.' THEN 1  
								ELSE 0 
							END AS is_approver
						FROM leave_absences a
						LEFT JOIN employees b ON b.id = a.employee_id
						LEFT JOIN master_leaves c ON c.id = a.masterleave_id
						LEFT JOIN approval_path d ON d.trx_id = a.id AND d.approval_matrix_type_id = 1
						left join approval_matrix bb on bb.id = d.approval_matrix_id
						left join approval_matrix_detail cc on cc.approval_matrix_id = bb.id
						left join approval_matrix_role dd on dd.id = cc.role_id
						left join approval_path_detail ee on ee.approval_path_id = d.id and ee.approval_level = cc.approval_level
						LEFT JOIN approval_matrix_role_pic g ON g.approval_matrix_role_id = cc.role_id
						left join approval_matrix_detail h on h.approval_matrix_id = d.approval_matrix_id and h.approval_level = d.current_approval_level
						left join approval_matrix_role i on i.id = h.role_id
						GROUP BY a.id) ao
						where (ao.employee_id = "'.$islogin_employee.'" or ao.direct_id = "'.$islogin_employee.'" or ao.is_approver_view = 1)
	                    '.$where.' ')->result(); */


	        $dataijin = $this->db->query('select ao.* 
						FROM (
						    SELECT 
						        a.id,
						        a.employee_id,
						        a.masterleave_id,
						        a.date_leave_start,
						        a.date_leave_end,
						        a.reason,
						        a.total_leave,
						        a.status_approval,
						        a.date_approval,
						        a.photo,
						        b.full_name, 
						        c.name AS leave_name,
						        CASE
						            WHEN a.status_approval = 1 THEN "Waiting Approval"
						            WHEN a.status_approval = 2 THEN "Approved"
						            WHEN a.status_approval = 3 THEN "Rejected"
						        END AS status,
						        ANY_VALUE(b.direct_id) AS direct_id,
						        ANY_VALUE(d.current_approval_level) AS current_approval_level,
						        ANY_VALUE(h.role_id) AS current_role_id,
						        ANY_VALUE(i.role_name) AS current_role_name,
						        GROUP_CONCAT(g.employee_id) AS all_employeeid_approver,
						        ANY_VALUE(
						            IF(
						                i.role_name = "Direct",
						                b.direct_id,
						                (
						                    SELECT GROUP_CONCAT(employee_id) 
						                    FROM approval_matrix_role_pic 
						                    WHERE approval_matrix_role_id = h.role_id
						                )
						            )
						        ) AS current_employeeid_approver,
						        CASE 
						            WHEN FIND_IN_SET('.$islogin_employee.', GROUP_CONCAT(g.employee_id)) > 0 THEN 1 
						            ELSE 0 
						        END AS is_approver_view,
						        CASE 
						            WHEN FIND_IN_SET(
						                '.$islogin_employee.', 
						                (
						                    SELECT GROUP_CONCAT(employee_id) 
						                    FROM approval_matrix_role_pic 
						                    WHERE approval_matrix_role_id = ANY_VALUE(h.role_id)
						                )
						            ) > 0 THEN 1
						            WHEN ANY_VALUE(i.role_name) = "Direct" AND ANY_VALUE(b.direct_id) = '.$islogin_employee.' THEN 1  
						            ELSE 0 
						        END AS is_approver
						    FROM leave_absences a
						    LEFT JOIN employees b ON b.id = a.employee_id
						    LEFT JOIN master_leaves c ON c.id = a.masterleave_id
						    LEFT JOIN approval_path d ON d.trx_id = a.id AND d.approval_matrix_type_id = 1
						    LEFT JOIN approval_matrix bb ON bb.id = d.approval_matrix_id
						    LEFT JOIN approval_matrix_detail cc ON cc.approval_matrix_id = bb.id
						    LEFT JOIN approval_matrix_role dd ON dd.id = cc.role_id
						    LEFT JOIN approval_path_detail ee ON ee.approval_path_id = d.id AND ee.approval_level = cc.approval_level
						    LEFT JOIN approval_matrix_role_pic g ON g.approval_matrix_role_id = cc.role_id
						    LEFT JOIN approval_matrix_detail h ON h.approval_matrix_id = d.approval_matrix_id AND h.approval_level = d.current_approval_level
						    LEFT JOIN approval_matrix_role i ON i.id = h.role_id
						    GROUP BY a.id
						) ao
						where (ao.employee_id = "'.$islogin_employee.'" or ao.direct_id = "'.$islogin_employee.'" or ao.is_approver_view = 1)
	                    '.$where.' ')->result();  


	    	$response = [
	    		'status' 	=> 200,
				'message' 	=> 'Success',
				'data' 		=> $dataijin
			];

    	}else{
    		$response = [
				'status' 	=> 401,
				'message' 	=> 'Failed',
				'error' 	=> 'Employee ID Login not found'
			];
    	}


    	/*$employee			= $_REQUEST['employee']; //filter employee

		$where=""; 
    	if($employee != ''){
    		$where = " where a.employee_id = '".$employee."' ";
    	}

    	$dataijin = $this->db->query("select a.id, b.full_name, a.date_leave_start, a.date_leave_end, c.name as 			leave_name, a.reason, a.total_leave, 
						(case
						when a.status_approval = 1 then 'Waiting Approval'
						when a.status_approval = 2 then 'Approved'
						when a.status_approval = 3 then 'Rejected'
						 end) as status, b.direct_id
					from leave_absences a left join employees b on b.id = a.employee_id
					left join master_leaves c on c.id = a.masterleave_id
					
                    ".$where." ")->result();  

    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $dataijin
		];*/

    	


    	

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }

    public function get_data_absen()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;
    	$employee = $_REQUEST['employee']; //filter employee

		$where=''; 
    	if($employee != ''){
    		$where = " where a.employee_id = '".$employee."' ";
    	}

    	
		/*$dataabsen = $this->db->query("select a.id, a.date_attendance, b.full_name, a.date_attendance_in, a.date_attendance_out, a.num_of_working_hours, if(a.is_late = 'Y','Late', '') as 'is_late_desc', 
			if(a.is_leaving_office_early = 'Y','Leaving Office Early','') as 'is_leaving_office_early_desc', b.direct_id from time_attendances a left join employees b on b.id = a.employee_id ".$where." ")->result();  */

		$dataabsen = $this->db->query("select 
					    a.id,
					    a.date_attendance,
					    b.full_name,
					    a.date_attendance_in,
					    a.date_attendance_out,
					    a.num_of_working_hours,
					    IF(a.is_late = 'Y', 'Late', '') AS is_late_desc,
					    IF(a.is_leaving_office_early = 'Y','Leaving Office Early','') AS is_leaving_office_early_desc,
					    b.direct_id,
					    b.shift_type,
					    CASE 
					     	WHEN a.date_attendance_in IS NOT NULL THEN ''
					        WHEN o.id IS NOT NULL THEN '' 
					        WHEN b.shift_type = 'Reguler' AND DAYOFWEEK(a.date_attendance) IN (1,7) THEN 'Holiday'
					        WHEN h.date IS NOT NULL THEN 'Holiday'
					        WHEN a.leave_absences_id IS NOT NULL THEN 'Holiday'
					        WHEN b.shift_type = 'Shift' AND (
					            CASE DAY(a.date_attendance)
					                WHEN 1  THEN gss.`01` WHEN 2  THEN gss.`02` WHEN 3  THEN gss.`03`
					                WHEN 4  THEN gss.`04` WHEN 5  THEN gss.`05` WHEN 6  THEN gss.`06`
					                WHEN 7  THEN gss.`07` WHEN 8  THEN gss.`08` WHEN 9  THEN gss.`09`
					                WHEN 10 THEN gss.`10` WHEN 11 THEN gss.`11` WHEN 12 THEN gss.`12`
					                WHEN 13 THEN gss.`13` WHEN 14 THEN gss.`14` WHEN 15 THEN gss.`15`
					                WHEN 16 THEN gss.`16` WHEN 17 THEN gss.`17` WHEN 18 THEN gss.`18`
					                WHEN 19 THEN gss.`19` WHEN 20 THEN gss.`20` WHEN 21 THEN gss.`21`
					                WHEN 22 THEN gss.`22` WHEN 23 THEN gss.`23` WHEN 24 THEN gss.`24`
					                WHEN 25 THEN gss.`25` WHEN 26 THEN gss.`26` WHEN 27 THEN gss.`27`
					                WHEN 28 THEN gss.`28` WHEN 29 THEN gss.`29` WHEN 30 THEN gss.`30`
					                WHEN 31 THEN gss.`31`
					            END
					        ) IS NULL THEN 'Holiday'
					        ELSE ''
					    END AS holiday_flag,
					    CASE 
					    	WHEN a.date_attendance_in IS NOT NULL THEN ''  
					        WHEN o.id IS NOT NULL THEN ''  
					        WHEN b.shift_type = 'Reguler' AND DAYOFWEEK(a.date_attendance) IN (1,7) THEN 'Weekend'
					        WHEN h.date IS NOT NULL THEN h.description
					        WHEN a.leave_absences_id IS NOT NULL THEN 'Leave'
					        WHEN b.shift_type = 'Shift' AND (
					            CASE DAY(a.date_attendance)
					                WHEN 1  THEN gss.`01` WHEN 2  THEN gss.`02` WHEN 3  THEN gss.`03`
					                WHEN 4  THEN gss.`04` WHEN 5  THEN gss.`05` WHEN 6  THEN gss.`06`
					                WHEN 7  THEN gss.`07` WHEN 8  THEN gss.`08` WHEN 9  THEN gss.`09`
					                WHEN 10 THEN gss.`10` WHEN 11 THEN gss.`11` WHEN 12 THEN gss.`12`
					                WHEN 13 THEN gss.`13` WHEN 14 THEN gss.`14` WHEN 15 THEN gss.`15`
					                WHEN 16 THEN gss.`16` WHEN 17 THEN gss.`17` WHEN 18 THEN gss.`18`
					                WHEN 19 THEN gss.`19` WHEN 20 THEN gss.`20` WHEN 21 THEN gss.`21`
					                WHEN 22 THEN gss.`22` WHEN 23 THEN gss.`23` WHEN 24 THEN gss.`24`
					                WHEN 25 THEN gss.`25` WHEN 26 THEN gss.`26` WHEN 27 THEN gss.`27`
					                WHEN 28 THEN gss.`28` WHEN 29 THEN gss.`29` WHEN 30 THEN gss.`30`
					                WHEN 31 THEN gss.`31`
					            END
					        ) IS NULL THEN 'No Shift'
					        ELSE ''
					    END AS holiday_type,
					    CASE 
					        WHEN o.id IS NOT NULL THEN 'Y'
					        ELSE ''
					    END AS overtime_flag
					FROM time_attendances a
					LEFT JOIN employees b ON b.id = a.employee_id
					LEFT JOIN master_holidays h ON h.date = a.date_attendance
					LEFT JOIN overtimes o 
					       ON o.employee_id = a.employee_id
					      AND a.date_attendance BETWEEN DATE(o.datetime_start) AND DATE(o.datetime_end)
					      AND o.status_id = 2 
					      AND o.type = 2
					LEFT JOIN group_shift_schedule gss 
					       ON gss.employee_id = a.employee_id
					      AND gss.periode = DATE_FORMAT(a.date_attendance, '%Y-%m')
					     	".$where."
					 ")->result();
				
			

    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $dataabsen
		];

    	

    

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function get_data_tasklist()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	/*$islogin_employee	= $_REQUEST['islogin_employee'];
    	$employee			= $_REQUEST['employee'];


    	if($islogin_employee != ''){

    		$where=''; 
	    	if($employee != ''){
	    		$where = " and a.employee_id = '".$employee."' ";
	    	}

	    	$datatasklist = $this->db->query("select a.id, a.employee_id, b.full_name as employee_name, a.task, c.task as parent_name, d.name as status_name, a.progress_percentage, a.due_date, a.status_id, a.parent_id, b.direct_id
						from tasklist a left join employees b on b.id = a.employee_id
						left join tasklist c on c.id = a.parent_id
						left join master_tasklist_status d on d.id = a.status_id
						where (a.employee_id = '".$islogin_employee."' or b.direct_id = '".$islogin_employee."') 
	                    ".$where." ")->result();  

	    	$response = [
	    		'status' 	=> 200,
				'message' 	=> 'Success',
				'data' 		=> $datatasklist
			];

    	}else{
    		$response = [
				'status' 	=> 401,
				'message' 	=> 'Failed',
				'error' 	=> 'Employee ID Login not found'
			];
    	}*/


    	$employee			= $_REQUEST['employee'];



		$where=''; 
    	if($employee != ''){
    		$where = " where a.employee_id = '".$employee."' ";
    	}

    	$datatasklist = $this->db->query("select a.id, a.employee_id, b.full_name as employee_name, a.task, c.task as parent_name, d.name as status_name, a.progress_percentage, a.due_date, a.status_id, a.parent_id, b.direct_id
					from tasklist a left join employees b on b.id = a.employee_id
					left join tasklist c on c.id = a.parent_id
					left join master_tasklist_status d on d.id = a.status_id
				
                    ".$where." ")->result();  

    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $datatasklist
		];

    	
    	

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function get_master_task()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	/*$employee	= $_REQUEST['employee'];

    	$where=''; 
    	if($employee != ''){
    		$where = " where a.employee_id = '".$employee."' ";
    	}*/

    	$datamaster = $this->db->query("select * from tasklist where parent_id = 0 ")->result();  

    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $datamaster
		];

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function get_master_status_tasklist()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	/*$employee	= $_REQUEST['employee'];

    	$where=''; 
    	if($employee != ''){
    		$where = " where a.employee_id = '".$employee."' ";
    	}*/

    	$datamaster = $this->db->query("select * from master_tasklist_status order by order_no asc ")->result();  

    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $datamaster
		];

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function get_master_leaves()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	/*$employee	= $_REQUEST['employee'];

    	$where=''; 
    	if($employee != ''){
    		$where = " where a.employee_id = '".$employee."' ";
    	}*/


    	$datamaster = $this->db->query("select * from master_leaves where name != 'Absence' ")->result();  

    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $datamaster
		];

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function get_data_employee()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$employee	= $_REQUEST['employee'];
    	$date 		= date("Y-m-d");
    	$period 	= date("Y-m", strtotime($date));
		$tgl 		= date("d", strtotime($date));

    	
    	$empType = $this->db->query("select shift_type from employees where id = '".$employee."' ")->result(); 
    	if($empType[0]->shift_type == 'Reguler'){
    		if($employee == null || $employee == ''){
	    		$where=''; 
	    	}else{
	    		$where = " where a.id = '".$employee."' ";
	    	}

    		$dataemp = $this->db->query("select a.id, a.full_name, b.name as division_name, a.shift_type, 					c.time_in, c.time_out 
						,(select sum(total_leave) from leave_absences where employee_id = a.id) as ttl_ijin
						,(select count(id) from time_attendances where employee_id = a.id and leave_type is null) as ttl_hadir
						,a.direct_id
						from employees a
						left join divisions b on b.id = a.division_id
						left join master_shift_time c on c.shift_type = a.shift_type
                    	".$where." ")->result();  
    	}else if($empType[0]->shift_type == 'Shift'){
    		if($employee == null || $employee == ''){
	    		$where=''; 
	    	}else{
	    		$where = " where b.employee_id = '".$employee."' and a.period = '".$period."' ";
	    	}

    		$dataemp = $this->db->query("select d.id, d.full_name, e.name as division_name,d.shift_type, c.time_in, c.time_out, d.direct_id
				,(select sum(total_leave) from leave_absences where employee_id = d.id) as ttl_ijin
				,(select count(id) from time_attendances where employee_id = d.id and leave_type is null) as ttl_hadir
				from shift_schedule a
				left join group_shift_schedule b on b.shift_schedule_id = a.id
				left join master_shift_time c on c.shift_id = b.`".$tgl."`
				left join employees d on d.id = b.employee_id
				left join divisions e on e.id = d.division_id
				".$where." ")->result();  
    	} 
    	

    	

    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $dataemp
		];

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function save_tasklist()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$type			= $_REQUEST['type']; // insert or update
    	$id 			= $_REQUEST['id'];
    	$employee		= $_REQUEST['employee_id'];
    	$task			= $_REQUEST['task'];
    	$progress		= $_REQUEST['progress'];
    	$task_parent	= $_REQUEST['task_parent_id'];
    	$due_date		= $_REQUEST['due_date'];
    	$status 		= $_REQUEST['status_id'];


    	if($type == 'insert'){

    		$data = [
				'employee_id' 			=> $employee,
				'task' 					=> $task,
				'progress_percentage'	=> $progress,
				'parent_id' 			=> $task_parent,
				'due_date' 				=> $due_date,
				'status_id' 			=> $status,
				'created_at'			=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->insert("tasklist", $data);
			$lastId = $this->db->insert_id();

			if($rs){
				$data2 = [
					'tasklist_id' 			=> $lastId,
					'progress_percentage'	=> $progress,
					'submit_at'				=> date("Y-m-d H:i:s")
				];
				$this->db->insert("history_progress_tasklist", $data2);


				if($status == 1){ //Open
					$updDate = [
						'open_date'		=> date("Y-m-d")
					];
					$this->db->update("tasklist", $updDate, "id = '".$lastId."'");
				}else if($status == 2){ //Progress
					$updDate = [
						'progress_date'	=> date("Y-m-d")
					];
					$this->db->update("tasklist", $updDate, "id = '".$lastId."'");
				}else if($status == 4){ //Request
					$updDate = [
						'request_date'	=> date("Y-m-d")
					];
					$this->db->update("tasklist", $updDate, "id = '".$lastId."'");
				}


				$response = [
		    		'status' 	=> 200,
					'message' 	=> 'Success'
				];
			}else{
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Error submit'
				];
			}

    	}else if($type == 'update'){
    		if($id != ''){
    			$data = [
					'employee_id' 			=> $employee,
					'task' 					=> $task,
					'progress_percentage'	=> $progress,
					'parent_id' 			=> $task_parent,
					'due_date' 				=> $due_date,
					'status_id' 			=> $status,
					'updated_at'			=> date("Y-m-d H:i:s")
				];
				$rs = $this->db->update("tasklist", $data, "id = '".$id."'");
				if($rs){

					$data2 = [
						'tasklist_id' 			=> $id,
						'progress_percentage'	=> $progress,
						'submit_at'				=> date("Y-m-d H:i:s")
					];
					$this->db->insert("history_progress_tasklist", $data2);


					if($status == 1){ //Open
						$updDate = [
							'open_date'		=> date("Y-m-d")
						];
						$this->db->update("tasklist", $updDate, "id = '".$id."'");
					}else if($status == 2){ //Progress
						$updDate = [
							'progress_date'	=> date("Y-m-d")
						];
						$this->db->update("tasklist", $updDate, "id = '".$id."'");
					}else if($status == 4){ //Request
						$updDate = [
							'request_date'	=> date("Y-m-d")
						];
						$this->db->update("tasklist", $updDate, "id = '".$id."'");
					}


					$response = [
			    		'status' 	=> 200,
						'message' 	=> 'Success'
					];
				}else{
					$response = [
						'status' 	=> 401,
						'message' 	=> 'Failed',
						'error' 	=> 'Error submit'
					];
				}

    		}else{
    			$response = [
					'status' 	=> 400, // Bad Request
					'message' 	=>'Failed',
					'error' 	=> 'ID not found'
				];
    		}
    	}else{
    		$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Type not found'
			];
    	}



		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function approval_ijin(){
    	$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;


		if(!empty($_REQUEST)){
			$status = $_REQUEST['status']; //approve or reject
			$id 	= $_REQUEST['id'];
			$approval_level = $_REQUEST['approval_level'];
			$employee_login = $_REQUEST['employee_login'];



			if($status != ''){
				if($id != ''){
					if($approval_level != ''){ 
						$CurrApproval = $this->getCurrApproval($id, $approval_level);
						if(!empty($CurrApproval)){ 
							$CurrApprovalId		= $CurrApproval[0]->id;
							$approval_path_id	= $CurrApproval[0]->approval_path_id;

							$cekApproval = $this->db->query("select * from approval_path_detail where id = '".$CurrApprovalId."' ")->result(); 
							if($cekApproval[0]->status != ''){
								$response = [
										'status' 	=> 401,
										'message' 	=> 'Failed',
										'error' 	=> 'Cannot double approval'
									];
							}else{
								if($status == 'approve'){ 

									$maxApproval = $this->getMaxApproval($id); 
									if($approval_level == $maxApproval){   //last approver
										$data1 = [
											'status_approval' 	=> 2,
											'date_approval'		=> date("Y-m-d H:i:s")
										];
										$rs = $this->db->update('leave_absences', $data1, "id = '".$id."'");

										if($rs){
											$leaves = $this->db->query("select * from leave_absences where id = '".$id."' ")->result(); 
											$total_leave = $leaves[0]->total_leave;

											// update table jatah cuti
											if($leaves[0]->masterleave_id != '2'){ //unpaid leave gak update sisa cuti
												$jatahcuti = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$employee."' and status = 1 order by period_start asc")->result(); 

												$is_update_jatah_selanjutnya=0;
												$sisa_cuti = $jatahcuti[0]->sisa_cuti-$total_leave;

												if($total_leave > $jatahcuti[0]->sisa_cuti){ 
													$is_update_jatah_selanjutnya=1;
													$sisa_cuti = 0;
													$diff_day2 = $total_leave-$jatahcuti[0]->sisa_cuti;
													$sisa_cuti2 = $jatahcuti[1]->sisa_cuti-$diff_day2;	
												}
												
												$data2 = [
													'sisa_cuti' 	=> $sisa_cuti,
													'updated_date'	=> date("Y-m-d H:i:s")
												];
												$this->db->update('total_cuti_karyawan', $data2, "id = '".$jatahcuti[0]->id."'");


												if($is_update_jatah_selanjutnya == 1){ 
													$data3 = [
														'sisa_cuti' 	=> $sisa_cuti2,
														'updated_date'	=> date("Y-m-d H:i:s")
													];
													$this->db->update('total_cuti_karyawan', $data3, "id = '".$jatahcuti[1]->id."'");
												}

											}


											// masukin ke table absensi
											$employees = $this->db->query("select * from employees where id = '".$leaves[0]->employee_id."' ")->result(); 

											$time_in 	= "";
											$time_out 	= "";
											if($employees[0]->shift_type == 'Reguler'){
												$dt = $this->db->query("select * from master_shift_time where shift_type = 'Reguler' ")->result(); 
												$time_in 	= $dt[0]->time_in;
												$time_out 	= $dt[0]->time_out;
											}
											
											
											$date_att = $leaves[0]->date_leave_start;

											for ($i=0; $i < $total_leave; $i++) { 


												$data2 = [
													'date_attendance' 			=> $date_att,
													'employee_id' 				=> $leaves[0]->employee_id,
													'attendance_type' 			=> $employees[0]->shift_type,
													'time_in' 					=> $time_in,
													'time_out' 					=> $time_out,
													'date_attendance_in' 		=> $date_att,
													'date_attendance_out'		=> $date_att,
													'created_at'				=> date("Y-m-d H:i:s"),
													'leave_type' 				=> $leaves[0]->masterleave_id,
													'leave_absences_id' 		=> $leaves[0]->id
												];
												$this->db->insert("time_attendances", $data2);

												
												$date_att = date("Y-m-d", strtotime($date_att.'+ 1 days'));

											}


											//update approval path
											$updApproval = [
												'status' 		=> "Approved",
												'approval_by' 	=> $employee_login,
												'approval_date'	=> date("Y-m-d H:i:s")
											];
											$this->db->update("approval_path_detail", $updApproval, "id = '".$CurrApprovalId."'");
												

										}
										
										
									}else{
										$next_level = $approval_level+1;
										//$nextApproval = getNextApproval($id, $next_level);
										
										$data2 = [
											'current_approval_level' => $next_level
										];
										$rs = $this->db->update("approval_path", $data2, "id = '".$approval_path_id."'");
										
										if($rs){
											$data = [
												'status' 		=> "Approved",
												'approval_by' 	=> $employee_login,
												'approval_date'	=> date("Y-m-d H:i:s")
											];
											$this->db->update("approval_path_detail", $data, "id = '".$CurrApprovalId."'");

											$dataApprovalDetail = [
												'approval_path_id' 	=> $approval_path_id, 
												'approval_level' 	=> $next_level
											];
											$this->db->insert("approval_path_detail", $dataApprovalDetail);
										}
										
									}

									
									$response = [
										'status' 	=> 200,
										'message' 	=> 'Success'
									];

								}else if($status == 'reject'){

									$leave = $this->db->query("select * from leave_absences where id = '".$id."' ")->result(); 

									$data1 = [
										'status_approval' 	=> 3,
										'date_approval'		=> date("Y-m-d H:i:s")
									];
									$rs = $this->db->update('leave_absences', $data1, "id = '".$id."'");

									if($rs){
										/*if($leave[0]->masterleave_id != 2){ // tipenya bukan unpaid leave maka jatah cuti dikembalikan
											//penambahan cuti
											$jatahcuti 			= $this->db->query("select * from total_cuti_karyawan where employee_id = '".$leave[0]->employee_id."' and status = 1 order by period_start asc")->result(); 
											$jml_tambahan_cuti 	= $leave[0]->total_leave;
											$sisa_cuti_1 		= $jatahcuti[0]->sisa_cuti+$jml_tambahan_cuti;

											$tambah_selanjutnya=0;
											if($sisa_cuti_1 > 12){
												$tambah_selanjutnya = 1;
												$slot_tambah 		= 12- $jatahcuti[0]->sisa_cuti;
												$sisa_slot_tambah 	= $jml_tambahan_cuti-$slot_tambah;
												$sisa_cuti_1 		= 12;
											}
											$data2 = [
												'sisa_cuti' 	=> $sisa_cuti_1,
												'updated_date'	=> date("Y-m-d H:i:s")
											];
											$this->db->update('total_cuti_karyawan', $data2, "id = '".$jatahcuti[0]->id."'");

											if($tambah_selanjutnya == 1){
												$sisa_cuti_2 = $jatahcuti[1]->sisa_cuti+$sisa_slot_tambah;
												if($sisa_cuti_2 > 12){
													$sisa_cuti_2 = 12;
												}

												$data3 = [
													'sisa_cuti' 	=> $sisa_cuti_2,
													'updated_date'	=> date("Y-m-d H:i:s")
												];
												$this->db->update('total_cuti_karyawan', $data3, "id = '".$jatahcuti[1]->id."'");
											}
										}*/


										//update approval path
										$dataapproval = [
											'status' 		=> "Rejected",
											'approval_by' 	=> $employee_login,
											'approval_date'	=> date("Y-m-d H:i:s")
										];
										$this->db->update("approval_path_detail", $dataapproval, "id = '".$CurrApprovalId."'");



										$response = [
								    		'status' 	=> 200,
											'message' 	=> 'Success'
										];

									}else{
										$response = [
											'status' 	=> 401,
											'message' 	=> 'Failed'
										];
									}

								}else{
									$response = [
										'status' 	=> 401,
										'message' 	=> 'Failed',
										'error' 	=> 'Status not found'
									];
								}
							}

						}else{
							$response = [
								'status' 	=> 400, // Bad Request
								'message' 	=>'Failed',
								'error' 	=> 'Approval Level not found'
							];
						}
					}else{
						$response = [
							'status' 	=> 400, // Bad Request
							'message' 	=>'Failed',
							'error' 	=> 'Approval Level not found'
						];
					}
				}else{
					$response = [
						'status' 	=> 400, // Bad Request
						'message' 	=>'Failed',
						'error' 	=> 'Require not satisfied'
					];
				}
				
			}else{
				$response = [
					'status' 	=> 400, // Bad Request
					'message' 	=>'Failed',
					'error' 	=> 'Require not satisfied'
				];
			}

		}else {
			$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
		}
		

		

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);

	}


	public function cek_sisa_cuti(){

		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;


		if(!empty($_REQUEST)){
			$employee = $_REQUEST['employee'];

			if($employee != ''){
				$cek_sisa_cuti 	= $this->db->query("select sum(sisa_cuti) as ttl_sisa_cuti from total_cuti_karyawan where employee_id = '".$employee."' and status = 1")->result(); 
				$sisa_cuti 		= $cek_sisa_cuti[0]->ttl_sisa_cuti;



				$response = [
		    		'status' 	=> 200,
					'message' 	=> 'Success',
					'sisa_cuti' => $sisa_cuti
				];

			}else{
				$response = [
					'status' 	=> 400, // Bad Request
					'message' 	=>'Failed',
					'error' 	=> 'Require not satisfied'
				];
			}

		}else{
			$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
		}




		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);

	}

	public function get_pendingan_approval(){

		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;


		if(!empty($_REQUEST)){
			$employee 	= $_REQUEST['employee'];

			if($employee != ''){
				
				$get_data_pendingan 	= $this->db->query("select 'leave attendance' as description, count(*) as total_pendingan_approval from leave_absences a left join employees b on b.id = a.employee_id where a.status_approval = 1 and b.direct_id = '".$employee."'
					union
					select 'overtime' as description, count(*) as total_pendingan_approval from overtimes a left join employees b on b.id = a.employee_id 
					where a.status_id = 1 and b.direct_id = '".$employee."'
					union
					select 'reimbursement' as description, count(*) as total_pendingan_approval from medicalreimbursements a left join employees b on b.id = a.employee_id 
					where a.status_id = 1 and b.direct_id = '".$employee."'
					union
					select 'cash advance' as description, count(*) as total_pendingan_approval from cash_advance a left join employees b on b.id = a.requested_by 
					where a.status_id = 1 and b.direct_id = '".$employee."' ")->result(); 

				$data_pendingan = "Tidak ada data";
				if(!empty($get_data_pendingan)){
					$data_pendingan = $get_data_pendingan;
				}

				
				$response = [
		    		'status' 			=> 200,
					'message' 			=> 'Success',
					'data_pendingan' 	=> $data_pendingan
				];
				
			}else{
				$response = [
					'status' 	=> 400, // Bad Request
					'message' 	=>'Failed',
					'error' 	=> 'Require not satisfied'
				];
			}

		}else{
			$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
		}




		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);

	}



	public function sync_health()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$employeeId			= $_REQUEST['employeeId'];
    	$windowStartUtc 	= $_REQUEST['windowStartUtc'];
    	$windowEndUtc		= $_REQUEST['windowEndUtc'];
    	$idempotencyKey		= $_REQUEST['idempotencyKey'];
    	$source				= $_REQUEST['source'];
    	$date				= $_REQUEST['date'];
    	$sleepMinutes		= $_REQUEST['sleepMinutes'];
    	$steps 				= $_REQUEST['steps'];
    	$activeCaloriesKcal = $_REQUEST['activeCaloriesKcal'];
    	$hrAvgBpm 			= $_REQUEST['hrAvgBpm'];
    	$hrSamples 			= $_REQUEST['hrSamples'];
    	$spo2AvgPct 		= $_REQUEST['spo2AvgPct'];
    	$spo2MinPct			= $_REQUEST['spo2MinPct'];
    	$spo2MaxPct			= $_REQUEST['spo2MaxPct'];
    	$spo2Samples		= $_REQUEST['spo2Samples'];
    	$rawHr_tsUtc		= $_REQUEST['rawHr_tsUtc'];
    	$rawHr_bpm 			= $_REQUEST['rawHr_bpm'];
    	$rawSpo2_tsUtc 		= $_REQUEST['rawSpo2_tsUtc'];
    	$rawSpo2_pct 		= $_REQUEST['rawSpo2_pct'];
    	$platform 			= $_REQUEST['platform'];



    	if($employeeId != '' && $windowStartUtc != '' && $windowEndUtc != '' && $idempotencyKey != ''){
    		$data = [
				'employee_id' 		=> $employeeId,
				'window_start_utc' 	=> $windowStartUtc,
				'window_end_utc'	=> $windowEndUtc,
				'idempotency_key' 	=> $idempotencyKey,
				'created_at'		=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->insert("health_sync_runs", $data);
			$lastId = $this->db->insert_id();

			if($rs){
				$data2 = [
					'employee_id' 	=> $employeeId,
					'ts_utc'		=> $rawHr_tsUtc,
					'bpm' 			=> $rawHr_bpm,
					'source'		=> $source,
					'sync_runs_id' 	=> $lastId,
					'created_at'	=> date("Y-m-d H:i:s")
				];
				$input_hr = $this->db->insert("health_raw_hr", $data2);

				$data3 = [
					'employee_id' 	=> $employeeId,
					'ts_utc'		=> $rawSpo2_tsUtc,
					'pct' 			=> $rawSpo2_pct,
					'source'		=> $source,
					'sync_runs_id' 	=> $lastId,
					'created_at'	=> date("Y-m-d H:i:s")
				];
				$input_spo2 = $this->db->insert("health_raw_spo2", $data3);


				/// update atau insert data daily
				/// cek jika belum ada maka insert, jika sudah ada maka update (like summary)
				$daily 	= $this->db->query("select * from health_daily where employee_id = '".$employeeId."' and date = '".$date."'")->result(); 
				if(!empty($daily)){ //update
					$data_daily = [
						'sleep_minutes' 		=> $sleepMinutes,
						'steps'					=> $steps,
						'active_calories_kcal' 	=> $activeCaloriesKcal,
						'hr_avg_bpm' 			=> $hrAvgBpm,
						'hr_samples' 			=> $hrSamples,
						'spo2_avg_pct'			=> $spo2AvgPct,
						'spo2_min_pct' 			=> $spo2MinPct,
						'spo2_max_pct' 			=> $spo2MaxPct,
						'spo2_samples' 			=> $spo2Samples,
						'source'				=> $source,
						'platform' 				=> $platform,
						'updated_at'			=> date("Y-m-d H:i:s"),
						'last_sync_runs_id' 	=> $lastId
					];
					$input_daily = $this->db->update("health_daily", $data_daily, "id = '".$daily[0]->id."'");

				}else{ //insert
					$data_daily = [
						'employee_id' 			=> $employeeId,
						'date'					=> $date,
						'sleep_minutes' 		=> $sleepMinutes,
						'steps'					=> $steps,
						'active_calories_kcal' 	=> $activeCaloriesKcal,
						'hr_avg_bpm' 			=> $hrAvgBpm,
						'hr_samples' 			=> $hrSamples,
						'spo2_avg_pct'			=> $spo2AvgPct,
						'spo2_min_pct' 			=> $spo2MinPct,
						'spo2_max_pct' 			=> $spo2MaxPct,
						'spo2_samples' 			=> $spo2Samples,
						'source'				=> $source,
						'platform' 				=> $platform,
						'created_at'			=> date("Y-m-d H:i:s"),
						'last_sync_runs_id' 	=> $lastId
					];
					$input_daily = $this->db->insert("health_daily", $data_daily);
				}

				if($input_hr && $input_spo2 && $input_daily){
					$ttl_daily	= $this->db->query("select count(*) as ttl from health_daily where last_sync_runs_id = '".$lastId."'")->result(); 
					$ttl_hr 	= $this->db->query("select count(*) as ttl from health_raw_hr where sync_runs_id = '".$lastId."'")->result(); 
					$ttl_spo2 	= $this->db->query("select count(*) as ttl from health_raw_spo2 where sync_runs_id = '".$lastId."'")->result(); 
					$data_sync = [
						'status' 				=> 'ok',
						'total_daily_upserts' 	=> $ttl_daily[0]->ttl,
						'total_raw_hr' 			=> $ttl_hr[0]->ttl,
						'total_raw_spo2' 		=> $ttl_spo2[0]->ttl,
						'updated_at'			=> date("Y-m-d H:i:s")
					];
					$this->db->update("health_sync_runs", $data_sync, "id = '".$lastId."'");
				}
				

				$response = [
		    		'status' 	=> 200,
					'message' 	=> 'Success'
				];
			}else{
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Error submit'
				];
			}
    	}else{
    		$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
    	}




		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function health_hr()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$employeeId			= $_REQUEST['employeeId'];
    	$source				= $_REQUEST['source'];
    	$rawHr_tsUtc		= $_REQUEST['rawHr_tsUtc'];
    	$rawHr_bpm 			= $_REQUEST['rawHr_bpm'];
    	$sync_runs_id 		= $_REQUEST['sync_runs_id'];



    	if($employeeId != '' && $sync_runs_id != '' && $source != '' && $rawHr_tsUtc != '' && $rawHr_bpm != ''){
    		$data2 = [
				'employee_id' 	=> $employeeId,
				'ts_utc'		=> $rawHr_tsUtc,
				'bpm' 			=> $rawHr_bpm,
				'source'		=> $source,
				'sync_runs_id' 	=> $sync_runs_id,
				'created_at'	=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->insert("health_raw_hr", $data2);
			
			if($rs){
				$ttl_daily	= $this->db->query("select count(*) as ttl from health_daily where last_sync_runs_id = '".$sync_runs_id."'")->result(); 
				$ttl_hr 	= $this->db->query("select count(*) as ttl from health_raw_hr where sync_runs_id = '".$sync_runs_id."'")->result(); 
				$ttl_spo2 	= $this->db->query("select count(*) as ttl from health_raw_spo2 where sync_runs_id = '".$sync_runs_id."'")->result(); 

				$data_sync = [
					'status' 				=> 'ok',
					'total_daily_upserts' 	=> $ttl_daily[0]->ttl,
					'total_raw_hr' 			=> $ttl_hr[0]->ttl,
					'total_raw_spo2' 		=> $ttl_spo2[0]->ttl,
					'updated_at'			=> date("Y-m-d H:i:s")
				];
				$this->db->update("health_sync_runs", $data_sync, "id = '".$sync_runs_id."'");
				

				$response = [
		    		'status' 	=> 200,
					'message' 	=> 'Success'
				];
			}else{
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Error submit'
				];
			}
    	}else{
    		$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
    	}




		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function health_spo2()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$employeeId			= $_REQUEST['employeeId'];
    	$source				= $_REQUEST['source'];
    	$rawSpo2_tsUtc 		= $_REQUEST['rawSpo2_tsUtc'];
    	$rawSpo2_pct 		= $_REQUEST['rawSpo2_pct'];
    	$sync_runs_id 		= $_REQUEST['sync_runs_id'];


    	if($employeeId != '' && $sync_runs_id != '' && $rawSpo2_tsUtc != '' && $rawSpo2_pct != '' && $source != ''){
    		$data3 = [
				'employee_id' 	=> $employeeId,
				'ts_utc'		=> $rawSpo2_tsUtc,
				'pct' 			=> $rawSpo2_pct,
				'source'		=> $source,
				'sync_runs_id' 	=> $sync_runs_id,
				'created_at'	=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->insert("health_raw_spo2", $data3);

			if($rs){
				
				$ttl_daily	= $this->db->query("select count(*) as ttl from health_daily where last_sync_runs_id = '".$sync_runs_id."'")->result(); 
				$ttl_hr 	= $this->db->query("select count(*) as ttl from health_raw_hr where sync_runs_id = '".$sync_runs_id."'")->result(); 
				$ttl_spo2 	= $this->db->query("select count(*) as ttl from health_raw_spo2 where sync_runs_id = '".$sync_runs_id."'")->result(); 
				$data_sync = [
					'status' 				=> 'ok',
					'total_daily_upserts' 	=> $ttl_daily[0]->ttl,
					'total_raw_hr' 			=> $ttl_hr[0]->ttl,
					'total_raw_spo2' 		=> $ttl_spo2[0]->ttl,
					'updated_at'			=> date("Y-m-d H:i:s")
				];
				$this->db->update("health_sync_runs", $data_sync, "id = '".$sync_runs_id."'");
				
				

				$response = [
		    		'status' 	=> 200,
					'message' 	=> 'Success'
				];
			}else{
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Error submit'
				];
			}
    	}else{
    		$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
    	}




		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function health_daily()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$employeeId			= $_REQUEST['employeeId'];
    	$source				= $_REQUEST['source'];
    	$date				= $_REQUEST['date'];
    	$sleepMinutes		= $_REQUEST['sleepMinutes'];
    	$steps 				= $_REQUEST['steps'];
    	$activeCaloriesKcal = $_REQUEST['activeCaloriesKcal'];
    	$hrAvgBpm 			= $_REQUEST['hrAvgBpm'];
    	$hrSamples 			= $_REQUEST['hrSamples'];
    	$spo2AvgPct 		= $_REQUEST['spo2AvgPct'];
    	$spo2MinPct			= $_REQUEST['spo2MinPct'];
    	$spo2MaxPct			= $_REQUEST['spo2MaxPct'];
    	$spo2Samples		= $_REQUEST['spo2Samples'];
    	$platform 			= $_REQUEST['platform'];
    	$sync_runs_id 		= $_REQUEST['sync_runs_id'];


    	if($employeeId != '' && $date != ''){
    		/// update atau insert data daily
			/// cek jika belum ada maka insert, jika sudah ada maka update (like summary)
			$daily 	= $this->db->query("select * from health_daily where employee_id = '".$employeeId."' and date = '".$date."'")->result(); 
			if(!empty($daily)){ //update
				$data_daily = [
					'sleep_minutes' 		=> $sleepMinutes,
					'steps'					=> $steps,
					'active_calories_kcal' 	=> $activeCaloriesKcal,
					'hr_avg_bpm' 			=> $hrAvgBpm,
					'hr_samples' 			=> $hrSamples,
					'spo2_avg_pct'			=> $spo2AvgPct,
					'spo2_min_pct' 			=> $spo2MinPct,
					'spo2_max_pct' 			=> $spo2MaxPct,
					'spo2_samples' 			=> $spo2Samples,
					'source'				=> $source,
					'platform' 				=> $platform,
					'updated_at'			=> date("Y-m-d H:i:s"),
					'last_sync_runs_id' 	=> $sync_runs_id
				];
				$rs = $this->db->update("health_daily", $data_daily, "id = '".$daily[0]->id."'");

			}else{ //insert
				$data_daily = [
					'employee_id' 			=> $employeeId,
					'date'					=> $date,
					'sleep_minutes' 		=> $sleepMinutes,
					'steps'					=> $steps,
					'active_calories_kcal' 	=> $activeCaloriesKcal,
					'hr_avg_bpm' 			=> $hrAvgBpm,
					'hr_samples' 			=> $hrSamples,
					'spo2_avg_pct'			=> $spo2AvgPct,
					'spo2_min_pct' 			=> $spo2MinPct,
					'spo2_max_pct' 			=> $spo2MaxPct,
					'spo2_samples' 			=> $spo2Samples,
					'source'				=> $source,
					'platform' 				=> $platform,
					'created_at'			=> date("Y-m-d H:i:s"),
					'last_sync_runs_id' 	=> $sync_runs_id
				];
				$rs = $this->db->insert("health_daily", $data_daily);
			}

			if($rs){
				$response = [
		    		'status' 	=> 200,
					'message' 	=> 'Success'
				];
			}else{
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Error submit'
				];
			}

    	}else{
    		$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
    	}




		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }



    public function get_career_list()
    { 
    	//$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;


    	$datacareer = $this->db->query("select a.*, c.name as divname, d.name as job_level_name,
    					(select GROUP_CONCAT(CONCAT(responsibility)  ORDER BY priority_level ASC SEPARATOR '|') from recruitment_job_descriptions where request_recruitment_id = a.id) as job_descriptions,
						(select GROUP_CONCAT(CONCAT(requirement_type, ':', requirement_text) SEPARATOR '|') from recruitment_requirements where request_recruitment_id = a.id) as requirements
						from request_recruitment a left join sections b on b.id = a.section_id
						left join divisions c on c.id = b.division_id
						left join master_job_level d on d.id = a.job_level_id
						where status = 'approved' ")->result();  
    	

    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $datacareer
		];

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    // Get next number 
	public function genCandidateCode() { 
		
		$yearcode = date("y");
		$monthcode = date("m");
		$period = $yearcode.$monthcode; 

		$lettercode = ('CND'); 


		$cek = $this->db->query("select * from candidates where SUBSTRING(candidate_code, 4, 4) = '".$period."'");
		$rs_cek = $cek->result_array();

		if(empty($rs_cek)){ 
			$num = '0001';
		}else{ 
			$cek2 = $this->db->query("select max(candidate_code) as maxnum from candidates where SUBSTRING(candidate_code, 4, 4) = '".$period."'");
			$rs_cek2 = $cek2->result_array();
			$dt = $rs_cek2[0]['maxnum']; 
			$getnum = substr($dt,7); 
			$num = str_pad($getnum + 1, 4, 0, STR_PAD_LEFT);
			
		}

		
		$nextnum = $lettercode.$period.$num;

		return $nextnum;
		
	} 


    public function save_candidates() {  
        header('Content-Type: application/json');

        $job_id     = $this->input->post('job_id');
        $full_name  = $this->input->post('full_name');
        $email      = $this->input->post('email');
        $phone      = $this->input->post('phone');

        $candidate_code = $this->genCandidateCode();

        ///bikin folder candidates
        $upload_dir = './uploads/candidates/'.$candidate_code.'/'; // nama folder
		// Cek apakah folder sudah ada
		if (!is_dir($upload_dir)) {
		    // Jika belum ada, buat folder
		    mkdir($upload_dir, 0755, true); // 0755 = permission, true = recursive
		}

        // Upload CV
        $config['upload_path']   = './uploads/candidates/'.$candidate_code.'';
        $config['allowed_types'] = 'pdf|doc|docx';
        //$config['max_size']      = 2048; //2 MB
        $config['max_size'] = 5120; // 5 MB

        $this->load->library('upload', $config);

        $cv_file = "";
        if ($this->upload->do_upload('cv')) {
            $cv_file = $this->upload->data('file_name');
        } else {
            echo json_encode([
                "status" => 400,
                "message" => $this->upload->display_errors()
            ]);
            return;
        }

        
        // Simpan ke DB
        $data = [
            'request_recruitment_id'=> $job_id,
            'candidate_code'   		=> $candidate_code,
            'full_name'  			=> $full_name,
            'email'      			=> $email,
            'phone' 				=> $phone,
           	'cv'    				=> $cv_file,
            'created_date' 			=> date('Y-m-d H:i:s')
        ];

        
        $rs = $this->db->insert("candidates", $data);


        if($rs){
        	echo json_encode([
	            "status"  => 200,
	            "message" => "Application submitted successfully",
	            "data"    => $data
	        ]);
        }else{
        	echo json_encode([
	            "status"  => 401,
	            "message" => "Application submitted failed"
	        ]);
        }

        
    }


    public function get_data_healthDaily()
    { 
    	$this->verify_token();

		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$employee 	= $_REQUEST['employee_id'];
    	$date_start = $_REQUEST['date_start'];
    	$date_end 	= $_REQUEST['date_end'];


		$whr_emp=''; 
    	if($employee != ''){
    		$whr_emp = " and employee_id = '".$employee."' ";
    	}
    	$whr_period_daily=""; 
		if($date_start != '' && $date_end){
			$whr_period_daily = " and (date between '".$date_start."' and '".$date_end."')";
		}


    	$datarow = $this->db->query("select h.*
						FROM health_daily h
						JOIN (
							SELECT date, MAX(id) AS max_id
							FROM health_daily
							WHERE 1=1 ".$whr_emp.$whr_period_daily."
							GROUP BY date
						) x ON h.date = x.date AND h.id = x.max_id
						ORDER BY h.date; ")->result();  

    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $datarow
		];

    	
    	

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function get_data_healthBpm()
    { 
    	$this->verify_token();

		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$employee 	= $_REQUEST['employee_id'];
    	$date_start = $_REQUEST['date_start'];
    	$date_end 	= $_REQUEST['date_end'];


		$whr_emp=''; 
    	if($employee != ''){
    		$whr_emp = " and employee_id = '".$employee."' ";
    	}
    	$whr_period=""; 
		if($date_start != '' && $date_end != ''){
			$whr_period = " and (DATE_FORMAT(ts_utc, '%Y-%m-%d') between '".$date_start."' and '".$date_end."')";
		}


    	$datarow = $this->db->query("select * from health_raw_hr where 1=1 ".$whr_emp.$whr_period."
						order by ts_utc asc ")->result();  

    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $datarow
		];

    	
    	

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function get_data_healthSpo2()
    { 
    	$this->verify_token();

		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$employee 	= $_REQUEST['employee_id'];
    	$date_start = $_REQUEST['date_start'];
    	$date_end 	= $_REQUEST['date_end'];


		$whr_emp=''; 
    	if($employee != ''){
    		$whr_emp = " and employee_id = '".$employee."' ";
    	}
    	$whr_period=""; 
		if($date_start != '' && $date_end != ''){
			$whr_period = " and (DATE_FORMAT(ts_utc, '%Y-%m-%d') between '".$date_start."' and '".$date_end."')";
		}


    	$datarow = $this->db->query("select * from health_raw_spo2 where 1=1 ".$whr_emp.$whr_period."
						order by ts_utc asc ")->result();  

    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $datarow
		];

    	
    	

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function upload_medcheck()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$employee	= $_POST['employee'];
    	$file 		= isset($_FILES['file']) ? $_FILES['file'] : null;

    	$dataEmp 	= $this->db->query("select * from employees where id = '".$employee."'")->result(); 
    	$empcode = "";
    	if(!empty($dataEmp)){
    		$empcode = $dataEmp[0]->emp_code;
    	} 

    	if(!empty($empcode)){
    		//upload 
			$dataU = array();
			$dataU['status'] = FALSE; 
			$fieldname='file';
			if(isset($_FILES[$fieldname]) && !empty($_FILES[$fieldname]['name']))
	        { 
	        	///bikin folder medcheck
		        $upload_dir = './uploads/employee/'.$empcode.'/medcheck/'; // nama folder
				// Cek apakah folder sudah ada
				if (!is_dir($upload_dir)) {
				    // Jika belum ada, buat folder
				    mkdir($upload_dir, 0755, true); // 0755 = permission, true = recursive
				}

	           
	            $config['upload_path']   = "uploads/employee/".$empcode."/medcheck/";
	            $config['allowed_types'] = "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
	            $config['max_size']      = "0"; 
	            
	            $this->load->library('upload', $config); 
	            
	            if(!$this->upload->do_upload($fieldname)){ 
	                $err_msg = $this->upload->display_errors(); 
	                $dataU['error_warning'] = strip_tags($err_msg);              
	                $dataU['status'] = FALSE;
	            } else { 
	                $fileData = $this->upload->data();
	                $dataU['upload_file'] = $fileData['file_name'];
	                $dataU['status'] = TRUE;
	            }
	        }
	        $document = '';
			if($dataU['status']){ 
				$document = $dataU['upload_file'];
			} else if(isset($dataU['error_warning'])){ 
				//echo $dataU['error_warning']; exit;
				$document = 'ERROR : '.$dataU['error_warning'];
			}
	        //end upload



	    	$data = [
				'employee_id' 			=> $employee,
				'file' 					=> $document,
				'created_at'			=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->insert("medical_check", $data);
			
			if($rs){
				
				$response = [
		    		'status' 	=> 200,
					'message' 	=> 'Success'
				];
			}else{
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Error submit'
				];
			}
    	}else{
    		
	        $response = [
	    		'status' 	=> 401,
				'message' 	=> 'Employee Code not found'
			];
    	}

    	



		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function approval_medcheck(){
    	$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;


		if(!empty($_REQUEST)){
			$status = $_REQUEST['status']; //OK or NOT OK
			$id 	= $_REQUEST['id'];

			if($status != ''){ 
				if($id != ''){ 
					$dataupd = [
						'status' 		=> $status,
						'approval_date'	=> date("Y-m-d H:i:s")
					];
					$rs = $this->db->update('medical_check', $dataupd, "id = '".$id."'");
					if($rs){
				
						$response = [
				    		'status' 	=> 200,
							'message' 	=> 'Success'
						];
					}else{
						$response = [
							'status' 	=> 401,
							'message' 	=> 'Failed',
							'error' 	=> 'Error submit'
						];
					}
				}else{
					$response = [
						'status' 	=> 400, // Bad Request
						'message' 	=>'Failed',
						'error' 	=> 'Require not satisfied'
					];
				}
				
			}else{
				$response = [
					'status' 	=> 400, // Bad Request
					'message' 	=>'Failed',
					'error' 	=> 'Require not satisfied'
				];
			}

		}else {
			$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
		}
		

		

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);

	}


	public function get_data_medcheck()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$islogin_employee	= $_REQUEST['islogin_employee'];
    	$employee			= $_REQUEST['employee']; //filter employee


    	if($islogin_employee != ''){

    		$where=""; 
	    	if($employee != ''){
	    		$where = " and a.employee_id = '".$employee."' ";
	    	}

	    	/*$datarow = $this->db->query("select a.*, b.full_name as employee_name, b.direct_id, 
	    		concat('http://localhost/_hrm/uploads/employee/',b.emp_code,'/medcheck/',a.file) as url_file 
	 	 	from medical_check a left join employees b on b.id = a.employee_id where (a.employee_id = '".$islogin_employee."' or b.direct_id = '".$islogin_employee."') ".$where." order by a.id desc")->result();  */

	 	 	$datarow = $this->db->query("select a.*, b.full_name as employee_name, b.direct_id, 
	    		concat('https://hrm.nathabuana.com/uploads/employee/',b.emp_code,'/medcheck/',a.file) as url_file 
	 	 	from medical_check a left join employees b on b.id = a.employee_id where (a.employee_id = '".$islogin_employee."' or b.direct_id = '".$islogin_employee."') ".$where." order by a.id desc")->result();  


	    	
	    	$response = [
	    		'status' 	=> 200,
				'message' 	=> 'Success',
				'data' 		=> $datarow
			];

    	}else{
    		$response = [
				'status' 	=> 401,
				'message' 	=> 'Failed',
				'error' 	=> 'Employee ID Login not found'
			];
    	}

    	

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function getCurrApproval($trx_id, $approval_level){
		$post 		= $this->input->post(null, true);
		

		$approval_matrix_type_id = 1;
		$rs =  $this->db->query("select b.* from approval_path a left join approval_path_detail b on b.approval_path_id = a.id and approval_level = ".$approval_level." where a.approval_matrix_type_id = ".$approval_matrix_type_id." and a.trx_id = ".$trx_id."")->result();
		

		return $rs;
	}

	public function getMaxApproval($trx_id){ 
		$post 		= $this->input->post(null, true);
		

		$approval_matrix_type_id = 1;
		$rs =  $this->db->query("select b.*, a.current_approval_level, c.role_name from approval_path a 
				left join approval_matrix_detail b on b.approval_matrix_id = a.approval_matrix_id
				left join approval_matrix_role c on c.id = b.role_id
				where approval_matrix_type_id = ".$approval_matrix_type_id." and trx_id = ".$trx_id." 
				order by b.approval_level desc limit 1 ")->result();
		

		return $rs[0]->approval_level;
	}


	public function get_approval_matrix_type()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	
 	 	$datarow = $this->db->query("select * from approval_matrix_mstype order by id asc ")->result();  


    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $datarow
		];

    	
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


	public function get_data_approvalLog()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	//$approval_matrix_type_id = 1; //absensi
    	$id	= $_REQUEST['id'];
    	$approval_matrix_type_id = $_REQUEST['approval_matrix_type_id'];
    	


    	if($id != ''){

	 	 	$datarow = $this->db->query("select dt.approval_level, dt.approver_name, dt.status_name, dt.approval_date from (select a.*, c.approval_level, d.role_name, e.id as 'id_detail',
				(case when e.id != '' and (e.status = '' or e.status is null) and a.current_approval_level = c.approval_level then 'Waiting Approval'
				when e.id != '' and e.status != '' then e.status
				else ''
				end) as status_name,
				IF(e.id != '' and e.status != '', (SELECT full_name FROM employees WHERE id = e.approval_by), d.role_name) AS approver_name,
				if(e.approval_date is null or e.approval_date = '0000-00-00 00:00:00','',e.approval_date) as approval_date
			from approval_path a 
			left join approval_matrix b on b.id = a.approval_matrix_id
			left join approval_matrix_detail c on c.approval_matrix_id = b.id
			left join approval_matrix_role d on d.id = c.role_id
			left join approval_path_detail e on e.approval_path_id = a.id and e.approval_level = c.approval_level
				where a.approval_matrix_type_id = ".$approval_matrix_type_id." and a.trx_id = '".$id."'
			order by c.approval_level asc)dt ")->result();  


	    	
	    	$response = [
	    		'status' 	=> 200,
				'message' 	=> 'Success',
				'data' 		=> $datarow
			];

    	}else{
    		$response = [
				'status' 	=> 401,
				'message' 	=> 'Failed',
				'error' 	=> 'Data not found'
			];
    	}

    	

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }



}

