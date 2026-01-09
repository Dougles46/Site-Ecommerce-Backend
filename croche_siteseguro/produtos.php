<?php 
include('db/conexao.php');
$page_title = 'Produtos';
$base_path = '';
?>
<?php include('includes/header.php'); ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3 border-bottom">
  <div class="container">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Produtos</li>
    </ol>
  </div>
</nav>

<!-- Hero Section Produtos -->
<section class="py-5 bg-light">
  <div class="container">
    <h1 class="display-5 fw-bold mb-3">Nossos Produtos</h1>
    <p class="lead text-muted">Explore nossa coleção de bolsas e acessórios em crochê, todos feitos à mão com carinho.</p>
    
    <!-- Barra de Busca e Filtros -->
    <div class="row g-3 mt-4">
      <div class="col-md-6">
        <div class="input-group">
          <span class="input-group-text bg-white border-end-0">
            <i class="fas fa-search text-muted"></i>
          </span>
          <input type="text" class="form-control border-start-0" id="searchInput" placeholder="Buscar produtos...">
        </div>
      </div>
      
      <div class="col-md-6">
        <div class="d-flex gap-2">
          <button class="btn btn-outline-primary" onclick="filterProducts('all')">
            <i class="fas fa-th"></i> Todos
          </button>
          <button class="btn btn-outline-primary" onclick="filterProducts('bolsa')">
            <i class="fas fa-shopping-bag"></i> Bolsas
          </button>
          <button class="btn btn-outline-primary" onclick="filterProducts('acessorio')">
            <i class="fas fa-ring"></i> Acessórios
          </button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Seção de Produtos -->
<section class="py-5">
  <div class="container">
    <?php
    $sql = "SELECT * FROM produtos ORDER BY criado_em DESC";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0):
    ?>
      <div class="row g-4" id="productsContainer">
        <?php
        while($p = $result->fetch_assoc()):
          $image_path = 'assets/img/' . htmlspecialchars($p['imagem']);
          $category = isset($p['categoria']) ? htmlspecialchars($p['categoria']) : 'produto';
        ?>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 product-card-wrapper" data-category="<?php echo $category; ?>">
            <div class="product-card">
              <div class="product-card-img-wrapper">
                <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($p['nome']); ?>" class="img-fluid">
                <span class="product-badge">
                  <i class="fas fa-handshake"></i> Artesanal
                </span>
              </div>
              
              <div class="product-card-body">
                <h5 class="product-card-title"><?php echo htmlspecialchars($p['nome']); ?></h5>
                <p class="product-card-description"><?php echo htmlspecialchars($p['descricao']); ?></p>
                <p class="product-card-price">R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></p>
                
                <div class="d-flex gap-2 mb-3">
                  <span class="badge badge-primary">
                    <i class="fas fa-check-circle"></i> Em Estoque
                  </span>
                </div>
              </div>
              
              <div class="product-card-footer">
                <button class="btn btn-primary btn-sm flex-grow-1" onclick="cart.addItem({id: <?php echo $p['id']; ?>, name: '<?php echo htmlspecialchars($p['nome']); ?>', price: <?php echo $p['preco']; ?>})" data-bs-toggle="tooltip" data-bs-title="Adicionar ao carrinho">
                  <i class="fas fa-shopping-cart"></i> Carrinho
                </button>
                <button class="btn btn-outline-primary btn-sm" onclick="shareProduct('<?php echo htmlspecialchars($p['nome']); ?>')" data-bs-toggle="tooltip" data-bs-title="Compartilhar">
                  <i class="fas fa-share-alt"></i>
                </button>
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
      <div class="alert alert-info text-center py-5" role="alert">
        <i class="fas fa-inbox fa-3x mb-3"></i>
        <p class="lead mb-0">Nenhum produto cadastrado ainda.</p>
        <small class="text-muted">Volte em breve para conferir nossas novidades!</small>
      </div>
    <?php
    endif;
    ?>
  </div>
</section>

<!-- Seção de CTA -->
<section class="py-5 bg-light">
  <div class="container text-center">
    <h3 class="mb-3">Não encontrou o que procura?</h3>
    <p class="text-muted mb-4">Entre em contato conosco! Podemos criar produtos personalizados de acordo com suas preferências.</p>
    <a href="contato.php" class="btn btn-primary">
      <i class="fas fa-envelope"></i> Solicitar Produto Personalizado
    </a>
  </div>
</section>

<?php include('includes/footer.php'); ?>

<script>
// Busca de produtos
document.getElementById('searchInput').addEventListener('keyup', function(e) {
  const query = e.target.value.toLowerCase();
  const products = document.querySelectorAll('.product-card-wrapper');
  
  products.forEach(product => {
    const title = product.querySelector('.product-card-title').textContent.toLowerCase();
    const description = product.querySelector('.product-card-description').textContent.toLowerCase();
    
    if (title.includes(query) || description.includes(query)) {
      product.style.display = 'block';
      product.style.animation = 'fadeIn 0.3s ease-in';
    } else {
      product.style.display = 'none';
    }
  });
});

// Compartilhar produto
function shareProduct(productName) {
  const text = `Confira este produto: ${productName} - Crochê & Arte`;
  const url = window.location.href;
  
  if (navigator.share) {
    navigator.share({
      title: 'Crochê & Arte',
      text: text,
      url: url
    }).catch(err => console.log('Erro ao compartilhar:', err));
  } else {
    // Fallback: copiar para clipboard
    copyToClipboard(`${text}\n${url}`);
  }
}

// Filtro de produtos
function filterProducts(category) {
  const products = document.querySelectorAll('.product-card-wrapper');
  
  products.forEach(product => {
    if (category === 'all' || product.dataset.category === category) {
      product.style.display = 'block';
      product.style.animation = 'fadeIn 0.3s ease-in';
    } else {
      product.style.display = 'none';
    }
  });
}
</script>

