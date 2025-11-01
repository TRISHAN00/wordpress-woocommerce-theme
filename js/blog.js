/**
 * Blog Interactive Features
 * Save as: js/blog.js
 */

document.addEventListener('DOMContentLoaded', function() {
  
  // =====================================
  // Table of Contents Auto-Generation
  // =====================================
  function generateTableOfContents() {
    const content = document.querySelector('.post-content');
    const tocContainer = document.getElementById('toc-list');
    
    if (!content || !tocContainer) return;
    
    const headings = content.querySelectorAll('h2, h3');
    
    if (headings.length === 0) {
      const widget = tocContainer.closest('.sidebar-widget');
      if (widget) widget.style.display = 'none';
      return;
    }
    
    let tocHTML = '';
    
    headings.forEach((heading, index) => {
      const level = heading.tagName.toLowerCase();
      const text = heading.textContent;
      const id = heading.id || 'heading-' + index;
      
      if (!heading.id) {
        heading.id = id;
      }
      
      tocHTML += `<a href="#${id}" class="toc-${level}" data-target="${id}">${text}</a>`;
    });
    
    tocContainer.innerHTML = tocHTML;
    
    // Active state on scroll
    const tocLinks = tocContainer.querySelectorAll('a');
    
    window.addEventListener('scroll', function() {
      let current = '';
      
      headings.forEach(heading => {
        const sectionTop = heading.offsetTop;
        const scrollPos = window.scrollY + 150;
        
        if (scrollPos >= sectionTop) {
          current = heading.id;
        }
      });
      
      tocLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('data-target') === current) {
          link.classList.add('active');
        }
      });
    });
    
    // Smooth scroll to section
    tocLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('data-target');
        const target = document.getElementById(targetId);
        
        if (target) {
          const offset = 100;
          const targetPosition = target.offsetTop - offset;
          
          window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
          });
        }
      });
    });
  }
  
  generateTableOfContents();
  
  
  // =====================================
  // Copy Code Block Feature
  // =====================================
  function addCopyButtonToCodeBlocks() {
    const codeBlocks = document.querySelectorAll('.post-content pre');
    
    codeBlocks.forEach(block => {
      const wrapper = document.createElement('div');
      wrapper.classList.add('code-block-wrapper');
      
      block.parentNode.insertBefore(wrapper, block);
      wrapper.appendChild(block);
      
      const copyBtn = document.createElement('button');
      copyBtn.classList.add('copy-code-btn');
      copyBtn.innerHTML = '<i class="far fa-copy"></i> Copy';
      
      wrapper.appendChild(copyBtn);
      
      copyBtn.addEventListener('click', function() {
        const code = block.querySelector('code') || block;
        const text = code.textContent;
        
        navigator.clipboard.writeText(text).then(() => {
          copyBtn.innerHTML = '<i class="far fa-check"></i> Copied!';
          copyBtn.classList.add('copied');
          
          setTimeout(() => {
            copyBtn.innerHTML = '<i class="far fa-copy"></i> Copy';
            copyBtn.classList.remove('copied');
          }, 2000);
        });
      });
    });
  }
  
  addCopyButtonToCodeBlocks();
  
  
  // =====================================
  // Image Lightbox
  // =====================================
  function initImageLightbox() {
    const contentImages = document.querySelectorAll('.post-content img');
    
    if (contentImages.length === 0) return;
    
    // Create lightbox
    const lightbox = document.createElement('div');
    lightbox.classList.add('image-lightbox');
    lightbox.innerHTML = `
      <div class="lightbox-overlay"></div>
      <div class="lightbox-content">
        <button class="lightbox-close"><i class="far fa-times"></i></button>
        <button class="lightbox-prev"><i class="far fa-chevron-left"></i></button>
        <button class="lightbox-next"><i class="far fa-chevron-right"></i></button>
        <img src="" alt="">
      </div>
    `;
    document.body.appendChild(lightbox);
    
    const lightboxImg = lightbox.querySelector('img');
    const closeBtn = lightbox.querySelector('.lightbox-close');
    const prevBtn = lightbox.querySelector('.lightbox-prev');
    const nextBtn = lightbox.querySelector('.lightbox-next');
    const overlay = lightbox.querySelector('.lightbox-overlay');
    
    let currentIndex = 0;
    const images = Array.from(contentImages);
    
    contentImages.forEach((img, index) => {
      img.style.cursor = 'pointer';
      img.addEventListener('click', function() {
        currentIndex = index;
        showLightbox(img.src);
      });
    });
    
    function showLightbox(src) {
      lightboxImg.src = src;
      lightbox.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
    
    function closeLightbox() {
      lightbox.classList.remove('active');
      document.body.style.overflow = '';
    }
    
    closeBtn.addEventListener('click', closeLightbox);
    overlay.addEventListener('click', closeLightbox);
    
    prevBtn.addEventListener('click', function() {
      currentIndex = (currentIndex - 1 + images.length) % images.length;
      lightboxImg.src = images[currentIndex].src;
    });
    
    nextBtn.addEventListener('click', function() {
      currentIndex = (currentIndex + 1) % images.length;
      lightboxImg.src = images[currentIndex].src;
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
      if (!lightbox.classList.contains('active')) return;
      
      if (e.key === 'Escape') closeLightbox();
      if (e.key === 'ArrowLeft') prevBtn.click();
      if (e.key === 'ArrowRight') nextBtn.click();
    });
  }
  
  initImageLightbox();
  
  
  // =====================================
  // Sticky Sidebar
  // =====================================
  function initStickySidebar() {
    const sidebar = document.querySelector('.post-sidebar');
    const stickyWidget = document.querySelector('.sticky-widget');
    
    if (!sidebar || !stickyWidget) return;
    
    const sidebarTop = sidebar.offsetTop;
    const sidebarHeight = sidebar.offsetHeight;
    const widgetHeight = stickyWidget.offsetHeight;
    
    window.addEventListener('scroll', function() {
      const scrollTop = window.scrollY;
      const windowHeight = window.innerHeight;
      
      if (scrollTop > sidebarTop - 100 && (scrollTop + widgetHeight + 100) < (sidebarTop + sidebarHeight)) {
        stickyWidget.style.position = 'fixed';
        stickyWidget.style.top = '100px';
        stickyWidget.style.width = sidebar.offsetWidth + 'px';
      } else if ((scrollTop + widgetHeight + 100) >= (sidebarTop + sidebarHeight)) {
        stickyWidget.style.position = 'absolute';
        stickyWidget.style.top = (sidebarHeight - widgetHeight) + 'px';
        stickyWidget.style.width = sidebar.offsetWidth + 'px';
      } else {
        stickyWidget.style.position = 'static';
        stickyWidget.style.width = 'auto';
      }
    });
  }
  
  initStickySidebar();
  
  
  // =====================================
  // Share Button Analytics
  // =====================================
  function trackShareClicks() {
    const shareButtons = document.querySelectorAll('.share-btn');
    
    shareButtons.forEach(btn => {
      btn.addEventListener('click', function() {
        const platform = this.classList.contains('facebook') ? 'Facebook' :
                        this.classList.contains('twitter') ? 'Twitter' :
                        this.classList.contains('linkedin') ? 'LinkedIn' :
                        this.classList.contains('whatsapp') ? 'WhatsApp' : 'Unknown';
        
        console.log('Shared on:', platform);
        
        // If using Google Analytics
        if (typeof gtag !== 'undefined') {
          gtag('event', 'share', {
            'event_category': 'Social',
            'event_label': platform,
            'value': document.title
          });
        }
      });
    });
  }
  
  trackShareClicks();
  
  
  // =====================================
  // Lazy Load Images
  // =====================================
  function lazyLoadImages() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          img.removeAttribute('data-src');
          observer.unobserve(img);
        }
      });
    });
    
    images.forEach(img => imageObserver.observe(img));
  }
  
  if ('IntersectionObserver' in window) {
    lazyLoadImages();
  }
  
  
  // =====================================
  // Scroll to Top in Long Posts
  // =====================================
  function addScrollToTop() {
    const content = document.querySelector('.post-content');
    
    if (!content || content.offsetHeight < 2000) return;
    
    const scrollTopBtn = document.createElement('button');
    scrollTopBtn.classList.add('scroll-to-top-post');
    scrollTopBtn.innerHTML = '<i class="far fa-arrow-up"></i>';
    document.body.appendChild(scrollTopBtn);
    
    window.addEventListener('scroll', function() {
      if (window.scrollY > 500) {
        scrollTopBtn.classList.add('visible');
      } else {
        scrollTopBtn.classList.remove('visible');
      }
    });
    
    scrollTopBtn.addEventListener('click', function() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }
  
  addScrollToTop();
  
  
  // =====================================
  // Estimated Time Remaining
  // =====================================
  function showReadingTimeRemaining() {
    const content = document.querySelector('.post-content');
    const progressBar = document.querySelector('.reading-progress-fill');
    
    if (!content || !progressBar) return;
    
    const wordsPerMinute = 200;
    const totalWords = content.textContent.trim().split(/\s+/).length;
    const totalMinutes = Math.ceil(totalWords / wordsPerMinute);
    
    const timeIndicator = document.createElement('div');
    timeIndicator.classList.add('time-remaining');
    timeIndicator.style.cssText = 'position: fixed; top: 60px; right: 20px; background: #fff; padding: 10px 20px; border-radius: 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); font-size: 14px; font-weight: 600; z-index: 999; opacity: 0; transition: opacity 0.3s;';
    document.body.appendChild(timeIndicator);
    
    window.addEventListener('scroll', function() {
      const scrolled = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
      const remainingPercent = 100 - scrolled;
      const remainingMinutes = Math.ceil((remainingPercent / 100) * totalMinutes);
      
      if (scrolled > 10 && scrolled < 95) {
        timeIndicator.style.opacity = '1';
        timeIndicator.innerHTML = `<i class="far fa-clock"></i> ${remainingMinutes} min left`;
      } else {
        timeIndicator.style.opacity = '0';
      }
    });
  }
  
  showReadingTimeRemaining();
  
  
  // =====================================
  // Newsletter Form Submission
  // =====================================
  const newsletterForm = document.querySelector('.newsletter-form');
  
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const email = this.querySelector('input[type="email"]').value;
      const submitBtn = this.querySelector('.btn-subscribe');
      const originalText = submitBtn.textContent;
      
      submitBtn.textContent = 'Subscribing...';
      submitBtn.disabled = true;
      
      // Simulate API call (replace with actual subscription logic)
      setTimeout(() => {
        submitBtn.textContent = '✓ Subscribed!';
        submitBtn.style.background = '#28a745';
        
        setTimeout(() => {
          submitBtn.textContent = originalText;
          submitBtn.style.background = '';
          submitBtn.disabled = false;
          newsletterForm.reset();
        }, 2000);
      }, 1500);
      
      console.log('Newsletter subscription:', email);
    });
  }
  
  
  // =====================================
  // Print Article Feature
  // =====================================
  function addPrintButton() {
    const shareSection = document.querySelector('.post-share .share-buttons');
    
    if (!shareSection) return;
    
    const printBtn = document.createElement('a');
    printBtn.href = '#';
    printBtn.classList.add('share-btn', 'print');
    printBtn.style.background = '#666';
    printBtn.innerHTML = '<i class="far fa-print"></i> Print';
    
    shareSection.appendChild(printBtn);
    
    printBtn.addEventListener('click', function(e) {
      e.preventDefault();
      window.print();
    });
  }
  
  addPrintButton();
  
});

// =====================================
// Additional CSS for JavaScript Features
// =====================================
const additionalStyles = `
<style>
.code-block-wrapper {
  position: relative;
  margin: 30px 0;
}

.copy-code-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  background: rgba(255, 111, 60, 0.9);
  color: #fff;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 12px;
  cursor: pointer;
  z-index: 10;
  transition: all 0.3s ease;
}

.copy-code-btn:hover {
  background: #ff4500;
}

.copy-code-btn.copied {
  background: #28a745;
}

.image-lightbox {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9999;
  display: none;
}

.image-lightbox.active {
  display: block;
}

.lightbox-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.9);
}

.lightbox-content {
  position: relative;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 60px;
}

.lightbox-content img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
  box-shadow: 0 10px 50px rgba(0, 0, 0, 0.5);
}

.lightbox-close {
  position: absolute;
  top: 20px;
  right: 20px;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  color: #fff;
  border: 2px solid rgba(255, 255, 255, 0.3);
  width: 50px;
  height: 50px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 20px;
  transition: all 0.3s ease;
}

.lightbox-close:hover,
.lightbox-prev:hover,
.lightbox-next:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: scale(1.1);
}

.lightbox-prev,
.lightbox-next {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  color: #fff;
  border: 2px solid rgba(255, 255, 255, 0.3);
  width: 50px;
  height: 50px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 20px;
  transition: all 0.3s ease;
}

.lightbox-prev {
  left: 20px;
}

.lightbox-next {
  right: 20px;
}

.scroll-to-top-post {
  position: fixed;
  bottom: 30px;
  right: 30px;
  width: 50px;
  height: 50px;
  background: linear-gradient(135deg, #ff6f3c, #ff4500);
  color: #fff;
  border: none;
  border-radius: 50%;
  font-size: 18px;
  cursor: pointer;
  box-shadow: 0 4px 20px rgba(255, 111, 60, 0.4);
  opacity: 0;
  visibility: hidden;
  transform: translateY(20px);
  transition: all 0.3s ease;
  z-index: 998;
}

.scroll-to-top-post.visible {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.scroll-to-top-post:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 25px rgba(255, 111, 60, 0.5);
}

@media (max-width: 767px) {
  .lightbox-content {
    padding: 20px;
  }
  
  .lightbox-prev,
  .lightbox-next {
    width: 40px;
    height: 40px;
    font-size: 16px;
  }
  
  .lightbox-prev {
    left: 10px;
  }
  
  .lightbox-next {
    right: 10px;
  }
  
  .scroll-to-top-post {
    width: 45px;
    height: 45px;
    bottom: 20px;
    right: 20px;
  }
  
  .time-remaining {
    top: 70px !important;
    right: 10px !important;
    padding: 8px 15px !important;
    font-size: 12px !important;
  }
}
</style>
`;

// Inject additional styles
document.head.insertAdjacentHTML('beforeend', additionalStyles);