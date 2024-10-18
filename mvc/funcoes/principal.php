<?php
	function getIP(){
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ipDoCliente = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}elseif(!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ipDoCliente = $_SERVER['HTTP_CLIENT_IP'];
		}else{
			$ipDoCliente = $_SERVER['REMOTE_ADDR'];
		}
		return $ipDoCliente;
	}
	
?>