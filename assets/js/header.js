// Enhanced jQuery for Responsive Header Navigation
$(document).ready(function() {
  
  // Mobile menu toggle
  function toggleMobileMenu() {
    const $mainNav = $('#mainNav');
    const $mobileToggle = $('.mobile-menu-toggle');
    
    $mainNav.toggleClass('active');
    $mobileToggle.toggleClass('active');
    
    // Prevent body scroll when menu is open
    if ($mainNav.hasClass('active')) {
      $('body').addClass('menu-open');
    } else {
      $('body').removeClass('menu-open');
      // Close all open dropdowns when closing menu
      $('.has-dropdown').removeClass('active');
    }
  }

  // Mobile menu toggle click
  $('.mobile-menu-toggle').on('click', function(e) {
    e.preventDefault();
    toggleMobileMenu();
  });

  // Handle dropdown clicks on mobile
  $(document).on('click', '.has-dropdown > a', function(e) {
    // Only prevent default on mobile
    if ($(window).width() <= 768) {
      e.preventDefault();
      const $parent = $(this).parent();
      const $dropdown = $(this).siblings('.dropdown');
      
      // Close other open dropdowns
      $('.has-dropdown').not($parent).removeClass('active').find('.dropdown').slideUp(300);
      
      // Toggle current dropdown
      $parent.toggleClass('active');
      if ($parent.hasClass('active')) {
        $dropdown.slideDown(300);
      } else {
        $dropdown.slideUp(300);
      }
    }
  });

  // Handle nested dropdown clicks on mobile
  $(document).on('click', '.dropdown .has-dropdown > a', function(e) {
    if ($(window).width() <= 768) {
      e.preventDefault();
      const $parent = $(this).parent();
      const $nestedDropdown = $(this).siblings('.dropdown');
      
      $parent.toggleClass('active');
      if ($parent.hasClass('active')) {
        $nestedDropdown.slideDown(300);
      } else {
        $nestedDropdown.slideUp(300);
      }
    }
  });

  // Close mobile menu when clicking outside
  $(document).on('click', function(e) {
    if (!$(e.target).closest('.main-nav, .mobile-menu-toggle').length) {
      if ($('#mainNav').hasClass('active')) {
        toggleMobileMenu();
      }
    }
  });

  // Handle window resize
  $(window).on('resize', function() {
    const windowWidth = $(window).width();
    
    // If switching to desktop view, close mobile menu
    if (windowWidth > 768) {
      $('#mainNav').removeClass('active');
      $('.mobile-menu-toggle').removeClass('active');
      $('.has-dropdown').removeClass('active');
      $('body').removeClass('menu-open');
      $('.dropdown').removeAttr('style'); // Remove inline styles
    }
  });

  // Smooth scroll for anchor links (if any)
  $('a[href^="#"]').on('click', function(e) {
    const target = $(this.hash);
    if (target.length) {
      e.preventDefault();
      $('html, body').animate({
        scrollTop: target.offset().top - 80
      }, 600);
      
      // Close mobile menu after clicking link
      if ($('#mainNav').hasClass('active')) {
        toggleMobileMenu();
      }
    }
  });

  // Add active class to current page nav item
  function setActiveNavItem() {
    const currentPath = window.location.pathname;
    const currentPage = currentPath.split('/').pop() || 'index.html';
    
    $('.nav-list a').removeClass('active');
    $(`.nav-list a[href*="${currentPage}"]`).addClass('active');
  }

  // Set active nav item on page load
  setActiveNavItem();


  // Add smooth transitions for better UX
  $('.main-nav .nav-list a').on('mouseenter', function() {
    if ($(window).width() > 768) {
      $(this).stop().animate({ paddingTop: '14px', paddingBottom: '18px' }, 200);
    }
  }).on('mouseleave', function() {
    if ($(window).width() > 768) {
      $(this).stop().animate({ paddingTop: '16px', paddingBottom: '16px' }, 200);
    }
  });

  // Prevent dropdown from closing when clicking inside
  $('.dropdown').on('click', function(e) {
    if ($(window).width() > 768) {
      e.stopPropagation();
    }
  });

  // Update cart count (example function)
  window.updateCartCount = function(count) {
    $('.cart-count').text(count);
    if (count > 0) {
      $('.cart-count').show();
    } else {
      $('.cart-count').hide();
    }
  };

  // Sticky header enhancement
  let lastScrollTop = 0;
  $(window).on('scroll', function() {
    const currentScroll = $(this).scrollTop();
    const $header = $('.site-header');
    
    // Add/remove sticky class
    if (currentScroll > 100) {
      $header.addClass('header-sticky');
    } else {
      $header.removeClass('header-sticky');
    }
    
    // Hide/show header on scroll (optional)
    if (currentScroll > lastScrollTop && currentScroll > 200) {
      // Scrolling down
      $header.addClass('header-hidden');
    } else {
      // Scrolling up
      $header.removeClass('header-hidden');
    }
    lastScrollTop = currentScroll;
  });

  // Accessibility improvements
  $('.mobile-menu-toggle').on('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      toggleMobileMenu();
    }
  });



  // Make the toggleMobileMenu function globally available
  window.toggleMobileMenu = toggleMobileMenu;
});
