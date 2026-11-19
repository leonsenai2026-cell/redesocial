<?php

// CREATE
function inserirPublicacao($con, $dados) {
    $stmt = $con->prepare(
        "INSERT INTO publicacoes (id_usuario, conteudo)
         VALUES (?, ?)"
    );
    $stmt->execute([
        $dados["id_usuario"],
        $dados["conteudo"]
    ]);

    return $con->lastInsertId();
}

// READ - lista todas as publicações
function listarPublicacoes($con) {
    $stmt = $con->query(
        "SELECT p.*, u.username
         FROM publicacoes p
         INNER JOIN usuarios u ON u.id_usuario = p.id_usuario
         ORDER BY p.id_publicacao DESC"
    );
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// READ - busca uma publicação pelo ID
function buscarPublicacaoPorId($con, $id) {
    $stmt = $con->prepare(
        "SELECT p.*, u.username
         FROM publicacoes p
         INNER JOIN usuarios u ON u.id_usuario = p.id_usuario
         WHERE p.id_publicacao = ?"
    );
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// UPDATE
function atualizarPublicacao($con, $id, $dados) {
    $stmt = $con->prepare(
        "UPDATE publicacoes SET conteudo = ?
         WHERE id_publicacao = ?"
    );
    $stmt->execute([$dados["conteudo"], $id]);
    return $stmt->rowCount();
}

// DELETE
function removerPublicacao($con, $id) {
    $stmt = $con->prepare(
        "DELETE FROM publicacoes WHERE id_publicacao = ?"
    );
    $stmt->execute([$id]);
    return $stmt->rowCount();
}
