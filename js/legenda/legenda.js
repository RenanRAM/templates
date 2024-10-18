//inicialização
document.addEventListener('DOMContentLoaded', iniciarLegenda);


//tamanhos máximos que a legenda pode ter, em pixels
const max_x = 200;
const max_y = 40;
//cor do background da legenda
const cor_fundo = "#FFF";


//definições iniciais
let scrollbarVerticalAtiva = document.body.scrollHeight > window.innerHeight;//tentando detectar scrollbars ativas
let scrollbarHorizontalAtiva = document.body.scrollWidth > window.innerWidth;
let x_total = window.innerWidth;
let y_total = window.innerHeight;
let leg_aberta = false;
let frame_ok = true;//usado para sincromia da animação com os frames

function iniciarLegenda(){//função que inicia tudo
	const legenda = gerarLegenda();
	const elementos = pegarElementosLegenda();
	adicionarEvendos(elementos,legenda);
}

function gerarLegenda(){//gera o elemento de legenda e o insere no body
	const ele = document.createElement("div");
	ele.attributeStyleMap.set("position","fixed");
	ele.attributeStyleMap.set("z-index","99");
	ele.attributeStyleMap.set("max-width",max_x+"px");
	ele.attributeStyleMap.set("max-height",max_y+"px");
	ele.attributeStyleMap.set("background-color",cor_fundo);
	ele.attributeStyleMap.set("padding","3px 5px");
	ele.attributeStyleMap.set("border","1px solid black");
	ele.attributeStyleMap.set("text-align","center");
	fechaLegenda(ele);
	document.querySelector("body").append(ele);
	return ele;
}

function abreLegenda(leg,texto){//abre a legenda e insere o seu texto
	leg.attributeStyleMap.set("display","block");
	leg.textContent = texto;
	scrollbarVerticalAtiva = document.body.scrollHeight > window.innerHeight;
	scrollbarHorizontalAtiva = document.body.scrollWidth > window.innerWidth;
	x_total = window.innerWidth;
	y_total = window.innerHeight;
	leg_aberta = true;
}

function fechaLegenda(leg){//fecha a legenda
	leg.attributeStyleMap.set("display","none");
	leg_aberta = false;
}

function pegarElementosLegenda(){//pega todos elementos que terão a função de legenda
	return document.querySelectorAll("[legenda]");
}

function adicionarEvendos(elementos,leg){
	elementos.forEach(ele=>{
		const texto = ele.getAttribute("legenda");
		ele.addEventListener("mouseover",(ev)=>{
			abreLegenda(leg,texto);
			moveLegenda(ev,leg);
		});
		ele.addEventListener("mousemove",(ev)=>{
			moveLegenda(ev,leg);
		});
		ele.addEventListener("mouseleave",()=>{
			fechaLegenda(leg);
		});
	});
}

function moveLegenda(ev,legenda){
	if(!leg_aberta) return;
	if(!frame_ok) return;

	
	frame_ok = false;
	const clientx = ev.clientX;
	const clienty = ev.clientY;
	//pegar tamanho da legenda atual
	const x_atual = legenda.clientWidth;
	const y_atual = legenda.clientHeight;
	//verificar posição nas bordas
	let x = 0;
	let y = 0;
	const padx = x_atual*0.2;
	
	let mar_x = 5;
	let mar_y = 10;
	if(scrollbarHorizontalAtiva){
		mar_y = 20;
	}
	if(scrollbarVerticalAtiva){
		mar_x = 20;
	}

	if((x_total-clientx) < (x_atual + mar_x)){
		//limite da direita
		x = clientx-x_atual-5;
	}else if(clientx <= padx){
		//limite da esquerda
		x = clientx+5;
	}else{
		//x normal
		x = clientx-padx;
	}
	if((y_total-clienty) < (y_atual+mar_y+10)){
		//limite de baixo
		y = clienty-y_atual-10;
	}else{
		//y normal
		y = clienty+10;
	}
	requestAnimationFrame(()=>{
		legenda.attributeStyleMap.set("top",y+"px");
		legenda.attributeStyleMap.set("left",x+"px");
		frame_ok = true;
	});
	
}