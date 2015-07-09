<?php

/**
 * If apache_request_headers() function does not exist, create it.
 * Source: http://www.php.net/manual/en/function.apache-request-headers.php#70810
 */
if(!function_exists('apache_request_headers') ) {
    function apache_request_headers() {
        $arh = array();
        $rx_http = '/\AHTTP_/';
        foreach($_SERVER as $key => $val) {
            if( preg_match($rx_http, $key) ) {
                $arh_key = preg_replace($rx_http, '', $key);
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', $arh_key);
                if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
                    foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
                    $arh_key = implode('_', $rx_matches);
                }
                $arh[$arh_key] = $val;
            }
        }
        return( $arh );
    }
}

$headers = apache_request_headers();

$fn = $headers['FILENAME'] ? $headers['FILENAME'] : $headers['Filename'];
$return = new stdClass();

if ($fn) {

    $tfn = tempnam('/tmp', 'upl');

    if ($tfn)
    {

        // put contents from file data from the AJAX call
        file_put_contents(
            $tfn,
            file_get_contents('php://input')
        );

        $return->filename = $fn;
        $return->status = 'success';
        $return->temp_path = $tfn;
        echo json_encode($return);
        exit();

    }

    else {
        $return->status =  "Error: Could not create temp filename";
    }

}
else {
    $return->status =  "Error: Could not get filename";
}

echo json_encode($return);