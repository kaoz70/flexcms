<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 10:26 AM
 */

namespace catalog;
use App\FieldData;
use App\Role;
use catalog\models\Category;
use Catalog\Models\Field;
use Exception;
use Illuminate\Database\Eloquent\Model;
use stdClass;

$_ns = __NAMESPACE__;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends \Catalog implements \AdminInterface {

    const SECTION = 'product';

    const URL_CREATE = 'admin/catalog/product/create/';
    const URL_UPDATE = 'admin/catalog/product/update/';
    const URL_DELETE = 'admin/catalog/product/delete/';
    const URL_INSERT = 'admin/catalog/product/insert';
    const URL_EDIT = 'admin/catalog/product/edit/';
    const URL_REORDER = 'admin/catalog/product/reorder/';

    /**
     * List of items
     *
     * @return mixed
     */
    public function index()
    {
        // TODO: Implement index() method.
    }

    /**
     * Create form interface
     *
     * @return mixed
     */
    public function create()
    {
        $product = new \catalog\models\Product();
        $product->root_id = $this->uri->segment('5');
        $this->_showView($product, true);
    }

    /**
     * Edit form interface
     *
     * @param $id
     *
     * @return mixed
     */
    public function edit($id)
    {
        $product = \catalog\models\Product::find($this->uri->segment(6));
        $product->root_id = $id;
        $this->_showView($product);
    }

    /**
     * Insert the item into database
     *
     * @return mixed
     */
    public function insert()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $product = $this->_store(new \catalog\models\Product());
            $response->new_id = $product->id;
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al crear el producto!', $e);
        }

        $this->load->view($this->responseView, [ $this->responseVarName => $response ]);

    }

    /**
     * Update the item in the database
     *
     * @param $id
     *
     * @return mixed
     */
    public function update($id)
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->_store(\catalog\models\Product::find($id));
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al actualizar el producto!', $e);
        }

        $this->load->view($this->responseView, [ $this->responseVarName => $response ]);
    }

    /**
     * Remove the item from the database
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        $response = new stdClass();
        $response->error_code = 0;

        try{
            $product = \catalog\models\Product::find($id);
            $product->delete();
        } catch (Exception $e) {
            $response = $this->error('Ocurri&oacute; un problema al eliminar el producto!', $e);
        }

        $this->load->view($this->responseView, [ $this->responseVarName => $response ] );
    }

    /**
     * Shows the editor view
     *
     * @param Model $model
     * @param bool $new
     * @return mixed
     */
    public function _showView(Model $model, $new = FALSE)
    {

        $data['product'] = $model;
        $data['title'] = $new ? 'Nuevo producto' : 'Modificar Producto';
        $data['nuevo'] = $new ? 'nuevo' : '';

        $root = Category::find($model->root_id);
        $root->findChildren(999);

        $data['categories'] = $root->getChildren();
        $data['txt_boton'] = $new ? 'Crear Producto' : 'Modificar Producto';
        $data['link'] = $new ? base_url(static::URL_INSERT) : base_url(static::URL_UPDATE . $model->id);
        $data['fields'] = Field::where('section', 'product')->orderBy('position')->get();
        $data['translations'] = $model->getTranslations(static::SECTION);
        $data['field_translations'] = $model->getTranslations('product_field');
        $data['groups'] =  Role::all();

        $data['url_edit'] = static::URL_EDIT . $model->root_id;
        $data['url_delete'] = static::URL_DELETE;

        $this->load->view('catalog/product_view',$data);

    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param Model $model
     * @return mixed
     */
    public function _store(Model $model)
    {

        $model->category_id = $this->input->post('category_id');
        $model->stock_quantity = $this->input->post('stock_quantity');
        $model->weight = $this->input->post('weight');
        $model->important = (bool) $this->input->post('important');
        $model->enabled = (bool) $this->input->post('enabled');
        $model->css_class = $this->input->post('css_class');
        $model->visible_to = $this->input->post('visible_to');

        $model->save();

        //Set the products translations
        $model->setTranslations($this->input->post());

        //Set the product's dynamic field data and translations
        FieldData::setTranslations($model, 'product_field_data', $this->input->post());

        return $model;

    }
}