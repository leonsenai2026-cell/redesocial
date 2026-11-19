<?php
require_once __DIR__ . "/../models/PublicacaoModel.php";

function receberDadosPublicacao() {
    $dados = json_decode(file_get_contents("php://input"), true);

    if (!is_array($dados)) {
        http_response_code(400);
        echo json_encode(["erro" => "Envie um JSON com os dados da publicação."]);
        return null;
    }

    foreach (["id_usuario", "conteudo"] as $campo) {
        if (!array_key_exists($campo, $dados)) {
            http_response_code(400);
            echo json_encode(["erro" => "O campo {$campo} é obrigatório."]);
            return null;
        }
    }

    $dados["id_usuario"] = filter_var($dados["id_usuario"], FILTER_VALIDATE_INT, [
        "options" => ["min_range" => 1]
    ]);
    if ($dados["id_usuario"] === false || !is_string($dados["conteudo"]) || trim($dados["conteudo"]) === "") {
        http_response_code(400);
        echo json_encode(["erro" => "Informe um id_usuario válido e um conteúdo não vazio."]);
        return null;
    }

    return $dados;
}

function receberIdPublicacao() {
    $id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT, [
        "options" => ["min_range" => 1]
    ]);

    if ($id === false) {
        http_response_code(400);
        echo json_encode(["erro" => "Id da publicação inválido."]);
        return null;
    }
    return $id;
}

function criarPublicacao($con) {
    $dados = receberDadosPublicacao();
    if ($dados === null) return;

    $id = inserirPublicacao($con, $dados);
    http_response_code(201);
    echo json_encode(["mensagem" => "Publicação criada com sucesso!", "id" => $id]);
}

function lerPublicacoes($con) {
    if (isset($_GET["id"])) {
        $id = receberIdPublicacao();
        if ($id === null) return;

        $publicacao = buscarPublicacaoPorId($con, $id);
        if (!$publicacao) {
            http_response_code(404);
            echo json_encode(["erro" => "Publicação não encontrada."]);
            return;
        }
        echo json_encode($publicacao);
        return;
    }

    echo json_encode(listarPublicacoes($con));
}

function editarPublicacao($con) {
    $id = receberIdPublicacao();
    if ($id === null) return;

    $dados = receberDadosPublicacao();
    if ($dados === null) return;

    if (atualizarPublicacao($con, $id, $dados) === 0 && !buscarPublicacaoPorId($con, $id)) {
        http_response_code(404);
        echo json_encode(["erro" => "Publicação não encontrada."]);
        return;
    }
    echo json_encode(["mensagem" => "Publicação atualizada com sucesso!"]);
}

function excluirPublicacao($con) {
    $id = receberIdPublicacao();
    if ($id === null) return;

    if (removerPublicacao($con, $id) === 0) {
        http_response_code(404);
        echo json_encode(["erro" => "Publicação não encontrada."]);
        return;
    }
    echo json_encode(["mensagem" => "Publicação removida com sucesso!"]);
}
