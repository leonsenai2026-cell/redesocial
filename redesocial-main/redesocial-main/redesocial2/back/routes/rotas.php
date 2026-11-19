<?php
require_once __DIR__ . "/../controller/PublicacaoController.php";
require_once __DIR__ . "/../controller/CurtidaController.php";

$metodo = $_SERVER["REQUEST_METHOD"];

// A rota pode ser informada, por exemplo, como:
// /api.php?rota=publicacao ou /api.php?rota=curtida
$rota = $_GET["rota"] ?? "";

switch ($rota) {
    case "publicacao":
    case "publicacoes":
        switch ($metodo) {
            case "POST":
                criarPublicacao($con);
                break;
            case "GET":
                lerPublicacoes($con);
                break;
            case "PUT":
                editarPublicacao($con);
                break;
            case "DELETE":
                excluirPublicacao($con);
                break;
            default:
                http_response_code(405);
                echo json_encode(["erro" => "Método não permitido para publicações."]);
        }
        break;

    case "curtida":
    case "curtidas":
        switch ($metodo) {
            case "POST":
                criarCurtida($con);
                break;
            case "GET":
                lerCurtidas($con);
                break;
            case "DELETE":
                excluirCurtida($con);
                break;
            default:
                http_response_code(405);
                echo json_encode(["erro" => "Método não permitido para curtidas."]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["erro" => "Rota não encontrada."]);
}
