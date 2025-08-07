


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
    	$nama_db="hrm"; $username_db="hrm"; $password_db="hrm@2025!";
    	$cek_url = $this->api->query_db($nama_db, $username_db, $password_db, $sql); 
    
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
					/*$dt = $this->db->query("select a.*, b.time_in, b.time_out, b.name from shift_schedule a
					left join master_shift_time b on b.id = a.master_shift_time_id
					where a.employee_id = '".$employee."' and a.year_periode = '".$year."' and a.month_periode = '".$month."' and date = '".$date."' ")->result(); */

					$data_attendances = $this->db->query("select * from time_attendances where date_attendance = '".$date."' and employee_id = '".$employee."'")->result(); 
					//jika sudah ada absen hari ini, maka akan cek shift besok, kalau dapet shift 3, maka bisa checkin. Karna shift 3 jadwalnya tengah malam, jadi bisa checkin di tgl sebelumnya.
					if((!empty($data_attendances)) && $data_attendances[0]->date_attendance_in != null && $data_attendances[0]->date_attendance_in != '0000-00-00 00:00:00' && $data_attendances[0]->date_attendance_out != null && $data_attendances[0]->date_attendance_out != '0000-00-00 00:00:00'){

						$dateTomorrow = date("Y-m-d", strtotime($date . " +1 day"));
						$period  = date('Y-m', strtotime($dateTomorrow));
						$tgl = date('d', strtotime($dateTomorrow));
					}

					$dt = $this->db->query("select a.*, b.periode
							, b.`".$tgl."` as 'shift' 
							, c.time_in, c.time_out, c.name 
							from shift_schedule a
							left join group_shift_schedule b on b.shift_schedule_id = a.id
							left join master_shift_time c on c.shift_id = b.`".$tgl."`
							where b.employee_id = '".$employee."' and a.period = '".$period."' ")->result(); 

					if($dt[0]->shift != 3){ //bukan shift 3, tidak bisa checkin di tgl sebelumnya
						//$emp_shift_type=0;
						$period = date("Y-m", strtotime($date)); 
						$tgl = date("d", strtotime($date));
						$dt = $this->db->query("select a.*, b.periode, b.`".$tgl."` as 'shift', c.time_in, c.time_out, c.name 
							from shift_schedule a left join group_shift_schedule b on b.shift_schedule_id = a.id 
							left join master_shift_time c on c.shift_id = b.`".$tgl."`
							where b.employee_id = '".$employee."' and a.period = '".$period."' ")->result();
					}

				}else{ //tidak ada shift type
					$emp_shift_type=0;
				} 


				if($emp_shift_type == 1){ 
					$attendance_type 	= $dt[0]->name;
					$time_in 			= $dt[0]->time_in;
					$time_out 			= $dt[0]->time_out;
					$post_timein 		= strtotime($time_in);
					$post_timeout 		= strtotime($time_out);


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
								$cek_data_shift = $this->db->query("select * from time_attendances where employee_id = '".$employee."' and date_attendance = '".$date."' and (date_attendance_in is not null and date_attendance_in != '0000-00-00') and (date_attendance_out is not null and date_attendance_out != '0000-00-00') ")->result();
								if(!empty($cek_data_shift) && $attendance_type == 'Shift 3'){ //maka set bahwa absen yg akan dilakukan adalah absen utk hari besok (hanya utk shift 3)
							
									$date = date("Y-m-d", strtotime($date . " +1 day"));

									$cek_data_shift_besok = $this->db->query("select * from time_attendances where employee_id = '".$employee."' and date_attendance = '".$date."' ")->result();
									if(!empty($cek_data_shift_besok)){ 
										$error='Cannot double checkin';
									}else{ 
										/*$dt = $this->db->query("select a.*, b.time_in, b.time_out, b.name from shift_schedule a left join master_shift_time b on b.id = a.master_shift_time_id
										where a.employee_id = '".$employee."' and a.year_periode = '".$year."' and a.month_periode = '".$month."' and date = '".$date."' ")->result(); */

										$dt = $this->db->query("select a.*, b.periode
												, b.`".$tgl."` as 'shift' 
												, c.time_in, c.time_out, c.name 
												from shift_schedule a
												left join group_shift_schedule b on b.shift_schedule_id = a.id 
												left join master_shift_time c on c.shift_id = b.`".$tgl."`
												where b.employee_id = '".$employee."' and a.period = '".$period."' ")->result(); 

										if(empty($dt)){
											$error='Checkin Date not valid';
										}else{
											$attendance_type 	= $dt[0]->name;
											$time_in 			= $dt[0]->time_in;
											$time_out 			= $dt[0]->time_out;
											$datetime_in 		= $date.' '.$time_in;
											$post_datetimein 	= strtotime($datetime_in);
											

											$is_late=''; 
											if($timestamp_datetime > $post_datetimein){
												$is_late='Y';
											}
										}
									}

								}else{ 
									/*$error='Checkin Date not valid';*/
									$error='Cannot double checkin';
								}
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
					
					$datetime_out = $dt[0]->date.' '.$dt[0]->time_out;
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
						'error' 	=> 'Harap lampirkan file'
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

    public function insert_ijin($employee, $leave_type, $date_start, $date_end, $reason, $photo){

    	if($employee != '' && $date_start != '' && $date_end != '' && $leave_type != ''){ 
			$cek_sisa_cuti 	= $this->api->get_data_sisa_cuti($employee, $date_start, $date_end);
			$sisa_cuti 		= $cek_sisa_cuti[0]->ttl_sisa_cuti;

			$diff_day		= $this->api->dayCount($date_start, $date_end);

			if($leave_type == '6'){ //Half day leave
				$diff_day = $diff_day*0.5;
			}
			if($leave_type == '5'){ //Sick Leave
				$diff_day = 0 ;
			}
			

			if($diff_day <= $sisa_cuti || $leave_type == '2'){ //unpaid leave gak ngecek sisa cuti

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

						return $rs;
					}else return null;
	            }

			}
			else return 'sisa_cuti_tidak_cukup';
			
		}else return null;


    }


    public function update_ijin($employee, $leave_type, $date_start, $date_end, $reason, $id){

    	if(!empty($id)){

			if($date_start != '' && $date_end != '' && $leave_type != ''){ 
				$getcurrLeave = $this->db->query("select * from leave_absences where id = '".$id."' ")->result(); 

				if($getcurrLeave[0]->status_approval == 1){ //waiting approval
					/*$getcurrTotalCuti =0;
					if($getcurrLeave[0]->masterleave_id != 2){ //data sebelumnya bukan unpaid leave, maka sisa cuti dibalikin
						$getcurrTotalCuti = $getcurrLeave[0]->total_leave;
					}

					$cek_sisa_cuti 	= $this->api->get_data_sisa_cuti($employee, $date_start, $date_end); 
					$sisa_cuti 		= $cek_sisa_cuti[0]->ttl_sisa_cuti+$getcurrTotalCuti;*/


					$cek_sisa_cuti 	= $this->api->get_data_sisa_cuti($employee, $date_start, $date_end);
					$sisa_cuti 		= $cek_sisa_cuti[0]->ttl_sisa_cuti;

					$diff_day		= $this->api->dayCount($date_start, $date_end);

					if($leave_type == '6'){ //Half day leave
						$diff_day = $diff_day*0.5;
					}
					if($leave_type == '5'){ //Sick Leave
						$diff_day = 0 ;
					}

					if($diff_day <= $sisa_cuti || $leave_type == '2'){ //unpaid leave gak ngecek sisa cuti

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

			            if($leave_type == 5 && ($document == '' || $document == null)){
			            	return 'lampirkan_file';
			            }else{
			            	$data = [

								'date_leave_start' 			=> $date_start,
								'date_leave_end' 			=> $date_end,
								'masterleave_id' 			=> $leave_type,
								'reason' 					=> $reason,
								'total_leave' 				=> $diff_day,
								'updated_at'				=> date("Y-m-d H:i:s")
								
							];

							$rs = $this->db->update("leave_absences", $data, "id = '".	$id."'");

							//update sisa jatah cuti
							if($rs){

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
	    		$where = " and a.employee_id = '".$employee."' ";
	    	}

	    	$dataijin = $this->db->query("select a.id, b.full_name, a.date_leave_start, a.date_leave_end, c.name as 			leave_name, a.reason, a.total_leave, 
							(case
							when a.status_approval = 1 then 'Waiting Approval'
							when a.status_approval = 2 then 'Approved'
							when a.status_approval = 3 then 'Rejected'
							 end) as status, b.direct_id
						from leave_absences a left join employees b on b.id = a.employee_id
						left join master_leaves c on c.id = a.masterleave_id
						where (a.employee_id = '".$islogin_employee."' or b.direct_id = '".$islogin_employee."') 
	                    ".$where." ")->result();  

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

    	/*$islogin_employee	= $_REQUEST['islogin_employee'];
    	$employee			= $_REQUEST['employee']; //filter employee


    	if($islogin_employee != ''){

    		$where=''; 
	    	if($employee != ''){
	    		$where = " and a.employee_id = '".$employee."' ";
	    	}

	    	$dataabsen = $this->db->query("select a.id, a.date_attendance, b.full_name, a.date_attendance_in, a.date_attendance_out, a.num_of_working_hours, if(a.is_late = 'Y','Late', '') as 'is_late_desc', 
				if(a.is_leaving_office_early = 'Y','Leaving Office Early','') as 'is_leaving_office_early_desc', b.direct_id 
				from time_attendances a left join employees b on b.id = a.employee_id
				where (a.employee_id = '".$islogin_employee."' or b.direct_id = '".$islogin_employee."') 
	                    ".$where." ")->result();  

	    	$response = [
	    		'status' 	=> 200,
				'message' 	=> 'Success',
				'data' 		=> $dataabsen
			];

    	}else{
    		$response = [
				'status' 	=> 401,
				'message' 	=> 'Failed',
				'error' 	=> 'Employee ID Login not found'
			];
    	}*/



    	$employee			= $_REQUEST['employee']; //filter employee

		$where=''; 
    	if($employee != ''){
    		$where = " where a.employee_id = '".$employee."' ";
    	}

    	$dataabsen = $this->db->query("select a.id, a.date_attendance, b.full_name, a.date_attendance_in, a.date_attendance_out, a.num_of_working_hours, if(a.is_late = 'Y','Late', '') as 'is_late_desc', 
			if(a.is_leaving_office_early = 'Y','Leaving Office Early','') as 'is_leaving_office_early_desc', b.direct_id 
			from time_attendances a left join employees b on b.id = a.employee_id
			 
                    ".$where." ")->result();  

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

			if($status != ''){
				if($id != ''){
					if($status == 'approve'){ 

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



}
