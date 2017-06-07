<html>
<body>
    <h1>Resetear contraseña para <?php echo $identity;?></h1>
    <p>Haga click en el siguiente enlace para <?php echo anchor('es/candidatos/password/reset/'. $forgotten_password_code, 'Resetear su contrseña');?>.</p>
</body>
</html>