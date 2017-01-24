<?php use App\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ImageConfig extends RESTController {

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
                $response->setData(\App\ImageConfig::where('category_id', $page_id)->orderBy('position', 'asc')->get());
            }

        } catch (\Illuminate\Database\QueryException $e) {
            $response->setError('Ocurri&oacute; un problema al obener las imagenes!', $e);
        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al obener las imagenes!', $e);
        }

        $this->response($response);

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

            $response->setData(\App\ImageConfig::where('category_id', $image->category_id)->orderBy('position', 'asc')->get());

        } catch (\Illuminate\Database\QueryException $e) {
            $response->setError('Ocurri&oacute; un problema al obener las imagenes!', $e);
        }catch (FileException $e) {
            $response->setError('Ocurri&oacute; un problema al obener las imagenes!', $e);
        }

        $this->response($response);

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

            $response->setData(\App\ImageConfig::where('category_id', $image->category_id)->orderBy('position', 'asc')->get());

        } catch (\Illuminate\Database\QueryException $e) {
            $response->setError('Ocurri&oacute; un problema al obener las imagenes!', $e);
        }catch (FileException $e) {
            $response->setError('Ocurri&oacute; un problema al obener las imagenes!', $e);
        }

        $this->response($response);

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

            $response->setData(\App\ImageConfig::orderBy('position', 'asc')->get());
            $response->setMessage('Idioma configuraci&oacute;n de imagen eliminada correctamente');

        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al eliminar la configuraci&oacute;n de imagen!', $e);
        }

        $this->response($response);

    }

    public function reorder_put($page_id)
    {

        $response = new Response();

        try{
            \App\ImageConfig::reorder($this->put(), $page_id);
            $response->setMessage('Se guard&oacute; el nuevo orden de elementos');
        } catch (Exception $e) {
            $response->setError('Ocurri&oacute; un problema al reorganizar los idiomas!', $e);
        }

        $this->response($response);

    }

}
