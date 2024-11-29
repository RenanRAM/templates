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
	
	//sessão
	iniciar_sessao();
	regenerar_id_sessao();

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

<?php
	//sessões
	function iniciar_sessao(){//inicia uma sessão de forma + segura e previnindo perda de id de sessão
		session_set_cookie_params(['httponly' => true,]);
		session_name('session1');
		session_start();
		if (isset($_SESSION['destruir'])) {
			if ($_SESSION['destruir'] < (time()-600)) {
				//destruir dados da sessão
				destruir_sessao();
			}
			if (isset($_SESSION['novo_session_id'])) {
				//fecha a sessão atual para poder abrir outra
				session_commit();

				//abrindo outra sessão
				session_id($_SESSION['novo_session_id']);
				session_start();
				return;
			}
		}	
	}

	function regenerar_id_sessao(){//regenera o id de sessão sem destruir totalmente a sessão anterior, por causa de clientes com conexão lenta etc
		if(isset($_SESSION['ts'])){//ignora sessões muito recentes
			if($_SESSION['ts']+100 > time()){
				return;
			}
		}
		$new_session_id = session_create_id();
		$dados_anteriores = $_SESSION;

		$_SESSION['novo_session_id'] = $new_session_id;
		$_SESSION['destruir'] = time();

		// Write and close current session;
		session_commit();

		// Start session with new session ID
		session_id($new_session_id);
		session_start();

		$_SESSION = $dados_anteriores;
		$_SESSION['ts'] = time();
	}

	function destruir_sessao(){//destroi tudo sobre sessão
		$_SESSION = array();//limpa a sessão
		$params = session_get_cookie_params();
		//apaga o cookie da sessão
		setcookie(session_name(), '', time() - 9999,
		$params["path"], $params["domain"],
		$params["secure"], $params["httponly"]);
		// Destrói a sessão
		session_destroy();
	}
	//fim de sessões
?>