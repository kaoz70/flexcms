<?

class BaseController extends MX_Controller
{

    protected $responseView = 'admin/request/json';
    protected $responseVarName = 'return';

    /**
     * Format the error messages
     * @param $message
     * @param $error
     * @return stdClass
     */
    public function error($message, Exception $error)
    {
        $response = new stdClass();
        $response->message = $message;
        $response->error_code = $error->getCode() ?: 1;
        $response->error_message = $error->getMessage();
        return $response;
    }

}