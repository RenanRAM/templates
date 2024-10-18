<?php

	define("PATH",'http://localhost/templates/mvc');

	//SQL
/*
	define('HOST','localhost');
	define('USER','root');
	define('PASSWORD','');
	define('DATABASE','nome');
*/
	//hora
	date_default_timezone_set('America/Sao_Paulo');
	
	//configurações de sessão segura
	session_name('session1');
	session_start();
	session_regenerate_id();

	//tags meta
	define('METATAGS',<<<META
	<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Renan Werle Ruschel">
		<meta name="keywords" content="moveis projetos sob medida ambientes salas cozinhas quartos banheiros cleusa casa apartamento planejados">
		<meta name="description" content="criacao de ambientes e moveis sob medida">
		<meta name="language" content="pt-BR">
	META);

	include('controllers/controlador.php');
	include('funcoes/principal.php');
	include('classes/principal.php');
?>