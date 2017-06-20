<?php use App\ImageSection;
use App\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ImageConfig extends RESTController {

    private function getAll()
    {
        return ImageSection::getBySection('content');
    }

    public function index_get($page_id, $image_id = null)
    {

        $response = new Response();

        try{

            //One image config
            if($image_id) {

                $image = \App\ImageConfig::find($image_id);

                $data = [
                    'image' => $image,
                    'watermark' => $image->watermark(),
                ];

                $response->setData($data);

            }

            //All image configs
            else {
                $response->setData($this->getAll());
            }

        } catch (\Illuminate\Database\QueryException $e) {
            $response->setError('Ocurri&oacute; un problema al obener las imagenes!', $e);
        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al obener las imagenes!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    /**
     * Create new image configuration
     */
    public function index_post()
    {

        $response = new Response();

        try{

            $image = \App\ImageConfig::store(new \App\ImageConfig(), $this->post('image'), $this->post('file'));
            $image->position = \App\ImageConfig::all()->count() + 1;
            $image->save();

            $response->setData($this->getAll());

        } catch (\Illuminate\Database\QueryException $e) {
            $response->setError('Ocurri&oacute; un problema al obener las imagenes!', $e);
        }catch (FileException $e) {
            $response->setError('Ocurri&oacute; un problema al obener las imagenes!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    /**
     * Update an image configuration
     * @param $id
     */
    public function index_put($id)
    {

        $response = new Response();

        try{

            $image = \App\ImageConfig::store(\App\ImageConfig::find($id), $this->put('image'), $this->put('file'));
            $image->save();

            $response->setData($this->getAll());

        } catch (\Illuminate\Database\QueryException $e) {
            $response->setError('Ocurri&oacute; un problema al obener las imagenes!', $e);
        }catch (FileException $e) {
            $response->setError('Ocurri&oacute; un problema al obener las imagenes!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    /**
     * Deletes a image config
     * @param $id
     */
    public function index_delete($id)
    {

        $response = new Response();

        try{

            //Delete the content
            $image = \App\ImageConfig::find($id);
            $image->deleteWatermark();
            $image->delete();

            $response->setData($this->getAll());
            $response->setMessage('Configuraci&oacute;n de imagen eliminada correctamente');

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al eliminar la configuraci&oacute;n de imagen!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

    public function reorder_put($section_id)
    {

        $response = new Response();

        try{
            foreach ($this->put() as $section) {
                \App\ImageConfig::reorder($section['items'], $section['id']);
            }
            $response->setMessage('Se guard&oacute; el nuevo orden de elementos');
        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al reorganizar los elementos!', $e);
        }

        $this->response($response, $response->getStatusHeader());

    }

}
