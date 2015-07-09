<?php
use Cartalyst\NestedSets\Nodes\EloquentNode;

class GalleryTree extends EloquentNode {

	public $lang = 'es';

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'descargas_categorias';

	protected $worker = 'LangWorker';
	
	protected $fillable = ['temporal'];

	protected function loadTree($depth = 0, Closure $callback = null)
	{

		$tree = $this->createWorker()->treeLang($this, $depth, array(
			'table' => "{$this->lang}_descargas_categorias",
			'column' => 'descargaCategoriaId',
			'trans_columns' => array('descargaCategoriaNombre', 'descargaCategoriaUrl'),
		), $callback);

		// The tree method from the worker is none-the-wiser
		// to whether we are retrieving a root node or not. If
		// we only have one child, it will therefore return a
		// singular object. We'll ensure we're actually returning
		// an array.
		if ( ! is_array($tree))
		{
			$tree = array($tree);
		}

		return $tree;
	}

	/**
	 * Returns the parent for the node.
	 *
	 * @return Cartalyst\NestedSets\Nodes\EloquentNode
	 */
	public function getParent()
	{
		return $this->createWorker()->parentNodeOverride($this);
	}

}

