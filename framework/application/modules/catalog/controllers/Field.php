<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 10:34 AM
 */

namespace catalog;
use App\Input;
use App\Translation;
use catalog\models\Product;
use Exception;
use Illuminate\Database\Eloquent\Model;
use stdClass;

$_ns = __NAMESPACE__;

class Field extends \Field implements \AdminParentInterface {

    const FIELD_SECTION = 'product';

    const URL_CREATE = 'admin/catalog/field/create';
    const URL_UPDATE = 'admin/catalog/field/update';
    const URL_DELETE = 'admin/catalog/field/delete';
    const URL_INSERT = 'admin/catalog/field/insert';
    const URL_EDIT = 'admin/catalog/field/edit';
    const URL_REORDER = 'admin/catalog/field/reorder';

    public function index($parent_id = null)
    {
        parent::index($parent_id);
    }

    public function create($parent_id = null)
    {
        $field = new \App\Field();
        $field->section = static::FIELD_SECTION;
        $field->data = null;
        $this->_showView($field, true);
    }

    public function insert($parent_id = null)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{

            $field = $this->_store(new \Catalog\Models\Field());
            $field->position = \App\Field::where('section', static::FIELD_SECTION)->get()->count();
            $field->save();

            //Create the field for any products already in the database
            $field->createChildTableFields(Product::where('temporary', 0)->get(), static::FIELD_SECTION);
            $response->new_id = $field->id;

        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al insertar el campo!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));

    }

    public function edit($id)
    {
        $this->_showView(\App\Field::find($id));
    }

    public function update($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $response->new_id = $this->_store(\Catalog\Models\Field::find($id))->id;
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar el campo!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));

    }

    public function delete($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{

            //Delete the field
            $field = \App\Field::find($id);
            $field->delete();

            //Delete the field's translations
            $translations = Translation::where('parent_id', $id)->where('type', static::FIELD_SECTION . '_field');
            $translations->delete();

        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al eliminar el campo!', $e);
        }

        $this->load->view(static::RESPONSE_VIEW, array(static::RESPONSE_VAR => $response));

    }

    /**
     * @param Model $field
     * @param bool $new
     * @return mixed
     */
    public function _showView(Model $field, $new = FALSE)
    {

        $data['field'] = $field;
        $data['data'] = json_decode($field->data);

        $data['titulo'] = $new ? 'Crear Campo' : 'Editar Campo';

        $data['txt_boton'] = $new ? 'Crear' : 'Modificar';
        $data['link']  = $new ? base_url(static::URL_INSERT) : base_url(static::URL_UPDATE . '/' . $field->id);
        $data['nuevo'] = $new ? 'nuevo' : '';

        $data['url_edit'] = static::URL_EDIT;
        $data['url_delete'] = static::URL_DELETE;

        $data['inputs'] = Input::where('section', static::FIELD_SECTION)->get();
        $data['translations'] = $field->getTranslations(static::FIELD_SECTION . '_field');

        $this->load->view('catalog/field_view', $data);
    }

    public function _store(Model $model) {

        $input = $this->input->post();

        $model->css_class = $this->input->post('css_class');
        $model->section = static::FIELD_SECTION;
        $model->name = $this->input->post('name');
        $model->input_id = $this->input->post('input_id');
        $model->label_enabled = (bool) $this->input->post('label_enabled');
        $model->enabled = (bool) $this->input->post('enabled');

        //Custom field data
        $data = [
            'view_in_widget' => (bool) $this->input->post('view_in_widget'),
            'view_in_list' => (bool) $this->input->post('view_in_list'),
            'view_in_cart' => (bool) $this->input->post('view_in_cart'),
            'view_in_filters' => (bool) $this->input->post('view_in_filters'),
        ];
        $model->data = json_encode($data);

        $model->save();

        //Update the content's translations
        $model->setTranslations($input);

        return $model;
    }

}