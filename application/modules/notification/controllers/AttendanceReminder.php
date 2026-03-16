<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AttendanceReminder extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->input->is_cli_request()) {
            show_error('Direct access not allowed', 403);
        }

        $this->load->model(
            'notification/AttendanceReminder_model',
            'reminder'
        );
        $this->load->model(
            'notification/TimeAttendance_model',
            'attendance'
        );
        $this->load->model(
            'notification/AttendanceReminderLog_model',
            'reminderLog'
        );
    }

    /**
     * ENTRY POINT CRON
     * php index.php notification AttendanceReminder run
     */
    public function run()
    {
        $start = microtime(true);
        $now   = new DateTime();

        $employees = $this->reminder->getActiveEmployeesWithDevice();

        foreach ($employees as $employee) {
            $timeIn = $this->reminder->getDefaultTimeIn($employee);

            $attendance = $this->attendance
                ->getOrCreateTodayAttendance(
                    $employee->employee_id,
                    $timeIn
                );

            $attendance = $this->attendance
                ->getTodayAttendance($employee->employee_id);

            // belum ada jadwal absensi hari ini
            if (!$attendance || empty($attendance->time_in)) {
                continue;
            }

            // sudah check-in → skip
            if ($this->attendance->hasCheckedInToday($employee->employee_id)) {
                continue;
            }

            // tentukan jenis reminder
            $type = $this->reminder
                ->shouldSendReminder($employee, $attendance, $now);

            if (!$type) {
                continue;
            }

            $today = date('Y-m-d');
            // sudah pernah dikirim sebelumnya → skip
            if ($this->reminderLog->hasSent(
                $employee->employee_id,
                $today,
                $type
            )) {
                continue;
            }

            // KIRIM NOTIF
            $this->reminder->sendToDevice(
                $employee->fcm_token,
                'Pengingat Absensi',
                $type === 'before'
                    ? '30 menit lagi jam masuk kerja'
                    : 'Kamu belum absen masuk hari ini',
                [
                    'type' => 'attendance_reminder',
                    'reminder' => $type
                ]
            );

            // SIMPAN LOG
            $this->reminderLog->log(
                $employee->employee_id,
                $today,
                $type
            );
        }


        $duration = round(microtime(true) - $start, 2);
        echo "[OK] Attendance reminder executed in {$duration}s" . PHP_EOL;
    }

    private function getTodayCheckInTime(
        object $employee,
        ?object $attendance
    ): ?DateTime {
        if (!empty($attendance?->time_in)) {
            return new DateTime(
                $attendance->date_attendance . ' ' . $attendance->time_in
            );
        }

        if (!empty($employee->shift_type)) {
            $time = explode('-', $employee->shift_type)[0];
            return new DateTime(date('Y-m-d') . ' ' . $time);
        }

        return null;
    }


}
