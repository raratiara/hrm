<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['firebase'] = [
    'project_id' => 'mas-hrm-2026',
    'service_account' => APPPATH . 'config/firebase/service-account.json',
    'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
    'token_uri' => 'https://oauth2.googleapis.com/token'
];
