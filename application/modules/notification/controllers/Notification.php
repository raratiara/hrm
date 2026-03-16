<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('notification/Notification_model', 'notif');
    }

    public function send_test()
    {
        $userId = $this->input->post('user_id');

        if (!$userId) {
            return $this->output->set_output(json_encode([
                'status' => false,
                'message' => 'user_id required'
            ]));
        }

        // Ambil device aktif (mobile)
        $device = $this->db
            ->where('user_id', $userId)
            ->where('type', 'mobile')
            ->where('is_active', 1)
            ->order_by('id', 'DESC')
            ->get('user_devices')
            ->row();

        if (!$device || empty($device->fcm_token)) {
            return $this->output->set_output(json_encode([
                'status' => false,
                'message' => 'FCM token not found for this user'
            ]));
        }

        $result = $this->notif->sendNotification(
            $device->fcm_token,
            'Test Notification',
            'Push notification berhasil 🎉',
            [
                'type' => 'test',
                'device_id' => (string) $device->id
            ]
        );

        return $this->output->set_output(json_encode([
            'status' => true,
            'firebase_response' => $result
        ]));
    }
}
