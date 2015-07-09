<?php

class Usuarios_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function get()
	{
		return $this->db
			->join('users_groups', 'users_groups.user_id = users.id', 'LEFT')
			->group_by('user_id')
			->get('users')->result();
	}

    function getUserByUsername($username)
    {
        $this->db->where('username', $username);
        $this->db->where('active', 1);
        $query = $this->db->get('users');
        return $query->row();
    }

    function updateAttempts($id, $attemp, $time = '')
    {

        $data = array(
            'failed_login_attempts' => $attemp,
            'blocked_login_time' => $time
        );

        $this->db->where('id', $id);
        $this->db->update('users', $data);

    }

    function getGroups($isSuperAdmin)
    {
        $this->db->order_by('order');
        if(!$isSuperAdmin)
            $this->db->where('id !=', 3);
        $query = $this->db->get('groups');
        return $query->result();
    }
	
	public function updateUserGroup($userId, $groupId)
	{
		
		$data = array(
        	'group_id' => $groupId
        );
		
		$this->db->where('user_id', $userId);
		$this->db->update('users_groups', $data);
	}

    public function getTemplate()
    {
        $this->db->join('es_user_fields', 'es_user_fields.userFieldId = user_fields.userFieldId', 'LEFT');
        $this->db->join('input', 'input.inputId = user_fields.inputId', 'LEFT');
        $this->db->order_by('userFieldPosition', 'asc');
        $query = $this->db->get('user_fields');
        return $query->result();
    }

    public function getUserFieldsTemplate($userId)
    {
        $this->db->join('es_user_fields', 'es_user_fields.userFieldId = user_fields.userFieldId', 'LEFT');
        $this->db->join('user_fields_rel', 'user_fields_rel.userFieldId = user_fields.userFieldId', 'LEFT');
        $this->db->join('input', 'input.inputId = user_fields.inputId', 'LEFT');
        $this->db->order_by('userFieldPosition', 'asc');
        $this->db->where('userId', $userId);
        $query = $this->db->get('user_fields');
        return $query->result();
    }

    function getInputs()
    {
        $this->db->where('input_seccion', 'usuarios');
        $this->db->order_by('inputTipoContenido', 'asc');
        $query = $this->db->get('input');

        return $query->result();
    }

    function guardarCampo()
    {

        $habilitado = 0;
        if($this->input->post('userFieldActive') == 'on')
            $habilitado = 1;

        $userFieldRequired = 0;
        if($this->input->post('userFieldRequired') == 'on')
            $userFieldRequired = 1;

        $data = array(
            'inputId' => $this->input->post('inputId'),
            'userFieldClass' => $this->input->post('userFieldClass'),
            'userFieldActive' => $habilitado,
            'userFieldRequired' => $userFieldRequired,
            'userFieldValidation' => $this->input->post('userFieldValidation')
        );

        $this->db->insert('user_fields', $data);
        $lastInsertId = $this->db->insert_id();

        $idiomas = $this->getLanguages();

        foreach ($idiomas as $key => $idioma) {

            $dim = $idioma['idiomaDiminutivo'];

            $dataIdioma = array(
                'userFieldId' => $lastInsertId,
                'userFieldLabel' => $this->input->post($dim.'_userFieldLabel'),
                'userFieldPlaceholder' => $this->input->post($dim.'_userFieldPlaceholder')
            );

            $this->db->insert($dim.'_user_fields', $dataIdioma);

        }

        //Create fields for each existing user
        $usuarios = $this->get();

        foreach ($usuarios as $key => $usuario) {
            $dataCampo = array(
                'userId' => $usuario->user_id,
                'userFieldId' => $lastInsertId
            );

            $this->db->insert('user_fields_rel', $dataCampo);
        }

        return $lastInsertId;

    }

    public function actualizarCampo()
    {

        $habilitado = 0;
        if($this->input->post('userFieldActive') == 'on')
            $habilitado = 1;

        $userFieldRequired = 0;
        if($this->input->post('userFieldRequired') == 'on')
            $userFieldRequired = 1;

        $data = array(
            'inputId' => $this->input->post('inputId'),
            'userFieldClass' => $this->input->post('userFieldClass'),
            'userFieldActive' => $habilitado,
            'userFieldRequired' => $userFieldRequired,
            'userFieldValidation' => $this->input->post('userFieldValidation')
        );

        $userFieldId = $this->input->post('userFieldId');

        $this->db->where('userFieldId', $userFieldId);
        $this->db->update('user_fields', $data);

        $idiomas = $this->getLanguages();

        foreach ($idiomas as $key => $idioma) {

            $dim = $idioma['idiomaDiminutivo'];



            $this->db->where('userFieldId', $userFieldId);
            $query = $this->db->get($dim.'_user_fields');

            /*
             * If field exists
             */
            if((bool)$query->result()) {

                $dataIdioma = array(
                    'userFieldLabel' => $this->input->post($dim.'_userFieldLabel'),
                    'userFieldPlaceholder' => $this->input->post($dim.'_userFieldPlaceholder')
                );

                $this->db->where('userFieldId', $userFieldId);
                $this->db->update($dim.'_user_fields', $dataIdioma);
            } else {
                $dataIdioma = array(
                    'userFieldId' => $userFieldId,
                    'userFieldLabel' => $this->input->post($dim.'_userFieldLabel'),
                    'userFieldPlaceholder' => $this->input->post($dim.'_userFieldPlaceholder')
                );

                $this->db->insert($dim.'_user_fields', $dataIdioma);
            }


        }

    }

    private function getLanguages()
    {
        $query = $this->db->get('idioma');
        return $query->result_array();
    }

    public function getCampoTranslation($diminutivo, $userFieldId)
    {
        $this->db->where('userFieldId', $userFieldId);
        $query = $this->db->get($diminutivo.'_user_fields');

        return $query->row();
    }

    public function getCampo($userFieldId)
    {
        $this->db->where('userFieldId', $userFieldId);
        $query = $this->db->get('user_fields');

        return $query->row();
    }

    public function eliminarCampo($id)
    {
        $this->db->where('userFieldId', $id);
        $this->db->delete('user_fields');
    }

    public function camposTraducciones($userId, $userFieldId, $idioma)
    {
        $this->db->join($idioma.'_user_fields', $idioma.'_user_fields.userFieldId = user_fields.userFieldId', 'LEFT');
        $this->db->join('user_fields_rel', 'user_fields.userFieldId = user_fields_rel.userFieldId', 'LEFT');
        $this->db->where('user_fields_rel.userFieldId', $userFieldId);
        $this->db->where('userId', $userId);
        $query = $this->db->get('user_fields');

        $return = new stdClass();
        $return->traduccion = $query->row();

        $this->db->join($idioma.'_user_fields', $idioma.'_user_fields.userFieldId = user_fields.userFieldId', 'LEFT');
        $this->db->where('user_fields.userFieldId', $userFieldId);
        $query = $this->db->get('user_fields');

        $return->label = $query->row();

        return $return;
    }

    public function insertar($user)
    {
        $campos = $this->input->post('campo');

        if($campos){
            foreach($campos as $key => $campo){

                $data = array(
                    'userFieldId' => $key,
                    'userId' => $user,
                    'userFieldRelContent' => $campo
                );

                $this->db->insert('user_fields_rel', $data);
            }

        }

    }

    public function actualizar($user)
    {

        $campos = $this->input->post('campo');

        foreach($campos as $key => $campo){

            $this->db->where('userFieldId', $key);
            $this->db->where('userId', $user);
            $query = $this->db->get('user_fields_rel');

            $result = $query->row();

            if(!(bool)$result){

                $data = array(
                    'userFieldId' => $key,
                    'userId' => $user,
                    'userFieldRelContent' => $campo
                );

                $this->db->insert('user_fields_rel', $data);

            } else {
                $data = array(
                    'userFieldRelContent' => $campo
                );

                $this->db->where('userFieldId', $key);
                $this->db->where('userId', $user);
                $this->db->update('user_fields_rel', $data);
            }

        }


    }

    public function reorganizarCampos()
    {

        //Obtenemos el string que viene del ajax y lo transformamos a array
        $id = json_decode($this->input->post('posiciones'), true);

        //Obtenemos todos los campos del FAQ
        $query = $this->db->get('user_fields');

        //Obtenemos el numero de campos totales
        $numCampos = $query->num_rows();

        //Ordenamos las preguntas segun el orden del arreglo de IDs
        for($i = 0 ; $i < $numCampos ; $i++){

            $data = array(
                'userFieldPosition' => $i + 1
            );

            $this->db->where('userFieldId', $id[$i]);
            $this->db->update('user_fields', $data);

        }

    }

    public function countries()
    {
        $query = $this->db->get('user_countries');
        return $query->result();
    }


}