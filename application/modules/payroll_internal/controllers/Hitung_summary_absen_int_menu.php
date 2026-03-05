<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hitung_summary_absen_int_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "hitung_summary_absen_int_menu"; // identify menu
 	const  LABELMASTER				= "Menu Hitung Summary Absen";
 	const  LABELFOLDER				= "payroll_internal"; // module folder
 	const  LABELPATH				= "hitung_summary_absen_int_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "payroll_internal"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID", "Bulan Penggajian", "Tahun Penggajian","Tgl Start Absen","Tgl End Absen"];

	
	/* Export */
	public $colnames 				= ["ID", "Bulan Penggajian", "Tahun Penggajian","Tgl Start Absen","Tgl End Absen"];
	public $colfields 				= ["id","month_name","tahun_penggajian","tgl_start_absen","tgl_end_absen"];


	/* Form Field Asset */
	public function form_field_asset()
	{
		

		$field = [];
		
		$field['txtperiodstart']	= $this->self_model->return_build_txt('','period_start','period_start','','','required');
		$field['txtperiodend']		= $this->self_model->return_build_txt('','period_end','period_end','','','required');
		$field['txtyear']			= $this->self_model->return_build_txt('','penggajian_year','penggajian_year','','','required');
		$field['txtperiodstart_edit']	= $this->self_model->return_build_txt('','period_start_edit','period_start_edit');
		$field['txtperiodend_edit']		= $this->self_model->return_build_txt('','period_end_edit','period_end_edit');
		$field['txtyear_edit']			= $this->self_model->return_build_txt('','penggajian_year_edit','penggajian_year_edit');
		
		$msmonth 					= $this->db->query("select * from master_month order by id asc")->result(); 
		$field['selmonth'] 			= $this->self_model->return_build_select2me($msmonth,'','','','penggajian_month','penggajian_month','','','id','name_indo',' ','','','required',3,'-');
		$field['selmonth_edit'] 			= $this->self_model->return_build_select2me($msmonth,'','','','penggajian_month_edit','penggajian_month_edit','','','id','name_indo',' ','','','',3,'-');
		/*$field['is_all_employee'] 	= $this->self_model->return_build_radio('Ya', [['Ya','Ya'],['Tidak','Tidak']], 'is_all_employee', '', 'inline');*/
		$msemp 						= $this->db->query("select * from employees where emp_source = 'internal' and status_id = 1 order by full_name asc")->result(); 
		$field['selemployeeids'] 	= $this->self_model->return_build_select2me($msemp,'multiple','','','employeeIds[]','employeeIds','','','id','full_name',' ','','','',3,'-');
		
		$field['selflemployee'] 	= $this->self_model->return_build_select2me($msemp,'','','','flemployee','flemployee','','','id','full_name',' ','','','',3,'-');

		$field['is_all_employee'] 	= $this->self_model->return_build_radio('Semua', [['Semua','Semua'],['Karyawan','Per Karyawan']], 'is_all_employee', '', 'inline');
		

		
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


	public function getAbsenceReportSummaryAbsen(){

	    $sql = "
	        select 
			c.name_indo as month_name,
			a.tahun_penggajian as tahun,
			a.tgl_start_absen as tgl_start,
			a.tgl_end_absen as tgl_end,
			bb.full_name,
			b.total_hari_kerja,
			b.total_masuk,
			b.total_ijin,
			b.total_cuti,
			b.total_alfa,
			b.total_lembur,
			b.total_jam_kerja,
			b.total_jam_lembur
			from summary_absen_internal a 
			left join summary_absen_internal_detail b on b.summary_absen_internal_id = a.id
			left join employees bb on bb.id = b.emp_id 
			left join master_month c on c.id = a.bulan_penggajian
			where a.id = ".$_GET['summary_id']."
			order by a.tahun_penggajian desc, a.bulan_penggajian asc, bb.full_name asc
	    ";

	    $data = $this->db->query($sql)->result();

	    $periode_penggajian = $data[0]->month_name.' '.$data[0]->tahun;
	    $periode_absensi = $data[0]->tgl_start.' s/d '.$data[0]->tgl_end;

	    ob_start();

	    echo '<?xml version="1.0"?>
	    <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
	     xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">

	     <Styles>
	        <Style ss:ID="Title">
	            <Font ss:Bold="1" ss:Size="14"/>
	        </Style>
	        <Style ss:ID="Title2">
	            <Font ss:Bold="1" ss:Size="10"/>
	        </Style>
	        <Style ss:ID="Header">
	            <Font ss:Bold="1"/>
	            <Interior ss:Color="#D9D9D9" ss:Pattern="Solid"/>
	        </Style>
	     </Styles>';

	    echo '<Worksheet ss:Name="Summary Absen">
	            <Table>';

	    // ===== TITLE =====
	    echo '<Row>
	            <Cell ss:MergeAcross="12" ss:StyleID="Title">
	                <Data ss:Type="String">SUMMARY ABSENSI</Data>
	            </Cell>
	          </Row>';
	    echo '<Row>
	            <Cell ss:MergeAcross="12" ss:StyleID="Title2">
	                <Data ss:Type="String">Periode Penggajian : '.$periode_penggajian.'</Data>
	            </Cell>
	          </Row>';
	    echo '<Row>
	            <Cell ss:MergeAcross="12" ss:StyleID="Title2">
	                <Data ss:Type="String">Periode Absensi : '.$periode_absensi.'</Data>
	            </Cell>
	          </Row>';

	    echo '<Row></Row>';

	    // ===== HEADER =====
	    $headers = [
	        'No',
	        'Nama Karyawan',
	        'Total Hari Kerja',
	        'Total Masuk',
	        'Total Ijin',
	        'Total Cuti',
	        'Total Alfa',
	        'Total Jam Kerja',
	        'Total Jam Lembur',
	        'Total Nominal Lembur'
	    ];

	    echo '<Row>';
	    foreach ($headers as $h){
	        echo '<Cell ss:StyleID="Header">
	                <Data ss:Type="String">'.htmlspecialchars($h).'</Data>
	              </Cell>';
	    }
	    echo '</Row>';

	    // ===== DATA =====
	    $no = 1;
	    foreach ($data as $row){
	        echo '<Row>';
	        echo '<Cell><Data ss:Type="Number">'.$no++.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="String">'.htmlspecialchars($row->full_name).'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_hari_kerja.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_masuk.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_ijin.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_cuti.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_alfa.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_jam_kerja.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_jam_lembur.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_lembur.'</Data></Cell>';
	        echo '</Row>';
	    }

	    echo '</Table></Worksheet></Workbook>';

	    $content = ob_get_clean();

	    
	    header("Content-Type: application/vnd.ms-excel");
	    header("Content-Disposition: attachment; filename=Summary Absen.xls");
	    header("Cache-Control: max-age=0");
	    echo $content;
	    exit;
	}



	public function getAbsenceReportSummaryAbsen_pdf()
	{
	    $sql = "
	        select 
			c.name_indo as month_name,
			a.tahun_penggajian as tahun,
			a.tgl_start_absen as tgl_start,
			a.tgl_end_absen as tgl_end,
			bb.full_name,
			b.total_hari_kerja,
			b.total_masuk,
			b.total_ijin,
			b.total_cuti,
			b.total_alfa,
			b.total_lembur,
			b.total_jam_kerja,
			b.total_jam_lembur
			from summary_absen_internal a 
			left join summary_absen_internal_detail b on b.summary_absen_internal_id = a.id
			left join employees bb on bb.id = b.emp_id 
			left join master_month c on c.id = a.bulan_penggajian
			where a.id = ".$_GET['summary_id']."
			order by a.tahun_penggajian desc, a.bulan_penggajian asc, bb.full_name asc
	    ";

	    $data = $this->db->query($sql)->result();

	    $this->load->library('html_pdf');

	    $pdfData = [
	        'title' => 'SUMMARY ABSENSI',
	        'periode_penggajian' => $data[0]->month_name.' '.$data[0]->tahun,
	        'periode_absensi' => $data[0]->tgl_start.' s/d '.$data[0]->tgl_end,
	        'data'  => $data
	    ];

	    $pdfBinary = $this->html_pdf->render_to_string(
	        'pdf/report_summary_absen',
	        $pdfData
	    );

	    if (ob_get_level()) ob_end_clean();

	    header("Content-Type: application/pdf");
	    header("Content-Disposition: attachment; filename=Summary Absen.pdf");
	    echo $pdfBinary;
	    exit;
	}




	public function genabsenrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);

			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewAbsenRow($row);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewAbsenRow($row,$id,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}


	




}
