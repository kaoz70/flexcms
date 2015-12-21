<?php

namespace admin\auth;
use AdminInterface;
use App\ImageSection;
use App\UserField;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Users\EloquentUser;
use Exception;
use Illuminate\Database\Eloquent\Model;
use stdClass;

$_ns = __NAMESPACE__;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class User extends Main implements AdminInterface {

	public function create()
	{
		var_dump($this->baseUrl);
		$this->_showView();
	}
	
	public function edit($id)
	{
		// Get the user object
		if ($user = $this->users->find($id)) {
			$this->_showView($user);
		} else {
			$this->load->view('admin/request/json', array('return' => "El usuario con id {$id} no existe"));
		}
	}

	public function _showView( Model $user = null )
	{

		$data['user'] = $user ?: $this->users->createModel();

		// The current user roles
		$data['userRoles'] = $data['user']->roles->lists('id')->toArray();

		// Get all the available roles
		$data['roles'] = $this->roles->createModel()->all();

		$data['fields'] = UserField::where('section', 'auth')->get();
		$data['link'] = $user ?
			base_url("auth/admin/user/update/" . $user->id) :
			base_url("auth/admin/user/insert");
		$data['imagen'] = '';
		$data['imagenOrig'] = '';
		$data['imagenExtension'] = '';
		$data['cropDimensions'] = ImageSection::find(13);
		$data['usuarioImagenCoord'] = '';
		$data['nuevo'] = $user ? FALSE : TRUE;
		$data['active'] = $this->activation->completed($data['user']);

		// Show the form
		$this->load->view('user_view', $data);

	}

	public function insert()
	{

		$response = new stdClass();
		$response->error_code = 0;

		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');

		if ($this->form_validation->run() === FALSE)
		{
			$response->error_code = 1;
			$response->message = 'Error';
			$response->error_message = validation_errors();
			return $this->load->view('admin/request/json', array('return' => $response));
		}

		try {
			// Create the user
			if($user = $this->users->create($this->input->post())) {
				// Activate the user automatically
				$this->activation->complete(
					$user,
					$this->activation->create($user)->code
				);
				// Handle the user roles
				$this->handleUserRoles($user, $this->input->post('roles'));
				$response->new_id = $user->id;
			} else {
				$response->error_code = 1;
				$response->message = 'Ocurri&oacute; un problema al crear el usuario!';
			}

		} catch (\Exception $e) {
			$response = $this->error('Ocurri&oacute; un problema al crear el usuario!', $e);
		}

        $this->load->view('admin/request/json', array('return' => $response));

	}

    public function update($id)
    {

	    $response = new stdClass();
	    $response->error_code = 0;

	    try {
		    // Get the user object
		    $user = $this->users->find($id);
		    // Get the submited request data
		    $input = $this->input->post();
		    // Check if we should remove the password from the
		    // submitted data, this is because the password
		    // is not required when updating a user.
		    $input = array_where($input, function($key, $value)
		    {
			    return (str_contains($key, 'password') && empty($value)) ? false : true;
		    });
		    // Get the submitted roles
		    $roles = array_get($input, 'roles', []);
		    // Update the user
		    $this->users->update($user, $input);
		    // Handle the user roles
		    $this->handleUserRoles($user, $roles);
	    } catch (\Exception $e) {
		    $response = $this->error('Ocurri&oacute; un problema al actualizar el usuario!', $e);
	    }

	    $this->load->view('admin/request/json', array('return' => $response));


	    /* $this->load->library('ion_auth');

		 $id = $this->input->post('userId');

		 $active = 0;
		 if($this->input->post('active') == 'on')
			 $active = 1;

		 $activeOld = $this->input->post('activeOld');

		 if($active != $activeOld)
		 {

			 if($active == 0)
			 {
				 //mandar mail desactivacion
				 $output = 'Ud ha sido desactivado de ' . $_SERVER['SERVER_NAME'];

				 $config['mailtype'] = 'html';
				 $this->email->initialize($config);
				 $this->email->from('contacto@' . str_replace('www.', '', $_SERVER['SERVER_NAME']), 'Website Mailer');
				 $this->email->to($this->input->post('email'));
				 $this->email->subject('Contact form ' . $_SERVER['SERVER_NAME']);
				 $this->email->message($output);
			 }
			 else {
				 //mandar mail activacion
				 $output = 'Su cuenta en ' . $_SERVER['SERVER_NAME'] . ' ha sido activada.';

				 $config['mailtype'] = 'html';
				 $this->email->initialize($config);
				 $this->email->from('contacto@' . str_replace('www.', '', $_SERVER['SERVER_NAME']), 'Website Mailer');
				 $this->email->to($this->input->post('email'));
				 $this->email->subject('Contact form ' . $_SERVER['SERVER_NAME']);
				 $this->email->message($output);

			 }

			 $this->email->send();

		 }

		 $data = array(
			 'first_name' => $this->input->post('first_name'),
			 'last_name' => $this->input->post('last_name'),
			 'username' => $this->input->post('username'),
			 'email' => $this->input->post('email'),
			 'active' => $active,
			 'usuarioImagen' => $this->input->post('usuarioImagen'),
			 'usuarioImagenCoord' => urldecode($this->input->post('usuarioImagenCoord'))
		 );

		 if($this->input->post('userPass1'))
			 $data['password'] = $this->input->post('userPass1');

		 $this->ion_auth->update($id, $data);

		 //For some reason Ion Auth doesnt update user group
		 $this->Usuarios->updateUserGroup($id, $this->input->post('group'));

		 $response = new stdClass();
		 $response->error_code = 0;

		 try{
			 $this->Usuarios->actualizar($id);
		 } catch (Exception $e) {
			 $response = $this->cms_general->error('Ocurri&oacute; un problema al actualizar el usuario!', $e);
		 }

		 $this->load->view('admin/request/json', array('return' => $response));*/

    }

	public function delete($id)
	{

		$response = new stdClass();
		$response->error_code = 0;

        try{
	        // Check if we're not deleting ourselves
	        if (Sentinel::getUser()->id != $id) {
		        // Get the user object
		        if ($user = $this->users->find($id)) {
			        // Delete the user
			        $user->delete();
		        } else {
			        $response->error_code = 1;
			        $response->message = 'Error';
			        $response->error_message = "El usuario con id {$id} no existe";
			        return $this->load->view('admin/request/json', array('return' => $response));
		        }
	        } else {
		        $response->error_code = 1;
		        $response->message = 'Error';
		        $response->error_message = 'No se puede eliminar a si mismo';
		        return $this->load->view('admin/request/json', array('return' => $response));
	        }
        } catch (Exception $e) {
            $response = $this->cms_general->error('Ocurri&oacute; un problema al eliminar el usuario!', $e);
        }

        $this->load->view('admin/request/json', array('return' => $response));

	}

	/**
	 * Handles the processing of the given user roles.
	 *
	 * @param  \Cartalyst\Sentinel\Users\EloquentUser
	 * @param  array  $roles
	 * @return void
	 */
	protected function handleUserRoles(EloquentUser $user, array $roles)
	{
		// Get the user roles
		$userRoles = $user->roles->lists('id')->toArray();
		// Prepare the roles to be added and removed
		$toAdd = array_diff($roles, $userRoles);
		$toDel = array_diff($userRoles, $roles);
		// Attach the user roles
		if (! empty($toAdd)) {
			$user->roles()->attach($toAdd);
		}
		// Detach the user roles
		if (! empty($toDel)) {
			$user->roles()->detach($toDel);
		}
	}

}