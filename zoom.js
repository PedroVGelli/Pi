  document.addEventListener("DOMContentLoaded", function() {
    const img = document.getElementById("myimage");
    const result = document.getElementById("myresult");
    
    // Função para atualizar a área de zoom
    function zoom(e) {
      // Obtenha as coordenadas da imagem
      const imgRect = img.getBoundingClientRect();
      const x = e.clientX - imgRect.left;
      const y = e.clientY - imgRect.top;
      
      // Calcula a posição do background na área de zoom
      const resultWidth = result.offsetWidth;
      const resultHeight = result.offsetHeight;
      const imgWidth = img.width;
      const imgHeight = img.height;
      
      // Define o background da área de zoom
      result.style.backgroundImage = `url('${img.src}')`;
      result.style.backgroundSize = `${imgWidth}px ${imgHeight}px`;
      
      // Calcula a posição do background na área de zoom
      const xPos = (x / imgRect.width) * 100;
      const yPos = (y / imgRect.height) * 100;
      
      // Ajusta a posição de background na área de zoom
      result.style.backgroundPosition = `-${xPos * (resultWidth / 100)}px -${yPos * (resultHeight / 100)}px`;
      
      // Exibe a área de zoom
      result.style.display = "block";
    }

    // Adiciona eventos para mover e sair do mouse
    img.addEventListener("mousemove", zoom);
    img.addEventListener("mouseout", function() {
      result.style.display = "none";
    });
    
    // Inicialmente oculta a área de zoom
    result.style.display = "none";
  });

  document.addEventListener("DOMContentLoaded", function() {
    const stars = document.querySelectorAll('#star img');

    function fillStars(count) {
      stars.forEach((star, index) => {
        star.src = index < count ? 'img/estrla-preenchida.png' : 'img/estrela-vazia.png';
      });
    }

    stars.forEach((star, index) => {
      star.addEventListener('mouseover', () => {
        fillStars(index + 1); // Preenche todas as estrelas até a estrela atual
      });

      star.addEventListener('mouseout', () => {
        fillStars(0); // Restaura todas as estrelas para vazias
      });

      star.addEventListener('click', () => {
        // Se desejar adicionar uma ação ao clicar, como selecionar uma classificação
        console.log(`Você selecionou a classificação ${index + 1}`);
      });
    });
  });
