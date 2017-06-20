<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data['success'] = $success;
$data['notify'] = $notify;

$data['data'] = [
    'heading' => $heading,
    'message' => $message,
];

echo json_encode($data);
