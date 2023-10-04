<?php
session_start();
function lista_empresas($conexao)
{
    $empresas = array();
    $sql_selecionar_empresas = "select * from tbl_empresa";
    $resultado_executa = mysqli_query($conexao,$sql_selecionar_empresas);

    $verificando_resultado = mysqli_num_rows($resultado_executa);
    if($verificando_resultado > 0){
        $empresas = mysqli_fetch_all($resultado_executa,MYSQLI_ASSOC);
    }

    //mysqli_close($conexao);
    return $empresas;
}

function cadastrar_conta($conexao,$valor,$data_pagar,$pago,$id_empresa)
{
    $sql_inserir_conta = "insert into tbl_conta_pagar(valor,data_pagar,pago,id_empresa)values(?,?,?,?)";
    $comando = mysqli_stmt_init($conexao);

    if(!mysqli_stmt_prepare($comando,$sql_inserir_conta))
        exit("Erro sql");

    mysqli_stmt_bind_param($comando,"ssss",$valor,$data_pagar,$pago,$id_empresa);
    mysqli_stmt_execute($comando);
    //mysqli_close($conexao);
    header("Location:../index.php");
}

function listar_contas($conexao)
{
    $contas_pagar = array();
    $sql_selecionar_contas_pagar = "select * from tbl_conta_pagar";
    $resultado_listagem_contas = mysqli_query($conexao,$sql_selecionar_contas_pagar);

    $verificando_resultado = mysqli_num_rows($resultado_listagem_contas);
    if($verificando_resultado > 0){
        $contas_pagar = mysqli_fetch_all($resultado_listagem_contas,MYSQLI_ASSOC);
    }

    //mysqli_close($conexao);
    return $contas_pagar;
}

function busca_conta_especifica($conexao,$codigo_conta_pagar)
{
    $conta = "";
    $sql_BuscaContaEspecifica = "select * from tbl_conta_pagar where id_conta_pagar = ?";
    $stmt = mysqli_stmt_init($conexao);
    
    if(!mysqli_stmt_prepare($stmt,$sql_BuscaContaEspecifica))
        exit("Erro sql");

    mysqli_stmt_bind_param($stmt, 'i', $codigo_conta_pagar);
    mysqli_stmt_execute($stmt);
    
    $conta = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    
    $_SESSION["conta_localizada"] = $conta;

    //mysqli_close($conexao);

    busca_empresa_especifica($conexao,$_SESSION["conta_localizada"]["id_empresa"]);
}

function busca_empresa_especifica($conexao,$codigo_empresa)
{
    $empresa = "";
    $sql_BuscaEmpresaEspecifica = "select * from tbl_empresa where id_empresa = ?";
    $stmt = mysqli_stmt_init($conexao);
    
    if(!mysqli_stmt_prepare($stmt,$sql_BuscaEmpresaEspecifica))
        exit("Erro sql");

    mysqli_stmt_bind_param($stmt, 'i', $codigo_empresa);
    mysqli_stmt_execute($stmt);
    
    $empresa = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    
    $_SESSION["empresa_localizada"] = $empresa;

    mysqli_close($conexao);
    header("Location:../index.php");
}

function alterar_conta($conexao,$valor,$data_pagar,$pago,$id_empresa)
{
    if(isset($_SESSION["conta_localizada"]))
    {
        $sql_AlterarConta = "update tbl_conta_pagar set valor = ?,data_pagar = ?,pago = ?,id_empresa = ? where id_conta_pagar = ?";
        $stmt = mysqli_stmt_init($conexao);

        if(!mysqli_stmt_prepare($stmt, $sql_AlterarConta))
            exit('Erro sql');

        mysqli_stmt_bind_param($stmt, 'sssii', $valor, $data_pagar, $pago, $id_empresa,$_SESSION["conta_localizada"]["id_conta_pagar"]);
        mysqli_stmt_execute($stmt);
        mysqli_close($conexao);
        if(isset($_SESSION["conta_localizada"]) && isset($_SESSION["empresa_localizada"]))
        {
            unset($_SESSION["conta_localizada"],$_SESSION["empresa_localizada"]);
        }
        
        header("Location:../index.php");
    }else{
        exit();
    }
}

function informa_pagamento($conexao,$valor,$data_pagar,$pago,$id_empresa,$id_conta_pagar)
{
    $sql_AlterarConta = "update tbl_conta_pagar set valor = ?,data_pagar = ?,pago = ?,id_empresa = ? where id_conta_pagar = ?";
    $stmt = mysqli_stmt_init($conexao);

    if(!mysqli_stmt_prepare($stmt, $sql_AlterarConta))
       exit('Erro sql');

    mysqli_stmt_bind_param($stmt, 'sssii', $valor, $data_pagar, $pago, $id_empresa,$id_conta_pagar);
    mysqli_stmt_execute($stmt);
    mysqli_close($conexao);
    header("Location:../index.php");    
}

function deletar_conta($conexao,$codigo_conta_pagar)
{
    $sql = "DELETE FROM tbl_conta_pagar WHERE id_conta_pagar = ?";
    $stmt = mysqli_stmt_init($conexao);

    if(!mysqli_stmt_prepare($stmt, $sql))
       exit('SQL error');

    mysqli_stmt_bind_param($stmt, 'i', $codigo_conta_pagar);
    mysqli_stmt_execute($stmt);
    header("Location:../index.php");
}
?>