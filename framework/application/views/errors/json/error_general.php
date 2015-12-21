<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data['heading'] = $heading;
$data['message'] = $message;

echo json_encode($data);
