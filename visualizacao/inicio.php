<?php
require("../api/FinanceiroAPI.php");
require("../modelo/Conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento Financeiro</title>
    <style>
        .container {
            position: relative;
            margin-top: 100px;
        }

        .child {
            /* width: 100%; */
            height: 50px;

            /* Centralizar na vertical e na horizontal */
            position: absolute;

            left: 30%;
            margin: -25px 0 0 -25px;
            /* aplique margens superior e esquerda negativas para centralizar de verdade o elemento */
        }

        .classic {

            position: absolute;

            /* top: 50%; */

            margin-top: 55px;

            left: 50%;

            transform: translate(-50%, -50%);

        }
    </style>
</head>

<body>
    <?php
    $recebe_codigo_empresa = "";
    if (isset($_POST["lista_empresas"]) && isset($_POST["data_pagar_conta"]) && isset($_POST["valor_pagar_empresa"]) && isset($_POST["insercao"])) {
        $resultado_cadastrar = inserir_conta($conexao, $_POST["valor_pagar_empresa"], $_POST["data_pagar_conta"], 0, $_POST["lista_empresas"]);
        echo $resultado_cadastrar;
    } elseif (isset($_GET["opcao"])) {
        if ($_GET["opcao"] == "edicao") {
            if (isset($_GET["codigo_conta_edicao"])) {
                retorna_conta_especifica($conexao, $_GET["codigo_conta_edicao"]);
            }
        } elseif ($_GET["opcao"] == "exclusao") {
            excluir_conta_especifica($conexao, $_GET["codigo_conta_exclusao"]);
        } elseif ($_GET["opcao"] == "informar_pagamento") {

            $recebe_valor = "";
            if ($_GET["data_pagamento"] < date("Y-m-d")) {
                $pctm = -5.00; //desconto de 5%
                $recebe_valor = $_GET["valor"] * (1 + ($pctm / 100));

                echo "<script type='javascript'>alert('Você teve desconto de 5% devido estar pagando antes da data de vencimento');</script>";
            } else if ($_GET["data_pagamento"] == date("Y-m-d")) {
                echo "Não tem desconto";
                $recebe_valor = $_GET["valor"];

                echo "<script type='javascript'>alert('Sua conta não teve desconto devido estar pagando na data do pagamento');</script>";
            } else if ($_GET["data_pagamento"] > date("Y-m-d")) {
                $pctm = 10.00; //acréscimo de 10%
                $recebe_valor = $_GET["valor"] * (1 + ($pctm / 100));

                echo "<script type='javascript'>alert('Sua conta teve um acréscimo de 5% devido estar pagando após a data de pagamento');</script>";
            }

            informaPagamentoAlterar($conexao, $recebe_valor, $_GET["data_pagamento"], 1, $_GET["codigo_empresa"], $_GET["codigo_conta_informa_pagamento"]);
        }
    } elseif (isset($_POST["edicao"])) {
        if (isset($_POST["lista_empresas"]) && isset($_POST["data_pagar_conta"]) && isset($_POST["valor_pagar_empresa"])) {
            alterar_conta_especifica($conexao, $_POST["valor_pagar_empresa"], $_POST["data_pagar_conta"], 0, $_POST["lista_empresas"]);
        }
    }
    ?>
    <div class="classic">
        <form id="formulario-cadastra-conta" action="inicio.php" method="post">
            <label>Selecione a empresa:</label>

            <select name="lista_empresas" id="lista-empresas">
                <option value="">Selecione a empresa</option>
                <?php

                $resultado = ler_empresas($conexao);
                $recebe_opcoes = "";
                foreach ($resultado as $registros) {
                    if (isset($_SESSION["empresa_localizada"])) {
                        if ($_SESSION["empresa_localizada"]["id_empresa"] == $registros["id_empresa"]) {
                            echo '<option value="' . $registros['id_empresa'] . '" selected>' . $registros['nome'] . '</option>';
                        } else {
                            echo '<option value="' . $registros['id_empresa'] . '">' . $registros['nome'] . '</option>';
                        }
                    } else {
                        echo '<option value="' . $registros['id_empresa'] . '">' . $registros['nome'] . '</option>';
                    }
                }
                ?>
            </select><br>
            <label>Informa a data para pagamento</label>
            <input type="date" name="data_pagar_conta" id="data-pagar-conta" value="<?php
                                                                                    if (isset($_SESSION["conta_localizada"])) {
                                                                                        $valor_formatad_pt_br = $_SESSION["conta_localizada"]["data_pagar"];
                                                                                        echo $valor_formatad_pt_br;
                                                                                    } else {
                                                                                        echo "";
                                                                                    }
                                                                                    ?>"><br>
            <label>Informe o valor a ser pago a empresa</label>
            <input type="text" name="valor_pagar_empresa" id="valor-pago-empresa" value="<?php
                                                                                            if (isset($_SESSION["conta_localizada"]["valor"])) {
                                                                                                echo $_SESSION["conta_localizada"]["valor"];
                                                                                            } else {
                                                                                                echo "";
                                                                                            }
                                                                                            ?>"><br>
            <input type="hidden" name="<?php if (isset($_SESSION["conta_localizada"])) {
                                            if ($_SESSION["conta_localizada"] != "") {
                                                echo "edicao";
                                            } else {
                                                echo "insercao";
                                            }
                                        } else {
                                            echo "insercao";
                                        } ?>" value="cadastrar">
            <input type="submit" value="Gravar">
        </form>
    </div>
    <br><br>

    <div class="container">
        <div class="child">
            <form action="inicio.php">
                <label for="">Selecione o filtro desejado</label>
                <select name="filtro_selecionado" id="">
                    <option value="selecione">Selecione</option>
                    <option value="nome_empresa">Nome da empresa</option>
                    <option value="valor_pagar">Valor a pagar</option>
                    <option value="data_pagamento">Data Pagamento</option>
                </select>
                <label for="">Informa o valor</label>
                <input type="text" name="filtro_informado" placeholder="Informa o valor que deseja pesquisar" style="width: 250px;">
                <input type="submit" value="Pesquisar">
            </form>
        </div>
    </div>


    <table border="1" style="width: 100%;">
        <tr>
            <th>Valor</th>
            <th>Pago</th>
            <td colspan="3">Opções</td>
        </tr>
        <?php
        $resultado_contas = listagem_contas($conexao);
        foreach ($resultado_contas as $contas) {
            $verifica_pagamento = "";
            $coluna = "";
            if ($contas["pago"] == 0) {
                $verifica_pagamento = "Não Pago";
                $coluna = "<td><a href='inicio.php?codigo_conta_informa_pagamento=" . $contas["id_conta_pagar"] . "&codigo_empresa=" . $contas["id_empresa"] . "&data_pagamento=" . $contas['data_pagar'] . "&valor=" . $contas["valor"] . "&opcao=informar_pagamento'><input type='checkbox' value='pago'/>Paga</a></td>";
            } else {
                $verifica_pagamento = "Pago";
                $coluna = "<td>Paga</td>";
            }
            $texto_exclusao = 'Tem certeza que deseja deletar este registro?';
            echo "
            <tr>
            <td>R$" . number_format($contas["valor"], 2, ",", ".") . "</td>
            <td>" . $verifica_pagamento . "</td>
            <td><a href='inicio.php?codigo_conta_exclusao=" . $contas['id_conta_pagar'] . "&opcao=exclusao'>Excluir</a></td>
            <td><a href='inicio.php?codigo_conta_edicao=" . $contas['id_conta_pagar'] . "&opcao=edicao'>Editar</a></td>
            $coluna
            </tr>";
        }
        ?>
    </table>
</body>

</html>

<script>
    // function informar_pagamento(recebe_data_pagamento, valor, e) {
    //     e.preventDefault();
    //     debugger;

    //     let data_americana = recebe_data_pagamento;
    //     let data_brasileira = data_americana.split('-').reverse().join('/');


    //     const timeElapsed = Date.now();
    //     const today = new Date(timeElapsed);
    //     let recebe_data_atual = today.toLocaleDateString();

    //     if (data_brasileira < recebe_data_atual) {
    //         var valor = (valor * 0.5);
    //         alert("Você teve desconto de 5% devido estar pagando antes da data de vencimento");
    //     } else if (data_brasileira == recebe_data_atual) {
    //         console.log("entrou aqui");
    //         alert("Sua conta não teve desconto devido estar pagando na data do pagamento");
    //     } else if (data_brasileira > recebe_data_atual) {
    //         alert("Sua conta teve um acréscimo de 5% devido estar pagando após a data de pagamento");
    //         var valor = (valor * 0.10);
    //     }


    // var ajax = new XMLHttpRequest();

    // // Seta tipo de requisição e URL com os parâmetros
    // ajax.open("GET", "../api/FinanceiroAPI.php/?processo_empresas=informa_pagamento&codigo_conta="+recebe_codigo_conta+"&codigo_empresa="+recebe_codigo_empresa+"&valor_atualizado="+valor+"&data_pagamento="+data_americana, true);

    // // Envia a requisição
    // ajax.send();

    // // Cria um evento para receber o retorno.
    // ajax.onreadystatechange = function() {
    //     // Caso o state seja 4 e o http.status for 200, é porque a requisiçõe deu certo.
    //     if (ajax.readyState == 4 && ajax.status == 200) {
    //         debugger;
    //         var data = ajax.responseText;
    //         // Retorno do Ajax
    //         console.log(data);
    //     }
    // }
    // }
</script>