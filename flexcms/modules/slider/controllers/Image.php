<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/10/15
 * Time: 12:17 PM
 */

namespace slider;
use App\Slider;
use Illuminate\Database\Eloquent\Model;

$_ns = __NAMESPACE__;

class Image extends \AdminController implements \AdminParentInterface {

    public function index($parent_id)
    {

        $data['items'] = \App\Image::where('image_section_id', $parent_id)->get();

        $data['url_rel'] = base_url('admin/sliders/images/'.$parent_id);
        $data['url_sort'] = base_url('admin/sliders/image/reorder/'.$parent_id);
        $data['url_modificar'] = base_url('admin/sliders/image/edit/'.$parent_id);
        $data['url_eliminar'] = base_url('admin/sliders/image/delete/'.$parent_id);
        $data['url_path'] =  base_url() . 'assets/public/images/banners/banner_' . $parent_id . '_';
        $data['method'] =  'banner/' . $parent_id;

        $banner = Slider::find($parent_id);
        $data['width'] = $banner->width;
        $data['height'] = $banner->height;

        $data['search'] = false;
        $data['drag'] = true;
        $data['nivel'] = 'nivel4';
        $data['list_id'] = 'banner_images';

        $data['idx_id'] = 'bannerImagesId';
        $data['idx_nombre'] = 'bannerImageName';
        $data['idx_extension'] = 'bannerImageExtension';

        $data['txt_titulo'] = 'Imágenes del Banner';

        /*
          * Menu
          */
        $data['menu'][] = anchor(base_url('admin/slider/edit/' . $parent_id), 'configuraci&oacute;n', [
            'class' => $data['nivel'] . ' nivel3 ajax boton n1'
        ]);
        $data['bottomMargin'] = count($data['menu']) * 34;

        $this->load->view('admin/listadoGaleria_view', $data);
    }

    public function insert($parent_id){}

    public function edit($bannerId)
    {

        $imageId = $this->uri->segment(6);
        $image = $this->Banners->getImage($imageId);
        $banner = $this->Banners->get($bannerId, 'es');

        $data['bannerImageEnabled'] = '';

        if($image->bannerImageEnabled)
            $data['bannerImageEnabled'] = 'checked="checked"';

        $data['bannerId'] = $bannerId;
        $data['imageId'] = $imageId;
        $data['titulo'] = "Modificar Imágen";
        $data['bannerImageName'] = $image->bannerImageName;
        $data['bannerImageLink'] = $image->bannerImageLink;
        $data['bannerImageExtension'] = $image->bannerImageExtension;
        $data['bannerImagenCoord'] = urlencode($image->bannerImagenCoord);
        $data['imagen'] = '';
        $data['txt_boton'] = "guardar imágen";
        $data['txt_botImagen'] = "Subir Imágen";
        $data['link'] = base_url('admin/sliders/image/update/' . $bannerId . '/' . $imageId);
        $camposRes = $this->Banners->getCampos();
        $data['width'] = $banner['bannerWidth'];
        $data['height'] = $banner['bannerHeight'];
        $data['nuevo'] = '';
        $data['removeUrl'] = '';

        $data['imagen'] = '';
        $data['imagenOrig'] = '';

        if($image->bannerImageExtension != '')
        {
            //Eliminamos el cache del navegador
            $extension = $image->bannerImageExtension;
            $extension = preg_replace('/\?+\d{0,}/', '', $extension);
            $data['imagen'] = '<img src="' . base_url() . 'assets/public/images/banners/banner_' . $bannerId . '_' . $imageId . '_admin.' . $extension . '?' . time() . '" />';
            $data['imagenOrig'] = base_url() . 'assets/public/images/banners/banner_' . $bannerId . '_' . $imageId . '_orig.' . $extension . '?' . time();
        }

        /*
         * TRADUCCIONES
         */
        $data['idiomas'] = $this->Idioma->getLanguages();

        $campos = array();

        foreach ($camposRes as $keyCampo => $campoResult) {

            $campo = new stdClass();
            $campo->inputId = $campoResult['inputId'];
            $campo->bannerCampoId = $campoResult['bannerCampoId'];
            $campo->bannerCampoNombre = $campoResult['bannerCampoNombre'];
            $campo->inputTipoContenido = $campoResult['inputTipoContenido'];

            $traducciones = array();

            foreach ($data['idiomas'] as $key => $idioma) {
                $traducciones[$idioma['idiomaDiminutivo']] = new stdClass();
                $traducciones[$idioma['idiomaDiminutivo']]->nombre = $idioma['idiomaNombre'];
                $tx = $this->Banners->getImageTranslation($idioma['idiomaDiminutivo'], $campoResult['bannerCampoId'], $imageId);
                $traducciones[$idioma['idiomaDiminutivo']]->contenido = new stdClass();
                if($tx)
                    $traducciones[$idioma['idiomaDiminutivo']]->contenido->bannerCamposTexto = $tx->bannerCamposTexto;
                else
                    $traducciones[$idioma['idiomaDiminutivo']]->contenido->bannerCamposTexto = '';
            }

            $campo->traducciones = $traducciones;

            array_push($campos, $campo);

        }

        $data['campos'] = $campos;

        $this->load->view('admin/sliders/bannersImagesCrear_view',$data);
    }

    public function update($bannerId)
    {

        $imageId = $this->uri->segment(6);
        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Banners->updateImage($bannerId, $imageId);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el banner!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function delete($bannerId)
    {

        $imageId = $this->uri->segment(6);
        $imagen = $this->Banners->getImage($imageId);

        //Eliminamos la imagen
        $imageExtension = preg_replace('/\?+\d{0,}/', '', $imagen->bannerImageExtension);

        //TODO: correctly delete all the images, using a DB query
        if(file_exists('./assets/public/images/banners/banner_' . $bannerId . '_' . $imageId . '.' . $imageExtension))
            unlink('./assets/public/images/banners/banner_' . $bannerId . '_' . $imageId . '.' . $imageExtension);

        if(file_exists('./assets/public/images/banners/banner_' . $bannerId . '_' . $imageId . '_thumb.' . $imageExtension))
            unlink('./assets/public/images/banners/banner_' . $bannerId . '_' . $imageId . '_thumb.' . $imageExtension);

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Banners->deleteImage($imageId);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la im&aacute;gen!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function reorder($id)
    {
        $this->Banners->reorder($id);
    }

    /**
     * Create form interface
     *
     * @param $parent_id
     * @return mixed
     */
    public function create($parent_id)
    {
        // TODO: Implement create() method.
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
        // TODO: Implement _showView() method.
    }

    /**
     * Inserts or updates the current model with the provided post data
     *
     * @param Model $model
     * @return mixed
     */
    public function _store(Model $model)
    {
        // TODO: Implement _store() method.
    }
}