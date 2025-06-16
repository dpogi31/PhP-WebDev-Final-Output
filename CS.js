document.addEventListener('DOMContentLoaded', () => {
  //  MODAL HELPERS 
  function showModal(modal) {
    if (modal.classList.contains('modal-overlay-contact')) {
      modal.classList.add('show');
    } else {
      modal.style.display = 'flex';
    }
    document.body.style.overflow = 'hidden';
  }

  function hideModal(modal) {
    if (modal.classList.contains('modal-overlay-contact')) {
      modal.classList.remove('show');
    } else {
      modal.style.display = 'none';
    }
    document.body.style.overflow = '';
  }

  function closeOnOutsideClick(modal) {
    window.addEventListener('click', (e) => {
      if (e.target === modal) {
        hideModal(modal);
      }
    });
  }

  //  MODAL SETUP FUNCTION 
  function setupModal(triggerId, modalId, closeId, formId, successMessage) {
    const trigger = document.getElementById(triggerId);
    const modal = document.getElementById(modalId);
    const close = document.getElementById(closeId);
    const form = document.getElementById(formId);

    if (trigger && modal && close) {
      trigger.addEventListener('click', (e) => {
        e.preventDefault();
        showModal(modal);
      });

      close.addEventListener('click', () => hideModal(modal));
      closeOnOutsideClick(modal);
    }

    if (form && successMessage) {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        alert(successMessage);
        hideModal(modal);
        form.reset();
      });
    }
  }

  //  SUPPORT MODAL 
  setupModal('supportBtn', 'supportModal', 'closeSupportModal', 'supportForm', "Thank you for reaching out to support. We'll get back to you soon!");

  //  CONTACT MODAL 
  setupModal('contactBtn', 'contactModal', 'closeContactModal', 'contactForm', "Thank you for reaching out. We'll get back to you soon!");

  //  LEARN MORE MODAL 
  setupModal('learnMoreBtn', 'learnMoreModal', 'closeModal');

  //  LOGOUT MODAL 
  const logoutBtn = document.getElementById('logoutBtn');
  const logoutModal = document.getElementById('logoutModal');
  const cancelLogoutBtn = document.getElementById('cancelLogout');

  if (logoutBtn && logoutModal && cancelLogoutBtn) {
    logoutBtn.addEventListener('click', (e) => {
      e.preventDefault();
      showModal(logoutModal);
    });

    cancelLogoutBtn.addEventListener('click', () => hideModal(logoutModal));
    closeOnOutsideClick(logoutModal);
  }

  //  AUTH MODAL (Login/Signup Toggle) 
  const authModal = document.getElementById('authModal');
  const openAuthBtn = document.getElementById('loginBtn');
  const closeAuthBtn = authModal ? authModal.querySelector('.close-btn') : null;
  const loginForm = document.getElementById('loginForm');
  const signupForm = document.getElementById('signupForm');
  const loginToggle = document.getElementById('loginToggle');
  const signupToggle = document.getElementById('signupToggle');
  const loginErrorDiv = document.getElementById('loginError');

  if (openAuthBtn && authModal) {
    openAuthBtn.addEventListener('click', (e) => {
      e.preventDefault();
      showModal(authModal);
    });
  }

  if (closeAuthBtn && authModal) {
    closeAuthBtn.addEventListener('click', () => hideModal(authModal));
    closeOnOutsideClick(authModal);
  }

  if (loginToggle && signupToggle && loginForm && signupForm) {
    loginToggle.addEventListener('click', () => {
      loginForm.classList.remove('hidden');
      signupForm.classList.add('hidden');
      loginToggle.classList.add('active');
      signupToggle.classList.remove('active');
    });

    signupToggle.addEventListener('click', () => {
      signupForm.classList.remove('hidden');
      loginForm.classList.add('hidden');
      signupToggle.classList.add('active');
      loginToggle.classList.remove('active');
    });
  }

  if (loginForm && loginErrorDiv) {
    loginForm.addEventListener('submit', (e) => {
      e.preventDefault();
      loginErrorDiv.textContent = '';

      const formData = new FormData(loginForm);

      fetch('login_process.php', {
        method: 'POST',
        body: formData,
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            window.location.href = 'CSHomePage.php';
          } else {
            loginErrorDiv.textContent = data.message || 'Login failed.';
          }
        })
        .catch(() => {
          loginErrorDiv.textContent = 'Error connecting to server.';
        });
    });
  }

  //  SEARCH FUNCTION 
  const searchInput = document.getElementById('productSearch');
  const searchButton = document.querySelector('.search-container button');

  function searchProducts() {
    if (!searchInput) return;
    const query = searchInput.value.toLowerCase();
    const products = document.querySelectorAll('.product-card');

    products.forEach(card => {
      const title = card.querySelector('h2')?.textContent.toLowerCase() || '';
      card.classList.toggle('hidden', !title.includes(query));
    });
  }

  if (searchInput) searchInput.addEventListener('input', searchProducts);
  if (searchButton) searchButton.addEventListener('click', searchProducts);
});
