<div class="main_content">

    <fb:registration
        fields="name,email"
        redirect-uri="<?=base_url($diminutivo.'/'.$pagina_url.'/register/facebook')?>"
        width="530">
    </fb:registration>

	<div class="iniciar_sesion">
		<p class="ya_miembro">¿Ya eres miembro? <a href="<?=base_url($diminutivo . '/' . $pagina_url)?>">Iniciar sesión</a></p>
	</div>

</div>