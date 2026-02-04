<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hitung_gaji_os_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "hitung_gaji_os_menu"; // identify menu
 	const  LABELMASTER				= "Menu Hitung Gaji Outsource";
 	const  LABELFOLDER				= "payroll_outsource"; // module folder
 	const  LABELPATH				= "hitung_gaji_os_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "payroll_outsource"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	


	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Bulan Penggajian","Tahun Penggajian","Karyawan","Project"];

	
	/* Export */
	public $colnames 				= ["ID","Bulan Penggajian","Tahun Penggajian","Karyawan","Project"];
	public $colfields 				= ["id","periode_bulan_name","periode_tahun","full_name","project_name"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		
		$karyawan_id = $_SESSION['worker'];
		$whr='';
		if($_SESSION['role'] != 1){ //bukan super user
			$whr=' and id = "'.$karyawan_id.'" or direct_id = "'.$karyawan_id.'" ';
		}



		$field = [];

		$field['txtxx']	= $this->self_model->return_build_txt('','xx','xx');
		$field['txtgajibulanan']	= $this->self_model->return_build_txt('','period_end','period_end','','','readonly');
		$field['txtgajiharian']	= $this->self_model->return_build_txt('','period_end','period_end','','','readonly');

		$field['txtperiodstart']	= $this->self_model->return_build_txt('','period_start','period_start','','','readonly');
		$field['txtperiodend']		= $this->self_model->return_build_txt('','period_end','period_end','','','readonly');
		$field['txtyear']			= $this->self_model->return_build_txt('','penggajian_year','penggajian_year','','','required');
		$field['txtperiodstart_edit_gaji']	= $this->self_model->return_build_txt('','period_start_edit_gaji','period_start_edit_gaji','','','readonly');
		$field['txtperiodend_edit_gaji']		= $this->self_model->return_build_txt('','period_end_edit_gaji','period_end_edit_gaji','','','readonly');
		$field['txtyear_edit_gaji']			= $this->self_model->return_build_txt('','penggajian_year_edit_gaji','penggajian_year_edit_gaji');
		
		$msmonth 					= $this->db->query("select * from master_month order by id asc")->result(); 
		$field['selmonth'] 			= $this->self_model->return_build_select2me($msmonth,'','','','penggajian_month','penggajian_month','','','id','name_indo',' ','','','required',3,'-');
		$field['selmonth_edit_gaji'] 			= $this->self_model->return_build_select2me($msmonth,'','','','penggajian_month_edit_gaji','penggajian_month_edit_gaji','','','id','name_indo',' ','','','',3,'-');
		/*$field['is_all_employee'] 	= $this->self_model->return_build_radio('Ya', [['Ya','Ya'],['Tidak','Tidak']], 'is_all_employee', '', 'inline');*/
		$msemp 						= $this->db->query("select * from employees where emp_source = 'outsource' and status_id = 1 order by full_name asc")->result(); 
		$field['selemployeeids'] 	= $this->self_model->return_build_select2me($msemp,'multiple','','','employeeIds[]','employeeIds','','','id','full_name',' ','','','',3,'-');
		
		$field['selflemployee'] 	= $this->self_model->return_build_select2me($msemp,'','','','flemployee','flemployee','','','id','full_name',' ','','','',3,'-');

		$field['is_all_project'] 	= $this->self_model->return_build_radio('Semua', [['Semua','Semua'],['Sebagian','Sebagian'],['Karyawan','Per Karyawan']], 'is_all_project', '', 'inline');
		$msproject 					= $this->db->query('select id,
										(case when jenis_pekerjaan != "" and lokasi != "" then concat(code," (",lokasi," - ",jenis_pekerjaan,")")
										when jenis_pekerjaan != "" and lokasi = "" then concat(code," (",jenis_pekerjaan,")")
										when lokasi != "" and jenis_pekerjaan = "" then concat(code," (",lokasi,")")
										else code end
										) as project_desc, project_name
										from project_outsource order by code asc')->result(); 
		$field['selprojectids'] 	= $this->self_model->return_build_select2me($msproject,'multiple','','','projectIds[]','projectIds','','','id','project_name',' ','','','',3,'-');
		$field['selproject_edit_gaji'] 	= $this->self_model->return_build_select2me($msproject,'','','','project_edit_gaji','project_edit_gaji','','','id','project_name',' ','','','',3,'-');



		$msproject = $this->db->query("select id,project_name as project_label FROM project_outsource ORDER BY project_name ASC ")->result();

		$field['selflproject'] = $this->self_model->return_build_select2me(
			$msproject,
			'',
			'',
			'',
			'flproject',
			'flproject',
			'',
			'',
			'id',
			'project_label',
			' ',
			'',
			'',
			'',
			3,
			'-'
		);
		
		
		
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


 	public function gengajiosrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);

			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewGajiOSRow($row);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewGajiOSRow($row,$id,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}


	public function getPayrollReport_pdf()
	{
	    $this->load->library('html_pdf');
	    $this->load->helper('global');

	    if (!empty($_GET['flproject'])) {

	        $sql = "
	            select a.*, b.full_name, c.name_indo AS periode_bulan_name,
	                   b.emp_code, d.project_name, e.name AS job_title_name,
	                   f.tanggal_pembayaran_lembur
	            FROM payroll_slip a 
	            LEFT JOIN employees b ON b.id = a.employee_id 
	            LEFT JOIN master_month c ON c.id = a.periode_bulan
	            LEFT JOIN project_outsource d ON d.id = b.project_id
	            LEFT JOIN master_job_title_os e ON e.id = b.job_title_id
	            LEFT JOIN data_customer f ON f.id = d.customer_id
	            WHERE b.project_id = ".$_GET['flproject']."
	            ORDER BY b.full_name
	        ";

	        $data = $this->db->query($sql)->result();

	        if (empty($data)) {
	            echo "Slip Gaji tidak ditemukan";
	            return;
	        }

	        $pdfData = [
	            'employees' => $data
	        ];

	        $pdfBinary = $this->html_pdf->render_to_string_portrait(
	            'pdf/gaji_os_perproject',
	            $pdfData
	        );

	        if (ob_get_level()) ob_end_clean();

	        header("Content-Type: application/pdf");
	        header("Content-Disposition: attachment; filename=GAJI - ".$data[0]->project_name.".pdf");
	        echo $pdfBinary;
	        exit;

	    } else {
	        echo "Project tidak dipilih";
	    }
	}



	public function getPayrollReport_pdf_old()
	{
		$this->load->library('html_pdf');
		$this->load->helper('global');


		
		if(isset($_GET['flproject']) && $_GET['flproject'] != '' && $_GET['flproject'] != 0){

		    $sql = "
		        select a.*, b.full_name, c.name_indo as periode_bulan_name, b.emp_code, d.project_name, e.name as job_title_name, f.tanggal_pembayaran_lembur
				from payroll_slip a 
				left join employees b on b.id = a.employee_id 
				left join master_month c on c.id = a.periode_bulan
				left join project_outsource d on d.id = b.project_id
				left join master_job_title_os e on e.id = b.job_title_id
				left join data_customer f on f.id = d.customer_id
				where b.project_id = ".$_GET['flproject']."
		    ";

		    $data = $this->db->query($sql)->result();

		    if(!empty($data)){
		    	$pdfData = [
				    'periode_bulan'      		=> $data[0]->periode_bulan_name,
				    'periode_tahun'      		=> $data[0]->periode_tahun,
				    'nik'    					=> $data[0]->emp_code,
				    'emp_name'       			=> $data[0]->full_name,
				    'project_name'    			=> $data[0]->project_name,
				    'jabatan' 		  			=> $data[0]->job_title_name,
				    'tanggal_pembayaran_lembur'	=> $data[0]->tanggal_pembayaran_lembur
				];



				$pdfBinary = $this->html_pdf->render_to_string_portrait(
			        'pdf/gaji_os',
			        $pdfData
			    );

			    if (ob_get_level()) ob_end_clean();

			    header("Content-Type: application/pdf");
			    header("Content-Disposition: attachment; filename=gaji_os.pdf");
			    echo $pdfBinary;
			    exit;

		    }else{
		    	echo "Slip Gaji tidak ditemukan"; 
		    }

		}else{
			echo "Slip Gaji tidak ditemukan"; 
		}
	    
	    
	}


	public function getPayrollReport_perEmployee_pdf()
	{
		$this->load->library('html_pdf');
		$this->load->helper('global');


		
		if(isset($_GET['emp_id']) && $_GET['emp_id'] != '' && $_GET['emp_id'] != 0){

		    $sql = "
		        select a.*, b.full_name, c.name_indo as periode_bulan_name, b.emp_code, d.project_name, e.name as job_title_name, f.tanggal_pembayaran_lembur
				from payroll_slip a 
				left join employees b on b.id = a.employee_id 
				left join master_month c on c.id = a.periode_bulan
				left join project_outsource d on d.id = b.project_id
				left join master_job_title_os e on e.id = b.job_title_id
				left join data_customer f on f.id = d.customer_id
				where a.employee_id = ".$_GET['emp_id']." 
		    ";

		    $data = $this->db->query($sql)->result();

		    if(!empty($data)){
		    	$pdfData = [
				    'periode_bulan'      		=> $data[0]->periode_bulan_name,
				    'periode_tahun'      		=> $data[0]->periode_tahun,
				    'nik'    					=> $data[0]->emp_code,
				    'emp_name'       			=> $data[0]->full_name,
				    'project_name'    			=> $data[0]->project_name,
				    'jabatan' 		  			=> $data[0]->job_title_name,
				    'tanggal_pembayaran_lembur'	=> $data[0]->tanggal_pembayaran_lembur
				];



				$pdfBinary = $this->html_pdf->render_to_string_portrait(
			        'pdf/gaji_os',
			        $pdfData
			    );

			    if (ob_get_level()) ob_end_clean();

			    header("Content-Type: application/pdf");
			    header("Content-Disposition: attachment; filename=Gaji - ".$data[0]->full_name.".pdf");
			    echo $pdfBinary;
			    exit;

		    }else{
		    	echo "Slip Gaji tidak ditemukan"; 
		    }

		}else{
			echo "Slip Gaji tidak ditemukan"; 
		}
	    
	    
	}



	public function getOvertimeReport_perEmployee_pdf()
	{
		$this->load->library('html_pdf');
		$this->load->helper('global');


		
		if(isset($_GET['flemployee']) && $_GET['flemployee'] != '' && $_GET['flemployee'] != 0){
			
			

		    $sql = "
		        select a.*, b.full_name, c.name_indo as periode_bulan_name, b.emp_code, d.project_name, e.name as job_title_name, f.tanggal_pembayaran_lembur
				from payroll_slip a 
				left join employees b on b.id = a.employee_id 
				left join master_month c on c.id = a.periode_bulan
				left join project_outsource d on d.id = b.project_id
				left join master_job_title_os e on e.id = b.job_title_id
				left join data_customer f on f.id = d.customer_id
				where a.employee_id = '".$_GET['flemployee']."'
		    ";

		    $data = $this->db->query($sql)->result();

		    if(!empty($data)){
		    	$pdfData = [
				    'periode_bulan'      		=> $data[0]->periode_bulan_name,
				    'periode_tahun'      		=> $data[0]->periode_tahun,
				    'nik'    					=> $data[0]->emp_code,
				    'emp_name'       			=> $data[0]->full_name,
				    'project_name'    			=> $data[0]->project_name,
				    'jabatan' 		  			=> $data[0]->job_title_name,
				    'tanggal_pembayaran_lembur'	=> $data[0]->tanggal_pembayaran_lembur
				];



				$pdfBinary = $this->html_pdf->render_to_string_portrait(
			        'pdf/lembur_os',
			        $pdfData
			    );

			    if (ob_get_level()) ob_end_clean();

			    header("Content-Type: application/pdf");
			    header("Content-Disposition: attachment; filename=lembur_os.pdf");
			    echo $pdfBinary;
			    exit;

		    }else{
		    	echo "Report Lembur tidak ditemukan"; 
		    }

		}else{
			echo "Report Lembur tidak ditemukan"; 
		}
	    
	    
	}


	public function getOvertimeReport_pdf()
	{
	    $this->load->library('html_pdf');
	    $this->load->helper('global');

	    if (empty($_GET['flproject'])) {
	        echo "Report Lembur tidak ditemukan";
	        return;
	    }

	    // ================= DATA =================
	    $sql = "
	        select 
	            a.*, 
	            b.full_name,
	            b.emp_code,
	            d.project_name,
	            e.name AS job_title_name,
	            c.name_indo AS periode_bulan_name,
	            f.tanggal_pembayaran_lembur
	        FROM payroll_slip a
	        LEFT JOIN employees b ON b.id = a.employee_id
	        LEFT JOIN project_outsource d ON d.id = b.project_id
	        LEFT JOIN master_job_title_os e ON e.id = b.job_title_id
	        LEFT JOIN master_month c ON c.id = a.periode_bulan
	        LEFT JOIN data_customer f ON f.id = d.customer_id
	        WHERE b.project_id = ".$this->db->escape($_GET['flproject'])."
	        ORDER BY b.full_name ASC
	    ";

	    $employees = $this->db->query($sql)->result();

	    if (empty($employees)) {
	        echo "Report Lembur tidak ditemukan";
	        return;
	    }

	    // ================= RENDER PDF =================
	    $pdfBinary = $this->html_pdf->render_to_string_portrait(
	        'pdf/lembur_os_perproject',
	        [
	            'employees' => $employees
	        ]
	    );

	    if (ob_get_level()) ob_end_clean();

	    header("Content-Type: application/pdf");
	    header("Content-Disposition: attachment; filename=Report Lembur - ".$employees[0]->project_name.".pdf");
	    echo $pdfBinary;
	    exit;
	}



	public function getSummaryAbsen(){
		$post = $this->input->post(null, true);
		$bln 	= $post['bln'];
		$thn 	= $post['thn'];

		$rs =  $this->self_model->getSummaryAbsen($bln, $thn);
		

		echo json_encode($rs);
	}

	public function getGaji(){
		$post = $this->input->post(null, true);
		$project = $post['project'];
		$bln 	= $post['bln'];
		$thn 	= $post['thn'];

		$rs =  $this->self_model->getGaji($project, $bln, $thn);
		

		echo json_encode($rs);
	}


	public function save_edit_gaji_per_project()
	{
	    $post = $this->input->post();

	    // ================= VALIDASI AWAL =================
	    if (
	        empty($post['project_edit_gaji']) ||
	        empty($post['penggajian_month_edit_gaji']) ||
	        empty($post['penggajian_year_edit_gaji'])
	    ) {
	        echo json_encode([
	            'status'  => false,
	            'message' => 'Data wajib belum lengkap'
	        ]);
	        return;
	    }

	    if (!preg_match('/^\d{4}$/', $post['penggajian_year_edit_gaji'])) {
	        echo json_encode([
	            'status'  => false,
	            'message' => 'Tahun tidak valid'
	        ]);
	        return;
	    }

	    // ================= SET VARIABLE =================
	    $project = $post['project_edit_gaji'];

	    $period_start = !empty($post['period_start_edit_gaji'])
	        ? date('Y-m-d', strtotime($post['period_start_edit_gaji']))
	        : null;

	    $period_end = !empty($post['period_end_edit_gaji'])
	        ? date('Y-m-d', strtotime($post['period_end_edit_gaji']))
	        : null;

	    // ================= CEK DATA =================
	    $cek_data = $this->db->query("
	        SELECT a.*, b.project_id
	        FROM payroll_slip a
	        LEFT JOIN employees b ON b.id = a.employee_id
	        WHERE a.periode_bulan = ?
	          AND a.periode_tahun = ?
	          AND b.project_id = ?
	    ", [
	        $post['penggajian_month_edit_gaji'],
	        $post['penggajian_year_edit_gaji'],
	        $project
	    ])->result();

	    if (empty($cek_data)) {
	        echo json_encode([
	            'status'  => false,
	            'message' => 'Perhitungan Gaji tidak ditemukan. Silakan hitung gaji terlebih dahulu.'
	        ]);
	        return;
	    }

	    if (empty($period_start)) {
	        $period_start = $cek_data[0]->tgl_start;
	    }
	    if (empty($period_end)) {
	        $period_end = $cek_data[0]->tgl_end;
	    }

	    // ================= PROSES DETAIL =================
	    if (!isset($post['hdnempid_gaji']) || count($post['hdnempid_gaji']) == 0) {
	        echo json_encode([
	            'status'  => false,
	            'message' => 'Data detail absen tidak ditemukan'
	        ]);
	        return;
	    }

	    $success = false;

	    $item_len_min = min(array_keys($post['hdnempid_gaji']));
	    $item_len_max = max(array_keys($post['hdnempid_gaji']));

	    for ($i = $item_len_min; $i <= $item_len_max; $i++) {

	        if (!isset($post['hdnempid_gaji'][$i])) {
	            continue;
	        }

	        $hdnid = isset($post['hdnid_edit_gaji'][$i]) ? trim($post['hdnid_edit_gaji'][$i]) : '';

	        $itemData = [
	            /*'tgl_start_absensi'         => $period_start,
	            'tgl_end_absensi'           => $period_end,*/
	           
	            'total_masuk'       => trim($post['jml_hadir_edit_gaji'][$i]),
	            'total_tidak_masuk' => trim($post['jml_tdkhadir_edit_gaji'][$i]),
	            'total_jam_kerja'   => trim($post['jml_jam_kerja_edit_gaji'][$i]),
	            'total_jam_lembur'  => trim($post['jam_lembur_edit_gaji'][$i]),
	            'gaji_bulanan'  	=> trim($post['gaji_bulanan_edit_gaji'][$i]),
	            'gaji_harian'  		=> trim($post['gaji_harian_edit_gaji'][$i]),
	            'gaji'  			=> trim($post['gaji_edit_gaji'][$i]),
	            'tunjangan_jabatan' => trim($post['tunj_jabatan_edit_gaji'][$i]),
	            'tunjangan_transport'  	=> trim($post['tunj_transport_edit_gaji'][$i]),
	            'tunjangan_konsumsi' 	=> trim($post['tunj_konsumsi_edit_gaji'][$i]),
	            'tunjangan_komunikasi'  => trim($post['tunj_komunikasi_edit_gaji'][$i]),
	            'lembur_perjam'  	=> trim($post['lembur_perjam_edit_gaji'][$i]),
	            'ot'  				=> trim($post['ot_edit_gaji'][$i]),
	            'total_pendapatan'  => trim($post['ttl_pendapatan_edit_gaji'][$i]),
	            'bpjs_kesehatan'  	=> trim($post['bpjs_kes_edit_gaji'][$i]),
	            'bpjs_tk'  			=> trim($post['bpjs_tk_edit_gaji'][$i]),
	            'absen'  			=> trim($post['absen_edit_gaji'][$i]),
	            'seragam'  			=> trim($post['seragam_edit_gaji'][$i]),
	            'pelatihan'  		=> trim($post['pelatihan_edit_gaji'][$i]),
	            'lain_lain'  		=> trim($post['lainlain_edit_gaji'][$i]),
	            'hutang'   			=> trim($post['hutang_edit_gaji'][$i]),
	            'sosial'  			=> trim($post['sosial_edit_gaji'][$i]),
	            'payroll'  			=> trim($post['payroll_edit_gaji'][$i]),
	            'pph_120'  			=> trim($post['pph120_edit_gaji'][$i]),
	            'subtotal'  		=> trim($post['subtotal_edit_gaji'][$i]),
	            'gaji_bersih'  		=> trim($post['gaji_bersih_edit_gaji'][$i])
	        ];

	        if (!empty($hdnid)) {
	            // UPDATE
	            $itemData['updated_at'] = date('Y-m-d H:i:s');
	            $itemData['updated_by'] = $_SESSION['worker'];

	            if ($this->db->update('payroll_slip', $itemData, ['id' => $hdnid])) {
	                $success = true;
	            }
	        } else {
	            // INSERT
	            $itemData['periode_bulan']  = $post['penggajian_month_edit_gaji'];
	            $itemData['periode_tahun']  = $post['penggajian_year_edit_gaji'];
	            $itemData['employee_id']    = trim($post['hdnempid_gaji'][$i]);
	            $itemData['tgl_start_absensi']	= trim($post['period_start_edit_gaji']);
	            $itemData['tgl_end_absensi']    = trim($post['period_end_edit_gaji']);
	            $itemData['created_at'] 	= date('Y-m-d H:i:s');
	            $itemData['created_by'] 	= $_SESSION['worker'];


	            if ($this->db->insert('payroll_slip', $itemData)) {

	            	$dataEmp = $this->db->query("select no_bpjs, no_bpjs_ketenagakerjaan from employees where id = ".$post['hdnempid_gaji'][$i]."")->result();

	            	$log_bpjs = [
						'employee_id' 		=> trim($post['hdnempid_gaji'][$i]),
						'no_bpjs_kesehatan' => $dataEmp[0]->no_bpjs,
						'no_bpjs_tk'  		=> $dataEmp[0]->no_bpjs_ketenagakerjaan,
						'gaji_pokok' 		=> trim($post['gaji_bulanan_edit_gaji'][$i]),
						'nominal_bpjs_kesehatan'  => trim($post['bpjs_kes_edit_gaji'][$i]),
						'nominal_bpjs_tk'  	=> trim($post['bpjs_tk_edit_gaji'][$i]),
						'tanggal_potong'  	=> date("Y-m-d H:i:s")
						/*'tanggal_setor'		=> ''*/
					];
					$this->db->insert("history_bpjs", $log_bpjs);


	                $success = true;
	            }
	        }
	    }

	    // ================= RESPONSE FINAL =================
	    if ($success) {
	        echo json_encode([
	            'status'  => true,
	            'message' => 'Data berhasil disimpan'
	        ]);
	    } else {
	        echo json_encode([
	            'status'  => false,
	            'message' => 'Tidak ada data yang berhasil diproses'
	        ]);
	    }
	}



	public function geneditabsenrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);
			$bln 	= $post['bln'];
			$thn 	= $post['thn'];

			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewEditAbsenRow($row);
			} else if(isset($post['project'])) { 
				$row = 0;
				$id = trim($post['project']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewEditAbsenRow($row,$id,$bln,$thn,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}


	public function getAbsenceReportGaji_pdf()
	{
	    if (empty($_GET['flproject'])) {
	        echo 'Project tidak dipilih';
	        return;
	    }

	    $this->load->library('html_pdf');

	    // ================= PROJECT NAME =================
	    $projectRow = $this->db
	        ->select('project_name')
	        ->from('project_outsource')
	        ->where('id', $_GET['flproject'])
	        ->get()
	        ->row();

	    $projectName = $projectRow ? $projectRow->project_name : 'Unknown';

	    // ================= DATA =================
	    $sql = "
	        SELECT a.*, b.emp_code, b.full_name
	        FROM payroll_slip a
	        LEFT JOIN employees b ON b.id = a.employee_id
	        WHERE b.project_id = ".$_GET['flproject']."
	        ORDER BY b.full_name ASC
	    ";

	    $data = $this->db->query($sql)->result();

	    if (empty($data)) {
	        echo 'Data absensi tidak ditemukan';
	        return;
	    }

	    // ================= SUMMARY =================
	    $valSummary = [];
	    $no = 1;

	    foreach ($data as $row) {
	        $valSummary[] = [
	            $no++,
	            $row->emp_code,
	            $row->full_name,
	            $row->total_hari_kerja,
	            $row->total_masuk,
	            $row->total_ijin,
	            $row->total_cuti,
	            $row->total_alfa,
	            $row->total_lembur,
	            $row->total_jam_kerja,
	            $row->total_jam_lembur
	        ];
	    }

	    // ================= PDF DATA =================
	    $pdfData = [
	        'project_name' => $projectName,
	        'projects' => [[
	            'project_name' => $projectName,
	            'summary'      => $valSummary
	        ]]
	    ];

	    // ================= RENDER PDF =================
	    $pdfBinary = $this->html_pdf->render_to_string(
	        'pdf/report_absen_os_penggajian',
	        $pdfData
	    );

	    // ================= OUTPUT =================
	    if (ob_get_level()) ob_end_clean();

	    $fileName = 'Absensi - '.$projectName.'.pdf';

	    header('Content-Type: application/pdf');
	    header('Content-Disposition: attachment; filename="'.$fileName.'"');
	    header('Content-Length: '.strlen($pdfBinary));

	    echo $pdfBinary;
	    exit;
	}



	public function getAbsenceReportGaji_pdf_zip()
	{
	    
	    if (!empty($_GET['flproject'])) {

	        // ================= ZIP =================
		    $path = FCPATH.'uploads/report_absensi_pdf/';
		    if (!file_exists($path)) mkdir($path, 0777, true);

		    $zipName = $path.'absensi_pdf_'.date('Ymd_His').'.zip';
		    $zip = new ZipArchive();
		    $zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE);

		    $this->load->library('html_pdf');

		    

	        // ambil nama project (SEKALI)
	        $projectRow = $this->db
	            ->select('project_name')
	            ->from('project_outsource')
	            ->where('id', $_GET['flproject'])
	            ->get()->row();

	        $projectName = $projectRow ? $projectRow->project_name : 'Unknown';

	        $valSummary   = [];
	        $no = 1;

	        
	        $sql = "
	            select a.*, b.emp_code, b.full_name from payroll_slip a left join employees b on b.id = a.employee_id
				where b.project_id = ".$_GET['flproject']."
	            ORDER BY b.full_name ASC
	        ";

	        $data = $this->db->query($sql)->result();
	       

	        if(!empty($data)){
	        	// ================= SUMMARY =================
	        	foreach($data as $row){
	        		$valSummary[] = [
			            $no++,
			            $row->emp_code,
			            $row->full_name,
			            $row->total_hari_kerja,
			            $row->total_masuk,
			            $row->total_ijin,
			            $row->total_cuti,
			            $row->total_alfa,
			            $row->total_lembur,
			            $row->total_jam_kerja,
			            $row->total_jam_lembur
			        ];
	        	}
	        }
	        

	           
	        

	        // ================= RENDER PDF =================
	        $pdfData = [
	            'project_name' => $projectName,
	            'projects' => [[
	                'project_name' => $projectName,
	                'summary'      => $valSummary
	            ]]
	        ];

	        $pdfBinary = $this->html_pdf->render_to_string('pdf/report_absen_os_penggajian', $pdfData);
	        $zip->addFromString('absensi_'.strtolower(str_replace(' ','_',$projectName)).'.pdf', $pdfBinary);
		    

		    $zip->close();

		    // ================= DOWNLOAD =================
		    if (ob_get_level()) ob_end_clean();
		    header('Content-Type: application/zip');
		    header('Content-Disposition: attachment; filename='.basename($zipName));
		    header('Content-Length: '.filesize($zipName));
		    readfile($zipName);
		    unlink($zipName);
		    exit;

	    }

	    
	}


}
