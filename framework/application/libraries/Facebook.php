<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( session_status() == PHP_SESSION_NONE ) {
    session_start();
}

use Facebook\GraphSessionInfo;
use Facebook\FacebookSession;
use Facebook\FacebookCurl;
use Facebook\FacebookHttpable;
use Facebook\FacebookCurlHttpClient;
use Facebook\FacebookResponse;
use Facebook\FacebookAuthorizationException;
use Facebook\FacebookRequestException;
use Facebook\FacebookRequest;
use Facebook\FacebookSDKException;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\GraphObject;
 
class Facebook extends Html {
    var $ci;
    var $helper;
    var $session;
	var $config;
 
    public function __construct($params) {
        $this->ci =& get_instance();
		$this->ci->load->model('configuracion_model', 'Config');
		$this->config = $params['config'];
        FacebookSession::setDefaultApplication( $this->config->facebook_app_id, $this->config->facebook_app_secret );
    }
 
	public function get_session()
	{

		$helper = new \Facebook\FacebookJavaScriptLoginHelper();
		try {
			$this->session = $helper->getSession();
		} catch(FacebookRequestException $ex) {
			// When Facebook returns an error
			//print_r($ex);
		} catch(\Exception $ex) {
			// When validation fails or other local issues
			//print_r($ex);
		}
		if ($this->session) {
			return $this->session;
		}

	}

	public function exchange_token($token)
	{
		//Exchange short life access token with a long life access token
		try {
			$response_token = (array)(new FacebookRequest(
				$this->session, 'GET', '/oauth/access_token?grant_type=fb_exchange_token&client_id=' . $this->config->facebook_app_id . '&client_secret=' . $this->config->facebook_app_secret . '&fb_exchange_token=' . $token
			))->execute()->getResponse();
		} catch(FacebookRequestException $e) {
			return false;
		}

		//Get the correct expiry time
		try {
			$response = (new FacebookRequest(
				$this->session, 'GET', '/debug_token?input_token=' . $response_token['access_token']
			))->execute()->getResponse();
			$response_token['expires'] = $response->data->expires_at;
		} catch(FacebookRequestException $e) {
			return false;
		}

		return $response_token;
	}
 
    public function get_user() {

        if ( $this->session ) {
            try {
                $request = (new FacebookRequest( $this->session, 'GET', '/me' ))->execute();
                $user = $request->getGraphObject()->asArray();
 
                return $user;
 
            } catch(FacebookRequestException $e) {
                return false;
 
            }
        }
    }
}
 
