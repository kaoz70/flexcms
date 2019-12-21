<?php use App\Config;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use App\Language;

class Page extends MX_Controller {

    /**
     * Stores the site's configuration data
     *
     * @var array
     */
    public $config;

    /**
     * Language data
     *
     * @var
     */
    public $language;

    /**
     * Data array of all the variables that will be passed into the views
     *
     * @var
     */
    public $data;

    /**
     * Stores the current page Class
     * @var
     */
    private $page;

    /**
     * Reference to the Class singleton
     *
     * @var	object
     */
    private static $instance;

    function __construct(){
        parent::__construct();
        self::$instance =& $this;

        $this->config = Config::get();
        define('THEMEPATH', APPPATH . 'themes/' . $this->config['theme']);
    }

    /**
     * Remove the need to call the page() method in the URL we can use / instead of html/page/
     */
    public function _remap()
    {
        //If the URL is a page
        //TODO fix this
        if($this->uri->segment(1) AND $this->uri->segment(2)) {
            $this->createPage($this->uri->segment(2));
        }

        //If no URL, its the index page
        else {
            $this->index();
        }

    }

    /**
     * Reference to the Class method.
     *
     * Returns current Class instance object
     *
     * @return object
     */
    static function &get_instance()
    {
        return self::$instance;
    }

    /**
     * Index method, called when no URI segment is present, it guesses the users preferred language and redirects to
     * the home page in the correct language or defaults to spanish home page
     */
    private function index()
    {
        try {
            $this->language = Language::preferred();
            $this->page = Config::siteIndex($this->language->id);
            $this->createPage($this->page);
        } catch(Exception $e) {
            show_error($e->getMessage(), 500, 'Error');
        }
    }

    /**
     * Main method where all the pages pass through,
     * it constructs the page with all the bits and pieces of info around
     *
     * @param \App\Page $page
     */
    private function createPage(\App\Page $page)
    {
        try {
            //dd($page->data);
            \App\View::blade(APPPATH . 'views/structure', $page->data);
        } catch (Throwable $e) {
            show_error($e->getMessage(), 500, 'Error');
        }
    }



}
