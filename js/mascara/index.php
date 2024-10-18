<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mascaras</title>
</head>
<body>
	<!--Este é um exemplo de como usar o arquivo numascara.js: um arquivo que aplica máscaras personalizadas, apenas para números, em elementos input-->
	
	<!--Tudo que precisa fazer ´importar o arquivo numascara.js no documento html e pronto, já podemos usar.
		Existem 2 formas de usar, uso direto da máscara no html atravéz do atributo numascara ou uso indireto com o atributo mascara, o qual depende de um objeto js chamado mascaras que contem as propriedades com o nome das máscaras e seus valores como string, veja os exemplos:
	-->

	<!--Usando 'numascara' podemos escrever a mascara diretamente no atributo html-->
	<input type="text" placeholder="Data" numascara="2/2/4">
	<input type="text" placeholder="CPF" numascara="CPF: 11">
	<input type="text" placeholder="CPF" numascara="CPF: 3.3.3-2">
	
	<!--Usando 'mascara' precisa ser declarado o objeto "mascaras"(let mascaras={...}) no js, contendo a propriedade com o nome de 'mascara' e seu valor-->
	<input type="text" placeholder="Fone" mascara="fone">

	<input type="text" placeholder="Fone" mascara="mascara2">
</body>
<script type="text/javascript" src="numascara.js"></script>
<script type="text/javascript">
	let mascaras = {
		mascara2 : '5-4',
		fone:'(2) 5-4'
	};
</script>
</html>