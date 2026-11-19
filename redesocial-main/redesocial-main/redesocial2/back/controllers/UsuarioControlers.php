<?php
require_once __DIR__ . "/../models/UsuarioModels.php";

// RECEBER E VALIDAR OS DADOS
function receberDadosUsuario(){
    $dados = json_decode(file_get_contents("php://input"), true);

    if(!is_array($dados)){
        echo json_encode([
            "Erro" => "Envie um JSON com dados!"
        ]);
        return null;
    }
    
    // nome, username, email e senha -> existe
    foreach (["nome", "username", "email", "senha"] as $campo){
        if(!array_key_exists($campo, $dados) || !is_string($dados[$campo]) || trim($dados[$campo]) === ""){
            http_response_code(400);
            echo json_encode([
                "Erro" => "Campo $campo é obrigatório!"
            ]);
            return null;
        }
    }
    return $dados;
}

function receberIdUsuario(){
    $id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT, [
        "options" => ["min_range" => 1]
    ]);
    
    if ($id === false) {
        http_response_code(400);
        echo json_encode([
            "Erro" => "Id inválido!"
        ]);
        return null;
    }
    
    return $id;
}

function createUsuario($con){
    $dados = receberDadosUsuario();
    if ($dados === null) return;

    $id = inserir($con, $dados);
    http_response_code(201);
    echo json_encode([
        "mensagem" => "Usuário criado com sucesso!",
        "id" => $id
    ]);
}

function readUsuario($con){
    $id = receberIdUsuario();
    if ($id === null) return;

    $usuario = buscarPorId($con, $id); // Supondo que exista essa função no seu Model
    
    if (!$usuario) {
        http_response_code(404);
        echo json_encode(["Erro" => "Usuário não encontrado!"]);
        return;
    }

    echo json_encode($usuario);
}

function updateUsuario($con){
    $id = receberIdUsuario();
    if ($id === null) return;

    $dados = receberDadosUsuario();
    if ($dados === null) return; 

    atualizar($con, $id, $dados);
    echo json_encode([
        "mensagem" => "Usuário atualizado com sucesso!"
    ]);
}

function deleteUsuario($con){
    $id = receberIdUsuario();
    if ($id === null) return;

    remover($con, $id);
    echo json_encode([
        "mensagem" => "Usuário removido com sucesso!"
    ]);
}
