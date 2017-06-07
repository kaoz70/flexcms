<div class="content">
	<form id="module_filtros">
		
		<h3>Categorias</h3>
		<select id="filtro_categorias">
			<? foreach ($categorias as $key => $categoria): ?>
			<option value="<?=$categoria['categoriaId']?>"><?=$categoria['productoCategoriaNombre']?></option>
			<? endforeach ?>
		</select>
		
		<? foreach ($filtros as $key1 => $filtro): ?>
		<h3><?=$filtro -> productoCampoValor ?></h3>
		<select class="campos" id="campo_<?=$filtro->productoCampoId?>">
			<? foreach ($filtro->productoFiltros as $key2 => $value): ?>
			<option value="<?=$value -> productoCampoRelContenido ?>"><?=$value -> productoCampoRelContenido ?></option>
			<? endforeach ?>
		</select>
		<? endforeach ?>
	<input type="submit" value="enviar" />
	</form>
</div>

<script type="text/javascript">
	
	var catalogFilters = {
		
		pathName: '',
		cleanPathName : '',
		filterArr: new Array(),
		
		init: function()
		{
			window.addEvent('domready', function() {
				document.id('module_filtros').addEvent('submit', catalogFilters.submitFilterHandler);
			});
		},
		
		submitFilterHandler: function(event)
		{
			
			event.preventDefault();
			
			var resultObj = {};
			var catArr = new Array();
			var filterArr = new Array();
			
			$$('#module_filtros select.campos').each(function(item, index){
				var checkObj = {};
				var id = item.get('id');
				
				checkObj.id = id.replace('campo_', '').toInt();
				
				var checkedArr = new Array();
				
				item.getElements(':selected').each(function(item, index){
					checkedArr.push(item.get('value'));
				});
				
				checkObj.filters = checkedArr;
				
				filterArr.push(checkObj);
			});
			
			
			$$('#module_filtros select#filtro_categorias').each(function(item, index){
				item.getElements(':checked').each(function(item, index){
					catArr.push(item.get('value'));
				});
			});
			
			resultObj.campos = filterArr;
			resultObj.categorias = catArr;
			
			catalogFilters.request(resultObj);
			
		},
		
		request: function(filters)
		{
			window.location = '<?=base_url($diminutivo.'/'.$page)?>/'+JSON.encode(filters).replace('/', '::');
		}
		
	}
	
	catalogFilters.init();

</script>