<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/26/15
 * Time: 3:07 PM
 */

namespace App;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Database\Eloquent\Model;

class User extends Model {

    const SECTION = 'user';

    static function updateData($id, $post, $section)
    {

        $config = Config::get('auth');
        $activate = (bool) $config['automatic_activation'];

        //Register the user
        $data = array(
            'first_name' => $post['first_name'],
            'last_name' => $post['last_name'],
            'image_extension' => $post['image_extension'],
            'image_coord' => urldecode($post['image_coord']),
            'password' => $post['userPass1'],
            'email' => $post['email'],
        );

        $user = Sentinel::findById($id);

        //If there is no user create one
        if(!$user) {

            //Check passwords
            if($post['userPass1'] != $post['userPass2']) {
                throw new \BadMethodCallException("Las contrase&ntilde;as no coinciden");
            }

            $user = Sentinel::register($data, $activate);

            //Create the user's fields
            foreach (Field::where('section', $section)->get() as $field) {
                $fieldData = new FieldData();
                $fieldData->parent_id = $user->id;
                $fieldData->field_id = $field->id;
                $fieldData->section = static::SECTION;
            }

        } else {
            $user = Sentinel::update($user, $data);
        }

        //Update the role
        if(isset($post['roles'])) {

            //Remove user from any role
            $roles = Sentinel::getRoleRepository()->all();
            foreach ($roles as $role) {
                $role->users()->detach($user);
            }

            //Add the user to the role
            $role = Sentinel::findRoleById($post['roles']);
            if(!$user->inRole($role)) {
                $role->users()->attach($user);
            }

        }

        //Set the user fields
        $fields = Field::where('section', static::SECTION)->get();

        foreach ($fields as $field) {

            $fieldData = FieldData::userData($user, $field);
            if(!$fieldData) {
                $fieldData = new FieldData();
                $fieldData->parent_id = $user->id;
                $fieldData->field_id = $field->id;
                $fieldData->section = static::SECTION;
            }

            $fieldData->data = $post['campo'][$field->id];
            $fieldData->save();

        }

        //Activate the user
        static::activate((bool)$post['active'], $user);

        //TODO: Set the user's permissions
        //$this->setPermissions($user);

        return $user;

    }

    private static function activate($active, $user)
    {
        $activationRepo = Sentinel::getActivationRepository();

        if($active != (bool) $activationRepo->completed($user)) {

            if($active) {

                //If no actication exists create a new one
                if(!$activation = $activationRepo->exists($user)) {
                    $activation = $activationRepo->create($user);
                }

                //Activate the user
                if($activationRepo->complete($user, $activation->code)) {
                    //mandar mail activacion
                    /*$output = 'Su cuenta en ' . $_SERVER['SERVER_NAME'] . ' ha sido activada.';

                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $this->email->from('contacto@' . str_replace('www.', '', $_SERVER['SERVER_NAME']), 'Website Mailer');
                    $this->email->to($this->input->post('email'));
                    $this->email->subject('Contact form ' . $_SERVER['SERVER_NAME']);
                    $this->email->message($output);*/
                }

            }
            else {

                $activationRepo->remove($user);

                //mandar mail desactivacion
                $output = 'Ud ha sido desactivado de ' . $_SERVER['SERVER_NAME'];

                /*$config['mailtype'] = 'html';
                $this->email->initialize($config);
                $this->email->from('contacto@' . str_replace('www.', '', $_SERVER['SERVER_NAME']), 'Website Mailer');
                $this->email->to($this->input->post('email'));
                $this->email->subject('Contact form ' . $_SERVER['SERVER_NAME']);
                $this->email->message($output);*/

            }

            //$this->email->send();

        }
    }

}