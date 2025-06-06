"use strict";

// ChartJS
if(window.Chart) {
  Chart.defaults.global.defaultFontFamily = "'Nunito', 'Segoe UI', 'Arial'";
  Chart.defaults.global.defaultFontSize = 12;
  Chart.defaults.global.defaultFontStyle = 500;
  Chart.defaults.global.defaultFontColor = "#999";
  Chart.defaults.global.tooltips.backgroundColor = "#000";
  Chart.defaults.global.tooltips.bodyFontColor = "rgba(255,255,255,.7)";
  Chart.defaults.global.tooltips.titleMarginBottom = 10;
  Chart.defaults.global.tooltips.titleFontSize = 14;
  Chart.defaults.global.tooltips.titleFontFamily = "'Nunito', 'Segoe UI', 'Arial'";
  Chart.defaults.global.tooltips.titleFontColor = '#fff';
  Chart.defaults.global.tooltips.xPadding = 15;
  Chart.defaults.global.tooltips.yPadding = 15;
  Chart.defaults.global.tooltips.displayColors = false;
  Chart.defaults.global.tooltips.intersect = false;
  Chart.defaults.global.tooltips.mode = 'nearest';
}

// DropzoneJS
if(window.Dropzone) {
  Dropzone.autoDiscover = false;
}

// Basic confirm box
$('[data-confirm]').each(function() {
  var me = $(this),
      me_data = me.data('confirm');

  me_data = me_data.split("|");
  me.fireModal({
    title: me_data[0],
    body: me_data[1],
    buttons: [
      {
        text: me.data('confirm-text-yes') || 'Yes',
        class: 'btn btn-danger btn-shadow',
        handler: function() {
          eval(me.data('confirm-yes'));
        }
      },
      {
        text: me.data('confirm-text-cancel') || 'Cancel',
        class: 'btn btn-secondary',
        handler: function(modal) {
          $.destroyModal(modal);
          eval(me.data('confirm-no'));
        }
      }
    ]
  })
});

// Global
$(function() {
  let sidebar_nicescroll_opts = {
    cursoropacitymin: 0,
    cursoropacitymax: .8,
    zindex: 892
  }, now_layout_class = null;

  var sidebar_sticky = function() {
    if($("body").hasClass('layout-2')) {
      $("body.layout-2 #sidebar-wrapper").stick_in_parent({
        parent: $('body')
      });
      $("body.layout-2 #sidebar-wrapper").stick_in_parent({recalc_every: 1});
    }
  }
  sidebar_sticky();

  var sidebar_nicescroll;
  var update_sidebar_nicescroll = function() {
    let a = setInterval(function() {
      if(sidebar_nicescroll != null)
        sidebar_nicescroll.resize();
    }, 10);

    setTimeout(function() {
      clearInterval(a);
    }, 600);
  }

  var sidebar_dropdown = function() {
    if($(".main-sidebar").length) {
      $(".main-sidebar").niceScroll(sidebar_nicescroll_opts);
      sidebar_nicescroll = $(".main-sidebar").getNiceScroll();

      $(".main-sidebar .sidebar-menu li a").on("click", function(e) {
        const me = $(this);
        const isDropdown = me.hasClass("has-dropdown") || me.attr("data-toggle") === "dropdown";
        const active = me.parent().hasClass("active");
        const w = $(window);

        // Mobile behavior
        if (w.outerWidth() <= 1024) {
          if (isDropdown) {
            // Toggle dropdown only
            setTimeout(function () {
              $('.main-sidebar .sidebar-menu li.active > .dropdown-menu').slideUp(500);
              $('.main-sidebar .sidebar-menu li.active').removeClass('active');

              if (active) {
                me.parent().removeClass('active');
                me.parent().find('> .dropdown-menu').slideUp(500);
              } else {
                me.parent().addClass('active');
                me.parent().find('> .dropdown-menu').slideDown(500);
              }

              update_sidebar_nicescroll();
            }, 300);

            e.preventDefault(); // Prevent navigation for dropdown
          } else {
            // For normal link on mobile, delay sidebar closing slightly
            setTimeout(function () {
              $("body").removeClass("sidebar-show").addClass("sidebar-gone");
            }, 100);
          }
        } else {
          // Desktop dropdown behavior
          if (isDropdown) {
            $('.main-sidebar .sidebar-menu li.active > .dropdown-menu').slideUp(500);
            $('.main-sidebar .sidebar-menu li.active').removeClass('active');

            if (active) {
              me.parent().removeClass('active');
              me.parent().find('> .dropdown-menu').slideUp(500);
            } else {
              me.parent().addClass('active');
              me.parent().find('> .dropdown-menu').slideDown(500);
            }

            update_sidebar_nicescroll();
            e.preventDefault(); // Prevent navigation for dropdown
          }
        }
      });

      // Initialize dropdown state
      $('.main-sidebar .sidebar-menu li.active > .dropdown-menu').slideDown(500, function() {
        update_sidebar_nicescroll();
        return false;
      });
    }
  }

  // Redundant event listener removed since we're already handling this in sidebar_dropdown function
  // Instead, add direct event handler to ensure sidebar closes on mobile
  $(document).on('click', '.main-sidebar .sidebar-menu li a', function() {
    if($(window).outerWidth() <= 1024) {
      // Force sidebar to close on mobile
      setTimeout(function() {
        $("body").removeClass("sidebar-show").addClass("sidebar-gone");
      }, 100);
    }
  });

  sidebar_dropdown();

  if($("#top-5-scroll").length) {
    $("#top-5-scroll").css({
      height: 315
    }).niceScroll();
  }

  $(".main-content").css({
    minHeight: $(window).outerHeight() - 108
  })

  $(".nav-collapse-toggle").click(function() {
    $(this).parent().find('.navbar-nav').toggleClass('show');
    return false;
  });

  // Force sidebar to close on any click on mobile screens
  $("body").on('click', '.main-content', function(e) {
    if($(window).outerWidth() <= 1024) {
      $("body").removeClass("sidebar-show").addClass("sidebar-gone");
    }
  });

  // Ensure links in sidebar actually navigate and close sidebar
  $(".main-sidebar .sidebar-menu li a").each(function() {
    var link = $(this);
    // Only apply to non-dropdown links with href
    if(!link.hasClass("has-dropdown") && link.attr("href") && link.attr("href") !== '#') {
      link.on('click', function(e) {
        if($(window).outerWidth() <= 1024) {
          // Don't prevent default for actual links so navigation works
          $("body").removeClass("sidebar-show").addClass("sidebar-gone");
        }
      });
    }
  });

  var toggle_sidebar_mini = function(mini) {
    let body = $('body');

    if(!mini) {
      body.removeClass('sidebar-mini');
      $(".main-sidebar").css({
        overflow: 'hidden'
      });
      setTimeout(function() {
        $(".main-sidebar").niceScroll(sidebar_nicescroll_opts);
        sidebar_nicescroll = $(".main-sidebar").getNiceScroll();
      }, 500);
      $(".main-sidebar .sidebar-menu > li > ul .dropdown-title").remove();
      $(".main-sidebar .sidebar-menu > li > a").removeAttr('data-toggle');
      $(".main-sidebar .sidebar-menu > li > a").removeAttr('data-original-title');
      $(".main-sidebar .sidebar-menu > li > a").removeAttr('title');
    }else{
      body.addClass('sidebar-mini');
      body.removeClass('sidebar-show');
      sidebar_nicescroll.remove();
      sidebar_nicescroll = null;
      $(".main-sidebar .sidebar-menu > li").each(function() {
        let me = $(this);

        if(me.find('> .dropdown-menu').length) {
          me.find('> .dropdown-menu').hide();
          me.find('> .dropdown-menu').prepend('<li class="dropdown-title pt-3">'+ me.find('> a').text() +'</li>');
        }else{
          me.find('> a').attr('data-toggle', 'tooltip');
          me.find('> a').attr('data-original-title', me.find('> a').text());
          $("[data-toggle='tooltip']").tooltip({
            placement: 'right'
          });
        }
      });
    }
  }

  $("[data-toggle='sidebar']").click(function() {
    var body = $("body"),
        w = $(window);

    body.removeClass('search-show search-gone');

    if(w.outerWidth() <= 1024) {
      // For mobile screen
      if(body.hasClass('sidebar-show')) {
        body.removeClass('sidebar-show').addClass('sidebar-gone');
      } else {
        body.removeClass('sidebar-mini sidebar-open sidebar-gone')
            .addClass('sidebar-show');
      }
    } else {
      // For desktop screen
      if(body.hasClass('sidebar-mini')) {
        toggle_sidebar_mini(false);
      } else {
        // Remove sidebar-show to avoid overlap
        body.removeClass('sidebar-show sidebar-open');
        toggle_sidebar_mini(true);
      }
    }

    // // Auto close sidebar for mobile after a short delay
    // if(w.outerWidth() <= 1024) {
    //   setTimeout(function() {
    //     $("body").removeClass('sidebar-show').addClass('sidebar-gone');
    //   }, 2000); // Auto close after 2 seconds
    // }

    update_sidebar_nicescroll();
    return false;
  });

  var toggleLayout = function() {
    var w = $(window),
        layout_class = $('body').attr('class') || '',
        layout_classes = (layout_class.trim().length > 0 ? layout_class.split(' ') : []),
        now_layout_class = '';

    // Find active layout class
    layout_classes.forEach(function(item) {
      if(item.indexOf('layout-') !== -1) {
        now_layout_class = item;
      }
    });

    // For mobile screen size
    if(w.outerWidth() <= 1024) {
      if($('body').hasClass('sidebar-mini')) {
        toggle_sidebar_mini(false);
        $('.main-sidebar').niceScroll(sidebar_nicescroll_opts);
        sidebar_nicescroll = $(".main-sidebar").getNiceScroll();
      }

        // Remove all layout classes
      $("body")
        .addClass("sidebar-gone")
        .removeClass("layout-2 layout-3 sidebar-mini sidebar-show");

        $(document).on('click touchend', function(e) {
            const w = $(window);
            const target = $(e.target);

            if (w.outerWidth() <= 1024) {
              const isSidebar = target.closest('.main-sidebar').length > 0;
              const isToggleBtn = target.closest('[data-toggle="sidebar"]').length > 0;

              if (!isSidebar && !isToggleBtn) {
                $("body").removeClass("sidebar-show").addClass("sidebar-gone");
                update_sidebar_nicescroll();
              }
            }
      });

      update_sidebar_nicescroll();

      // If layout-3, change navbar structure to sidebar
      if(now_layout_class === 'layout-3' && $(".navbar-secondary").length) {
        let nav_second = $(".navbar-secondary");

        nav_second.attr('data-nav-classes', nav_second.attr('class'));
        nav_second.removeAttr('class').addClass('main-sidebar');

        let main_sidebar = $(".main-sidebar");
        main_sidebar.find('.container').addClass('sidebar-wrapper').removeClass('container');
        main_sidebar.find('.navbar-nav').addClass('sidebar-menu').removeClass('navbar-nav');
        main_sidebar.find('.sidebar-menu .nav-item.dropdown.show a').click();
        main_sidebar.find('.sidebar-brand').remove();

        // Add sidebar brand if needed
        if ($('.navbar-brand').length) {
          main_sidebar.find('.sidebar-menu').before($('<div>', {
            class: 'sidebar-brand'
          }).append(
            $('<a>', {
              href: $('.navbar-brand').attr('href'),
            }).html($('.navbar-brand').html())
          ));
        }

        setTimeout(function() {
          sidebar_nicescroll = main_sidebar.niceScroll(sidebar_nicescroll_opts);
          sidebar_nicescroll = main_sidebar.getNiceScroll();
        }, 700);

        sidebar_dropdown();
        $(".main-wrapper").removeClass("container");
      }
    } else {
      // For desktop screen
      $("body").removeClass("sidebar-gone sidebar-show");
      if(now_layout_class)
        $("body").addClass(now_layout_class);

      let nav_second = $(".main-sidebar");

      if(now_layout_class === 'layout-3' && nav_second.hasClass('main-sidebar') && nav_second.data('nav-classes')) {
        nav_second.find(".sidebar-menu li a.has-dropdown").off('click');
        nav_second.find('.sidebar-brand').remove();

        nav_second.removeAttr('class').addClass(nav_second.data('nav-classes'));

        let main_sidebar = $(".navbar-secondary");
        main_sidebar.find('.sidebar-wrapper').addClass('container').removeClass('sidebar-wrapper');
        main_sidebar.find('.sidebar-menu').addClass('navbar-nav').removeClass('sidebar-menu');
        main_sidebar.find('.dropdown-menu').hide();
        main_sidebar.removeAttr('style tabindex data-nav-classes');

        $(".main-wrapper").addClass("container");
      } else if(now_layout_class === 'layout-2') {
        $("body").addClass("layout-2");
      } else {
        update_sidebar_nicescroll();
      }
    }
  };

  toggleLayout();
  $(window).resize(toggleLayout);

  $("[data-toggle='search']").click(function() {
    var body = $("body");

    if(body.hasClass('search-gone')) {
      body.addClass('search-gone');
      body.removeClass('search-show');
    }else{
      body.removeClass('search-gone');
      body.addClass('search-show');
    }
  });

  // tooltip
  $("[data-toggle='tooltip']").tooltip();

  // popover
  $('[data-toggle="popover"]').popover({
    container: 'body'
  });

  // Select2
  if(jQuery().select2) {
    $(".select2").select2();
  }

  // Selectric
  if(jQuery().selectric) {
    $(".selectric").selectric({
      disableOnMobile: false,
      nativeOnMobile: false
    });
  }

  $(".notification-toggle").dropdown();
  $(".notification-toggle").parent().on('shown.bs.dropdown', function() {
    $(".dropdown-list-icons").niceScroll({
      cursoropacitymin: .3,
      cursoropacitymax: .8,
      cursorwidth: 7
    });
  });

  $(".message-toggle").dropdown();
  $(".message-toggle").parent().on('shown.bs.dropdown', function() {
    $(".dropdown-list-message").niceScroll({
      cursoropacitymin: .3,
      cursoropacitymax: .8,
      cursorwidth: 7
    });
  });

  if($(".chat-content").length) {
    $(".chat-content").niceScroll({
        cursoropacitymin: .3,
        cursoropacitymax: .8,
    });
    $('.chat-content').getNiceScroll(0).doScrollTop($('.chat-content').height());
  }

  if(jQuery().summernote) {
    $(".summernote").summernote({
       dialogsInBody: true,
      minHeight: 250,
    });
    $(".summernote-simple").summernote({
       dialogsInBody: true,
      minHeight: 150,
      toolbar: [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough']],
        ['para', ['paragraph']]
      ]
    });
  }

  if(window.CodeMirror) {
    $(".codeeditor").each(function() {
      let editor = CodeMirror.fromTextArea(this, {
        lineNumbers: true,
        theme: "duotone-dark",
        mode: 'javascript',
        height: 200
      });
      editor.setSize("100%", 200);
    });
  }

  // Follow function
  $('.follow-btn, .following-btn').each(function() {
    var me = $(this),
        follow_text = 'Follow',
        unfollow_text = 'Following';

    me.click(function() {
      if(me.hasClass('following-btn')) {
        me.removeClass('btn-danger');
        me.removeClass('following-btn');
        me.addClass('btn-primary');
        me.html(follow_text);

        eval(me.data('unfollow-action'));
      }else{
        me.removeClass('btn-primary');
        me.addClass('btn-danger');
        me.addClass('following-btn');
        me.html(unfollow_text);

        eval(me.data('follow-action'));
      }
      return false;
    });
  });

  // Dismiss function
  $("[data-dismiss]").each(function() {
    var me = $(this),
        target = me.data('dismiss');

    me.click(function() {
      $(target).fadeOut(function() {
        $(target).remove();
      });
      return false;
    });
  });

  // Collapsable
  $("[data-collapse]").each(function() {
    var me = $(this),
        target = me.data('collapse');

    me.click(function() {
      $(target).collapse('toggle');
      $(target).on('shown.bs.collapse', function(e) {
        e.stopPropagation();
        me.html('<i class="fas fa-minus"></i>');
      });
      $(target).on('hidden.bs.collapse', function(e) {
        e.stopPropagation();
        me.html('<i class="fas fa-plus"></i>');
      });
      return false;
    });
  });

  // Gallery
  $(".gallery .gallery-item").each(function() {
    var me = $(this);

    me.attr('href', me.data('image'));
    me.attr('title', me.data('title'));
    if(me.parent().hasClass('gallery-fw')) {
      me.css({
        height: me.parent().data('item-height'),
      });
      me.find('div').css({
        lineHeight: me.parent().data('item-height') + 'px'
      });
    }
    me.css({
      backgroundImage: 'url("'+ me.data('image') +'")'
    });
  });
  if(jQuery().Chocolat) {
    $(".gallery").Chocolat({
      className: 'gallery',
      imageSelector: '.gallery-item',
    });
  }

  // Background
  $("[data-background]").each(function() {
    var me = $(this);
    me.css({
      backgroundImage: 'url(' + me.data('background') + ')'
    });
  });

  // Custom Tab
  $("[data-tab]").each(function() {
    var me = $(this);

    me.click(function() {
      if(!me.hasClass('active')) {
        var tab_group = $('[data-tab-group="' + me.data('tab') + '"]'),
            tab_group_active = $('[data-tab-group="' + me.data('tab') + '"].active'),
            target = $(me.attr('href')),
            links = $('[data-tab="'+me.data('tab') +'"]');

        links.removeClass('active');
        me.addClass('active');
        target.addClass('active');
        tab_group_active.removeClass('active');
      }
      return false;
    });
  });

  // Bootstrap 4 Validation
  $(".needs-validation").submit(function() {
    var form = $(this);
    if (form[0].checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
    }
    form.addClass('was-validated');
  });

  // alert dismissible
  $(".alert-dismissible").each(function() {
    var me = $(this);

    me.find('.close').click(function() {
      me.alert('close');
    });
  });

  if($('.main-navbar').length) {
  }

  // Image cropper
  $('[data-crop-image]').each(function(e) {
    $(this).css({
      overflow: 'hidden',
      position: 'relative',
      height: $(this).data('crop-image')
    });
  });

  // Slide Toggle
  $('[data-toggle-slide]').click(function() {
    let target = $(this).data('toggle-slide');

    $(target).slideToggle();
    return false;
  });

  // Dismiss modal
  $("[data-dismiss=modal]").click(function() {
    $(this).closest('.modal').modal('hide');

    return false;
  });

  // Width attribute
  $('[data-width]').each(function() {
    $(this).css({
      width: $(this).data('width')
    });
  });

  // Height attribute
  $('[data-height]').each(function() {
    $(this).css({
      height: $(this).data('height')
    });
  });

  // Chocolat
  if($('.chocolat-parent').length && jQuery().Chocolat) {
    $('.chocolat-parent').Chocolat();
  }

  // Sortable card
  if($('.sortable-card').length && jQuery().sortable) {
    $('.sortable-card').sortable({
      handle: '.card-header',
      opacity: .8,
      tolerance: 'pointer'
    });
  }

  // Daterangepicker
  if(jQuery().daterangepicker) {
    if($(".datepicker").length) {
      $('.datepicker').daterangepicker({
        locale: {format: 'YYYY-MM-DD'},
        singleDatePicker: true,
      });
    }
    if($(".datetimepicker").length) {
      $('.datetimepicker').daterangepicker({
        locale: {format: 'YYYY-MM-DD HH:mm'},
        singleDatePicker: true,
        timePicker: true,
        timePicker24Hour: true,
      });
    }
    if($(".daterange").length) {
      $('.daterange').daterangepicker({
        locale: {format: 'YYYY-MM-DD'},
        drops: 'down',
        opens: 'right'
      });
    }
  }

  // Timepicker
  if(jQuery().timepicker && $(".timepicker").length) {
    $(".timepicker").timepicker({
      icons: {
        up: 'fas fa-chevron-up',
        down: 'fas fa-chevron-down'
      }
    });
  }
});
