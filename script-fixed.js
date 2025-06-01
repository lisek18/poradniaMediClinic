
document.addEventListener("DOMContentLoaded", function () {
  // FAQ Accordion
  document.querySelectorAll('.accordion-header').forEach(header => {
    header.addEventListener('click', () => {
      const item = header.parentElement;
      item.classList.toggle('active');
    });
  });

  // Opinie - Karuzela z kropkami
  const testimonials = document.querySelectorAll('.testimonial');
  const dots = document.querySelectorAll('.dot');
  let currentTestimonial = 0;

  function showTestimonial(index) {
    testimonials.forEach((el, i) => {
      el.classList.toggle('active', i === index);
      dots[i].classList.toggle('active', i === index);
    });
  }

  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      currentTestimonial = index;
      showTestimonial(index);
    });
  });

  setInterval(() => {
    currentTestimonial = (currentTestimonial + 1) % testimonials.length;
    showTestimonial(currentTestimonial);
  }, 5000);

  showTestimonial(currentTestimonial);

  // Scroll to Top Button
  const scrollBtn = document.getElementById("scrollToTop");
  if (scrollBtn) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 300) {
        scrollBtn.style.display = "block";
      } else {
        scrollBtn.style.display = "none";
      }
    });

    scrollBtn.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }
});
