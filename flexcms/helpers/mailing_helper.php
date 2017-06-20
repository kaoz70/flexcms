<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Assets Helper
 * The function names are pretty self-explanatory
 *
 * @author 		Miguel Suarez <miguelsuarez70@gmail.com>
 * @copyright 	Copyright (c) 2014, Miguel Suarez
 * @version 	1.0.0
 */

/**
 * @param null $status
 * @return bool
 */
function mailchimp_status($status = NULL)
{
	return $status === 'sent' ? FALSE : TRUE;
}

/**
 * Very rudimentary Mailchimp string parser
 * @param $string
 * @param $list_id
 * @return mixed
 */
function mailchimp_parse($string, $list_id)
{

	//Remove the (#)
	$string = str_replace("(#)", '', $string);

	//Subscribers: (n subscribers)
	preg_match_all("/\(\d{1,}.{0,}\)/", $string, $output_array);
	$replace_string = '<a class="get_subscribers" href="' . base_url('admin/mailing/get_subscribers/' . $list_id) . '">{match}</a>';

	//Is a link: (http://....)
	if(!count($output_array[0])) {
		preg_match_all("/\(\D{1,}.{0,}\)/", $string, $output_array);
		$replace_string = '<a class="external" target="_blank" href="{match}">link</a>';
	}

	foreach ($output_array as $matches) {

		foreach ($matches as $match) {

			//Replace
			$string = str_replace($match, $replace_string, $string);

			//Replace {match}
			$string = str_replace("{match}", $match, $string);

		}

	}

	//Replace any ( or )
	$string = preg_replace("/\(|\)/", "", $string);

	return $string;

}

/* End of file mailing_helper.php */