<?

class RESTController extends REST_Controller
{

    /**
     * Returns the site's structure
     *
     * @return \Baum\Extensions\Eloquent\Collection
     */
    protected function tree()
    {
        //Get the root page
        $root = \App\Page::find(1);
        $lang = \App\Language::getDefault();

        //Baum's toHierarchy() returns an object for root nodes, this returns an array
        //@link https://github.com/etrepat/baum/issues/213
        $roots = new \Baum\Extensions\Eloquent\Collection();
        foreach ($root->getDescendantsLang($lang)->toHierarchy() as $child) {
            $roots->add($child);
        }

        return $roots;
    }

}