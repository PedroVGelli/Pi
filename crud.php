<?php
session_start();
require 'conexao.php'; // Certifique-se de que este caminho está correto

// Cria uma instância da classe Conexao e obtém a conexão
$conexao = new Conexao();
$pdo = $conexao->conectar();

// Processar inclusão de livro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inclusão de um novo livro
    if (isset($_POST['adicionar'])) {
        // Coleta dos dados
        $titulo = $_POST['titulo'];
        $autor = $_POST['autor'];
        $genero = $_POST['genero'];
        $sinopse = $_POST['sinopse'];
        $editora = $_POST['editora'];
        $edicao = $_POST['edicao'];
        $ISBN_10 = $_POST['ISBN_10'];
        $ISBN_13 = $_POST['ISBN_13'];
        $quant_livro = $_POST['quant_livro'];
        $ano = $_POST['ano'];
        $idioma = $_POST['idioma'];
        $dimensoes = $_POST['dimensoes'];
        $paginas = $_POST['paginas'];

        $imagemNome = null;

        // Lida com o upload da foto
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
            $fotoTmp = $_FILES['foto']['tmp_name'];
            $imagemNome = uniqid() . '.jpg'; // Gera um nome único para a foto
            $destino = 'uploads/' . $imagemNome;

            // Redimensiona a imagem para 218x148 px
            list($larguraOriginal, $alturaOriginal) = getimagesize($fotoTmp);
            $imagemOriginal = imagecreatefromjpeg($fotoTmp);
            $imagemRedimensionada = imagecreatetruecolor(218, 148);
            imagecopyresampled($imagemRedimensionada, $imagemOriginal, 0, 0, 0, 0, 218, 148, $larguraOriginal, $alturaOriginal);
            imagejpeg($imagemRedimensionada, $destino);
            imagedestroy($imagemOriginal);
            imagedestroy($imagemRedimensionada);
        }

        // Insere os dados no banco de dados
        $stmt = $pdo->prepare('INSERT INTO Livros (titulo, autor, genero, sinopse, editora, edicao, ISBN_10, ISBN_13, quant_livro, ano, idioma, dimensoes, paginas, imagem) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$titulo, $autor, $genero, $sinopse, $editora, $edicao, $ISBN_10, $ISBN_13, $quant_livro, $ano, $idioma, $dimensoes, $paginas, $imagemNome]);
    }

    // Exclusão de livros selecionados
    if (isset($_POST['excluir'])) {
        $ids = $_POST['ids'];

        // Verifica se $ids é um array e aplica array_map
        if (is_array($ids)) {
            $ids = array_map('intval', $ids);
            $ids = implode(',', $ids);

            // Remove as imagens dos livros antes de excluir
            $stmt = $pdo->query("SELECT imagem FROM Livros WHERE id_livro IN ($ids)");
            while ($row = $stmt->fetch()) {
                $imagem = $row['imagem'];
                if ($imagem && file_exists("uploads/$imagem")) {
                    unlink("uploads/$imagem"); // Remove o arquivo da imagem
                }
            }

            // Prepara e executa a exclusão dos livros
            $stmt = $pdo->prepare("DELETE FROM Livros WHERE id_livro IN ($ids)");
            $stmt->execute();
        } else {
            // Se $ids não for um array, não faz nada ou mostra uma mensagem de erro
            echo "Nenhum livro selecionado para exclusão.";
        }
    }

    // Edição de informações de um livro
    if (isset($_POST['editar'])) {
        $titulo = $_POST['titulo'];
        $autor = $_POST['autor'];
        $genero = $_POST['genero'];
        $sinopse = $_POST['sinopse'];
        $editora = $_POST['editora'];
        $edicao = $_POST['edicao'];
        $ISBN_10 = $_POST['ISBN_10'];
        $ISBN_13 = $_POST['ISBN_13'];
        $quant_livro = $_POST['quant_livro'];
        $ano = $_POST['ano'];
        $idioma = $_POST['idioma'];
        $dimensoes = $_POST['dimensoes'];
        $paginas = $_POST['paginas'];
        $id_livro = $_POST['id_livro'];

        // Lida com o upload da nova foto
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
            $fotoTmp = $_FILES['foto']['tmp_name'];
            $imagemNome = uniqid() . '.jpg'; // Gera um nome único para a foto
            $destino = 'uploads/' . $imagemNome;

            // Redimensiona a imagem para 218x148 px
            list($larguraOriginal, $alturaOriginal) = getimagesize($fotoTmp);
            $imagemOriginal = imagecreatefromjpeg($fotoTmp);
            $imagemRedimensionada = imagecreatetruecolor(218, 148);
            imagecopyresampled($imagemRedimensionada, $imagemOriginal, 0, 0, 0, 0, 218, 148, $larguraOriginal, $alturaOriginal);
            imagejpeg($imagemRedimensionada, $destino);
            imagedestroy($imagemOriginal);
            imagedestroy($imagemRedimensionada);

            // Remove a imagem antiga, se existir
            if ($imagemNome !== $_POST['imagem_antiga'] && file_exists("uploads/{$_POST['imagem_antiga']}")) {
                unlink("uploads/{$_POST['imagem_antiga']}");
            }
        } else {
            $imagemNome = $_POST['imagem_antiga']; // Mantém a imagem antiga se não for carregada uma nova
        }

        $stmt = $pdo->prepare('UPDATE Livros SET titulo = ?, autor = ?, genero = ?, sinopse = ?, editora = ?, edicao = ?, ISBN_10 = ?, ISBN_13 = ?, quant_livro = ?, ano = ?, idioma = ?, dimensoes = ?, paginas = ?, imagem = ? WHERE id_livro = ?');
        $stmt->execute([$titulo, $autor, $genero, $sinopse, $editora, $edicao, $ISBN_10, $ISBN_13, $quant_livro, $ano, $idioma, $dimensoes, $paginas, $imagemNome, $id_livro]);
    }
}
?>


<?php require 'header.php'?>
<h1 class="titulo-pagina">Gerenciamento de Produtos</h1>
<div class="usuario-adm">
<!-- Formulário para adicionar um novo livro -->
<form method="post" action="" enctype="multipart/form-data">
    <label>Título:</label>
    <input type="text" name="titulo" required>
    <label>Autor:</label>
    <input type="text" name="autor" required>
    <label>Gênero:</label>
    <input type="text" name="genero" required>
    <label>Sinopse:</label>
    <input type="text" name="sinopse" required>
    <label>Editora:</label>
    <input type="text" name="editora" required>
    <label>Edição:</label>
    <input type="text" name="edicao" required>
    <label>ISBN_10:</label>
    <input type="text" name="ISBN_10" required>
    <label>ISBN_13:</label>
    <input type="text" name="ISBN_13" required>
    <label>Quantidade de Livro:</label>
    <input type="text" name="quant_livro" required>
    <label>Ano:</label>
    <input type="text" name="ano" required>
    <label>Idioma:</label>
    <input type="text" name="idioma" required>
    <label>Dimensões:</label>
    <input type="text" name="dimensoes" required>
    <label>Páginas:</label>
    <input type="text" name="paginas" required>
    <label for="foto">Imagem:</label>
    <input type="file" name="foto" id="foto" class="form-control-file" accept="image/jpeg">
    <button type="submit" name="adicionar">Adicionar</button>
</form>

<!-- Formulário para exclusão de livros -->
<form method="post" action="">
    <table>
    <tr>
        <th></th>
        <th>Título</th>
        <th>Autor</th>
        <th>Gênero</th>
        <th>Sinopse</th>
        <th>Editora</th>
        <th>Edição</th>
        <th>ISBN_10</th>
        <th>ISBN_13</th>
        <th>Quantidade de Livro</th>
        <th>Ano</th>
        <th>Idioma</th>
        <th>Dimensões</th>
        <th>Páginas</th>
        <th>Imagem</th>
        <th>Editar</th>
    </tr>
    <?php
    // Listar todos os livros
    $stmt = $pdo->query('SELECT * FROM Livros');
    while ($row = $stmt->fetch()) {
        $imagem = $row['imagem'] ? "uploads/{$row['imagem']}" : 'uploads/default.jpg'; // Caminho da imagem ou imagem padrão
        echo "<tr>
            <td><input type='checkbox' name='ids[]' value='{$row['id_livro']}'></td>
            <td>{$row['titulo']}</td>
            <td>{$row['autor']}</td>
            <td>{$row['genero']}</td>
            <td>{$row['sinopse']}</td>
            <td>{$row['editora']}</td>
            <td>{$row['edicao']}</td>
            <td>{$row['ISBN_10']}</td>
            <td>{$row['ISBN_13']}</td>
            <td>{$row['quant_livro']}</td>
            <td>{$row['ano']}</td>
            <td>{$row['idioma']}</td>
            <td>{$row['dimensoes']}</td>
            <td>{$row['paginas']}</td>
            <td><img src='$imagem' alt='Imagem do Livro' style='width: 100px; height: auto;'></td>
            <td>
                <!-- Formulário de edição -->
                <form method='post' action='' enctype='multipart/form-data' style='display:inline'>
                    <input type='hidden' name='id_livro' value='{$row['id_livro']}'>
                    <input type='hidden' name='imagem_antiga' value='{$row['imagem']}'>
                    <input type='text' name='titulo' value='{$row['titulo']}' required>
                    <input type='text' name='autor' value='{$row['autor']}' required>
                    <input type='text' name='genero' value='{$row['genero']}' required>
                    <input type='text' name='sinopse' value='{$row['sinopse']}' required>
                    <input type='text' name='editora' value='{$row['editora']}' required>
                    <input type='text' name='edicao' value='{$row['edicao']}' required>
                    <input type='text' name='ISBN_10' value='{$row['ISBN_10']}' required>
                    <input type='text' name='ISBN_13' value='{$row['ISBN_13']}' required>
                    <input type='text' name='quant_livro' value='{$row['quant_livro']}' required>
                    <input type='text' name='ano' value='{$row['ano']}' required>
                    <input type='text' name='idioma' value='{$row['idioma']}' required>
                    <input type='text' name='dimensoes' value='{$row['dimensoes']}' required>
                    <input type='text' name='paginas' value='{$row['paginas']}' required>
                    <input type='file' name='foto' class='form-control-file' accept='image/jpeg'>
                    <button type='submit' name='editar'>Editar</button>
                </form>
            </td>
        </tr>";
    }
    ?>
</table>

    <!-- Botão para excluir os produtos selecionados -->
    <button type="submit" name="excluir">Excluir Selecionados</button>
</form>
    </div>
</body>
</html>