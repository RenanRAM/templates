<?php
	include('config.php');

	$url = '';
	if(isset($_GET['url']))
		$url = $_GET['url'];

	$c1 = new controlador1();

	$roteador = new roteadorURL($url);
	
	//$roteador->rota('caxi/?/aba',[$c1,'index']);
	$roteador->rota('caxi/?/aba/?',function(){
		global $p1;
		global $p2;
		echo $p2;
	});
	$roteador->rota('caxi/aba',[$c1,'palavra'],'abacaxi');
	/*fazer uma forma de poder passar o valor do ? da url desta forma:$roteador->rota('caxi/?/aba',[$c1,'palavra'],'?1');
		sendo que ?1 representa o valor do primeiro ? do padrão e ?2 do segundo e assim por diante
	*/

	$roteador->forcarCarregamento();




?>