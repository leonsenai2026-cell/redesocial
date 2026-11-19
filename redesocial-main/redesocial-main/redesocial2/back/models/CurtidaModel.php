<?php

// CREATE - registra uma curtida
function inserirCurtida($con, $dados) {
    $stmt = $con->prepare(
        "INSERT INTO curtidas (id_usuario, id_publicacao)
         VALUES (?, ?)"
    );
    $stmt->execute([
        $dados["id_usuario"],
        $dados["id_publicacao"]
    ]);
    return $con->lastInsertId();
}

// READ - lista curtidas de uma publicação
function listarCurtidasPorPublicacao($con, $idPublicacao) {
    $stmt = $con->prepare(
        "SELECT c.*, u.username
         FROM curtidas c
         INNER JOIN usuarios u ON u.id_usuario = c.id_usuario
         WHERE c.id_publicacao = ?
         ORDER BY c.id_curtida DESC"
    );
    $stmt->execute([$idPublicacao]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// READ - quantidade de curtidas de uma publicação
function contarCurtidas($con, $idPublicacao) {
    $stmt = $con->prepare(
        "SELECT COUNT(*) FROM curtidas WHERE id_publicacao = ?"
    );
    $stmt->execute([$idPublicacao]);
    return (int) $stmt->fetchColumn();
}

// DELETE - remove a curtida de um usuário em uma publicação
function removerCurtida($con, $idUsuario, $idPublicacao) {
    $stmt = $con->prepare(
        "DELETE FROM curtidas WHERE id_usuario = ? AND id_publicacao = ?"
    );
    $stmt->execute([$idUsuario, $idPublicacao]);
    return $stmt->rowCount();
}
