<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "invoice_menu"; // identify menu
 	const  LABELMASTER				= "Menu Invoice";
 	const  LABELFOLDER				= "payroll_outsource"; // module folder
 	const  LABELPATH				= "invoice_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "payroll_outsource"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Project Name","Invoice No","Invoice Date","PO Number","Periode Penggajian"];

	
	/* Export */
	public $colnames 				= ["ID","Project Name","Invoice No","Invoice Date","PO Number","Periode Penggajian"];
	public $colfields 				= ["id","project_name","invoice_no","invoice_date","po_number","periode_penggajian"];

	

	/* Form Field Asset */
	public function form_field_asset()
	{
		

		$field = [];
		
		
		$msproject = $this->db->query("select * FROM project_outsource ORDER BY project_name ASC ")->result();
		$field['selproject'] = $this->self_model->return_build_select2me($msproject,'','','','project','project','','','id','project_name',' ','','','required',3,'-');
		$field['selflproject'] = $this->self_model->return_build_select2me($msproject,'','','','flproject','flproject','','','id','project_name',' ','','','',3,'-');

		$msperiodegaji 					= array();
		$field['selperiodegaji'] 		= $this->self_model->return_build_select2me($msperiodegaji,'','','','periode_gaji','periode_gaji','','','id','periode_penggajian',' ','','','required',3,'-');

		$field['txtstartabsen'] = $this->self_model->return_build_txt('','start_absen','start_absen','','','readonly');
		$field['txtendabsen'] = $this->self_model->return_build_txt('','end_absen','end_absen','','','readonly');
		$field['txtnopo'] = $this->self_model->return_build_txt('','no_po','no_po');
		$field['txtjatuhtempo'] = $this->self_model->return_build_txt('','jatuh_tempo','jatuh_tempo');

		

		
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


 	


	public function getInvoiceReport(){

	    $sql = "
	        select 
	            c.name_indo as month_name,
	            a.tahun,
	            a.tgl_start,
	            a.tgl_end,
	            b.full_name,
	            a.total_hari_kerja,
	            a.total_masuk,
	            a.total_ijin,
	            a.total_cuti,
	            a.total_alfa,
	            a.total_lembur,
	            a.total_jam_kerja,
	            a.total_jam_lembur
	        from summary_absen_outsource a 
	        left join employees b on b.id = a.emp_id 
	        left join master_month c on c.id = a.bulan
	        order by a.tahun desc, a.bulan asc, b.full_name asc
	    ";

	    $data = $this->db->query($sql)->result();

	    ob_start();

	    echo '<?xml version="1.0"?>
	    <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
	     xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">

	     <Styles>
	        <Style ss:ID="Title">
	            <Font ss:Bold="1" ss:Size="14"/>
	        </Style>
	        <Style ss:ID="Header">
	            <Font ss:Bold="1"/>
	            <Interior ss:Color="#D9D9D9" ss:Pattern="Solid"/>
	        </Style>
	     </Styles>';

	    echo '<Worksheet ss:Name="Summary Absen Outsource">
	            <Table>';

	    // ===== TITLE =====
	    echo '<Row>
	            <Cell ss:MergeAcross="12" ss:StyleID="Title">
	                <Data ss:Type="String">SUMMARY ABSENSI OUTSOURCE</Data>
	            </Cell>
	          </Row>';

	    echo '<Row></Row>';

	    // ===== HEADER =====
	    $headers = [
	        'No',
	        'Bulan Penggajian',
	        'Tahun Penggajian',
	        'Periode Mulai',
	        'Periode Selesai',
	        'Nama Karyawan',
	        'Total Hari Kerja',
	        'Total Masuk',
	        'Total Ijin',
	        'Total Cuti',
	        'Total Alfa',
	        'Total Lembur (Hari)',
	        'Total Jam Kerja',
	        'Total Jam Lembur'
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
	        echo '<Cell><Data ss:Type="String">'.htmlspecialchars($row->month_name).'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->tahun.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="String">'.$row->tgl_start.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="String">'.$row->tgl_end.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="String">'.htmlspecialchars($row->full_name).'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_hari_kerja.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_masuk.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_ijin.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_cuti.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_alfa.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_lembur.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_jam_kerja.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_jam_lembur.'</Data></Cell>';
	        echo '</Row>';
	    }

	    echo '</Table></Worksheet></Workbook>';

	    $content = ob_get_clean();

	    header("Content-Type: application/vnd.ms-excel");
	    header("Content-Disposition: attachment; filename=summary_absen_outsource.xls");
	    header("Cache-Control: max-age=0");
	    echo $content;
	    exit;
	}



	public function getInvoiceReport_pdf()
	{
		$this->load->library('html_pdf');
		$this->load->helper('global');


		
		if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] != 0){
			
			$sql = "
		        select a.*, b.project_id, b.invoice_no, b.invoice_date, b.po_number, b.terms, 
				b.management_fee, b.jatuh_tempo, c.project_name, d.name as job_title_name,
				concat(f.name_indo,' ',e.tahun_penggajian) as periode_penggajian, e.tgl_start_absen, e.tgl_end_absen
				, g.name as customer_name, g.address as customer_address, g.npwp as customer_npwp
				from project_invoice_detail a
				left join project_invoice b on b.id = a.project_invoice_id
				left join project_outsource c on c.id = b.project_id
				left join master_job_title_os d on d.id = a.job_title_id
				left join payroll_slip e on e.id = b.payroll_slip_id
				left join master_month f on f.id = e.bulan_penggajian
				left join data_customer g on g.id = c.customer_id
				where a.project_invoice_id = ".$_GET['id']."
		    ";

		    $data = $this->db->query($sql)->result();

		    if(!empty($data)){
		    	$pdfData = [
				    'dataInv'       => $data,
				    'bank_account'    => '157-00-0754003-3',
				    'bank_name'       => 'PT. MANDIRI AGANGTA SEJAHTERA',
				    'bank_branch'     => 'Bank Mandiri Cabang Margonda Depok'
				];



				$pdfBinary = $this->html_pdf->render_to_string_portrait(
			        'pdf/invoice',
			        $pdfData
			    );

			    if (ob_get_level()) ob_end_clean();

			    header("Content-Type: application/pdf");
			    header("Content-Disposition: attachment; filename=invoice.pdf");
			    echo $pdfBinary;
			    exit;

		    }else{
		    	echo "Invoice tidak ditemukan"; 
		    }

		}else{
			echo "Invoice tidak ditemukan"; 
		}
	    
	    
	}


	public function getRincianBiayaReport_pdf()
	{
		$this->load->library('html_pdf');
		$this->load->helper('global');

		if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] != 0){

			//$project_id = 3;
		    $sql = "
		        select a.*, b.project_id, b.invoice_no, b.invoice_date, b.po_number, b.terms, 
				b.management_fee, b.jatuh_tempo, c.project_name, d.name as job_title_name,
				concat(f.name_indo,' ',e.tahun_penggajian) as periode_penggajian, e.tgl_start_absen, e.tgl_end_absen
				, g.name as customer_name, g.address as customer_address, g.npwp as customer_npwp, c.jenis_pekerjaan
				from project_invoice_detail a
				left join project_invoice b on b.id = a.project_invoice_id
				left join project_outsource c on c.id = b.project_id
				left join master_job_title_os d on d.id = a.job_title_id
				left join payroll_slip e on e.id = b.payroll_slip_id
				left join master_month f on f.id = e.bulan_penggajian
				left join data_customer g on g.id = c.customer_id
				where a.project_invoice_id = ".$_GET['id']."
		    ";


		    $data = $this->db->query($sql)->result();
		    if(!empty($data)){
		    	
			    $pdfData = [
				    'nama_customer'   		=> $data[0]->customer_name,
				    'alamat_customer' 		=> $data[0]->customer_address,
				    'jenis_pekerjaan' 		=> $data[0]->jenis_pekerjaan,
				    'tgl_start_absen' 		=> $data[0]->tgl_start_absen,
				    'tgl_end_absen' 		=> $data[0]->tgl_end_absen,
				    'project'         		=> $data[0]->project_name,
				    'no_invoice'      		=> $data[0]->invoice_no,
				    'management_fee' 		=> $data[0]->management_fee,
				    'invoice_date'         	=> formatTanggalIndo($data[0]->invoice_date),
				    'items' 				=> $data // array rincian
				   
				];



			    $pdfBinary = $this->html_pdf->render_to_string(
			        'pdf/rincian_biaya',
			        $pdfData
			    );

			    if (ob_get_level()) ob_end_clean();

			    header("Content-Type: application/pdf");
			    header("Content-Disposition: attachment; filename=rincian_biaya.pdf");
			    echo $pdfBinary;
			    exit;


		    }else{
		    	echo "Rincian Biaya tidak ditemukan"; die();
		    }

		}else{
	    	echo "Rincian Biaya tidak ditemukan"; die();
	    }

	    
	    
	}


	public function getBeritaAcaraPekerjaanReport_pdf()
	{
	    
	    $this->load->library('html_pdf');
	    $this->load->helper('global');



	    if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] != 0){
	    	//$project_id = 3;
		    $sql = "
		        select a.*, b.project_id, b.invoice_no, b.invoice_date, b.po_number, b.terms, 
				b.management_fee, b.jatuh_tempo, c.project_name, d.name as job_title_name,
				concat(f.name_indo,' ',e.tahun_penggajian) as periode_penggajian, e.tgl_start_absen, e.tgl_end_absen
				, g.name as customer_name, g.address as customer_address, g.npwp as customer_npwp
				, g.contact_name, g.contact_title, h.name as lokasi_name, c.periode_start, c.periode_end
				from project_invoice_detail a
				left join project_invoice b on b.id = a.project_invoice_id
				left join project_outsource c on c.id = b.project_id
				left join master_job_title_os d on d.id = a.job_title_id
				left join payroll_slip e on e.id = b.payroll_slip_id
				left join master_month f on f.id = e.bulan_penggajian
				left join data_customer g on g.id = c.customer_id
				left join master_work_location_outsource h on h.id = c.lokasi_id
				where a.project_invoice_id = ".$_GET['id']."
		    ";

		    $data = $this->db->query($sql)->result();

		    if(!empty($data)){
		    	
		    	$pdfData = [
		    		'dataInv' => $data
				  
				];




			    $pdfBinary = $this->html_pdf->render_to_string_portrait(
			        'pdf/berita_acara_pekerjaan',
			        $pdfData
			    );

			    if (ob_get_level()) ob_end_clean();

			    header("Content-Type: application/pdf");
			    header("Content-Disposition: attachment; filename=berita_acara_pekerjaan.pdf");
			    echo $pdfBinary;
			    exit;


		    }else{
		    	echo "Berita Acara Pekerjaan tidak ditemukan"; die();
		    }

	    }else{
	    	echo "Berita Acara Pekerjaan tidak ditemukan"; die();
	    }
	    

	    
	}


	public function getDataPeriodeGaji(){
		$post 		= $this->input->post(null, true);
		$project 	= $post['project'];

		$rs =  $this->self_model->getDataPeriodeGaji($project);
		

		echo json_encode($rs);
	}

	public function getDataPayroll(){
		$post 		= $this->input->post(null, true);
		$payroll_id = $post['payroll_id'];

		$rs =  $this->self_model->getDataPayroll($payroll_id);
		

		echo json_encode($rs);
	}



	

	

}
