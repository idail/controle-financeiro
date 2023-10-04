<?php
require("../controladora/Empresas.php");

function informaPagamentoAlterar($conexao,$valor,$data_pagar,$pago,$id_empresa,$id_conta_pagar){
    informaPagamento($conexao,$valor,$data_pagar,$pago,$id_empresa,$id_conta_pagar);
}

function ler_empresas($conexao)
{
    $retorno_buscaEmpresas = buscaEmpresas($conexao);
    return $retorno_buscaEmpresas;
}

function inserir_conta($conexao,$valor,$data_pagar,$pago,$id_empresa)
{
    $recebe_data_formato_americano = implode('-', array_reverse(explode('/', $data_pagar)));
    $retorno_inserirConta = cadastrarConta($conexao,$valor,$recebe_data_formato_americano,$pago,$id_empresa);
    return $retorno_inserirConta;
}

function listagem_contas($conexao)
{
    $retorno_ListagemContas = listar_contas_pagar($conexao);
    return $retorno_ListagemContas;
}

function retorna_conta_especifica($conexao,$codigo_conta_pagar)
{
    $retorno_conta_especifica = buscaContaEspecifica($conexao,$codigo_conta_pagar);
    return $retorno_conta_especifica;
}

function alterar_conta_especifica($conexao,$valor,$data_pagar,$pago,$id_empresa)
{
    $recebe_data_formato_americano = implode('-', array_reverse(explode('/', $data_pagar)));
    $retorno_alterar_conta_especifica = alterarContaEspecifica($conexao,$valor,$recebe_data_formato_americano,$pago,$id_empresa);
    return $retorno_alterar_conta_especifica;
}

function excluir_conta_especifica($conexao,$codigo_conta_pagar)
{
    $retorno = excluirContaEspecifica($conexao,$codigo_conta_pagar);
    return $retorno;
}
?>