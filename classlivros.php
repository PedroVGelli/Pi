<?php

$host = 'localhost';   // Endereço do servidor MySQL
$db   = 'Getting_books'; // Nome do banco de dados
$user = 'root';        // Nome de usuário do MySQL
$pass = '';            // Senha do MySQL

// Conectar ao banco de dados usando PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Exibir mensagem de erro se a conexão falhar
    echo 'Conexão falhou: ' . $e->getMessage();
}
?>