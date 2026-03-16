// Efecto de header al hacer scroll
window.addEventListener('scroll', function() {
    const header = document.getElementById('header');
    if (window.scrollY > 100) {
        header.classList.add('header-scroll');
    } else {
        header.classList.remove('header-scroll');
    }
});

// Smooth scroll para los enlaces internos
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        if(targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if(targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });
        }
    });
});

// Animación para elementos al hacer scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Aplicar animación a elementos
document.querySelectorAll('.servicio-card, .elegirnos-item, .estadistica-item').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    observer.observe(el);
});

// Validación básica del formulario
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const nombre = form.querySelector('input[name="nombre"]');
            const email = form.querySelector('input[name="email"]');
            const empresa = form.querySelector('input[name="empresa"]');
            const mensaje = form.querySelector('textarea[name="mensaje"]');
            
            let valid = true;
            
            // Validación básica
            if (!nombre.value.trim()) {
                valid = false;
                nombre.style.border = '2px solid #e74c3c';
            } else {
                nombre.style.border = '';
            }
            
            if (!email.value.trim() || !isValidEmail(email.value)) {
                valid = false;
                email.style.border = '2px solid #e74c3c';
            } else {
                email.style.border = '';
            }
            
            if (!empresa.value.trim()) {
                valid = false;
                empresa.style.border = '2px solid #e74c3c';
            } else {
                empresa.style.border = '';
            }
            
            if (!mensaje.value.trim()) {
                valid = false;
                mensaje.style.border = '2px solid #e74c3c';
            } else {
                mensaje.style.border = '';
            }
            
            if (!valid) {
                e.preventDefault();
                alert('Por favor, complete todos los campos correctamente.');
            }
        });
    }
});

// Función para validar email
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Efecto de contador para las estadísticas
function animateCounter(element, target, duration) {
    let start = 0;
    const increment = target / (duration / 16);
    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            element.textContent = target + '+';
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(start) + '+';
        }
    }, 16);
}

// Iniciar contadores cuando sean visibles
const counterObserver = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const target = parseInt(entry.target.getAttribute('data-target'));
            animateCounter(entry.target, target, 2000);
            counterObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.5 });

// Observar elementos de estadísticas para animación
document.querySelectorAll('.estadistica-numero').forEach(counter => {
    const currentText = counter.textContent;
    const targetNumber = parseInt(currentText.replace('+', ''));
    counter.setAttribute('data-target', targetNumber);
    counter.textContent = '0+';
    counterObserver.observe(counter);
});
