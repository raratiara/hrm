<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AttendanceReminderLog_model extends CI_Model
{
    protected $table = 'attendance_reminder_logs';

    public function hasSent(
        int $employeeId,
        string $date,
        string $type
    ): bool {
        return $this->db
            ->where('employee_id', $employeeId)
            ->where('attendance_date', $date)
            ->where('reminder_type', $type)
            ->limit(1)
            ->get($this->table)
            ->num_rows() > 0;
    }

    public function log(
        int $employeeId,
        string $date,
        string $type
    ): void {
        $this->db->insert($this->table, [
            'employee_id' => $employeeId,
            'attendance_date' => $date,
            'reminder_type' => $type,
            'sent_at' => date('Y-m-d H:i:s')
        ]);
    }
}
