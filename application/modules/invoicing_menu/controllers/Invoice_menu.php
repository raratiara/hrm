<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "invoice_menu"; // identify menu
 	const  LABELMASTER				= "Menu Invoice";
 	const  LABELFOLDER				= "invoicing_menu"; // module folder
 	const  LABELPATH				= "invoice_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "invoicing_menu"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Day", "Date","Employee Name","Employee Type","Time In","Time Out","Attendance IN","Attendance OUT","Late Desc","Leave Desc","Num of Working Hours"];

	
	/* Export */
	public $colnames 				= ["ID","Date","Employee Name","Employee Type","Time In","Time Out","Attendance IN","Attendance OUT","Late Desc","Leave Desc","Num of Working Hours"];
	public $colfields 				= ["id","date_attendance","full_name","attendance_type","time_in","time_out","date_attendance_in","date_attendance_out","is_late_desc","is_leaving_office_early_desc","num_of_working_hours"];


	/* Form Field Asset */
	public function form_field_asset()
	{
		

		$field = [];
		
		$msemp 							= $this->db->query("select * from employees where status_id = 1 order by full_name asc")->result(); 
		$field['selemployee'] 			= $this->self_model->return_build_select2me($msemp,'','','','employee','employee','','','id','full_name',' ','','','',3,'-');
		$field['selflemployee'] 		= $this->self_model->return_build_select2me($msemp,'','','','flemployee','flemployee','','','id','full_name',' ','','','',3,'-');
		

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


 	public function getDataEmp(){
		$post = $this->input->post(null, true);
		$empid = $post['empid'];

		$rs =  $this->self_model->getDataEmployee($empid);
		

		echo json_encode($rs);
	}


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
	    /*$sql = "
	        select 
	            c.name_indo AS month_name,
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
	        FROM summary_absen_outsource a
	        LEFT JOIN employees b ON b.id = a.emp_id
	        LEFT JOIN master_month c ON c.id = a.bulan
	        ORDER BY a.tahun DESC, a.bulan ASC, b.full_name ASC
	    ";

	    $data = $this->db->query($sql)->result();*/

	    $this->load->library('html_pdf');

	    /*$pdfData = [
	        'title' => 'SUMMARY ABSENSI OUTSOURCE',
	        'data'  => $data
	    ];*/

	    $pdfData = [
		    'invoice_no'      => 'INV-MAS-01946',
		    'invoice_date'    => '14 November 2025',
		    'po_number'       => '-',
		    'due_date'        => '28 November 2025',
		    'terms' 		  => '14 days',
		    'customer_name'   => 'PT. MITRA BELANJA ANDA',
		    'customer_address'=> 'Mall of Indonesia Lt. LG Unit B-02 ...',
		    'customer_npwp'   => '96.419.594.5-033.000',
		    'management_fee'  => '8',
		    'item_title'      => 'PENGADAAN JASA CLEANING SERVICE 12 PERSONIL',
		    'project_name'    => 'Grand Lucky MOI Kelapa Gading',
		    'periode_start'   => '01 Oktober',
		    'periode_end'     => '31 Oktober 2025',
		    'jumlah_harga_jual' => 54969360,
		    'ppn'             => 4924023,
		    'jumlah_sesudah_pajak'     => 55414382,
		    'subtotal' => 43444,
		    'management_fee_nominal'  => '8000',
		    'terbilang'       => 'Enam Puluh Lima Juta Lima Ratus Empat Belas Ribu Dua Ratus Lima Puluh Dua',
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
	}


	public function getRincianBiayaReport_pdf()
	{
	    /*$sql = "
	        select 
	            c.name_indo AS month_name,
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
	        FROM summary_absen_outsource a
	        LEFT JOIN employees b ON b.id = a.emp_id
	        LEFT JOIN master_month c ON c.id = a.bulan
	        ORDER BY a.tahun DESC, a.bulan ASC, b.full_name ASC
	    ";

	    $data = $this->db->query($sql)->result();*/

	    $this->load->library('html_pdf');

	    /*$pdfData = [
	        'title' => 'SUMMARY ABSENSI OUTSOURCE',
	        'data'  => $data
	    ];*/

	    /*$items = [
	    	'uraian' => 'supervisor',
	    	'qty' => '2',
	    	'satuan' => 'ribu',
	    	'harga' => '1000',
	    	'keterangan' => 'test',
	    	'total' => '2000',
	    ];*/


	    $items = $this->db->query("select * from project_outsource_boq")->result(); 

	    $pdfData = [
		    'nama_customer'   => 'PT MITRA BELANJA ANDA',
		    'alamat_customer' => 'Mall Of Indonesia Lt. LG Unit B-02, Jakarta Utara',
		    'project'         => 'Grand Lucky MOI Kelapa Gading',
		    'no_invoice'      => 'INV-MAS-01946',
		    'tanggal'         => '14 November 2025',
		    'items' => $items, // array rincian
		    'sub_total'       => 54649860,
		    'management_fee'  => 4371989,
		    'total'           => 59021849,
		    'ppn'             => 6492403,
		    'grand_total'     => 65514252
		];



	    $pdfBinary = $this->html_pdf->render_to_string_portrait(
	        'pdf/rincian_biaya',
	        $pdfData
	    );

	    if (ob_get_level()) ob_end_clean();

	    header("Content-Type: application/pdf");
	    header("Content-Disposition: attachment; filename=rincian_biaya.pdf");
	    echo $pdfBinary;
	    exit;
	}


	public function getBeritaAcaraPekerjaanReport_pdf()
	{
	    
	    $this->load->library('html_pdf');

	    $items = $this->db->query("select * from project_outsource_boq")->result(); 

	    $pdfData = [
		    'no_surat'        => 'INV-MAS-01947',
		    'nama_perusahaan' => 'PT. Mitra Belanja Anda',
		    'alamat'          => 'Mall Of Indonesia Lt. LG Unit B-02, Jakarta Utara',
		    'periode'         => '01 Oktober s/d 31 Oktober 2025',
		    'lokasi'          => 'Grand Lucky MOI Kelapa Gading',
		    'jenis_pekerjaan' => 'Trolley Boy dan Staff Fresh',
		    'jumlah_personil' => 18,
		    'tanggal'         => '14 November 2025',
		    'nama_client'     => 'PT. Mitra Belanja Anda',
		    'nama_ttd_kiri'   => 'Tri Ubaya Adi M.',
		    'jabatan_ttd_kiri'=> 'Direktur',
		    'nama_ttd_kanan'  => 'Hermawan Aris',
		    'jabatan_ttd_kanan'=> 'Store Manager'
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
	}

	

}
