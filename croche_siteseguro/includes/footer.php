<?php
// includes/footer.php
// Footer reutilizável com design moderno
?>

<!-- Footer -->
<footer class="mt-5">
  <div class="container">
    <div class="footer-content">
      <!-- Sobre -->
      <div class="footer-section">
        <h5><i class="fas fa-heart text-accent-light"></i> Crochê & Arte</h5>
        <p>Produtos artesanais feitos com carinho e dedicação. Cada peça é única e especial, refletindo a paixão pelo crochê.</p>
        <div class="social-links">
          <a href="https://www.instagram.com" class="text-accent-light me-3" data-bs-toggle="tooltip" data-bs-title="Instagram">
            <i class="fab fa-instagram fa-lg"></i>
          </a>
          <a href="https://www.facebook.com" class="text-accent-light me-3" data-bs-toggle="tooltip" data-bs-title="Facebook">
            <i class="fab fa-facebook fa-lg"></i>
          </a>
          <a href="https://wa.me/5513981265690" class="text-accent-light" data-bs-toggle="tooltip" data-bs-title="WhatsApp">
            <i class="fab fa-whatsapp fa-lg"></i>
          </a>
        </div>
      </div>

      <!-- Links Rápidos -->
      <div class="footer-section">
        <h5>Links Rápidos</h5>
        <ul>
          <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php">Home</a></li>
          <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>produtos.php">Produtos</a></li>
          <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>sobre.php">Sobre Nós</a></li>
          <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>contato.php">Contato</a></li>
          <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>admin/login.php">Painel Admin</a></li>
        </ul>
      </div>

      <!-- Contato -->
      <div class="footer-section">
        <h5>Contato</h5>
        <p>
          <i class="fas fa-phone text-accent-light"></i> <a href="tel:+5513981265690">(13) 98126-5690</a>
        </p>
        <p>
          <i class="fas fa-envelope text-accent-light"></i> <a href="mailto:contato@croche.art">contato@croche.art</a>
        </p>
        <p>
          <i class="fas fa-map-marker-alt text-accent-light"></i> São Paulo, SP - Brasil
        </p>
      </div>

      <!-- Newsletter -->
      <div class="footer-section">
        <h5>Newsletter</h5>
        <p>Receba novidades sobre nossos produtos.</p>
        <form id="newsletterForm" class="d-flex gap-2">
          <input type="email" class="form-control form-control-sm" placeholder="Seu email" required>
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-paper-plane"></i>
          </button>
        </form>
      </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
      <div class="row align-items-center">
        <div class="col-md-6">
          <p>&copy; <?php echo date('Y'); ?> Crochê & Arte. Todos os direitos reservados.</p>
        </div>
        <div class="col-md-6 text-md-end">
          <p>
            <a href="#">Política de Privacidade</a> | 
            <a href="#">Termos de Serviço</a> | 
            <a href="#">Trocas e Devoluções</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap 5.3.2 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Scripts customizados -->
<script src="<?php echo isset($base_path) ? $base_path : ''; ?>assets/js/main.js"></script>

<?php if (isset($additional_js)): ?>
  <?php foreach ($additional_js as $js): ?>
    <script src="<?php echo htmlspecialchars($js); ?>"></script>
  <?php endforeach; ?>
<?php endif; ?>

</body>
</html>

