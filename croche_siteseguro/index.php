<?php 
include('db/conexao.php');
$page_title = 'Home';
$base_path = '';
?>
<?php include('includes/header.php'); ?>

<!-- Hero Section -->
<section class="hero">
  <div class="container">
    <div class="hero-content">
      <h1>Feito à mão com carinho</h1>
      <p>Bolsas e acessórios em crochê — únicos, sustentáveis e cheios de personalidade</p>
      
      <div class="hero-cta">
        <a href="produtos.php" class="btn btn-primary">
          <i class="fas fa-shopping-bag"></i> Ver Produtos
        </a>
        <a href="contato.php" class="btn btn-outline-primary">
          <i class="fas fa-envelope"></i> Entrar em Contato
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Seção de Destaques -->
<section class="py-5 bg-light">
  <div class="container">
    <h2>Por que escolher Crochê & Arte?</h2>
    
    <div class="row g-4 mt-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
          <div class="mb-3">
            <i class="fas fa-heart fa-3x text-accent"></i>
          </div>
          <h5 class="card-title">Feito com Amor</h5>
          <p class="card-text text-muted">Cada peça é criada com dedicação e carinho, refletindo a paixão pelo crochê artesanal.</p>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
          <div class="mb-3">
            <i class="fas fa-leaf fa-3x text-accent"></i>
          </div>
          <h5 class="card-title">Sustentável</h5>
          <p class="card-text text-muted">Utilizamos materiais de qualidade e práticas ecologicamente responsáveis.</p>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
          <div class="mb-3">
            <i class="fas fa-star fa-3x text-accent"></i>
          </div>
          <h5 class="card-title">Única e Especial</h5>
          <p class="card-text text-muted">Cada criação é única, garantindo que você tenha algo verdadeiramente especial.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Seção de Produtos em Destaque -->
<section id="produtos" class="py-5">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Produtos em Destaque</h2>
      <a href="produtos.php" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-arrow-right"></i> Ver Todos
      </a>
    </div>
    
    <?php
    $sql = "SELECT * FROM produtos ORDER BY criado_em DESC LIMIT 6";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0):
    ?>
      <div class="row g-4">
        <?php
        while($p = $result->fetch_assoc()):
          $image_path = 'assets/img/' . htmlspecialchars($p['imagem']);
        ?>
          <div class="col-12 col-sm-6 col-md-4">
            <div class="product-card">
              <div class="product-card-img-wrapper">
                <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($p['nome']); ?>" class="img-fluid">
                <span class="product-badge">
                  <i class="fas fa-star"></i> Destaque
                </span>
              </div>
              
              <div class="product-card-body">
                <h5 class="product-card-title"><?php echo htmlspecialchars($p['nome']); ?></h5>
                <p class="product-card-description"><?php echo htmlspecialchars(substr($p['descricao'], 0, 80)) . '...'; ?></p>
                <p class="product-card-price">R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></p>
              </div>
              
              <div class="product-card-footer">
                <button class="btn btn-primary btn-sm flex-grow-1" onclick="cart.addItem({id: <?php echo $p['id']; ?>, name: '<?php echo htmlspecialchars($p['nome']); ?>', price: <?php echo $p['preco']; ?>})">
                  <i class="fas fa-shopping-cart"></i> Carrinho
                </button>
                <a href="contato.php" class="btn btn-outline-primary btn-sm flex-grow-1">
                  <i class="fas fa-info-circle"></i> Info
                </a>
              </div>
            </div>
          </div>
        <?php
        endwhile;
        ?>
      </div>
    <?php
    else:
    ?>
      <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle"></i> Nenhum produto cadastrado ainda. Volte em breve!
      </div>
    <?php
    endif;
    ?>
  </div>
</section>

<!-- Seção de Testimoniais -->
<section class="py-5 bg-light">
  <div class="container">
    <h2>O que nossos clientes dizem</h2>
    
    <div class="row g-4 mt-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="mb-3">
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
            </div>
            <p class="card-text">"Adorei a qualidade e o acabamento! A bolsa é exatamente como descrito."</p>
            <p class="fw-bold mb-0">Maria Silva</p>
            <small class="text-muted">São Paulo, SP</small>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="mb-3">
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
            </div>
            <p class="card-text">"Produto único e bem feito. Recomendo para quem busca algo diferente!"</p>
            <p class="fw-bold mb-0">João Santos</p>
            <small class="text-muted">Rio de Janeiro, RJ</small>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="mb-3">
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
            </div>
            <p class="card-text">"Entrega rápida e produto de excelente qualidade. Muito satisfeito!"</p>
            <p class="fw-bold mb-0">Ana Costa</p>
            <small class="text-muted">Belo Horizonte, MG</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Seção de Contato -->
<section id="contato" class="py-5">
  <div class="container">
    <h2>Fale Conosco</h2>
    
    <div class="row g-4 mt-4">
      <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body p-4">
            <h5 class="card-title mb-4">
              <i class="fas fa-phone text-accent"></i> Entre em Contato
            </h5>
            
            <div class="mb-4">
              <h6 class="fw-bold mb-2">WhatsApp</h6>
              <a href="https://wa.me/5513981265690" class="btn btn-success btn-sm">
                <i class="fab fa-whatsapp"></i> (13) 98126-5690
              </a>
            </div>
            
            <div class="mb-4">
              <h6 class="fw-bold mb-2">Email</h6>
              <a href="mailto:contato@croche.art" class="btn btn-info btn-sm">
                <i class="fas fa-envelope"></i> contato@croche.art
              </a>
            </div>
            
            <div>
              <h6 class="fw-bold mb-2">Redes Sociais</h6>
              <div class="d-flex gap-2">
                <a href="https://instagram.com" class="btn btn-outline-primary btn-sm">
                  <i class="fab fa-instagram"></i>
                </a>
                <a href="https://facebook.com" class="btn btn-outline-primary btn-sm">
                  <i class="fab fa-facebook"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body p-4">
            <h5 class="card-title mb-4">
              <i class="fas fa-envelope text-accent"></i> Envie uma Mensagem
            </h5>
            
            <form id="contactForm" novalidate>
              <div class="form-group">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control" id="name" required>
                <div class="invalid-feedback">Por favor, digite seu nome.</div>
              </div>
              
              <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" required>
                <div class="invalid-feedback">Por favor, digite um email válido.</div>
              </div>
              
              <div class="form-group">
                <label for="message" class="form-label">Mensagem</label>
                <textarea class="form-control" id="message" rows="4" required></textarea>
                <div class="invalid-feedback">Por favor, digite uma mensagem.</div>
              </div>
              
              <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-paper-plane"></i> Enviar Mensagem
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Seção Sobre -->
<section id="sobre" class="py-5 bg-light">
  <div class="container">
    <h2>Sobre Crochê & Arte</h2>
    
    <div class="row g-4 mt-4 align-items-center">
      <div class="col-md-6">
        <p class="lead">Somos uma pequena empresa dedicada à criação de produtos artesanais em crochê, feitos com amor e dedicação.</p>
        
        <p>Cada peça é única e especial, refletindo a paixão pelo crochê artesanal. Utilizamos materiais de qualidade e práticas ecologicamente responsáveis para garantir que você tenha um produto duradouro e sustentável.</p>
        
        <p>Nossa missão é trazer beleza, conforto e sustentabilidade para o seu dia a dia através de produtos artesanais de alta qualidade.</p>
        
        <div class="d-flex gap-3 mt-4">
          <div>
            <h5 class="text-accent">100+</h5>
            <p class="text-muted">Clientes Satisfeitos</p>
          </div>
          <div>
            <h5 class="text-accent">50+</h5>
            <p class="text-muted">Produtos Criados</p>
          </div>
          <div>
            <h5 class="text-accent">5+</h5>
            <p class="text-muted">Anos de Experiência</p>
          </div>
        </div>
      </div>
      
      <div class="col-md-6">
        <div class="card border-0 shadow-md">
          <img src="assets/img/bolsa1.jpg" alt="Crochê e Arte" class="card-img-top" style="height: 300px; object-fit: cover;">
          <div class="card-body">
            <p class="card-text">Produtos artesanais feitos com carinho pela nossa equipe. Cada peça é única e especial.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>

<script>
// Validação do formulário de contato
document.getElementById('contactForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  if (this.checkValidity() === false) {
    e.stopPropagation();
  } else {
    // Aqui você pode enviar os dados via AJAX ou para um servidor
    showNotification('Mensagem enviada com sucesso! Entraremos em contato em breve.', 'success');
    this.reset();
    this.classList.remove('was-validated');
  }
  
  this.classList.add('was-validated');
});
</script>

