<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TimeAttendance_model extends CI_Model
{
    protected $table = 'time_attendances';

    public function getTodayAttendance(int $employeeId)
    {
        return $this->db
            ->where('employee_id', $employeeId)
            ->where('date_attendance', date('Y-m-d'))
            ->limit(1)
            ->get($this->table)
            ->row();
    }

    public function hasCheckedInToday(int $employeeId): bool
    {
        $row = $this->getTodayAttendance($employeeId);

        return (
            $row &&
            !empty($row->date_attendance_in) &&
            $row->date_attendance_in !== '0000-00-00 00:00:00'
        );
    }

    public function getOrCreateTodayAttendance(
        int $employeeId,
        string $timeIn
    ) {
        $today = date('Y-m-d');

        $row = $this->db
            ->where('employee_id', $employeeId)
            ->where('date_attendance', $today)
            ->limit(1)
            ->get($this->table)
            ->row();

        if ($row) {
            return $row;
        }

        // ➕ AUTO CREATE
        $this->db->insert($this->table, [
            'employee_id'    => $employeeId,
            'date_attendance'=> $today,
            'time_in'        => $timeIn,
            'attendance_type'=> 'Reguler',
            'created_at'     => date('Y-m-d H:i:s')
        ]);

        return $this->getTodayAttendance($employeeId);
    }

}

