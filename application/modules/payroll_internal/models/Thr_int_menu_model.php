<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__.'/Bonus_int_menu_model.php';

class Thr_int_menu_model extends Bonus_int_menu_model
{
	protected $folder_name = "payroll_internal/thr_int_menu";
	protected $table_name = _PREFIX_TABLE."thr_internal";
	protected $detail_table_name = _PREFIX_TABLE."thr_internal_detail";
	protected $detail_foreign_key = "thr_internal_id";
	protected $amount_field = "thr_amount";
	protected $total_header_field = "total_thr";
}
