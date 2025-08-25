<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "absensi_menu"; // identify menu
 	const  LABELMASTER				= "Menu Absensi Karyawan";
 	const  LABELFOLDER				= "time_attendance"; // module folder
 	const  LABELPATH				= "absensi_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "time_attendance"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Day","Date","Employee Name","Absence Type","Time In","Time Out","Attendance IN","Attendance OUT","Late Desc","Leave Desc","Num of Working Hours"];

	
	/* Export */
	public $colnames 				= ["ID","Date","Employee Name","Absence Type","Time In","Time Out","Attendance IN","Attendance OUT","Late Desc","Leave Desc","Num of Working Hours"];
	public $colfields 				= ["id","date_attendance","full_name","attendance_type","time_in","time_out","date_attendance_in","date_attendance_out","is_late_desc","is_leaving_office_early_desc","num_of_working_hours"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$datetimeNow = date("Y-m-d H:i:s"); 
		$dateNow = date("Y-m-d");
		$period = date("Y-m");
		$tgl = date("d");
		$date_attendance = $dateNow;
		//$dateTomorrow='';


		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		/*$whr='';
		if($getdata[0]->id_groups != 1 && $getdata[0]->id_groups != 4){ //bukan super user && bukan HR admin
			$whr=' and id = "'.$karyawan_id.'" or direct_id = "'.$karyawan_id.'" ';
		}*/

		$empData = $this->db->query("select full_name, shift_type from employees where id = '".$karyawan_id."'")->result(); 
		$emp_shift_type=1; $time_in=""; $time_out=""; $attendance_type="";
		if($empData[0]->shift_type == 'Reguler'){
			$dt = $this->db->query("select * from master_shift_time where shift_type = 'Reguler' ")->result(); 

		}else if($empData[0]->shift_type == 'Shift'){
			// $data_attendances = $this->db->query("select * from time_attendances where date_attendance = '".$dateNow."' and employee_id = '".$karyawan_id."'")->result(); 
			// //jika sudah ada absen hari ini, maka akan cek shift besok, kalau dapet shift 3, maka bisa checkin. Karna shift 3 jadwalnya tengah malam, jadi bisa checkin di tgl sebelumnya.
			// if((!empty($data_attendances)) && $data_attendances[0]->date_attendance_in != null && $data_attendances[0]->date_attendance_in != '0000-00-00 00:00:00' && $data_attendances[0]->date_attendance_out != null && $data_attendances[0]->date_attendance_out != '0000-00-00 00:00:00'){

			// 	$dateTomorrow = date("Y-m-d", strtotime($dateNow . " +1 day"));
			// 	$period  = date('Y-m', strtotime($dateTomorrow));
			// 	$tgl = date('d', strtotime($dateTomorrow));
			// }

			// $dt = $this->db->query("select a.*, b.periode, b.`".$tgl."` as 'shift', c.time_in, c.time_out, c.name 
			// 		from shift_schedule a left join group_shift_schedule b on b.shift_schedule_id = a.id 
			// 		left join master_shift_time c on c.shift_id = b.`".$tgl."`
			// 		where b.employee_id = '".$karyawan_id."' and a.period = '".$period."' ")->result(); 
			
			// if($dt[0]->shift != 3){ //bukan shift 3, tidak bisa checkin di tgl sebelumnya
			// 	//$emp_shift_type=0;
			// 	$period = date("Y-m");
			// 	$tgl = date("d");
			// 	$dt = $this->db->query("select a.*, b.periode, b.`".$tgl."` as 'shift', c.time_in, c.time_out, c.name 
			// 		from shift_schedule a left join group_shift_schedule b on b.shift_schedule_id = a.id 
			// 		left join master_shift_time c on c.shift_id = b.`".$tgl."`
			// 		where b.employee_id = '".$karyawan_id."' and a.period = '".$period."' ")->result();

			// }else{
			// 	$date_attendance = $dateTomorrow;
			// }




			/// NEW SCRIPT
			$datetimemax_shift3 = $dateNow.' 08:00:00';
			if($datetimeNow < $datetimemax_shift3){ //brarti dia sdg checkin shift 3 di tgl sebelumnya (late)
				$dateYesterday = date("Y-m-d", strtotime($dateNow . " -1 day"));
				$period  = date('Y-m', strtotime($dateYesterday));
			 	$tgl = date('d', strtotime($dateYesterday));
			 	$date_attendance = $dateYesterday;
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
			where b.employee_id = '".$karyawan_id."'
			and a.period = '".$period."'
			")->result(); 

			if($dt[0]->shift == ""){
				$emp_shift_type=0;
			}

			
			/// END NEW SCRIPT

		}else{ //tidak ada shift type
			$emp_shift_type=0;
		} 

		if($emp_shift_type==1){
			$time_in 			= $dt[0]->time_in;
			$time_out 			= $dt[0]->time_out;
			$attendance_type 	= $dt[0]->name;
		}



		$field = [];
		$field['empid'] = $karyawan_id; 	
		/*$field['txtdateattendance']		= $this->self_model->return_build_txt('','date_attendance','date_attendance');*/
		$field['txtdateattendance']		= $this->self_model->return_build_txt($date_attendance,'date_attendance','date_attendance','','','readonly');
		/*$msemp 							= $this->db->query("select * from employees where status_id = 1 ".$whr." order by full_name asc")->result(); */
		/*$field['selemployee'] 			= $this->self_model->return_build_select2me($msemp,'','','','employee','employee','','','id','full_name',' ','','','',3,'-');*/
		$field['selemployee'] 			= $this->self_model->return_build_txt($empData[0]->full_name,'employee','employee','','','readonly');
		/*$field['txtimein'] 				= $this->self_model->return_build_txt('','time_in','time_in','','','readonly');*/
		$field['txtimein'] 				= $this->self_model->return_build_txt($time_in,'time_in','time_in','','','readonly');
		$field['txtattendancein'] 		= $this->self_model->return_build_txt($datetimeNow,'attendance_in','attendance_in','','','readonly');
		$field['txtlatedesc'] 			= $this->self_model->return_build_txt('','late_desc','late_desc','','','readonly');
		/*$field['txtemptype'] 			= $this->self_model->return_build_txt('','emp_type','emp_type','','','readonly');*/
		$field['txtemptype'] 			= $this->self_model->return_build_txt($attendance_type,'emp_type','emp_type','','','readonly');
		/*$field['txtimeout'] 			= $this->self_model->return_build_txt('','time_out','time_out','','','readonly');*/
		$field['txtimeout'] 			= $this->self_model->return_build_txt($time_out,'time_out','time_out','','','readonly');
		/*$field['txtattendanceout'] 		= $this->self_model->return_build_txt('','attendance_out','attendance_out');*/
		$field['txtattendanceout'] 		= $this->self_model->return_build_txt('','attendance_out','attendance_out','','','readonly');
		$field['txtleavingearlydesc']	= $this->self_model->return_build_txt('','leaving_early_desc','leaving_early_desc','','','readonly');
		$field['txtdesc'] 				= $this->self_model->return_build_txtarea('','description','description');
		
		

		$raw = [
		    ['id' => 'wfo', 'name' => 'WFO'],
		    ['id' => 'wfh', 'name' => 'WFH'],
		    ['id' => 'onsite', 'name' => 'On Site']
		];
		$msLoc = [];
		foreach ($raw as $row_raw) {
		    $obj = new stdClass();
		    $obj->id = $row_raw['id'];
		    $obj->name = $row_raw['name'];
		    $msLoc[] = $obj;
		}
		$field['selloc'] 			= $this->self_model->return_build_select2me($msLoc,'','','','location','location','','','id','name',' ','','','',3,'-');



		
		return $field;
	}

	//========================== Considering Already Fixed =======================//
 	/* Construct */
	public function __construct() {
        parent::__construct();
		# akses level
		$akses = $this->self_model->user_akses($this->module_name);
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',$akses["detail"]);
		define('_USER_ACCESS_LEVEL_IMPORT',$akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT',$akses["eksport"]);
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


 	public function getDataEmp(){
		$post = $this->input->post(null, true);
		$empid = $post['empid'];

		$rs =  $this->self_model->getDataEmployee($empid);
		

		echo json_encode($rs);
	}



}
