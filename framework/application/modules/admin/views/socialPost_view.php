<h2><?=$titulo; ?></h2>
<div class="contenido_col">

	<?php
	
	$attributes = array('class' => 'form');
	echo form_open($link, $attributes);
	
	?>
	
	<?php if ($user): ?>
		<div id="social_facebook">
			<h3>Facebook</h3>
			<img src="https://graph.facebook.com/<?php echo $user; ?>/picture">
			<?php if(isset($user_profile['name'])): ?>
			<?=$user_profile['name']?>
			<?php endif; ?>
			<a class="fb_logout" href="<?php echo $logoutUrl; ?>">Logout</a>
    	</div>
		
		
    <?php else: ?>
    	<div>
			<strong><em>No est&aacute; conectado con Facebook:</em></strong> 
        	<a class="fb_login" href="<?php echo $loginUrl; ?>">Conectar</a>
      	</div>
    <?php endif ?>
	
	<?php if (!isset($twitter->error)): ?>
	<div id="social_twitter">
		<h3>Twitter</h3>
		<img src="<?=$twitter->profile_image_url?>">
		<?=$twitter->name?> - @<?=$twitter->screen_name?>
	</div>
	<?php else: ?>
    	<div>
			<strong><em>No est&aacute; conectado con Twitter</em></strong> 
        	<p>Error: <?=$twitter->error?></p>
      	</div>
	 <?php endif ?>
	
	<?php if ($user && !isset($twitter->error)): ?>
		<div>
			<label for="mensaje">Post:</label>
			<textarea id="mensaje" cols="50" name="mensaje" ></textarea><!--140 maximo-->
		</div>
	<?php endif ?>
	
	<div id="fb-root"></div>
	
	<?= form_close(); ?> 
	
</div>
<?php if ($user && !isset($twitter->error)): ?>
<a href="<?= $link; ?>" class="guardar boton" ><?=$txt_boton;?></a>
<?php endif ?>

<script>
  FB.init({
	appId  : '<?=$facebook->appId?>',
	status : true,
	cookie : true,
	xfbml  : true,
	channelURL : '<?=base_url()?>assets/admin/scripts/channel.html',
	oauth  : true
  });
</script>
