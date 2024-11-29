
document.addEventListener('DOMContentLoaded', carregado);

function interpretarMask(mask){//funcionando
	if(typeof mask === "string"){
		const tam = mask.length;
		let vetor = [];
		let aux = '';
		let char = null;
		let char_aux = null;
		let fim = false;
		for(let i = 0;i<tam;i++){
			char = mask.charAt(i);
			if(isNaN(Number(char)) || (char===' ')){//verifica se o caracter não é um número ou é ' '
				//não é um número
				vetor.push(char);
			}else{
				//é um número
				aux = '';
				char_aux = null;
				fim = true;//se o laço 'for' acabar naturalmente quer dizer que acabou a string da mascara
				for(let a = i;a<tam;a++){
					char_aux = mask.charAt(a);
					if(isNaN(Number(char_aux)) || (char_aux===' ')){
						i = --a;//decrementar 1, pois o atual não é número
						fim = false;//ainda não acabou a string mask
						break;
					}
					aux += char_aux;
				}
				vetor.push(Number(aux));
				if(fim){
					break;
				}
			}
		}
		return vetor;
	}else{
		return false;
	}
}

function aplicarMask(gabarito,texto_pre){//funcionando
	if(!(Array.isArray(gabarito))){
		return false;
	}
	let texto = texto_pre.replace(/\D/g, '');//remover não números do texto aqui
	if(texto == ''){
		return '';
	}
	const tam_txt = texto.length;
	const tam_gab = gabarito.length;
	let aplicado = '';
	let i_gab = 0;
	let i_txt = 0;
	let aux = 0;
	principal:
	for(i_gab = 0; i_gab < tam_gab;i_gab++){//percorrer gabarito
		if(!(typeof gabarito[i_gab] === 'number')){
			aplicado += gabarito[i_gab];
		}else{
			aux = 0;
			for(;i_txt<tam_txt;i_txt++){
				if(aux == gabarito[i_gab]){//verificar se já foi tudo preenchido
					break;
				}
				aplicado+=texto.charAt(i_txt);
				aux++;
			}
			if(i_txt==tam_txt){// o texto acabou, sair de tudo
				break principal;
			}
		}
	}
	return aplicado;
}

let gabaritosMascaras = {};

function carregado(){
	//pesquisar inputs
	const inputs = document.querySelectorAll('input[mascara]');
	inputs.forEach(ele=>{
		let mascaraNome = ele.getAttribute("mascara");
		if(mascaraNome != ''){
			if(mascaras[mascaraNome] != ''){
				//fazer os gabaritos
				gabaritosMascaras[mascaraNome] = interpretarMask(mascaras[mascaraNome]);
				ele.addEventListener('input',()=>{
					//mudar o valor do input interpretando a mascara
					let textoInicial = ele.value;
					ele.value = aplicarMask(gabaritosMascaras[mascaraNome],textoInicial);
				});
			}
		}
	});

	const inputs2 = document.querySelectorAll('input[numascara]');
	inputs2.forEach((ele,index)=>{
		let chave = 'mascara_identificador'+index;
		let mascaraValor = ele.getAttribute("numascara");
		if(mascaraValor != ''){
			//fazer os gabaritos
			gabaritosMascaras[chave] = interpretarMask(mascaraValor);
			ele.addEventListener('input',()=>{
				//mudar o valor do input interpretando a mascara
				const textoInicial = ele.value;
				ele.value = aplicarMask(gabaritosMascaras[chave],textoInicial);
			});
		}
	});
	return false;
}
