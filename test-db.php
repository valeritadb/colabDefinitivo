<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/configuraciones_colab/db.php';

$pdo = getPDO();
$stmt = $pdo->query("SELECT id, nombre FROM categorias ORDER BY nombre");
$categorias = $stmt->fetchAll();

echo '<pre>';
print_r($categorias);
echo '</pre>';