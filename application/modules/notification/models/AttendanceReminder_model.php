<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AttendanceReminder_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('notification/Notification_model', 'notif');
    }

    /**
     * MAIN ENTRY — dipanggil controller / cron
     */
    public function run()
    {
        $employees = $this->getActiveEmployeesWithDevice();

        foreach ($employees as $emp) {
            $this->processEmployee($emp);
        }
    }

    /**
     * Ambil employee aktif + device mobile
     */
    public function getActiveEmployeesWithDevice()
    {
        return $this->db
            ->select('
                emp.id AS employee_id,
                emp.full_name,
                u.user_id,
                dev.fcm_token
            ')
            ->from('employees emp')
            ->join('user u', 'u.id_karyawan = emp.id')
            ->join('user_devices dev', 'dev.user_id = u.user_id AND dev.is_active = 1')
            ->where('u.isaktif', '1')
            ->where('dev.fcm_token IS NOT NULL', null, false)
            ->get()
            ->result();
    }


    /**
     * Proses reminder per employee
     */
    private function processEmployee(object $emp): void
    {
        $today = date('Y-m-d');
        $now   = new DateTimeImmutable();

        // ambil attendance hari ini
        $attendance = $this->db
            ->where('employee_id', $emp->id)
            ->where('date_attendance', $today)
            ->get('time_attendances')
            ->row();

        // kalau tidak ada jadwal → skip
        if (!$attendance || empty($attendance->time_in)) {
            return;
        }

        // sudah check-in → skip
        if (!empty($attendance->date_attendance_in)
            && $attendance->date_attendance_in !== '0000-00-00 00:00:00') {
            return;
        }

        $timeIn = new DateTimeImmutable($today . ' ' . $attendance->time_in);

        $before30 = $timeIn->modify('-30 minutes');
        $after30  = $timeIn->modify('+30 minutes');

        // 🔔 REMINDER SEBELUM
        if ($now >= $before30 && $now < $timeIn) {
            $this->sendReminder(
                $emp,
                '⏰ Reminder Absensi',
                '30 menit lagi jam masuk kerja, jangan lupa absen ya!',
                'before_checkin'
            );
        }

        // 🔔 REMINDER SESUDAH
        if ($now >= $after30) {
            $this->sendReminder(
                $emp,
                '⚠️ Belum Absen',
                'Kamu belum melakukan absensi masuk hari ini.',
                'after_checkin'
            );
        }
    }

    public function shouldSendReminder(
        object $emp,
        object $attendance,
        DateTime $now
    ): ?string {

        if (empty($attendance->time_in)) {
            return null;
        }

        $checkInTime = new DateTime(
            $attendance->date_attendance . ' ' . $attendance->time_in
        );

        $before = (clone $checkInTime)->modify('-30 minutes');
        $after  = (clone $checkInTime)->modify('+30 minutes');

        if ($now >= $before && $now < $checkInTime) {
            return 'before';
        }

        if ($now >= $checkInTime && $now <= $after) {
            return 'after';
        }

        return null;
    }


    /**
     * Kirim FCM
     */
    private function sendReminder(
        object $emp,
        string $title,
        string $body,
        string $type
    ): void {
        $this->notif->sendNotification(
            $emp->fcm_token,
            $title,
            $body,
            [
                'type' => 'attendance_reminder',
                'reminder_type' => $type,
                'employee_id' => (string) $emp->id
            ]
        );
    }
}
