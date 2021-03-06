<?php
use App\Translation;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 7/27/2016
 * Time: 10:33 AM
 */
class Field extends RESTController
{

    const FIELD_SECTION = '';
    const TRANSLATION_SECTION = '';

    const URL_CREATE = '';
    const URL_UPDATE = '';
    const URL_DELETE = '';
    const URL_INSERT = '';
    const URL_EDIT = '';
    const URL_REORDER = '';

    public function index($parent_id)
    {

        $data['items'] = \App\Field::where('section', static::FIELD_SECTION)
            ->where('parent_id', $parent_id)
            ->orderBy('position')
            ->get();

        $data['title'] = 'Editar Template';
        $data['list_id'] = 'banner_campos';
        $data['nivel'] = 'nivel3';

        $data['search'] = false;
        $data['drag'] = true;

        $data['url_sort'] = base_url(static::URL_REORDER);
        $data['url_edit'] = base_url(static::URL_EDIT);
        $data['url_delete'] = base_url(static::URL_DELETE);

        /*
         * MENU
         */
        $data['menu'][] = anchor(base_url(static::URL_CREATE . $parent_id), 'Crear Nuevo Elemento', [
            'class' => $data['nivel'] . ' ajax boton n1'
        ]);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/list_view', $data);
    }

    public function update($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $response->new_id = $this->_store(\App\Field::find($id))->id;
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar el campo!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));

    }

    /*public function delete($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{

            $field = \App\Field::find($id);
            $field->delete();

        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al eliminar el campo!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));

    }*/

    public function reorder()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            \App\Field::reorder($this->input->post('posiciones'), static::FIELD_SECTION);
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al reorganizar los campos!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, [ static::RESPONSE_VAR => $response ] );

    }

    public function _showView(Model $field, $new = FALSE)
    {

    }

}