<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data = [
	'type' => get_class($exception),
	'message' => $message,
	'file' => $exception->getFile(),
	'line' => $exception->getLine(),
];

if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE) {
	$data['trace'] = $exception->getTrace();
}

echo json_encode($data);
