<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dash_reimbursement_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "dash_reimbursement_menu"; // identify menu
 	const  LABELMASTER				= "Menu Dashboard Reimbursement";
 	const  LABELFOLDER				= "dashboard"; // module folder
 	const  LABELPATH				= "dash_reimbursement_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "dashboard"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Dashboard"; // 
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


		$msemp 				= $this->db->query("select * from employees where status_id = 1 order by full_name asc")->result(); 
		$field['selemp'] 	= $this->self_model->return_build_select2me($msemp,'','','','fldashemp','fldashemp','','','id','full_name',' ','','','',3,'-');

		$msdiv 				= $this->db->query("select * from divisions order by name asc")->result(); 
		$field['seldiv'] 	= $this->self_model->return_build_select2me($msdiv,'','','','fldiv','fldiv','','','id','name',' ','','','',1,'-');

		$field['master_emp'] = $this->db->query("select * from employees where status_id = 1 order by full_name asc")->result(); 
		
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


 	public function get_data_total(){
 		$post = $this->input->post(null, true);
		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" where b.division_id = '".$fldiv."'";
		}


		$ttl_reimburs = $this->db->query("select count(a.id) as ttl from medicalreimbursements a left join employees b on b.id = a.employee_id left join divisions c on c.id = b.division_id ".$whereDiv." ")->result(); 

		$ttl_amountreimburs = $this->db->query("select sum(a.nominal_reimburse) as ttl from medicalreimbursements a left join employees b on b.id = a.employee_id left join divisions c on c.id = b.division_id ".$whereDiv." ")->result(); 

		$ttl_pertype = $this->db->query("select SUM(CASE WHEN reimburs_type_id = 1 THEN 1 ELSE 0 END) AS total_rawatjalan, 
			SUM(CASE WHEN reimburs_type_id = 2 THEN 1 ELSE 0 END) AS total_rawatinap, 
			SUM(CASE WHEN reimburs_type_id = 3 THEN 1 ELSE 0 END) AS total_kacamata, 
			SUM(CASE WHEN reimburs_type_id = 4 THEN 1 ELSE 0 END) AS total_persalinan
			from medicalreimbursements a left join employees b on b.id = a.employee_id
			left join divisions c on c.id = b.division_id
			".$whereDiv." ")->result(); 
		$total_ttl_amountreimburs = $ttl_amountreimburs[0]->ttl;
		if(empty($ttl_amountreimburs[0]->ttl)){
			$total_ttl_amountreimburs = 0;
		}

		$total_rawatinap = $ttl_pertype[0]->total_rawatinap;
		if(empty($ttl_pertype[0]->total_rawatinap)){
			$total_rawatinap = 0;
		}
		$total_kacamata = $ttl_pertype[0]->total_kacamata;
		if(empty($ttl_pertype[0]->total_kacamata)){
			$total_kacamata = 0;
		}
		$total_persalinan = $ttl_pertype[0]->total_persalinan;
		if(empty($ttl_pertype[0]->total_persalinan)){
			$total_persalinan = 0;
		}
		$total_rawatjalan = $ttl_pertype[0]->total_rawatjalan;
		if(empty($ttl_pertype[0]->total_rawatjalan)){
			$total_rawatjalan = 0;
		}
		


		$rs = array(
			'ttl_reimburs' 				=> $ttl_reimburs[0]->ttl,
			'ttl_amount_reimburs'		=> $total_ttl_amountreimburs,
			'total_rawatinap'			=> $total_rawatinap,
			'total_kacamata'			=> $total_kacamata,
			'total_persalinan'			=> $total_persalinan,
			'total_rawatjalan'			=> $total_rawatjalan
		);


		
		echo json_encode($rs);
 	}


 	public function get_data_reimFor(){
	    $post  = $this->input->post(null, true);
	    $fldiv = $post['fldiv'];

	    // Query dengan filter division opsional
	    $data_emp = $this->db->query("
	        select 
	            c.id AS reimbforid,
	            c.name AS reimbforname,
	            COUNT(
	                CASE 
	                    WHEN ? IS NULL OR ? = '' THEN a.id
	                    WHEN e.id = ? THEN a.id
	                END
	            ) AS total
	        FROM master_reimbursfor_type c
	        LEFT JOIN medicalreimbursements a 
	            ON a.reimburse_for = c.id
	        LEFT JOIN employees d 
	            ON d.id = a.employee_id
	        LEFT JOIN divisions e 
	            ON e.id = d.division_id
	        GROUP BY c.id, c.name
	        ORDER BY c.name
	    ", [$fldiv, $fldiv, $fldiv])->result();

	    // Ubah hasil query ke format pie chart
	    $pie_data = [];
	    foreach ($data_emp as $row) {
	        $pie_data[] = [
	            'label' => $row->reimbforname,
	            'value' => (int) $row->total
	        ];
	    }

	    echo json_encode($pie_data);
	}



 	public function get_data_reimFor_old(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];



		$data_emp = $this->db->query(" select 
            c.id AS reimbforid,
            c.name AS reimbforname,
            COUNT(
                CASE 
                    WHEN ? IS NULL OR ? = '' THEN a.id
                    WHEN e.id = ? THEN a.id
                END
            ) AS total
        FROM master_reimbursfor_type c
        LEFT JOIN medicalreimbursements a 
            ON a.reimburse_for = c.id
        LEFT JOIN employees d 
            ON d.id = a.employee_id
        LEFT JOIN divisions e 
            ON e.id = d.division_id
        GROUP BY c.id, c.name
        ORDER BY c.name")->result(); 

		

		
		$rs = array(
			'ttl_boomer' 	=> $boomer,
			'ttl_gen_x' 	=> $gen_x,
			'ttl_gen_mill'	=> $gen_mill,
			'ttl_gen_z'		=> $gen_z,
			'ttl_gen_alpha'	=> $gen_alpha,
			'ttl_unkgen' 	=> $unkgen
		);
		
		echo json_encode($rs);

 	}

 	public function get_data_empbyMaritalStatus(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" and division_id = '".$fldiv."'";
		}


		$data_emp = $this->db->query("select * from employees where status_id = 1 ".$whereDiv."")->result(); 

		$ttl_tk0=0; 	$ttl_tk2=0; 	$ttl_k0=0;			$ttl_k1=0; 		$ttl_k3=0;
		$ttl_tk1=0; 	$ttl_tk3=0; 	$ttl_undefined=0; 	$ttl_k2=0; 

		foreach($data_emp as $row){
			$maritalStat = $row->marital_status_id;

			if ($maritalStat == 1) {
		        $ttl_tk0 += 1;
		    } elseif ($maritalStat == 2) {
		        $ttl_tk1 += 1;
		    } elseif ($maritalStat == 3) {
		        $ttl_tk2 += 1;
		    } elseif ($maritalStat == 4) {
		        $ttl_tk3 += 1;
		    } elseif ($maritalStat == 5) {
	         	$ttl_k0 += 1;
		    } elseif ($maritalStat == 6) {
	         	$ttl_k1 += 1;
		    }elseif ($maritalStat == 7) {
	         	$ttl_k2 += 1;
		    }elseif ($maritalStat == 8) {
	         	$ttl_k3 += 1;
		    } else {
		        $ttl_undefined += 1;
		    }
		}

		
		$rs = array(
			'ttl_tk0' 	=> $ttl_tk0,
			'ttl_tk1' 	=> $ttl_tk1,
			'ttl_tk2'	=> $ttl_tk2,
			'ttl_tk3'	=> $ttl_tk3,
			'ttl_k0'	=> $ttl_k0,
			'ttl_k1'	=> $ttl_k1,
			'ttl_k2'	=> $ttl_k2,
			'ttl_k3'	=> $ttl_k3,
			'ttl_undefined' => $ttl_undefined
		);
		
		echo json_encode($rs);

 	}


 	public function get_data_monthlyReimbSummary(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		// buat where dinamis
	    $whereDiv = "";
	    if (!empty($fldiv)) { 
	        $whereDiv = " WHERE a.division_id = '".$this->db->escape_str($fldiv)."' ";
	    }

		
		$rs = $this->db->query("select
					aa.date_reimbursment, a.division_id, b.name as division_name,
					SUM(CASE WHEN aa.status_id = '1' THEN 1 ELSE 0 END) AS total_waitingapproval,
					SUM(CASE WHEN aa.status_id = '2' THEN 1 ELSE 0 END) AS total_approve,
				    SUM(CASE WHEN aa.status_id = '3' THEN 1 ELSE 0 END) AS total_reject,
					COUNT(*) AS total_reimburs
				FROM
				medicalreimbursements aa 
					left join employees a on a.id = aa.employee_id
					left join divisions b on b.id = a.division_id
					$whereDiv
				GROUP BY
					DATE_FORMAT(aa.date_reimbursment, '%Y-%m')
				ORDER BY date_reimbursment ASC")->result(); 

		$date_reimbursment=[]; $total_waitingapproval=[]; $total_approve=[]; $total_reject=[];
		foreach($rs as $row){
			$date_reimbursment[] 		= $row->date_reimbursment;
			$total_waitingapproval[] 	= $row->total_waitingapproval;
			$total_approve[]			= $row->total_approve;
			$total_reject[]				= $row->total_reject;
		}


		$data = array(
			'date_reimbursment' 	=> $date_reimbursment,
			'total_waitingapproval' => $total_waitingapproval,
			'total_approve' 		=> $total_approve,
			'total_reject' 			=> $total_reject
		);


		echo json_encode($data);
 	}


 	public function get_data_byDiv(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" and c.id = '".$fldiv."'";
		}


    	$rs = $this->db->query("select 
								    c.id AS division_id,
								    c.name AS division_name,
								    COUNT(a.id) AS total
								FROM divisions c
								LEFT JOIN employees b ON b.division_id = c.id
								LEFT JOIN medicalreimbursements a ON a.employee_id = b.id
								where 1=1 ".$whereDiv."
								GROUP BY c.id, c.name
								ORDER BY c.name
								")->result(); 

		$division_name=[]; $total=[]; 
		foreach($rs as $row){
			$division_name[] 	= $row->division_name;
			$total[] 			= $row->total;
			
		}


		$data = array(
			'division_name' => $division_name,
			'total'			=> $total
		);


		echo json_encode($data);


 	}



 	public function get_data_projectSummary(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" and b.division_id = '".$fldiv."'";
		}


    	$rs = $this->db->query("select 
								    c.id AS division_id,
								    c.name AS division_name,
								    SUM(CASE WHEN a.status_id = 1 THEN 1 ELSE 0 END) AS total_open,
								    SUM(CASE WHEN a.status_id = 2 THEN 1 ELSE 0 END) AS total_inprogress,
								    SUM(CASE WHEN a.status_id = 3 THEN 1 ELSE 0 END) AS total_closed
								FROM divisions c
								LEFT JOIN employees b ON b.division_id = c.id
								LEFT JOIN tasklist a ON a.employee_id = b.id and a.status_id != '' ".$whereDiv."
								GROUP BY c.id, c.name

						 ")->result(); 

		$division_name=[]; $total_open=[]; $total_inprogress=[]; $total_closed=[]; 
		foreach($rs as $row){
			$division_name[] 		= $row->division_name;
			$total_open[] 			= $row->total_open;
			$total_inprogress[] 	= $row->total_inprogress;
			$total_closed[] 		= $row->total_closed;
			
		}


		$data = array(
			'division_name' 	=> $division_name,
			'total_open'		=> $total_open,
			'total_inprogress' 	=> $total_inprogress,
			'total_closed' 		=> $total_closed
		);


		echo json_encode($data);

 	}


 	public function get_data_bySubtype(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		if (!empty($fldiv)) {
		    // ada filter divisi
		    $sql = "
		        select 
		            c.id AS subtypeid,
		            c.fulltipe AS subtypename,
		            COUNT(
		                CASE WHEN b.division_id = '".$fldiv."' THEN a.id END
		            ) AS total,
		            '".$fldiv."' AS division_id
		        FROM (
		            SELECT aa.id, CONCAT(bb.name,' - ',aa.name) AS fulltipe 
		            FROM master_reimburs_subtype aa 
		            LEFT JOIN master_reimburs_type bb ON bb.id = aa.reimburs_type_id
		        ) c
		        LEFT JOIN reimbursement_detail ab 
		            ON ab.subtype_id = c.id
		        LEFT JOIN medicalreimbursements a 
		            ON a.id = ab.reimbursement_id
		        LEFT JOIN employees b 
		            ON b.id = a.employee_id
		        GROUP BY c.id, c.fulltipe
		        ORDER BY c.fulltipe
		    ";
		} else {
		    // tidak ada filter divisi → hitung semua
		    $sql = "
		        select 
		            c.id AS subtypeid,
		            c.fulltipe AS subtypename,
		            COUNT(a.id) AS total,
		            NULL AS division_id
		        FROM (
		            SELECT aa.id, CONCAT(bb.name,' - ',aa.name) AS fulltipe 
		            FROM master_reimburs_subtype aa 
		            LEFT JOIN master_reimburs_type bb ON bb.id = aa.reimburs_type_id
		        ) c
		        LEFT JOIN reimbursement_detail ab 
		            ON ab.subtype_id = c.id
		        LEFT JOIN medicalreimbursements a 
		            ON a.id = ab.reimbursement_id
		        LEFT JOIN employees b 
		            ON b.id = a.employee_id
		        GROUP BY c.id, c.fulltipe
		        ORDER BY c.fulltipe
		    ";
		}

		$result = $this->db->query($sql)->result_array();

		/*$subtypename=[]; $total=[]; 
		foreach($result as $row){
			$subtypename[] 	= $row->subtypename;
			$total[] 		= $row->total;
			
		}*/
		$subtypename=[]; 
		$total=[]; 
		foreach($result as $row){
		    $subtypename[] = $row['subtypename'];
		    $total[]       = $row['total'];
		}


		$data = array(
			'subtypename' 	=> $subtypename,
			'total'			=> $total
		);


		echo json_encode($data);


 	}


 	public function get_data_monthlyReimbAmount()
	{
	    $post  = $this->input->post(null, true);
		$fldiv = isset($post['fldiv']) ? trim($post['fldiv']) : "";

		if (empty($fldiv)) { // all div
		    $sql = "
		        select 
		            CONCAT(YEAR(CURDATE()), '-', LPAD(b.bln, 2, '0')) AS tahun_bulan,
		            COALESCE(SUM(m.nominal_reimburse), 0) AS nominal_raw,
		            FORMAT(COALESCE(SUM(m.nominal_reimburse), 0), 2) AS nominal_reimburse
		        FROM (
		            SELECT 1 AS bln UNION ALL
		            SELECT 2 UNION ALL
		            SELECT 3 UNION ALL
		            SELECT 4 UNION ALL
		            SELECT 5 UNION ALL
		            SELECT 6 UNION ALL
		            SELECT 7 UNION ALL
		            SELECT 8 UNION ALL
		            SELECT 9 UNION ALL
		            SELECT 10 UNION ALL
		            SELECT 11 UNION ALL
		            SELECT 12
		        ) b
		        LEFT JOIN medicalreimbursements m 
		            ON MONTH(m.date_reimbursment) = b.bln 
		           AND YEAR(m.date_reimbursment) = YEAR(CURDATE())
		        LEFT JOIN employees n 
		            ON n.id = m.employee_id
		        GROUP BY b.bln
		        ORDER BY b.bln
		    ";
		} else { // with filter
		    $sql = "
		        select 
		            CONCAT(YEAR(CURDATE()), '-', LPAD(b.bln, 2, '0')) AS tahun_bulan,
		            COALESCE(SUM(
		                CASE WHEN n.division_id = '".$fldiv."' THEN m.nominal_reimburse ELSE 0 END
		            ), 0) AS nominal_raw,
		            FORMAT(COALESCE(SUM(
		                CASE WHEN n.division_id = '".$fldiv."' THEN m.nominal_reimburse ELSE 0 END
		            ), 0), 2) AS nominal_reimburse
		        FROM (
		            SELECT 1 AS bln UNION ALL
		            SELECT 2 UNION ALL
		            SELECT 3 UNION ALL
		            SELECT 4 UNION ALL
		            SELECT 5 UNION ALL
		            SELECT 6 UNION ALL
		            SELECT 7 UNION ALL
		            SELECT 8 UNION ALL
		            SELECT 9 UNION ALL
		            SELECT 10 UNION ALL
		            SELECT 11 UNION ALL
		            SELECT 12
		        ) b
		        LEFT JOIN medicalreimbursements m 
		            ON MONTH(m.date_reimbursment) = b.bln 
		           AND YEAR(m.date_reimbursment) = YEAR(CURDATE())
		        LEFT JOIN employees n 
		            ON n.id = m.employee_id
		        GROUP BY b.bln
		        ORDER BY b.bln
		    ";
		}

		$rs = $this->db->query($sql)->result();

	    $periode = [];
	    $nominal_raw = [];
	    $nominal_reimburse = [];
	    foreach ($rs as $row) {
	        $periode[]           = $row->tahun_bulan;
	        $nominal_raw[]       = $row->nominal_raw;
	        $nominal_reimburse[] = $row->nominal_reimburse;
	    }

	    $data = array(
	        'periode'            => $periode,
	        'nominal_raw'        => $nominal_raw,
	        'nominal_reimburse'  => $nominal_reimburse
	    );

	    echo json_encode($data);
	}


 	public function get_data_monthlyReimbAmount_old(){
 		$post = $this->input->post(null, true);
 		$fldiv = $post['fldiv'];

		$filterDiv = "";
		if (!empty($fldiv)) {
		    $filterDiv = " AND n.division_id = '".$fldiv."' ";
		}


    	$rs = $this->db->query("select 
								    CONCAT(YEAR(CURDATE()), '-', LPAD(b.bln, 2, '0')) AS tahun_bulan,
								    COALESCE(SUM(m.nominal_reimburse), 0) AS nominal_raw,
								    FORMAT(COALESCE(SUM(m.nominal_reimburse), 0), 2) AS nominal_reimburse
								FROM (
								    SELECT 1 AS bln UNION ALL
								    SELECT 2 UNION ALL
								    SELECT 3 UNION ALL
								    SELECT 4 UNION ALL
								    SELECT 5 UNION ALL
								    SELECT 6 UNION ALL
								    SELECT 7 UNION ALL
								    SELECT 8 UNION ALL
								    SELECT 9 UNION ALL
								    SELECT 10 UNION ALL
								    SELECT 11 UNION ALL
								    SELECT 12
								) b
								LEFT JOIN medicalreimbursements m 
								    ON MONTH(m.date_reimbursment) = b.bln 
								   AND YEAR(m.date_reimbursment) = YEAR(CURDATE())
								   left join employees n on n.id = m.employee_id
								   ".$filterDiv."
								GROUP BY b.bln
								ORDER BY b.bln;
								")->result(); 

		$periode=[]; $nominal_reimburse=[]; 
		foreach($rs as $row){
			$periode[] 				= $row->tahun_bulan;
			$nominal_reimburse[] 	= $row->nominal_raw;
			
		}


		$data = array(
			'periode' 			=> $periode,
			'nominal_reimburse'	=> $nominal_reimburse
		);


		echo json_encode($data);


 	}



}
