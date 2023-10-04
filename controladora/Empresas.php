<?php
require("../modelo/Empresa.php");

function buscaEmpresas($conexao){
    $retorno_lista_empresas = lista_empresas($conexao);
    return $retorno_lista_empresas;
}

function cadastrarConta($conexao,$valor,$data_pagar,$pago,$id_empresa){
    $retorno_inserir_conta = cadastrar_conta($conexao,$valor,$data_pagar,$pago,$id_empresa);
    return $retorno_inserir_conta;
}

function listar_contas_pagar($conexao)
{
    $retorno_contas = listar_contas($conexao);
    return $retorno_contas;
}

function buscaContaEspecifica($conexao,$codigo_conta_pagar)
{
    $retorno_conta_especifica = busca_conta_especifica($conexao,$codigo_conta_pagar);
    return $retorno_conta_especifica;
}

function alterarContaEspecifica($conexao,$valor,$data_pagar,$pago,$id_empresa){
    $retorno_alterar_conta_especifica = alterar_conta($conexao,$valor,$data_pagar,$pago,$id_empresa);
    return $retorno_alterar_conta_especifica;
}

function excluirContaEspecifica($conexao,$codigo_conta_pagar)
{
    deletar_conta($conexao,$codigo_conta_pagar);
}

function informaPagamento($conexao,$valor,$data_pagar,$pago,$id_empresa,$id_conta_pagar)
{
    informa_pagamento($conexao,$valor,$data_pagar,$pago,$id_empresa,$id_conta_pagar);
}
?>