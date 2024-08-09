<?php
// Declaração da classe Conexao
class Conexao {
    // Atributos para armazenar os detalhes da conexão
    private $host = 'localhost';
    private $dbname = 'Getting_books';
    private $usuario = 'root';
    private $senha = '';

    // Método para conectar ao banco de dados
    public function conectar() {
        try {
            // Cria uma nova instância de PDO para a conexão com o banco de dados
            $conexao = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->usuario, $this->senha);
            // Define o modo de erro do PDO para exceções
            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Retorna a conexão
            return $conexao;
        } catch (PDOException $e) {
            // Em caso de erro, exibe a mensagem de erro
            echo 'Erro: ' . $e->getMessage();
        }
    }
}
?>