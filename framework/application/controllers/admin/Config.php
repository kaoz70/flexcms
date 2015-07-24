<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Config extends MY_Controller {
	 
	function __construct(){
		parent::__construct();

		$this->load->model('configuracion_model', 'Config');
		$this->load->model('idiomas_model', 'Idioma');
		$this->load->model('admin/page_model', 'Paginas');
        $this->load->helper('text');
		$this->load->library('Seguridad');
		$this->seguridad->init();
		
	}
	
	public function index()
	{
        $data['isSuperAdmin'] = FALSE;

        if($this->ion_auth->in_group('superadmin'))
            $data['isSuperAdmin'] = TRUE;

        $this->load->view('admin/config/index_view', $data);
	}

    public function general(){

        $data['config'] = $this->Config->get();
        $data['titulo'] = 'General';
        $data['txt_guardar'] = 'Guardar Configuraci&oacute;n';
        $data['paginas'] = $this->Paginas->getPages();
        $data['themes'] = directory_map('./themes/', 1);

        //keep folders
        foreach ($data['themes'] as $key => $path) {

	        //Remove any slash or backslash
	        $path = str_replace('/', '', $path);
	        $path = str_replace('\\', '', $path);

	        $data['themes'][$key] = $path;
	        //Remove any files
            if(strpos($path, '.')){
                unset($data['themes'][$key]);
            }
        }

        $data['isSuperAdmin'] = FALSE;

        if($this->ion_auth->in_group('superadmin'))
            $data['isSuperAdmin'] = TRUE;

        $this->load->view('admin/config/general_view', $data);

    }

	public function social()
	{
		$data['config'] = $this->Config->get();
		$data['titulo'] = 'Social';
		$data['txt_guardar'] = 'Guardar Configuraci&oacute;n';
		$this->load->view('admin/config/social_view', $data);
	}

    public function sections(){

        $data['titulo'] = 'Secciones';
        $data['txt_guardar'] = 'Guardar';

        $data['secciones_todas'] = $this->Config->getSecciones();
        $data['secciones_cliente'] = $this->Config->getSecciones(1);

        //Remove the duplicate ones. TODO: optimize this code
        foreach ($data['secciones_todas'] as $key => $seccion_toda)
        {
            foreach ($data['secciones_cliente'] as $seccion_cliente)
            {
                if($seccion_toda->adminSeccionId === $seccion_cliente->adminSeccionId)
                {
                    unset($data['secciones_todas'][$key]);
                }
            }
        }

        $seccionesAdmin = $data['secciones_cliente'];
        $seccionesAdminArr = array();

        foreach($seccionesAdmin as $sec)
        {
            array_push($seccionesAdminArr, $sec->adminSeccionId);
        }

        $data['seccionesAdmin'] = htmlspecialchars(json_encode($seccionesAdminArr));

        $data['isSuperAdmin'] = FALSE;

        if($this->ion_auth->in_group('superadmin'))
            $data['isSuperAdmin'] = TRUE;

        $this->load->view('admin/config/secciones_view', $data);

    }

    public function images(){
        $data['secciones'] = $this->getSectionGroups();
        $data['imagenes'] = $this->Config->getImagenes();

        $this->load->view('admin/config/imagenes_view', $data);
    }

    public function create_image(){

        $data['secciones'] = $this->getSectionGroups();
        $data['imagenSufijo'] = '';
        $data['imagenAncho'] = '';
        $data['imagenAlto'] = '';
        $data['imagenNombre'] = '';
        $data['imagenSeccionId'] = '';
        $data['imagenCrop'] = '';
        $data['imagenId'] = '';
        $data['removeUrl'] = base_url('admin/config/delete_image/');

        $data['titulo'] = 'Nueva Imágen';
        $data['txt_boton'] = 'Crear Imágen';
        $data['link'] = base_url('admin/config/insert_image/');
        $data['nuevo'] = 'nuevo';

        $this->load->view('admin/config/image_view', $data);
    }

    public function edit_image(){

        $imagen = $this->Config->getImagen($this->uri->segment(4));

        $imagenId = $imagen->imagenId;

        $data['secciones'] = $this->getSectionGroups();
        $data['imagenSufijo'] = $imagen->imagenSufijo;
        $data['imagenAncho'] = $imagen->imagenAncho;
        $data['imagenAlto'] = $imagen->imagenAlto;
        $data['imagenNombre'] = $imagen->imagenNombre;
        $data['imagenSeccionId'] = $imagen->imagenSeccionId;
        $data['imagenId'] = $imagenId;
        $data['removeUrl'] = '';

        $data['imagenCrop'] = '';
        if($imagen->imagenCrop){
            $data['imagenCrop'] = 'checked="checked"';
        }

        $data['titulo'] = 'Modificar Imágen';
        $data['txt_boton'] = 'Modificar Imágen';
        $data['link'] = base_url('admin/config/update_image/' . $imagenId);
        $data['nuevo'] = '';

        $this->load->view('admin/config/image_view', $data);
    }

    public function insert_image()
    {
        $response = new stdClass();
        $response->error_code = 0;

        try{
            $id = $this->Config->createImagen();
            $response->new_id = $id;
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al crear la im&aacute;gen!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function delete_image()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Config->eliminarImagen($this->uri->segment(4));
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar la im&aacute;gen!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    public function update_image()
    {

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Config->actualizarImagen($this->uri->segment(4));
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar la im&aacute;gen!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }


    public function reorder_images($id){

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Config->reorganizarImagenes($id);
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al reorganizar las im&aacute;genes!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }

    private function getSectionGroups(){
        $secciones = $this->Config->getSeccionesImagenes();

        $groups=array();
        foreach ($secciones as $seccion) {
            $groups[$seccion->adminSeccionId][] = $seccion;
        }

        return$groups;
    }
	
	public function save()
	{

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Config->save();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al guardar!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}

    public function save_sections(){

        $response = new stdClass();
        $response->error_code = 0;

        try{
            $this->Config->guardarSecciones();
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al guardar!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

    }
	
	
}
