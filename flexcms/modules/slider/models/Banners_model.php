<?php
class Banners_model extends CI_Model
{

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function getAll($enabled = TRUE)
	{

		$this->db->cache_on();

		if($enabled) {
			$this->db->where('bannerEnabled', 1);
		}

		$this->db->where('bannerTemporal', 0);
		$query = $this->db->get('banners');

		$this->db->cache_off();

		return $query->result_array();
	}

	public function get($bannerId, $lang)
	{

		$this->db->where('bannerEnabled', 1);
		$this->db->where('bannerId', $bannerId);
		$query = $this->db->get('banners');

		$banner = $query->row_array();

		if($banner){

			//All the banner images
			$images = $this->getImages($banner['bannerId']);
			$banner['images'] = $images;

			//TODO fix multiple DB calls
			if(count($images) > 0) {
				foreach ($banner['images'] as $key => $value) {
					$banner['images'][$key]['labels'] = $this->getLabels($value['bannerImagesId'], $lang);
				}
			}

		}

		return $banner;
	}

	public function getImages($id, $enabled = TRUE)
	{
		$this->db->where('bannerId', $id);

		if($enabled){
			$this->db->where('bannerImageEnabled', 1);
		}

		$this->db->where('bannerImageTemporal', 0);
		$this->db->order_by('bannerImagenPosicion', 'asc');
		$query = $this->db->get('banner_images');
		return $query->result_array();
	}

	private function getLabels($ids, $lang)
	{
		$this->db->select('bannerCamposImagenId, bannerCampoPosicion, bannerCampoValor, bannerCamposTexto, bannerCampoClase, bannerCampoLabelHabilitado, bannerCampoLabel');
		$this->db->where('banner_campos_rel.bannerCamposImagenId', $ids);
		$this->db->where('bannerCamposTexto !=', 'NULL');
		$this->db->join('banner_campos', 'banner_campos.bannerCampoId = banner_campos_rel.bannerCampoId', 'left');
		$this->db->join($lang.'_banner_campos', $lang.'_banner_campos.bannerCampoId = banner_campos.bannerCampoId', 'LEFT');
		$this->db->join($lang.'_banner_campos_rel', $lang.'_banner_campos_rel.bannerCamposRelId = banner_campos_rel.bannerCampoRelId', 'LEFT');
		$this->db->order_by('bannerCampoPosicion', 'asc');
		$query = $this->db->get('banner_campos_rel');
		return $query->result();

	}


	/*******************************************************************************************************************
	 * ------------------------------------------------- ADMIN ---------------------------------------------------------
	 ******************************************************************************************************************/


	function add()
	{

		$data = array(
			'bannerType' => 'bxSlider',
			'bannerTemporal' => 1
		);

		$this->db->insert('banners', $data);

		return $this->db->insert_id();

	}

	function delete($id)
	{
		$this->db->where('bannerId', $id);
		$this->db->delete('banners');
	}


	/**
	 * Prepare the var so its value are correctly stored in the JSON string
	 *
	 * @param $item
	 * @return bool|mixed|null
	 */
	private function prepareVar($item)
	{

		//Check if boolean
		if($item === "true" OR $item === "false") {
			$item === "true" ? $item = true : $item;
			$item === "false" ? $item = false : $item;
			return $item;
		}

		//Check if null
		if($item === "null"){
			return null;
		}

		//Check if int
		$int = filter_var($item, FILTER_VALIDATE_INT);
		$int !== FALSE ? $item = $int : $item;

		//Check if float
		$float = filter_var($item, FILTER_VALIDATE_FLOAT);
		$float !== FALSE ? $item = $float : $item;

		return $item;
	}

	function update()
	{

		$id = $this->input->post('bannerId');

		$habilitado = 0;
		if($this->input->post('bannerEnabled') == 'on')
			$habilitado = 1;

        $config = NULL;

        //if config present
        if(array_key_exists($this->input->post('bannerType'), $this->input->post('config'))) {
            $config = $this->input->post('config')[$this->input->post('bannerType')];

            foreach ($config as $key => $item) {

                $config[$key] = $this->prepareVar($item);

                if(is_array($item)){
                    foreach($item as $key2 => $prop){
                        $config[$key][$key2] = $this->prepareVar($prop);
                    }
                }

            }
        }

		$data = array(
			'bannerName' => $this->input->post('bannerName'),
			'bannerClass' => $this->input->post('bannerClass'),
			'bannerType' => $this->input->post('bannerType'),
			'bannerWidth' => $this->input->post('bannerWidth'),
			'bannerHeight' => $this->input->post('bannerHeight'),
			'config' => json_encode($config, JSON_PRETTY_PRINT),
			'bannerEnabled' => $habilitado,
			'bannerTemporal' => 0
		);

		/*
		 * There's a weird bug in CI or PHP that prevents me from setting this in the previous array, or else "bannerName" will save as 1
		 */
		$data['bannerName'] = $this->input->post('bannerName');

		$this->db->where('bannerId', $id);
		$this->db->update('banners', $data);
	}

	public function getImage($id)
	{
		$this->db->where('bannerImagesId', $id);
		$query = $this->db->get('banner_images');
		return $query->row();
	}

	public function insertImage($bannerId)
	{

		$data = array(
			'bannerId' => $bannerId,
			'bannerImageTemporal' => 1
		);

		$this->db->insert('banner_images', $data);
		$lastInsertId = $this->db->insert_id();


		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];
			$campos = $this->input->post($dim.'_campos');

			if((bool)$campos)
			{
				$campoIds = array_keys($campos);

				for($i = 0 ; $i < count($campos) ; $i++)
				{

					$this->db->join('banner_campos_rel', 'banner_campos_rel.bannerCampoRelId = '.$dim.'_banner_campos_rel.bannerCamposRelId', 'LEFT');
					$this->db->where('banner_campos_rel.bannerCampoId', $campoIds[$i]);
					$this->db->where('bannerCamposImagenId', $lastInsertId);
					$query = $this->db->get($dim.'_banner_campos_rel');
					$resultTrans = $query->row();

					$dataIdioma = array(
						'bannerCamposTexto' => ''
					);

					if(count($resultTrans) != 0)
					{
						$this->db->where('bannerCamposRelId', $resultTrans->bannerCampoRelId);
						$this->db->update($dim.'_banner_campos_rel', $dataIdioma);
					}
					else {

						$dataIdiomaRel = array(
							'bannerCampoId' => $campoIds[$i],
							'bannerCamposImagenId' => $lastInsertId
						);
						$this->db->insert('banner_campos_rel', $dataIdiomaRel);

						$dataIdioma = array(
							'bannerCamposRelId' => $this->db->insert_id(),
							'bannerCamposTexto' => ''
						);

						$this->db->insert($dim.'_banner_campos_rel', $dataIdioma);
					}



				}
			}



		}

		return $lastInsertId;

	}

	public function updateImage($bannerId='', $imageId='')
	{
		$habilitado = 0;
		if($this->input->post('bannerImageEnabled') == 'on')
			$habilitado = 1;

		if($this->input->post('bannerImagen'))
		{
			$extension = preg_replace('/\?+\d{0,}/', '', $this->input->post('bannerImagen')) . '?' . time();
		}

		$data = array(
			'bannerImageName' => $this->input->post('bannerImageName'),
			'bannerImageLink' => $this->input->post('bannerImageLink'),
			'bannerId' => $this->input->post('bannerId'),
			'bannerImageExtension' => $extension,
			'bannerImageEnabled' => $habilitado,
			'bannerImageTemporal' => 0,
			'bannerImagenCoord' => urldecode($this->input->post('bannerImagenCoord'))
		);

		$this->db->where('bannerImagesId', $imageId);
		$this->db->update('banner_images', $data);

		$idiomas = $this->getLanguages();

		foreach ($idiomas as $key => $idioma) {

			$dim = $idioma['idiomaDiminutivo'];
			$campos = $this->input->post($dim.'_campos');

			if((bool)$campos)
			{
				$campoIds = array_keys($campos);

				for($i = 0 ; $i < count($campos) ; $i++)
				{

					//Revisamos si existe
					$this->db->join('banner_campos_rel', 'banner_campos_rel.bannerCampoRelId = '.$dim.'_banner_campos_rel.bannerCamposRelId', 'LEFT');
					$this->db->where('banner_campos_rel.bannerCampoId', $campoIds[$i]);
					$this->db->where('bannerCamposImagenId', $imageId);
					$query = $this->db->get($dim.'_banner_campos_rel');
					$resultTrans = $query->row();

					$dataIdioma = array(
						'bannerCamposTexto' => $campos[$campoIds[$i]]
					);

					if(count($resultTrans) != 0)
					{
						$this->db->where('bannerCamposRelId', $resultTrans->bannerCampoRelId);
						$this->db->update($dim.'_banner_campos_rel', $dataIdioma);
					}
					else {

						$dataIdiomaRel = array(
							'bannerCampoId' => $campoIds[$i],
							'bannerCamposImagenId' => $imageId
						);
						$this->db->insert('banner_campos_rel', $dataIdiomaRel);
						$this->db->insert_id();

						$dataIdioma = array(
							'bannerCamposRelId' => $this->db->insert_id(),
							'bannerCamposTexto' => $campos[$campoIds[$i]]
						);

						$this->db->insert($dim.'_banner_campos_rel', $dataIdioma);
					}

				}
			}

		}

	}

	public function deleteImage($id='')
	{
		$this->db->where('bannerImagesId', $id);
		$this->db->delete('banner_images');
	}

	public function reorder($bannerId)
	{

		//Obtenemos el string que viene del ajax y lo transformamos a array
		$id = json_decode($this->input->post('posiciones'), true);

		//Obtenemos todas las imagenes
		$this->db->where('bannerId', $bannerId);
		$query = $this->db->get('banner_images');

		//Ordenamos las preguntas segun el orden del arreglo de IDs
		for($i = 0 ; $i < $query->num_rows() ; $i++){

			$data = array(
				'bannerImagenPosicion' => $i + 1
			);

			if(array_key_exists($i, $id)) {
				$this->db->where('bannerImagesId', $id[$i]);
				$this->db->update('banner_images', $data);
			}

		}

	}



}