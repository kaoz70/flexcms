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


    public function getCampos($idioma)
    {
        $this->db->join($idioma.'_user_fields', $idioma.'_user_fields.userFieldId = user_fields.userFieldId', 'LEFT');
        $this->db->join('input', 'input.inputId = user_fields.inputId', 'LEFT');
        $this->db->order_by('userFieldPosition', 'ASC');
        $query = $this->db->get('user_fields');
        return $query->result();
    }

    public function insertarCampos($user)
    {
        $campos = $this->input->post('campo');

		//Maybe facebook?
		if($campos) {
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

    public function actualizarCampos($user)
    {
        $campos = $this->input->post('campo');

        foreach($campos as $key => $campo){

            $data = array(
                'userFieldRelContent' => $campo
            );

            $this->db->where('userFieldId', $key);
            $this->db->where('userId', $user);
            $this->db->update('user_fields_rel', $data);
        }

    }

    public function getCamposUser($userId, $idioma)
    {
        $this->db->join($idioma.'_user_fields', $idioma.'_user_fields.userFieldId = user_fields.userFieldId', 'LEFT');
        $this->db->join('input', 'input.inputId = user_fields.inputId', 'LEFT');
        $this->db->join('user_fields_rel', 'user_fields_rel.userFieldId = user_fields.userFieldId', 'LEFT');
        $this->db->where('userId', $userId);
        $this->db->order_by('userFieldPosition', 'ASC');
        $query = $this->db->get('user_fields');

        return $query->result();
    }

    public function countries()
    {
        $query = $this->db->get('user_countries');
        return $query->result();
    }

}