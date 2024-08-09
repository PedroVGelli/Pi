<?php
session_start();
require 'classlivros.php'; // Certifique-se de que este arquivo define e configura $pdo

// Preparar a consulta para buscar o livro com um ID específico
// Presumindo que você está passando um ID na URL, por exemplo: index.php?id=1
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare('SELECT * FROM Livros WHERE id_livro = :id');
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$livro = $stmt->fetch();

// Verificar se o livro foi encontrado
if (!$livro) {
    die('Livro não encontrado.');
}
?>

<?php require 'header.php'; ?>
<section>
    <div class="infodiv">
        <div class="divorg">    
            <div class="adequada">
                <div class="img-zoom-container">
                    <?php if (!empty($livro['imagem'])): ?>
                        <img id="myimage" src="uploads/<?php echo htmlspecialchars($livro['imagem']); ?>" alt="<?php echo htmlspecialchars($livro['titulo']); ?>">
                    <?php else: ?>
                        <img src="img/default.png" alt="Imagem não disponível">
                    <?php endif; ?>
                    <div id="myresult" class="img-zoom-result"></div>
                </div>
    
                <div class="secondorg">
                    <div>
                        <?php if (!empty($livro['imagem'])): ?>
                            <img class="imgsecond" src="uploads/<?php echo htmlspecialchars($livro['imagem']); ?>" alt="<?php echo htmlspecialchars($livro['titulo']); ?>">
                        <?php else: ?>
                            <img class="imgsecond" src="img/default.png" alt="Imagem não disponível">
                        <?php endif; ?>
                    </div>
                    <div>
                        <?php if (!empty($livro['imagem'])): ?>
                            <img class="imgsecond" src="uploads/<?php echo htmlspecialchars($livro['imagem']); ?>" alt="<?php echo htmlspecialchars($livro['titulo']); ?>">
                        <?php else: ?>
                            <img src="img/default.png" alt="Imagem não disponível">
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div id="descricao">
                <h1 id="titulo" aria-label="Titulo do Livro"><?php echo htmlspecialchars($livro['titulo']); ?></h1>
                <p id="autor" aria-label="Nome dos autores"><?php echo htmlspecialchars($livro['autor']); ?></p>
                <h2 id="star">
                    <img src="img/estrela-vazia.png" alt="estrela 1" class="star">
                    <img src="img/estrela-vazia.png" alt="estrela 2" class="star">
                    <img src="img/estrela-vazia.png" alt="estrela 3" class="star">
                    <img src="img/estrela-vazia.png" alt="estrela 4" class="star">
                    <img src="img/estrela-vazia.png" alt="estrela 5" class="star">
                </h2>
                
                <div>
                    <p class="categ"> <strong><i><?php echo htmlspecialchars($livro['genero']);?></i></strong></p>
                   
                </div>
                  
                <h1>Descrição</h1>
                <p id="p-descricao">
                    <?php echo htmlspecialchars($livro['sinopse']); ?> <br>
                    <a href="#titulo-segsection" class="link-livro" aria-label="Link para mais detalhes sobre o livro">Mais detalhes</a>
                </p>
                <p id="disponivel"><?php echo htmlspecialchars($livro['quant_livro']); ?> Livros disponíveis para a troca</p>
                <a href="troca.php?id=<?php echo urlencode($livro['id_livro']); ?>" id="quero-trocar" aria-label="Link para fazer a troca do livro">   
                    <p id="troca"><strong><i>Quero Trocar</i></strong></p>
                </a>
            </div>
        </div>
    </div>
</section>

<section id="titulo-segsection" class="titulo-segsection">
    <div id="seg-section">
        <h1 class="titulo-segsection">Sinopse:</h1>
        <p id="descricao-sinopse">
            Nova edição de Geometria Analítica - Um Tratamento Vetorial confirma sua posição como um clássico das ciências exatas. Ampliado e completamente revisto pelos autores, o livro traz centenas de novos exemplos e exercícios, além de ilustrações totalmente refeitas. Possui novo layout que proporciona uma leitura mais agradável e facilita a compreensão e a localização de tópicos e exercícios, mas a estrutura didática bem-sucedida das edições anteriores foi cuidadosamente mantida. Escrita em linguagem clara e objetiva, este livro traz respostas para os exercícios e estratégias de solução, o que o torna um guia essencial para o estudo da Geometria. <a
                href="https://books.google.com/books/about/Geometria_Anal%C3%ADtica.html?id=lmnqAAAACAAJ&source=kp_book_description"
                target="_blank" class="link-livro" aria-label="Link para mais informações sobre o livro no Google Books">Google Books</a>
        </p> <br>
        <h1 class="titulo-segsection">Detalhes do Produto</h1>
        <div class="detalhes-grid">
            <p><strong>ISBN 10:</strong> <?php echo htmlspecialchars($livro['ISBN_10']);?></p>
            <p><strong>ISBN 13:</strong> <?php echo htmlspecialchars($livro['ISBN_13']);?> </p>
            <p><strong>Editora:</strong> <?php echo htmlspecialchars($livro['editora']);?></p>
            <p><strong>Idioma:</strong> <?php echo htmlspecialchars($livro['idioma']);?></p>
            <p><strong>Edição:</strong> <?php echo htmlspecialchars($livro['edicao']);?>ª</p>
            <p><strong>Ano:</strong> <?php echo htmlspecialchars($livro['ano']);?></p>
            <p><strong>Páginas:</strong> <?php echo htmlspecialchars($livro['paginas']);?></p>
            <p><strong>Dimensões:</strong> <?php echo htmlspecialchars($livro['dimensoes']);?></p>
        </div>
    </div>
</section>
</body>
</html>
