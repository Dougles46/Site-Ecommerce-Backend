/**
 * main.js - Scripts principais do site Crochê & Arte
 * Funcionalidades interativas e utilitários
 */

// ==========================================
// INICIALIZAÇÃO
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
  initializeTooltips();
  initializePopovers();
  initializeFormValidation();
  initializeScrollAnimations();
  initializeNewsletterForm();
  initializeLazyLoading();
});

// ==========================================
// TOOLTIPS
// ==========================================

function initializeTooltips() {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
}

// ==========================================
// POPOVERS
// ==========================================

function initializePopovers() {
  const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
  popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });
}

// ==========================================
// VALIDAÇÃO DE FORMULÁRIOS
// ==========================================

function initializeFormValidation() {
  const forms = document.querySelectorAll('form[novalidate]');
  
  forms.forEach(form => {
    form.addEventListener('submit', function(event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
}

// ==========================================
// ANIMAÇÕES AO SCROLL
// ==========================================

function initializeScrollAnimations() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
  };

  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.animation = 'slideInUp 0.6s ease-out forwards';
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  // Observar cards de produtos
  document.querySelectorAll('.product-card').forEach(card => {
    card.style.opacity = '0';
    observer.observe(card);
  });

  // Observar seções
  document.querySelectorAll('section').forEach(section => {
    if (!section.classList.contains('hero')) {
      observer.observe(section);
    }
  });
}

// ==========================================
// LAZY LOADING DE IMAGENS
// ==========================================

function initializeLazyLoading() {
  if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          img.classList.add('loaded');
          observer.unobserve(img);
        }
      });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
      imageObserver.observe(img);
    });
  }
}

// ==========================================
// FORMULÁRIO DE NEWSLETTER
// ==========================================

function initializeNewsletterForm() {
  const form = document.getElementById('newsletterForm');
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const email = form.querySelector('input[type="email"]').value;
      
      // Simular envio (em produção, isso seria uma requisição AJAX)
      if (email) {
        showNotification('Obrigado! Você foi inscrito em nossa newsletter.', 'success');
        form.reset();
      }
    });
  }
}

// ==========================================
// NOTIFICAÇÕES
// ==========================================

function showNotification(message, type = 'info') {
  const alertDiv = document.createElement('div');
  alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
  alertDiv.setAttribute('role', 'alert');
  alertDiv.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  `;
  
  const container = document.querySelector('.container');
  if (container) {
    container.insertBefore(alertDiv, container.firstChild);
    
    // Remover automaticamente após 5 segundos
    setTimeout(() => {
      alertDiv.remove();
    }, 5000);
  }
}

// ==========================================
// FILTRO DE PRODUTOS
// ==========================================

function filterProducts(category) {
  const products = document.querySelectorAll('.product-card');
  
  products.forEach(product => {
    if (category === 'all' || product.dataset.category === category) {
      product.style.display = 'block';
      product.style.animation = 'fadeIn 0.3s ease-in';
    } else {
      product.style.display = 'none';
    }
  });
}

// ==========================================
// CARRINHO DE COMPRAS (SIMULADO)
// ==========================================

class ShoppingCart {
  constructor() {
    this.items = JSON.parse(localStorage.getItem('cart')) || [];
  }

  addItem(product) {
    const existingItem = this.items.find(item => item.id === product.id);
    
    if (existingItem) {
      existingItem.quantity += 1;
    } else {
      this.items.push({ ...product, quantity: 1 });
    }
    
    this.save();
    showNotification(`${product.name} adicionado ao carrinho!`, 'success');
  }

  removeItem(productId) {
    this.items = this.items.filter(item => item.id !== productId);
    this.save();
  }

  updateQuantity(productId, quantity) {
    const item = this.items.find(item => item.id === productId);
    if (item) {
      item.quantity = Math.max(1, quantity);
      this.save();
    }
  }

  getTotal() {
    return this.items.reduce((total, item) => total + (item.price * item.quantity), 0);
  }

  getItemCount() {
    return this.items.reduce((count, item) => count + item.quantity, 0);
  }

  save() {
    localStorage.setItem('cart', JSON.stringify(this.items));
    this.updateCartBadge();
  }

  updateCartBadge() {
    const badge = document.getElementById('cartBadge');
    if (badge) {
      badge.textContent = this.getItemCount();
    }
  }

  clear() {
    this.items = [];
    this.save();
  }
}

// Inicializar carrinho global
const cart = new ShoppingCart();

// ==========================================
// BUSCA DE PRODUTOS
// ==========================================

function searchProducts(query) {
  const products = document.querySelectorAll('.product-card');
  const lowerQuery = query.toLowerCase();
  
  products.forEach(product => {
    const title = product.querySelector('.product-card-title').textContent.toLowerCase();
    const description = product.querySelector('.product-card-description').textContent.toLowerCase();
    
    if (title.includes(lowerQuery) || description.includes(lowerQuery)) {
      product.style.display = 'block';
      product.style.animation = 'fadeIn 0.3s ease-in';
    } else {
      product.style.display = 'none';
    }
  });
}

// ==========================================
// MODO ESCURO
// ==========================================

function toggleDarkMode() {
  const isDarkMode = localStorage.getItem('darkMode') === 'true';
  
  if (isDarkMode) {
    document.documentElement.style.colorScheme = 'light';
    localStorage.setItem('darkMode', 'false');
  } else {
    document.documentElement.style.colorScheme = 'dark';
    localStorage.setItem('darkMode', 'true');
  }
}

// Verificar preferência de modo escuro ao carregar
if (localStorage.getItem('darkMode') === 'true') {
  document.documentElement.style.colorScheme = 'dark';
}

// ==========================================
// SMOOTH SCROLL
// ==========================================

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    const href = this.getAttribute('href');
    if (href !== '#' && document.querySelector(href)) {
      e.preventDefault();
      document.querySelector(href).scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    }
  });
});

// ==========================================
// UTILITÁRIOS
// ==========================================

// Formatar moeda
function formatCurrency(value) {
  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  }).format(value);
}

// Formatar data
function formatDate(date) {
  return new Intl.DateTimeFormat('pt-BR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(new Date(date));
}

// Copiar para clipboard
function copyToClipboard(text) {
  navigator.clipboard.writeText(text).then(() => {
    showNotification('Copiado para a área de transferência!', 'success');
  }).catch(() => {
    showNotification('Erro ao copiar para a área de transferência.', 'danger');
  });
}

// ==========================================
// PERFORMANCE
// ==========================================

// Lazy load de iframes
document.querySelectorAll('iframe[data-src]').forEach(iframe => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        iframe.src = iframe.dataset.src;
        observer.unobserve(iframe);
      }
    });
  });
  observer.observe(iframe);
});

// ==========================================
// ANALYTICS (OPCIONAL)
// ==========================================

// Rastrear eventos de clique em produtos
document.querySelectorAll('.product-card').forEach(card => {
  card.addEventListener('click', function() {
    const productName = this.querySelector('.product-card-title').textContent;
    console.log('Produto visualizado:', productName);
    // Aqui você pode enviar dados para um serviço de analytics
  });
});

