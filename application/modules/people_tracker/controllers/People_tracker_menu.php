<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class People_tracker_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "people_tracker_menu"; // identify menu
 	const  LABELMASTER				= "Menu People Tracker";
 	const  LABELFOLDER				= "people_tracker"; // module folder
 	const  LABELPATH				= "people_tracker_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "people_tracker"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "People Tracker"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Floating Crane","CCTV Code","CCTV Name"];
	
	/* Export */
	public $colnames 				= ["Date","Order No","Order Name","Floating Crane","Mother Vessel","Activity","Datetime Start","Datetime End","Total Time","Degree","Degree 2","PIC","Status"];
	public $colfields 				= ["date","order_no","order_name","floating_crane_name","mother_vessel_name","activity_name","datetime_start","datetime_end","total_time","degree","degree_2","pic","status_name"];




	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = [];


		$msemp 				= $this->db->query("select * from employees")->result(); 
		$field['selemp'] 	= $this->self_model->return_build_select2me($msemp,'','','','fldashemp','fldashemp','','','id','full_name',' ','','','',3,'-');

		$field['master_emp'] = $this->db->query("select * from employees order by full_name asc")->result(); 
		
		return $field;
	}

	//========================== Considering Already Fixed =======================//
 	/* Construct */
	public function __construct() {
        parent::__construct(); 
        
        
		# akses level
		$akses = $this->self_model->user_akses($this->module_name);
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]); 
		/*define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',$akses["detail"]);
		define('_USER_ACCESS_LEVEL_IMPORT',$akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT',$akses["eksport"]);*/
    }

	/* Module */
 	public $folder_name				= self::LABELFOLDER."/".self::LABELPATH; // module path
 	public $module_name				= self::LABELMODULE;
 	public $model_name				= self::LABELPATH."_model";

	/* Navigation */
 	public $parent_menu				= self::LABELFOLDER;
 	public $subparent_menu			= self::LABELNAVSEG1;
 	public $subparentitem_menu		= self::LABELNAVSEG2;
 	public $sub_menu 				= self::LABELMODULE;

	/* Label */
 	public $label_parent_modul		= self::LABELFOLDER;
 	public $label_subparent_modul	= self::LABELSUBPARENTSEG1;
 	public $label_subparentitem_modul	= self::LABELSUBPARENTSEG2;
 	public $label_modul				= self::LABELMASTER;
 	public $label_list_data			= "Daftar Data ".self::LABELMASTER;
 	public $label_add_data			= "Tambah Data ".self::LABELMASTER;
 	public $label_update_data		= "Edit Data ".self::LABELMASTER;
 	public $label_sukses_disimpan 	= "Data berhasil disimpan";
 	public $label_gagal_disimpan 	= "Data gagal disimpan";
 	public $label_delete_data		= "Hapus Data ".self::LABELMASTER;
 	public $label_sukses_dihapus 	= "Data berhasil dihapus";
 	public $label_gagal_dihapus 	= "Data gagal dihapus";
 	public $label_detail_data		= "Datail Data ".self::LABELMASTER;
 	public $label_import_data		= "Import Data ".self::LABELMASTER;
 	public $label_sukses_diimport 	= "Data berhasil diimport";
 	public $label_gagal_diimport 	= "Import data di baris : ";
 	public $label_export_data		= "Export";
 	public $label_gagal_eksekusi 	= "Eksekusi gagal karena ketiadaan data";

	//============================== Additional Method ==============================//


 	public function get_maps()
	{ 
		$post 	= $this->input->post(null, true);
		$empid 	= $post['empid'];
		$period = $post['period'];
		$tipe 	= $post['tipe'];


		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			if($tipe == 'absensi'){
				if($empid == '' && $period == ''){
			
					$rs =  $this->db->query("
							select 
								a.id,
								a.full_name AS nama,
								a.last_lat AS lat,
								a.last_long AS lng,
								ta_latest.date_attendance,
								ta_latest.work_location,
							    ta_latest.photo,
							    ta_latest.tipe,
							    ta_latest.datetime_attendance
							FROM employees a
							JOIN (
								SELECT t.employee_id, t.date_attendance, t.lat_checkin, t.long_checkin, t.work_location, 
							    t.photo,
							    (case 
							    when t.date_attendance_out is not null then 'checkout'
							    when t.date_attendance_in is not null then 'checkin'
							    else '-'
							    end) as tipe,
							    (case 
							    when t.date_attendance_out is not null then date_attendance_out
							    when t.date_attendance_in is not null then date_attendance_in
							    else '-'
							    end) as datetime_attendance
								FROM time_attendances t
								INNER JOIN (
									SELECT employee_id, MAX(date_attendance) AS max_date
									FROM time_attendances 
									where ((lat_checkin is not null and long_checkin is not null)or (lat_checkout is not null and long_checkout is not null))
									GROUP BY employee_id
								) tm ON t.employee_id = tm.employee_id AND t.date_attendance = tm.max_date
							) ta_latest
								ON ta_latest.employee_id = a.id
							WHERE 
								a.last_lat IS NOT NULL AND a.last_lat != ''
								AND a.last_long IS NOT NULL AND a.last_long != ''
							")->result();

				}else{
					$whr_emp=""; $whr_period=""; $whr_emp2=""; $whr_period2="";
					if($empid != ''){
						$empid = "'" . implode("','", $empid) . "'";
						$whr_emp = " and (a.employee_id in (".$empid."))";
						$whr_emp2 = " and (a.emp_id in (".$empid."))";
					}
					if($period != ''){
						$exp = explode(" - ",$period);
						$start = $exp[0];
						$end = $exp[1];

						$whr_period = " and ((a.date_attendance_in between '".$start."' and '".$end."') 
											or (a.date_attendance_out between '".$start."' and '".$end."') 
											)";
						$whr_period2 = " and (a.datetime between '".$start."' and '".$end."')";
					}


					$rs =  $this->db->query("
						select a.id,a.employee_id, b.full_name as nama,
							 a.date_attendance,  a.date_attendance_in, a.date_attendance_out
							, if(lat_checkout is null or lat_checkout='',lat_checkin,lat_checkout) as lat
							, if(long_checkout is null or long_checkout='',long_checkin,long_checkout) as lng
							,(case 
							    when a.date_attendance_out is not null then 'checkout'
							    when a.date_attendance_in is not null then 'checkin'
							    else '-'
							end) as tipe,
							(case 
							    when a.date_attendance_out is not null then date_attendance_out
							    when a.date_attendance_in is not null then date_attendance_in
							    else '-'
							end) as datetime_attendance
							, a.photo
							,a.work_location, 'time_attendances' as source
						from time_attendances a 
						left join employees b on b.id = a.employee_id
						where 
							(((lat_checkin is not null or lat_checkin != '') and (long_checkin is not null or long_checkin != '')) or ((lat_checkout is not null or lat_checkout != '') and (long_checkout is not null or long_checkout != ''))) 
							".$whr_emp.$whr_period."
								
					")->result();

					/*$rs =  $this->db->query("
						select a.id,a.employee_id, b.full_name as nama,
							 a.date_attendance,  a.date_attendance_in, a.date_attendance_out
							, if(lat_checkout is null or lat_checkout='',lat_checkin,lat_checkout) as lat
							, if(long_checkout is null or long_checkout='',long_checkin,long_checkout) as lng
							,(case 
							    when a.date_attendance_out is not null then 'checkout'
							    when a.date_attendance_in is not null then 'checkin'
							    else '-'
							end) as tipe,
							(case 
							    when a.date_attendance_out is not null then date_attendance_out
							    when a.date_attendance_in is not null then date_attendance_in
							    else '-'
							end) as datetime_attendance
							, a.photo
							,a.work_location, 'time_attendances' as source
						from time_attendances a 
						left join employees b on b.id = a.employee_id
						where 
							(((lat_checkin is not null or lat_checkin != '') and (long_checkin is not null or long_checkin != '')) or ((lat_checkout is not null or lat_checkout != '') and (long_checkout is not null or long_checkout != ''))) 
							".$whr_emp.$whr_period."
						
						UNION

						select a.id, 
							a.emp_id as employee_id, 
							b.full_name as nama, 
							DATE_FORMAT(a.datetime, '%Y-%m-%d') as date_attendance, 
							a.datetime as date_attendance_in, 
							a.datetime as date_attendance_out, 
							a.latitude as lat, 
							a.longitude as lng,
							'' as tipe,
							a.datetime as datetime_attendance,
							'' as photo, 
							'' as work_location,
							'tracker_history' as source
						from tracker_history a
						left join employees b on b.id = a.emp_id
						where ((a.latitude is not null or a.latitude != '') and (a.longitude is not null or a.longitude != ''))
								".$whr_emp2.$whr_period2."
								
					")->result();*/

				}

				echo json_encode($rs);

			}else{ //tracker
				$whr_emp2=""; $whr_period2="";
				if($empid != ''){
					$empid = "'" . implode("','", $empid) . "'";
					$whr_emp2 = " and (a.emp_id in (".$empid."))";
				}
				if($period != ''){
					$exp = explode(" - ",$period);
					$start = $exp[0];
					$end = $exp[1];

					$whr_period2 = " and (a.datetime between '".$start."' and '".$end."')";
				}	


				$rs =  $this->db->query("
						select a.id, 
							a.emp_id as employee_id, 
							b.full_name as nama, 
							DATE_FORMAT(a.datetime, '%Y-%m-%d') as date_attendance, 
							a.datetime as date_attendance_in, 
							a.datetime as date_attendance_out, 
							a.latitude as lat, 
							a.longitude as lng,
							'' as tipe,
							a.datetime as datetime_attendance,
							'' as photo, 
							'' as work_location,
							'tracker_history' as source,
							a.heading
						from tracker_history a
						left join employees b on b.id = a.emp_id
						where ((a.latitude is not null or a.latitude != '') and (a.longitude is not null or a.longitude != ''))
						".$whr_emp2.$whr_period2."
								
				")->result();



				/// get detail tracker
			    $dt = '';
			    if (!empty($rs)) {
			        foreach ($rs as $row) {
			            $dt .= '<tr data-id="'.$row->id.'">';
			            $dt .= '<td style="font-size:8px">'.$row->nama.'</td>';
			            $dt .= '<td style="font-size:8px">'.$row->datetime_attendance.'</td>';
			            $dt .= '</tr>';
			        }
			    } else {
			        $dt .= '<tr><td colspan="2" class="text-center text-muted">No data</td></tr>';
			    }

				/// end get detail tracker


				$valResult = [
		            'data_maps'   => $rs,
		            'data_table'  => $dt
		        ];




				echo json_encode($valResult);

			}
			
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}



}
