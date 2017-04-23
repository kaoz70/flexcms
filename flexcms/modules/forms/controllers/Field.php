<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 11:19 AM
 */

namespace Contact;
$_ns = __NAMESPACE__;

use App\Input;
use App\Response;
use App\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Field extends \Field {

    const FIELD_SECTION = 'form';
    protected static $type = 'form_field';

    /**
     * Returns the different field types
     */
    public function index_get()
    {
        $response = new Response();

        try{
            $response->setData(Input::where('section', static::FIELD_SECTION)->get());
        } catch (QueryException $e) {
            $response->setError('Ocurri&oacute; un error al obtener los tipos de campos!', $e);
        }

        $this->response($response, $response->getStatusHeader());
    }

    public function index_delete($id)
    {

        $response = new Response();

        try{

            //Delete the form
            $field = Models\Field::find($id);
            $field->delete();

            //Delete any translations
            Translation::where('parent_id', $field->id)->where('type', static::$type)->delete();

            $response->setMessage("Campo eliminado satisfactoriamente");
            $response->setData(Models\Field::where('parent_id', $field->parent_id)->get());

        } catch (QueryException $e) {
            $response->setError('Ocurri&oacute; un problema al eliminar el campo!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

}