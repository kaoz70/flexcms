<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 7/24/15
 * Time: 5:02 PM
 */

namespace cart;
$_ns = __NAMESPACE__;

include_once("Cart.php");

class DiscountItems extends Cart {

	public function edit($id)
	{

		$this->load->model('admin/catalogo_model', 'Catalogo');

		$data['titulo'] = 'Productos';
		$data['txt_guardar'] = 'Guardar';
		$data['grupo_id'] = $id;

		$data['items_seleccionados'] = $this->Cart->getGrupoDescuentoItems($id);
		$data['items_todos'] = $this->menuSubcategories(0, $data['items_seleccionados']);

		$seccionesAdmin = $data['items_seleccionados'];

		$seccionesAdminArr = array();

		foreach($seccionesAdmin as $sec)
		{
			array_push($seccionesAdminArr, $sec->disc_group_item_id);
		}

		$data['seccionesAdmin'] = htmlspecialchars(json_encode($seccionesAdminArr));

		$this->load->view('admin/cart/gruposDescuentoItems_view.php', $data);

	}

	public function update($id)
	{
		$this->Cart->insert_discount_group_items($id);
	}

	/**
	 * Build the HTML for the category groups
	 */
	function menuSubcategories($root_id = 0, $current_items = array())
	{
		$this->html  = array();
		$this->items = $this->Catalogo->getCategories();

		foreach ( $this->items as $item )
			$children[$item['categoriaPadre']][] = $item;

		// loop will be false if the root has no children (i.e., an empty menu!)
		$loop = !empty( $children[$root_id] );

		// initializing $parent as the root
		$parent = $root_id;
		$parent_stack = array();

		// HTML wrapper for the menu (open)
		$this->html[] = '';

		while ( $loop && ( ( $option = each( $children[$parent] ) ) || ( $parent > $root_id ) ) )
		{

			if ( $option === false )
			{
				$parent = array_pop( $parent_stack );

				// HTML for menu item containing childrens (close)
				$this->html[] = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 ) . '</ul>';
				$this->html[] = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 ) . '</li>';
			}
			elseif ( !empty( $children[$option['value']['categoriaId']] ) )
			{
				$tab = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 );

				//Get category items
				$items = $this->Catalogo->getProductos($option['value']['categoriaId']);

				$itemHtml = $this->generateItemHtml($items, $option['value']['categoriaId'], $tab, $current_items);

				// HTML for menu item containing childrens (open)
				$this->html[] = sprintf(
					'%1$s<li class="pagina field">',
					$tab // %1$s = tabulation
				);
				$this->html[] = $tab . "\t" . '<h3 class="header">Categoría: '.$option['value']['productoCategoriaNombre'].'</h3>';
				$this->html[] = $tab . $itemHtml;
				$this->html[] = $tab . "\t" . '<ul id="list_'.$option['value']['categoriaId'].'" class="sorteable content" data-sort="admin/descargas/reorganizarDescargas/'.$option['value']['categoriaPadre'].'">';

				array_push( $parent_stack, $option['value']['categoriaPadre'] );
				$parent = $option['value']['categoriaId'];
			}
			else
			{

				$tab = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 );

				$items = $this->Catalogo->getProductos($option['value']['categoriaId']);

				$itemHtml = $this->generateItemHtml($items, $option['value']['categoriaId'], $tab, $current_items);

				// HTML for menu item with no children (aka "leaf")
				$this->html[] = sprintf(
					'%1$s<li class="pagina field">',
					$tab // %1$s = tabulation
				);

				$this->html[] = $tab . "\t\t\t" . '<h3 class="header">Categoría: '.$option['value']['productoCategoriaNombre'].'</h3>';
				$this->html[] = $tab . $itemHtml;
				$this->html[] = $tab . "\t\t" . '</li>';

			}

		}

		// HTML wrapper for the menu (close)
		$this->html[] = '';

		return implode( "\r\n", $this->html );
	}

	function generateItemHtml($items, $catId, $tab, $current_items){

		//remove any duplicated items
		foreach($items as $key => $item){
			foreach ($current_items as $i){
				if($item['productoId'] === $i->productoId){
					unset($items[$key]);
				}
			}
		}

		$itemHtml = $tab . '<ul id="p_list_'.$catId.'" class="sorteable content secciones" >' . PHP_EOL;
		foreach($items as $item){
			$itemHtml .= $tab . '<li class="listado drag" id="'.$item['productoId'].'">' . PHP_EOL;
			$itemHtml .= $tab . "\t" . '<div class="mover">mover</div>' . PHP_EOL;
			$itemHtml .= $tab . "\t" . '<a class="nombre modificar nivel2" href="'.base_url('admin/catalogo/modificarProducto/'.$item['productoId']).'">'.$item['productoNombre'].'</a>' . PHP_EOL;
			$itemHtml .= $tab . "\t" . '<a href="'.base_url('admin/catalogo/eliminarProducto/'.$item['productoId']).'" class="eliminar" >eliminar</a>' . PHP_EOL;
			$itemHtml .= $tab . '</li>' . PHP_EOL;
		}
		$itemHtml .= $tab . "\t" . '</ul>' . PHP_EOL;

		return $itemHtml;
	}

}