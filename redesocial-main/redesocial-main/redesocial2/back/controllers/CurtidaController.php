<?php
require_once __DIR__ . "/../models/CurtidaModel.php";

function receberDadosCurtida() {
    $dados = json_decode(file_get_contents("php://input"), true);

    if (!is_array($dados)) {
        http_response_code(400);
        echo json_encode(["erro" => "Envie um JSON com os dados da curtida."]);
        return null;
    }

    foreach (["id_usuario", "id_publicacao"] as $campo) {
        $valor = filter_var($dados[$campo] ?? null, FILTER_VALIDATE_INT, [
            "options" => ["min_range" => 1]
        ]);
        if ($valor === false) {
            http_response_code(400);
            echo json_encode(["erro" => "O campo {$campo} deve ser um número inteiro positivo."]);
            return null;
        }
        $dados[$campo] = $valor;
    }

    return $dados;
}

function receberIdPublicacaoCurtida() {
    $id = filter_var($_GET["id_publicacao"] ?? null, FILTER_VALIDATE_INT, [
        "options" => ["min_range" => 1]
    ]);
    if ($id === false) {
        http_response_code(400);
        echo json_encode(["erro" => "Id da publicação inválido."]);
        return null;
    }
    return $id;
}

function criarCurtida($con) {
    $dados = receberDadosCurtida();
    if ($dados === null) return;

    try {
        $id = inserirCurtida($con, $dados);
        http_response_code(201);
        echo json_encode(["mensagem" => "Curtida registrada com sucesso!", "id" => $id]);
    } catch (PDOException $e) {
        // Também impede curtidas duplicadas caso exista índice UNIQUE no banco.
        http_response_code(409);
        echo json_encode(["erro" => "Não foi possível registrar a curtida. Verifique se ela já existe e se os IDs são válidos."]);
    }
}

function lerCurtidas($con) {
    $idPublicacao = receberIdPublicacaoCurtida();
    if ($idPublicacao === null) return;

    echo json_encode([
        "id_publicacao" => $idPublicacao,
        "quantidade" => contarCurtidas($con, $idPublicacao),
        "curtidas" => listarCurtidasPorPublicacao($con, $idPublicacao)
    ]);
}

function excluirCurtida($con) {
    $dados = receberDadosCurtida();
    if ($dados === null) return;

    if (removerCurtida($con, $dados["id_usuario"], $dados["id_publicacao"]) === 0) {
        http_response_code(404);
        echo json_encode(["erro" => "Curtida não encontrada."]);
        return;
    }
    echo json_encode(["mensagem" => "Curtida removida com sucesso!"]);
}
