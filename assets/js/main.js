(function ($) {
  "use strict";

  var windowOn = $(window);

  windowOn.on("load", function () {
    wowAnimation();
  });

  // preloader
  windowOn.on("load", function () {
    $("#loading").fadeOut(500);
  });

  // back-to-top
  var btn = $("#back-to-top");
  windowOn.scroll(function () {
    if (windowOn.scrollTop() > 300) {
      btn.addClass("show");
    } else {
      btn.removeClass("show");
    }
  });
  btn.on("click", function () {
    $("html, body").animate({ scrollTop: 0 }, "300");
  });


  // sticky js
  windowOn.on("scroll", function () {
    var scroll = windowOn.scrollTop();
    if (scroll < 100) {
      $("#tp-header-sticky").removeClass("header-sticky");
    } else {
      $("#tp-header-sticky").addClass("header-sticky");
    }
  });

  // mobile menu
  var tpMenuWrap = $(".tp-mobile-menu-active > ul").clone();
  var tpSideMenu = $(".tp-offcanvas-menu nav");
  tpSideMenu.append(tpMenuWrap);
  if ($(tpSideMenu).find(".sub-menu, .tp-mega-menu").length != 0) {
    $(tpSideMenu)
      .find(".sub-menu, .tp-mega-menu")
      .parent()
      .append(
        '<button class="tp-menu-close"><i class="fas fa-chevron-right"></i></button>'
      );
  }

  var sideMenuList = $(
    ".tp-offcanvas-menu nav > ul > li button.tp-menu-close, .tp-offcanvas-menu nav > ul li.has-dropdown > a"
  );
  $(sideMenuList).on("click", function (e) {
    e.preventDefault();
    if (!$(this).parent().hasClass("active")) {
      $(this).parent().addClass("active");
      $(this).siblings(".sub-menu, .tp-mega-menu").slideDown();
    } else {
      $(this).siblings(".sub-menu, .tp-mega-menu").slideUp();
      $(this).parent().removeClass("active");
    }
  });

  // offcanvas bar
  $(".tp-offcanvas-toogle").on("click", function () {
    $(".tp-offcanvas").addClass("tp-offcanvas-open");
    $(".tp-offcanvas-overlay").addClass("tp-offcanvas-overlay-open");
  });
  $(".tp-offcanvas-close-toggle,.tp-offcanvas-overlay").on(
    "click",
    function () {
      $(".tp-offcanvas").removeClass("tp-offcanvas-open");
      $(".tp-offcanvas-overlay").removeClass("tp-offcanvas-overlay-open");
    }
  );

  // Search bar
  $(".tp-search-toggle").on("click", function () {
    $(".tp-header-search-bar").addClass("tp-search-open");
    $(".tp-offcanvas-overlay").addClass("tp-offcanvas-overlay-open");
  });

  $(".tp-search-close,.tp-offcanvas-overlay").on("click", function () {
    $(".tp-header-search-bar").removeClass("tp-search-open");
    $(".tp-offcanvas-overlay").removeClass("tp-offcanvas-overlay-open");
  });

  // data bg img
  $("[data-background]").each(function () {
    $(this).css(
      "background-image",
      "url(" + $(this).attr("data-background") + ")"
    );
  });

  // $('#productCategory').niceSelect();


  // data bg color
  $("[data-bg-color]").each(function () {
    $(this).css("background-color", $(this).attr("data-bg-color"));
  });

  // data bg color
  $("[data-color]").each(function () {
    $(this).css("color", $(this).attr("data-color"));
  });

  $(".popup-image").magnificPopup({
    type: "image",
    // other options
  });
  $(".popup-video").magnificPopup({
    type: "iframe",
    // other options
  });


  // Close overlay when clicking outside
  // document.addEventListener("click", (e) => {
  //   document.querySelectorAll(".product-overlay.active").forEach((overlay) => {
  //     if (
  //       !overlay.contains(e.target) &&
  //       !overlay
  //         .closest(".product-card")
  //         .querySelector(".select-options-btn")
  //         .contains(e.target)
  //     ) {
  //       overlay.classList.remove("active");
  //     }
  //   });
  // });

  // Prevent clicks inside overlay from closing it
  // document.querySelectorAll(".product-overlay").forEach((overlay) => {
  //   overlay.addEventListener("click", (e) => {
  //     e.stopPropagation();
  //   });
  // });


  if ($(".grid").length != 0) {
    var $grid = $(".grid").imagesLoaded(function () {
      $(".grid").isotope({
        itemSelector: ".grid-item",
        percentPosition: true,
        masonry: {
          columnWidth: 1,
        },
      });

      // filter items on button click
      $(".tp-portfolio-filter").on("click", "button", function () {
        var filterValue = $(this).attr("data-filter");
        $grid.isotope({ filter: filterValue });
      });
      //for menu active class
      $(".tp-portfolio-filter button").on("click", function (event) {
        $(this).siblings(".active").removeClass("active");
        $(this).addClass("active");
        event.preventDefault();
      });
    });
  }
  var swiper = new Swiper(".tp-banner-active", {
    slidesPerView: 1,
    spaceBetween: 0,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    keyboard: {
      enabled: true,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    effect: "fade", // optional: fade effect for smooth transition
    fadeEffect: {
      crossFade: true,
    },
  });

  var swiper = new Swiper(".mySwiper", {
    loop: true,
    spaceBetween: 10,
    slidesPerView: 4,
    freeMode: true,
    watchSlidesProgress: true,
  });
  var swiper2 = new Swiper(".mySwiper2", {
    loop: true,
    spaceBetween: 10,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    thumbs: {
      swiper: swiper,
    },
  });

  var relatedSwiper = new Swiper(".relatedSwiper", {
    slidesPerView: 3,
    spaceBetween: 20,
    loop: true,
    speed: 800, // smooth transition speed (ms)
  
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      0: { slidesPerView: 1 },
      576: { slidesPerView: 2 },
      768: { slidesPerView: 3 },
      1200: { slidesPerView: 4 },
    },
  });

  // home banner slider
  // home banner slider
  var bannerSliderEl = document.querySelector(".banner-area-slide-active");

  if (bannerSliderEl) {
    var bannerSlider = new Swiper(".banner-area-slide-active", {
      slidesPerView: 1,
      speed: 1500, // smooth transition (default is 300)
      loop: true, // optional: makes it continuous
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      keyboard: {
        enabled: true,
      },
      pagination: {
        el: ".swiper-pagination",
        type: "progressbar",
      },
      navigation: {
        nextEl: ".banner-slide-button-next",
        prevEl: ".banner-slide-button-prev",
      },
      effect: "slide", // you can try 'fade' or 'cube' too
    });
  }

  // tp-test-active slider
  var swiper = new Swiper(".tp-test-active", {
    slidesPerView: 4,
    spaceBetween: 30,
    keyboard: {
      enabled: true,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".tp-review-button-next",
      prevEl: ".tp-review-button-prev",
    },
    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 2,
      },
      992: {
        slidesPerView: 3,
      },
      1200: {
        slidesPerView: 4,
      },
    },
  });

  // tp-testimonial-content-active
  var slider = new Swiper(".tp-testimonial-content-active", {
    slidesPerView: 1,
    centeredSlides: true,
    loop: true,
    loopedSlides: 3,
    navigation: {
      nextEl: ".tp-room-details-slide-next",
      prevEl: ".tp-room-details-slide-prev",
    },
  });
  var thumbs = new Swiper(".tp-testimonial-thumb-active", {
    slidesPerView: 3,
    spaceBetween: 10,
    centeredSlides: false,
    centeredSlides: true,
    loop: true,
    slideToClickedSlide: true,
  });

  slider.controller.control = thumbs;
  thumbs.controller.control = slider;

  // brand slider
  var swiper = new Swiper(".tp-brand-top-active", {
    slidesPerView: "auto",
    spaceBetween: 80,
    freemode: true,
    centeredSlides: true,
    loop: true,
    speed: 4000,
    allowTouchMove: false,
    autoplay: {
      delay: 1,
      disableOnInteraction: true,
    },
  });

  // brand slider
  var swiper = new Swiper(".tp-brand-bottom-active", {
    slidesPerView: "auto",
    spaceBetween: 80,
    freemode: true,
    centeredSlides: true,
    loop: true,
    speed: 4000,
    allowTouchMove: false,
    autoplay: {
      delay: 1,
      disableOnInteraction: true,
    },
  });

  // brand title slider
  var swiper = new Swiper(".tp-brand-title-active", {
    slidesPerView: "auto",
    spaceBetween: 40,
    freemode: true,
    centeredSlides: true,
    loop: true,
    speed: 4000,
    allowTouchMove: false,
    autoplay: {
      delay: 1,
      disableOnInteraction: true,
    },
  });

  // brand normal slider
  var swiper = new Swiper(".tp-brand-nromal-active", {
    slidesPerView: 5,
    spaceBetween: 30,
    keyboard: {
      enabled: true,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".tp-swiper-team-button-next",
      prevEl: ".tp-swiper-team-button-prev",
    },
    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 2,
      },
      992: {
        slidesPerView: 3,
      },
      1200: {
        slidesPerView: 5,
      },
    },
  });

  // team slider
  var swiper = new Swiper(".tp-team-active", {
    slidesPerView: 4,
    spaceBetween: 30,
    keyboard: {
      enabled: true,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".tp-swiper-team-button-next",
      prevEl: ".tp-swiper-team-button-prev",
    },
    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 2,
      },
      992: {
        slidesPerView: 3,
      },
      1200: {
        slidesPerView: 4,
      },
    },
  });

  // project slider
  var swiper = new Swiper(".tp-project-active", {
    slidesPerView: 4,
    spaceBetween: 30,
    keyboard: {
      enabled: true,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".tp-swiper-team-button-next",
      prevEl: ".tp-swiper-team-button-prev",
    },
    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 2,
      },
      992: {
        slidesPerView: 3,
      },
      1200: {
        slidesPerView: 4,
      },
    },
  });

  // wow
  function wowAnimation() {
    var wow = new WOW({
      boxClass: "wow",
      animateClass: "animated",
      offset: 0,
      mobile: false,
      live: true,
    });
    wow.init();
  }

  // jarallax
  if ($(".jarallax").length) {
    $(".jarallax").jarallax({
      speed: 0.2,
    });
  }
})(jQuery);
