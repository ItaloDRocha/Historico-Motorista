<?php
// session_start();
// include './restriction.php';
// include "proc/conexao.php";
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

// error_reporting(E_ALL);
// ini_set("display_errors", "On");
error_reporting(0);

$id_motorista = "218";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="Description" content="Enter your description here" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <style>
        <?php
        include "assets/css/css.css";
        include "assets/css/texto.css";
        ?>
    </style>
    <title>Histórico</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<body>

    <?php
    
    $json_finalizadas = null; //Aqui seram armazenadas as viagens finalizadas desse motorista

    $viagens = file_get_contents("viagem.json");
    $viagens = json_decode($viagens);
    $count = 0;

    foreach ($viagens as $key => $dados) {
        $token_viagem = $dados->token;

        if (file_exists("passageiro/viagens/$token_viagem.json")) {
            $file_viagem = file_get_contents("passageiro/viagens/$token_viagem.json");

            $dados = json_decode($file_viagem);

            // Esse bloco apenas simula uma alteração das datas para melhor visualização do front

            if ($dados->status == "finalizada" && $dados->id_motorista == "$id_motorista") {

                $data_jsificada = explode(" ", $dados->data_finaliza)[0];

                $data_jsificada = str_replace('/', '-', $data_jsificada);

                $data_jsificada = date('d-m-Y', strtotime("-$count day")); //Remova essa linha para fazer ajustes manuais nas datas dos jsons

                $data_jsificada = str_replace('-', '/', $data_jsificada);

                $dados->data_finaliza = $data_jsificada;

                $dados_alterados = $dados;

                $dados_alterados = json_encode($dados_alterados);
                file_put_contents("passageiro/viagens/$token_viagem.json", $dados_alterados);

                $count++;

                $json_finalizadas[] = $dados;
            }
        }
    }

    $json_finalizadas_js = json_encode($json_finalizadas); //Viagens finalizadas desse motorista, encodificadas para serem usadas no JS

    ?>

    <div class="container">
        <div class="semana">
            <div style='display:inline-flex'>

                <?php
                $date_completa_ativo = strftime('%d/%m/%Y', strtotime('today')); //Data yyyy-mm-dd atual
                $dia_inicial = -30;
                ?>


                <?php while ($dia_inicial <= 0) : //Enquanto o dia inicial for < 0, recalcula a data a ser usada no botão
                    $date_dia = strftime('%a', strtotime($dia_inicial . 'day'));
                    $date_dia = utf8_encode($date_dia); //Corrige acentos

                    $date_dia_n = strftime('%d', strtotime($dia_inicial . 'day')); //Dia dd

                    $date_completa = strftime('%d/%m/%Y', strtotime($dia_inicial . 'day')); //Data yyyy-mm-dd dinâmica
                ?>


                    <?php if ($dia_inicial != 0) :
                        $dia_inicial++ //Aumenta o dia em 1 para o próximo botão de dia
                    ?>
                        <div class="dia dia_btn" data-data_completa="<?= $date_completa ?>">
                            <h3 class="h3b"><?= $date_dia ?>.</h3>
                            <h4 class="h4a"><?= $date_dia_n ?></h4>

                        </div>

                    <?php else : //Quando a data for a atual, só pega a data de hj, sem fazer calculos desnecessários e adicionando o css do dia ativo
                        $dia_inicial++ //Aumenta o dia em 1 para encerrar o while
                    ?>
                        <div class="dia dia-ativo dia_btn" data-data_completa="<?= $date_completa_ativo ?>">
                            <h3 class="h3b"><?= $date_dia ?>.</h3>
                            <h4 class="h4a"><?= $date_dia_n ?></h4>
                        </div>
                    <?php endif ?>


                <?php endwhile ?>

            </div>
        </div>
        <div class="grid-6" style="margin-top: 80px">
            <div class="carro">
                <div class="grid-7">
                    <div style="margin-top: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="26" viewBox="0 0 38 26">
                            <path id="Icon" d="M34.2,26a4.149,4.149,0,0,1-.637-.05,3.827,3.827,0,0,1-3.162-3.8H7.6v.194A3.733,3.733,0,0,1,3.8,26a4.147,4.147,0,0,1-.636-.05A3.828,3.828,0,0,1,0,22.154V15.047a5.384,5.384,0,0,1,1.655-3.859,14.148,14.148,0,0,0,3.28-4.783l1.7-4.091A3.784,3.784,0,0,1,10.156,0H27.844a3.784,3.784,0,0,1,3.519,2.314l1.7,4.091a14.142,14.142,0,0,0,3.279,4.783A5.384,5.384,0,0,1,38,15.047v7.3A3.733,3.733,0,0,1,34.2,26ZM31.5,13A2.5,2.5,0,1,0,34,15.5,2.5,2.5,0,0,0,31.5,13Zm-25,0A2.5,2.5,0,1,0,9,15.5,2.5,2.5,0,0,0,6.5,13Zm8.875,1a1.506,1.506,0,0,0,0,3h8.25a1.505,1.505,0,0,0,0-3ZM9.782,2a1.929,1.929,0,0,0-1.8,1.136L6.062,7.817a.8.8,0,0,0,.08.764A.977.977,0,0,0,6.959,9H31.041a.977.977,0,0,0,.817-.418.805.805,0,0,0,.08-.764L30.013,3.136A1.928,1.928,0,0,0,28.219,2Z" transform="translate(0 0)" fill="#fff" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="h4b">
                            Corridas feitas
                        </h4>
                        <h4 id="n_corridas" class="h4c">
                            <?php
                            $viagens_finalizadas_hoje = 0;

                            if ($json_finalizadas != null) {
                                foreach ($json_finalizadas as $key => $row) {
                                    $data_json_0 = explode(" ", $row->data_finaliza);
                                    if ($data_json_0[0] == date('d/m/Y')) {
                                        $viagens_finalizadas_hoje++;
                                    }
                                }
                            }

                            echo $viagens_finalizadas_hoje; //Quantidade de viagens finalizadas hoje do motorista encontradas no json
                            ?>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="dinheiro">
                <div class="grid-7">
                    <div style="color: rgb(243, 243, 243);">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="33.596" height="33.6" viewBox="0 0 33.596 33.6">
                            <defs>
                                <clipPath id="clip-path">
                                    <path id="Shape" d="M16.906,33.6h0a2.25,2.25,0,0,1-1.659-.727l-.582-.64A2.274,2.274,0,0,0,13,31.5a2.246,2.246,0,0,0-.881.179l-.805.339a2.267,2.267,0,0,1-.881.178A2.24,2.24,0,0,1,8.287,30.62l-.242-.83A2.215,2.215,0,0,0,6,28.211l-.874-.034a2.244,2.244,0,0,1-1.657-.826,2.163,2.163,0,0,1-.452-1.726l.145-.852a2.168,2.168,0,0,0-1.145-2.292l-.777-.408A2.188,2.188,0,0,1,.111,20.64a2.152,2.152,0,0,1,.354-1.757l.505-.7a2.152,2.152,0,0,0-.02-2.552l-.52-.7a2.151,2.151,0,0,1-.38-1.745,2.2,2.2,0,0,1,1.1-1.459l.771-.415A2.179,2.179,0,0,0,3.032,9.011l-.16-.843A2.155,2.155,0,0,1,3.3,6.442,2.243,2.243,0,0,1,4.94,5.59l.873-.049A2.215,2.215,0,0,0,7.841,3.936l.229-.83a2.23,2.23,0,0,1,2.153-1.615,2.256,2.256,0,0,1,.852.167l.813.326a2.231,2.231,0,0,0,.849.167A2.256,2.256,0,0,0,14.42,1.4L15,.752a2.264,2.264,0,0,1,3.352-.02l.582.64A2.278,2.278,0,0,0,20.6,2.1a2.244,2.244,0,0,0,.88-.179l.805-.34a2.274,2.274,0,0,1,.881-.178,2.212,2.212,0,0,1,2.144,1.58l.243.83A2.215,2.215,0,0,0,27.6,5.394l.876.034a2.244,2.244,0,0,1,1.655.825,2.166,2.166,0,0,1,.454,1.727l-.146.85a2.171,2.171,0,0,0,1.147,2.293l.776.408a2.186,2.186,0,0,1,1.124,1.431,2.155,2.155,0,0,1-.352,1.759l-.507.7a2.153,2.153,0,0,0,.02,2.55l.522.694a2.148,2.148,0,0,1,.377,1.748,2.192,2.192,0,0,1-1.106,1.452l-.771.415a2.178,2.178,0,0,0-1.11,2.307l.16.844a2.153,2.153,0,0,1-.424,1.725,2.246,2.246,0,0,1-1.644.853l-.876.048a2.218,2.218,0,0,0-2.027,1.605l-.229.83a2.231,2.231,0,0,1-2.153,1.616,2.258,2.258,0,0,1-.852-.167l-.811-.326a2.22,2.22,0,0,0-.85-.168,2.255,2.255,0,0,0-1.683.751l-.577.646A2.252,2.252,0,0,1,16.906,33.6ZM12.582,20.518c-.2,0-.295.179-.4.487-.119.359-.224.731-.325,1.091l-.085.3c-.174.631-.1.78.493,1.061a8.5,8.5,0,0,0,2.443.688c.661.1.68.125.688.8,0,.3,0,.6.008.91a.548.548,0,0,0,.6.612c.228.007.459.01.687.01s.459,0,.687-.01a.52.52,0,0,0,.57-.578c0-.137,0-.275,0-.414,0-.278.009-.562,0-.839a.655.655,0,0,1,.582-.748,4.66,4.66,0,0,0,2.4-1.509,4.044,4.044,0,0,0,.908-3.392,4.473,4.473,0,0,0-2.282-3.078,19.75,19.75,0,0,0-2.045-.917l-.371-.15a5.168,5.168,0,0,1-1.326-.728,1.17,1.17,0,0,1-.5-1.069,1.212,1.212,0,0,1,.787-.937,2.318,2.318,0,0,1,.814-.165c.117-.006.235-.009.351-.009a6.26,6.26,0,0,1,2.7.6,1.009,1.009,0,0,0,.409.126c.179,0,.287-.124.4-.456.138-.415.262-.844.381-1.258l.006-.021.085-.293a.553.553,0,0,0-.367-.735,7.772,7.772,0,0,0-1.923-.566c-.868-.127-.868-.127-.876-.971V8.34c-.008-1.212-.008-1.212-1.244-1.212l-.18,0-.18,0c-.06,0-.12,0-.18,0-.584.014-.682.11-.693.688v.769c-.007.749-.007.749-.714,1l-.036.013a4.072,4.072,0,0,0-3.02,3.756,3.826,3.826,0,0,0,2.221,3.68,13.248,13.248,0,0,0,1.911.872c.284.112.578.228.864.35a4.383,4.383,0,0,1,1.014.585,1.346,1.346,0,0,1-.333,2.381,3.3,3.3,0,0,1-1.307.261,3.955,3.955,0,0,1-.477-.03,8.015,8.015,0,0,1-2.714-.8A1.026,1.026,0,0,0,12.582,20.518Z" transform="translate(0 0)" fill="#fff" />
                                </clipPath>
                            </defs>
                            <g clip-path="url(#clip-path)">
                                <g id="Colors_Primary" data-name="Colors/Primary" transform="translate(-4.2 -4.2)">
                                    <rect id="Colors_Primary_background" data-name="Colors/Primary background" width="42" height="42" fill="rgba(0,0,0,0)" />
                                    <rect id="Rectangle_3" data-name="Rectangle 3" width="42" height="42" fill="#fff" />
                                </g>
                            </g>
                        </svg>
                    </div>
                    <div>
                        <h4 class="h4b">
                            Receita total
                        </h4>
                        <h4 id="n_receita" class="h4c">
                            <?php
                            $receita_total = 0;
                            foreach ($json_finalizadas as $key => $dados) {

                                $data_json_1 = explode(" ", $dados->data_finaliza);
                                if ($data_json_1[0] == date('d/m/Y')) {

                                    $preco = explode(" ", $dados->price); //Retira o R$ do json, o ideal é guardar os valores já em xxx.xx sem formatacao
                                    $preco = floatval(str_replace(",", ".", $preco[1])); //Substitue a , por . e transforma em float
                                    $receita_total +=  $preco; //Soma o total da receita
                                }
                            }
                            $receita_total_txt =  number_format($receita_total, 2, ',', '.');
                            ?>

                            R$ <?= $receita_total_txt ?>
                            <!-- Receita total das viagens encontradas desse motorista hoje -->
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class='corridas'>


        </div>


        <!-- <div class="corridas2">
            <div class="grid-8">
                <div class="foto">

                </div>
                <div class="nome">
                    <h4 class="h5a">Guilherme Ferreira</h4>
                    <h4 class="h5pag1">Dinheiro</h4>
                </div>
                <div class="distancia">
                    <h5 class="h5a">R$ 85,00</h5>
                    <h5 class="h5dist">9,1 KM</h5>
                </div>
            </div>
            <div class="corrida-info">
                <h3 class="h3d">Local de Saída:</h3>
                <h4 class="h3c">Rua Botucatum, 1795</h4>
                <hr>
                <h3 class="h3d">Local de Destino:</h3>
                <h4 class="h3c">Av Lapa, 2500</h4>
            </div>
            <br><br>
            <div class="grid-8">
                <div class="foto">

                </div>
                <div class="nome">
                    <h4 class="h5a">Gabriel F.</h4>
                    <h4 class="h5pag1">Dinheiro</h4>
                </div>
                <div class="distancia">
                    <h5 class="h5a">R$ 60,00</h5>
                    <h5 class="h5dist">42,8 KM</h5>
                </div>
            </div>
            <div class="corrida-info">
                <h3 class="h3d">Local de Saída:</h3>
                <h4 class="h3c">Av. Jorge Rodrigues, 100</h4>
                <hr>
                <h3 class="h3d">Local de Destino:</h3>
                <h4 class="h3c">Av Carlos Lacerda, 812</h4>
            </div>
        </div> -->

    </div>



    <script>
        $(document).ready(function() {

            // Animação de scroll pro dia atual
            var valor1 = $(".semana")[0].scrollWidth;
            var valor2 = $(".semana").outerWidth()
            var scroll_value = valor1 - valor2;
            $(".semana").animate({
                scrollLeft: scroll_value
            }, 500);

            setTimeout(() => { //Pesquisa os dados da data atual, ao carregar a pagina

                $(".dia-ativo.dia_btn").trigger("click");
            }, 500);

            $(".dia_btn").click(function(e) {

                /**
                 * Essa função pega a data do btn clicado, e verifica se existe alguma viagem feita nessa data nas viagens finalizadas do motorista
                 * Caso encontrar, é feito um append na classe corridas,refazendo o html com os dados da viagem encontrada
                 */

                $(".dia_btn").removeClass("dia-ativo")
                $(this).addClass("dia-ativo");
                $(".corridas").empty();

                var data_btn = this.dataset.data_completa;

                var json = <?= $json_finalizadas_js ?>; //Pega o array com todos as viagens finalizadas desse motorista

                var corridas_feitas = 0;
                var receita_total = 0;
                var index_json = 0;

                json.forEach((element, index) => { //some é a mesma coisa que foreach, porem da pra quebrar a repetição com um return true

                    var data_separada = element.data_finaliza.split(' ')
                    console.log("encontrou", data_separada[0], "procurando por", data_btn)
                    if (data_separada[0] == data_btn && element.status == "finalizada") {

                        var id_cliente = element.id_passageiro;
                        var preco = element.price.split(' ')[1];

                        var nome = "Ronaldinho Gaucho";
                        var img_passageiro = "assets/img/userimg_sample.avif";
                        var saida = "Rua do espinafre 255 , Belo Horizonte - MG ";

                        
                        preco = preco.replace(".", ",");

                        $(".corridas").append(`
                                                <div class='grid-8'>
                                                    <div class='foto'>
                                                        <img src="${img_passageiro}" alt="">
                                                    </div>
                                                <div class='nome'>
                                                    <h4 class='h5a'> ${nome}</h4>
                                                    <h4 class='h5pag'> ${element.tipo}</h4>
                                                </div>
                                                <div class='distancia'>
                                                    <h5 class='h5a'>R$  ${preco}</h5>
                                                    <h5 class='h5dist'> ${element.distancia}</h5>
                                                </div>
                                                </div>
                                                <div class='corrida-info'>
                                                <h3 class='h3d'>Local de Saída:</h3>
                                                    
                                                    <h4 class='h3c'> ${saida}</h4>
                                                    <hr>
                                                <h3 class='h3d'>Local de Destino:</h3>
                                                <h4 class='h3c'> ${element.destino}</h4>
                                                </div>
                                            `)

                        corridas_feitas++;
                        precoParse = preco.replace(",", "."); //Prepara o preco para ser formatado de string para númerico
                        receita_total += parseFloat(precoParse)


                        if (index_json == json.length - 1) { //Quando for o ultimo elemento da repeticao, executa essa funcao

                            atualizarDados(corridas_feitas, receita_total);
                            index_json++;
                        }

                    } else {
                        atualizarDados(corridas_feitas, receita_total);
                        index_json++;
                    }

                })
            });
            // console.log(json)

            //função utilizada posteriormente com conexão ao banco de dados

            // json.forEach((element, index) => { //some é a mesma coisa que foreach, porem da pra quebrar a repetição com um return true

            //     var data_separada = element.data_finaliza.split(' ')
            //     console.log("encontrou", data_separada[0], "procurando por", data_btn)
            //     if (data_separada[0] == data_btn && element.status == "finalizada") {

            //         var id_cliente = element.id_passageiro;
            //         var preco = element.price.split(' ')[1];
            //         $.post("proc/get-dados-passageiro-proc.php", {
            //                 id_cliente
            //             },
            //             function(data, textStatus, jqXHR) {

            //                 var dados = JSON.parse(data); //Dados do passageiro
            //                 console.log(dados)
            //                 dados = dados[0];
            //                 var nome = dados.nome;
            //                 var img_passageiro = dados.img;


            //                 preco = preco.replace(".", ",");

            //                 $(".corridas").append(`
            //                     <div class='grid-8'>
            //                             <div class='foto'>
            //                                 <img src="${img_passageiro}" alt="">
            //                             </div>
            //                         <div class='nome'>
            //                             <h4 class='h5a'> ${nome}</h4>
            //                             <h4 class='h5pag'> ${element.tipo}</h4>
            //                         </div>
            //                         <div class='distancia'>
            //                             <h5 class='h5a'>R$  ${preco}</h5>
            //                             <h5 class='h5dist'> ${element.distancia}</h5>
            //                         </div>
            //                     </div>
            //                     <div class='corrida-info'>
            //                         <h3 class='h3d'>Local de Saída:</h3>
            //                         <h4 class='h3c'> ${element.partida}</h4>
            //                         <hr>
            //                         <h3 class='h3d'>Local de Destino:</h3>
            //                         <h4 class='h3c'> ${element.destino}</h4>
            //                     </div>
            //                 `)


            //             }
            //         );

            //         corridas_feitas++;
            //         precoParse = preco.replace(",", "."); //Prepara o preco para ser formatado de string para númerico
            //         receita_total += parseFloat(precoParse)


            //         if (index_json == json.length - 1) { //Quando for o ultimo elemento da repeticao, executa essa funcao

            //             atualizarDados(corridas_feitas, receita_total);
            //             index_json++;
            //         }

            //     } else {
            //         atualizarDados(corridas_feitas, receita_total);
            //         index_json++;
            //     }

            // })



            function atualizarDados(corridas_feitas, receita_total) { //Atualiza os valores de acordo com o dia clicado
                // console.log("executou a funcao de atualizar dados")

                receita_total = String(receita_total.toFixed(2)).replace(".", ","); //Converte o proco de númerico para string

                $("#n_corridas").text(corridas_feitas);
                $("#n_receita").text(`R$ ${receita_total}`);
            }
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
</body>