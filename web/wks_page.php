
<!DOCTYPE html>
<html>
	<head>
		<title>CHESS</title>
		<style>
			.panel{
				margin: 0;
				padding: 0;
				font-size: 0;
			}

			.box{
				width: 40px; 
				height: 40px; 
				display: inline-block;
				border: 0.3px solid;
				margin: 0;
				margin-right: -0.6px;
				margin-bottom: -0.6px; 
				padding: 0;
				font-size: 9px;
				opacity: 0.7;
			}

			.footer{
				height: 50px;
				margin-top: 50px;
				background-color: #e9ecef;
			}

			.header{
				height: 50px;
				margin-bottom: 50px;
				background-color: #e9ecef;
				text-align: center;
			}

			
		</style>

	</head>
	<body>
		<div class="header">
			
		</div>
			<center>
				<div class="panel">
					<?php for($i=8; $i>=1; $i--){
						for($j=8; $j>=1; $j--){
							$cor=='#b5651d' ? $cor = 'black' : $cor = '#b5651d';
							$j==8 ? $l = "A" : ($j==7 ? $l = "B" : ($j==6 ? $l = "C" : ($j==5 ? $l = "D" : ($j==4 ? $l = "E" : ($j==3 ? $l = "F" : ($j==2 ? $l = "G" : $l = "H"))))));?>
							<div id="box<?php echo $i.$l ?>" style="background-color: <?php echo $cor ?>" onclick="select_drop('<?php echo $i.$l?>')" class="box"></div> 
							<?php if($j == 1){
								$cor=='#b5651d' ? $cor = 'black' : $cor = '#b5651d'; 
								echo "<br/>";
							}
						} 
					} ?>
				</div>
			</center>

			<div class="footer">

			</div>

			<script>
				var pieces = new Array;
				var boxes = new Array;
				init();

				function init(){
					popular_boxes();
					set_units();
					console.log(boxes); 
				}

				function popular_boxes(){
					for(i=8; i>=1; i--){
						for(j=8; j>=1; j--){
							var l = (j==8 ? "A" : (j==7 ? "B" : (j==6 ? "C" : (j==5 ? "D" : (j==4 ? "E" : (j==3 ? "F" : (j==2 ? "G" : "H")))))));
							var box = i+l;
							new Box(box, 0, "");
						}
					}
				}

				function set_units(){
					new Piece("p1","w","peon","2A");
					
				}

				function Piece (id, team, classe, box){
					this.id = id;
					this.team = team;
					this.classe = classe;
					this.box = box;
					for(i=0;i<boxes.length;i++){
						var box_u = boxes[i];
						if(box_u.id == box){
							box_u.status = 1;
							box_u.piece = id;
						}
					}
					document.getElementById("box"+this.box).style.backgroundImage = "url('image/"+this.classe+"-"+this.team+".png')";
					this.active = true;	
					pieces.push(this);
				}

				function Box (id, status, piece){
					this.id = id;
					this.status = status;
					this.piece = piece;
					boxes.push(this);
				}

				function select_drop(box){
					//childs = todas as divs de boxes.
					var childs = document.getElementsByClassName("panel")[0].children;
					//box_target = O elemento da box selecionada.
					var box_target = document.getElementById("box"+box);
					
					//Caso a borda do elemento selecionado já estiver marcada, desmarca a mesma.
					if(box_target.style.borderColor == 'blue'){
						box_target.style.cursor = "default";
						box_target.style.borderColor = 'black';
					}
					//Senão verifica qual ação será tomada
					else{
						var drop = false;
						//Varre todos os elementos box
						for(i=0; i<childs.length; i++){
							var child = childs[i];
							//Caso alguma child esteja marcada
							//significa que a ação de drop é verdadeira
							//Drop = Ação de trocar a peça de posição.
							if(child.style.borderColor == 'blue'){
								var box_origem = child;
								//Vare as peças para achar qual peça é a origem
								for(p=0; p<pieces.length; p++){
									//Verifica a posição de todas as peças em jogo.
									//Caso a peça[p] esteja ativa, e na mesma posição da box,
									//Executa a ação...
									var posicao = box_origem.id;
									var posicao_origem = posicao.substr(3,4);
									console.log("Posicao Origem: "+posicao_origem);
									if(pieces[p].box == posicao_origem){
										var peca_marcada = pieces[p];
										break;
									}
								}
								console.log("BOX ORIGEM: "+box_origem.id);
								console.log("BOX DESTINO: "+box_target.id);
								drop = true;
								break;
							}
						}
						//Verifica se a ação é de drop ou não
						console.log("DROP É: "+drop);
						if(drop == false){
							//Como a ação não é de drop, é de seleção.
							//Verifica todas as peças em jogo
							for(p=0; p<pieces.length; p++){
								//Verifica a posição de todas as peças em jogo.
								//Caso a peça[p] esteja ativa, e na mesma posição da box,
								//Executa a ação...
								if(pieces[p].box == box && pieces[p].active == true){
									console.log(pieces[p].box);	
									//Desmarca a seleção de todas os outros elementos.
									//Coloca o cursor como ponteiro para todos os elementos que
									//não sejam o selecionado.
									limpar_marcação();
									//"Seleciona" o elemento, inserindo uma borda azul para destaque.
									box_target.style.cursor = "default";
									box_target.style.borderColor = 'blue';
									//Com a box selecionada, quando o usuário selecionar uma box diferente
									//da box previamente selecionada (que esteja vazia), entrará no ELSE
									//abaixo para executar a ação de movimento.
								}
							}
						}
						else{
							//DROP É VERDADEIRO
							//Isso significa que a peça marcada terá de ser movida
							//Da posição (box_origem) para (box_target).
							
							var po = box_origem.id;
							var posicao_origem = po.substr(3,4);

							var pt = box_target.id;
							var posicao_target = pt.substr(3,4);

							peca_marcada.box = posicao_target;
							
							for(b=0; b<boxes.length; b++){
								box_u = boxes[b];
								if(box_u.id == posicao_target){
									box_u.status = 1;
									box_u.piece = peca_marcada.id;
								}
								if(box_u.id == posicao_origem){
									box_u.status = 0;
									box_u.piece = "";
								}
							}

							console.log(boxes); 

							box_origem.style.backgroundImage = "";
							box_target.style.backgroundImage = "url('image/"+peca_marcada.classe+"-"+peca_marcada.team+".png')";
							limpar_marcação();
						}
					}
				}

				function mov_possiveis(peca){
					//var numero = convert_letra((peca.box).substr(-1));
					var linha = (peca.box).substr(0);
					var coluna = (peca.box).substr(1);
					var movimentos = 0;
					var disponivel = new Array;
					var direcao = 0;
					//Movimento será categorizado por clase de peça
					//Numero de movimentos (0, 1, 2, 4), sendo 0: movimentos ilimitados na direção possível, e 4: somente cavalo.
					//Peão: 1 ou 2 (em caso de posição de início)
					//Torre: 0
					//Cavalo: 4
					//Bispo: 0
					//Rainha: 0
					//Rei: 1
					switch(peca.classe){
						case 'peon':
							//É necessário definir a direção do peão, dependendo do jogador.
							//Exemplo do peão. Se preto, direção é de linha 7 a linha 1, se branco, da linha 2 a linha 8. (Só se aplica a peão).
							//Linha Peões pretos: 7
							//Linha Peões brancos: 2
							//Linha Peças pretas: 8
							//Linha Peças brancas: 1
							peca.team == 'w' ? direcao = 8 : direcao = 1;
							if(direcao==8 && linha==2) movimentos = 2 ;
							if(direcao==8 && linha!=2) movimentos = 1 ;
							if(direcao==1 && linha==7) movimentos = 2 ;
							if(direcao==1 && linha!=7) movimentos = 1 ;
							//disponivel.push(numero+1);
							return;
					}
				}

				function convert_letra(letra){
					var numero = (letra=="A" ? 1 : (letra=="B" ? 2 : (letra=="C" ? 3 : (letra=="D" ? 4 : (letra=="E" ? 5 : (letra=="F" ? 6 : (letra =="G" ? 7 : 8)))))));
					return numero;
				}

				function convert_numero(numero){
					var letra = (numero==1 ? "A" : (numero==2 ? "B" : (numero==3 ? "C" : (numero==4 ? "D" : (numero==5 ? "E" : (numero==6 ? "F" : (numero ==7 ? "G" : "H")))))));
					return letra;
				}

				function limpar_marcação(){
					var childs = document.getElementsByClassName("panel")[0].children;
					for(i=0; i<childs.length; i++){
						var child = childs[i];
						child.style.borderColor = 'black';
						child.style.cursor = "pointer";
					}
				}
		</script>
	</body>
</html>