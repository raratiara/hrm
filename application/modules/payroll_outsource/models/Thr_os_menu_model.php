<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__.'/Bonus_thr_os_menu_model.php';

class Thr_os_menu_model extends Bonus_thr_os_menu_model
{
	protected $folder_name = "payroll_outsource/thr_os_menu";
	protected $table_name = _PREFIX_TABLE."thr_os";
	protected $detail_table_name = _PREFIX_TABLE."thr_os_detail";
	protected $detail_foreign_key = "thr_os_id";
	protected $amount_field = "thr_amount";
	protected $total_header_field = "total_thr";
	protected $approval_matrix_type_id = 17;
}
