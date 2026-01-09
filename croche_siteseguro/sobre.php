<?php 
include('db/conexao.php');
$page_title = 'Sobre';
$base_path = '';
?>
<?php include('includes/header.php'); ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3 border-bottom">
  <div class="container">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Sobre Nós</li>
    </ol>
  </div>
</nav>

<!-- Hero Section -->
<section class="py-5 bg-light">
  <div class="container">
    <h1 class="display-5 fw-bold mb-3">Sobre Crochê & Arte</h1>
    <p class="lead text-muted">Conheça a história por trás de cada peça artesanal criada com amor e dedicação.</p>
  </div>
</section>

<!-- Seção Principal -->
<section class="py-5">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <h2 class="mb-4">Nossa História</h2>
        
        <p>Crochê & Arte nasceu da paixão por criar peças únicas e sustentáveis. O que começou como um hobby se transformou em uma pequena empresa dedicada a trazer beleza, conforto e sustentabilidade para o seu dia a dia.</p>
        
        <p>Cada produto é cuidadosamente criado à mão, refletindo a dedicação e o amor que colocamos em nosso trabalho. Utilizamos materiais de qualidade e práticas ecologicamente responsáveis para garantir que você tenha um produto duradouro e sustentável.</p>
        
        <p>Nossa missão é simples: criar produtos artesanais de alta qualidade que tragam alegria e funcionalidade para a vida de nossos clientes.</p>
        
        <div class="d-flex gap-4 mt-5">
          <div class="text-center">
            <h4 class="text-accent fw-bold">100+</h4>
            <p class="text-muted small">Clientes Satisfeitos</p>
          </div>
          <div class="text-center">
            <h4 class="text-accent fw-bold">50+</h4>
            <p class="text-muted small">Produtos Criados</p>
          </div>
          <div class="text-center">
            <h4 class="text-accent fw-bold">5+</h4>
            <p class="text-muted small">Anos de Experiência</p>
          </div>
        </div>
      </div>
      
      <div class="col-lg-6">
        <img src="assets/img/bolsa1.jpg" alt="Crochê & Arte" class="img-fluid rounded-3 shadow-lg">
      </div>
    </div>
  </div>
</section>

<!-- Seção de Valores -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-5">Nossos Valores</h2>
    
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body text-center p-4">
            <div class="mb-3">
              <i class="fas fa-heart fa-3x text-accent"></i>
            </div>
            <h5 class="card-title">Qualidade</h5>
            <p class="card-text text-muted">Cada peça é criada com atenção aos detalhes e usando os melhores materiais disponíveis.</p>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body text-center p-4">
            <div class="mb-3">
              <i class="fas fa-leaf fa-3x text-accent"></i>
            </div>
            <h5 class="card-title">Sustentabilidade</h5>
            <p class="card-text text-muted">Praticamos métodos ecologicamente responsáveis em toda nossa produção.</p>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body text-center p-4">
            <div class="mb-3">
              <i class="fas fa-handshake fa-3x text-accent"></i>
            </div>
            <h5 class="card-title">Autenticidade</h5>
            <p class="card-text text-muted">Cada produto é único e autêntico, refletindo a paixão pelo artesanato.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Seção de Processo -->
<section class="py-5">
  <div class="container">
    <h2 class="text-center mb-5">Como Criamos Nossos Produtos</h2>
    
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="text-center">
          <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
            <i class="fas fa-lightbulb fa-2x text-accent"></i>
          </div>
          <h5 class="fw-bold">1. Inspiração</h5>
          <p class="text-muted small">Buscamos inspiração em tendências, cores e estilos para criar designs únicos.</p>
        </div>
      </div>
      
      <div class="col-md-6 col-lg-3">
        <div class="text-center">
          <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
            <i class="fas fa-pencil-alt fa-2x text-accent"></i>
          </div>
          <h5 class="fw-bold">2. Design</h5>
          <p class="text-muted small">Planejamos cada detalhe para garantir funcionalidade e beleza.</p>
        </div>
      </div>
      
      <div class="col-md-6 col-lg-3">
        <div class="text-center">
          <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
            <i class="fas fa-hands fa-2x text-accent"></i>
          </div>
          <h5 class="fw-bold">3. Criação</h5>
          <p class="text-muted small">Cada peça é criada à mão com dedicação e cuidado especial.</p>
        </div>
      </div>
      
      <div class="col-md-6 col-lg-3">
        <div class="text-center">
          <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
            <i class="fas fa-box fa-2x text-accent"></i>
          </div>
          <h5 class="fw-bold">4. Entrega</h5>
          <p class="text-muted small">Embalamos com cuidado e entregamos direto para você.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Seção de Testimoniais -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-5">O que nossos clientes dizem</h2>
    
    <div class="row g-4">
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
            <p class="card-text">"Adorei a qualidade e o acabamento! A bolsa é exatamente como descrito. Recomendo!"</p>
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
            <p class="card-text">"Produto único e bem feito. Recomendo para quem busca algo diferente e de qualidade!"</p>
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
            <p class="card-text">"Entrega rápida e produto de excelente qualidade. Muito satisfeito com a compra!"</p>
            <p class="fw-bold mb-0">Ana Costa</p>
            <small class="text-muted">Belo Horizonte, MG</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Seção de CTA -->
<section class="py-5">
  <div class="container text-center">
    <h3 class="mb-3">Pronto para começar?</h3>
    <p class="text-muted mb-4">Explore nossa coleção de produtos artesanais e encontre a peça perfeita para você.</p>
    <div class="d-flex gap-3 justify-content-center flex-wrap">
      <a href="produtos.php" class="btn btn-primary">
        <i class="fas fa-shopping-bag"></i> Ver Produtos
      </a>
      <a href="contato.php" class="btn btn-outline-primary">
        <i class="fas fa-envelope"></i> Entrar em Contato
      </a>
    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>

