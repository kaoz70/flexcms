<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 10-Nov-16
 * Time: 05:37 PM
 */

namespace App;


use Illuminate\Support\Collection;

class EditTranslations
{

    private $translations;
    private $content;

    public function __construct()
    {
        $this->translations = new Collection();
    }

    public function add(Language $language, $content)
    {

        $langArr = [
            'id' => $language->id,
            'name' => $language->name,
            'translation' => $content
        ];

        return $this->translations->push($langArr);

    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return Collection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    public function getAll()
    {

        $content = $this->getContent();
        $content->translations = $this->getTranslations();

        return $content;
    }

}