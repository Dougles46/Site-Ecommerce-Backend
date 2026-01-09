<?php 
include('db/conexao.php');
$page_title = 'Contato';
$base_path = '';
?>
<?php include('includes/header.php'); ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3 border-bottom">
  <div class="container">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Contato</li>
    </ol>
  </div>
</nav>

<!-- Hero Section -->
<section class="py-5 bg-light">
  <div class="container">
    <h1 class="display-5 fw-bold mb-3">Entre em Contato</h1>
    <p class="lead text-muted">Estamos aqui para ajudar! Envie-nos uma mensagem e responderemos assim que possível.</p>
  </div>
</section>

<!-- Seção de Contato -->
<section class="py-5">
  <div class="container">
    <div class="row g-5">
      <!-- Formulário -->
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <h5 class="card-title mb-4">
              <i class="fas fa-envelope text-accent"></i> Envie uma Mensagem
            </h5>
            
            <form id="contactForm" novalidate>
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="name" class="form-label">Nome Completo</label>
                  <input type="text" class="form-control" id="name" name="name" required>
                  <div class="invalid-feedback">Por favor, digite seu nome.</div>
                </div>
                
                <div class="col-md-6">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" required>
                  <div class="invalid-feedback">Por favor, digite um email válido.</div>
                </div>
                
                <div class="col-12">
                  <label for="phone" class="form-label">Telefone (opcional)</label>
                  <input type="tel" class="form-control" id="phone" name="phone" placeholder="(13) 98126-5690">
                </div>
                
                <div class="col-12">
                  <label for="subject" class="form-label">Assunto</label>
                  <select class="form-select" id="subject" name="subject" required>
                    <option value="">Selecione um assunto...</option>
                    <option value="duvida">Dúvida sobre Produtos</option>
                    <option value="pedido">Informação sobre Pedido</option>
                    <option value="personalizado">Produto Personalizado</option>
                    <option value="outro">Outro</option>
                  </select>
                  <div class="invalid-feedback">Por favor, selecione um assunto.</div>
                </div>
                
                <div class="col-12">
                  <label for="message" class="form-label">Mensagem</label>
                  <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
                  <div class="invalid-feedback">Por favor, digite uma mensagem.</div>
                </div>
                
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="privacy" name="privacy" required>
                    <label class="form-check-label" for="privacy">
                      Concordo com a <a href="#">política de privacidade</a>
                    </label>
                    <div class="invalid-feedback">Você deve concordar com a política de privacidade.</div>
                  </div>
                </div>
                
                <div class="col-12">
                  <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-paper-plane"></i> Enviar Mensagem
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      
      <!-- Informações de Contato -->
      <div class="col-lg-4">
        <!-- WhatsApp -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body p-4 text-center">
            <div class="mb-3">
              <i class="fab fa-whatsapp fa-3x text-success"></i>
            </div>
            <h5 class="card-title">WhatsApp</h5>
            <p class="card-text text-muted">Resposta rápida via WhatsApp</p>
            <a href="https://wa.me/5513981265690" class="btn btn-success btn-sm" target="_blank">
              <i class="fab fa-whatsapp"></i> Abrir WhatsApp
            </a>
          </div>
        </div>
        
        <!-- Email -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body p-4 text-center">
            <div class="mb-3">
              <i class="fas fa-envelope fa-3x text-info"></i>
            </div>
            <h5 class="card-title">Email</h5>
            <p class="card-text text-muted">contato@croche.art</p>
            <a href="mailto:contato@croche.art" class="btn btn-info btn-sm">
              <i class="fas fa-envelope"></i> Enviar Email
            </a>
          </div>
        </div>
        
        <!-- Horário de Atendimento -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body p-4">
            <h5 class="card-title mb-3">
              <i class="fas fa-clock text-accent"></i> Horário de Atendimento
            </h5>
            <ul class="list-unstyled">
              <li class="mb-2">
                <strong>Segunda a Sexta:</strong>
                <br>9:00 - 18:00
              </li>
              <li class="mb-2">
                <strong>Sábado:</strong>
                <br>10:00 - 16:00
              </li>
              <li>
                <strong>Domingo:</strong>
                <br>Fechado
              </li>
            </ul>
          </div>
        </div>
        
        <!-- Redes Sociais -->
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <h5 class="card-title mb-3">
              <i class="fas fa-share-alt text-accent"></i> Redes Sociais
            </h5>
            <div class="d-flex gap-2">
              <a href="https://instagram.com" class="btn btn-outline-primary btn-sm flex-grow-1" target="_blank" data-bs-toggle="tooltip" data-bs-title="Instagram">
                <i class="fab fa-instagram"></i>
              </a>
              <a href="https://facebook.com" class="btn btn-outline-primary btn-sm flex-grow-1" target="_blank" data-bs-toggle="tooltip" data-bs-title="Facebook">
                <i class="fab fa-facebook"></i>
              </a>
              <a href="https://pinterest.com" class="btn btn-outline-primary btn-sm flex-grow-1" target="_blank" data-bs-toggle="tooltip" data-bs-title="Pinterest">
                <i class="fab fa-pinterest"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Seção de FAQ -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-5">Perguntas Frequentes</h2>
    
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <div class="accordion" id="faqAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">
                Qual é o prazo de entrega?
              </button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                O prazo de entrega varia de 5 a 10 dias úteis, dependendo da localização. Você receberá um código de rastreamento assim que o pedido for enviado.
              </div>
            </div>
          </div>
          
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                Vocês fazem produtos personalizados?
              </button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Sim! Fazemos produtos personalizados de acordo com suas preferências. Entre em contato conosco para discutir os detalhes do seu pedido.
              </div>
            </div>
          </div>
          
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                Qual é a política de trocas e devoluções?
              </button>
            </h2>
            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Aceitamos trocas e devoluções em até 30 dias após a compra, desde que o produto esteja em perfeito estado. Entre em contato conosco para mais informações.
              </div>
            </div>
          </div>
          
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
                Como faço para cuidar do meu produto?
              </button>
            </h2>
            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Recomendamos lavar à mão com água fria e sabão neutro. Deixe secar naturalmente, longe da luz solar direta. Não use secadora de roupas.
              </div>
            </div>
          </div>
          
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">
                Vocês enviam para o exterior?
              </button>
            </h2>
            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Sim, enviamos para o exterior! Entre em contato conosco para obter um orçamento de frete internacional.
              </div>
            </div>
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

