(function() {
  this.Global = (function() {
    function Global() {
      this.clouds();
      this.autoTab();
      this.popover();
      this.checkboxes();
      this.tooltips();
      this.scrollFrequency();
      this.scrollMode();
      this.childrens();
      this.fancyselect();
    }

    Global.prototype.fancyselect = function() {
      return $('[data-toggle=fancyselect]').fancySelect();
    };

    Global.prototype.clouds = function() {
      var loopClouds, position;
      console.log('clouds');
      position = 0;
      loopClouds = function() {
        position = position - 180;
        $('.footer-clouds').stop().animate({
          backgroundPosition: position + "px"
        }, 10000, "linear", loopClouds);
      };
      return loopClouds();
    };

    Global.prototype.autoTab = function() {
      var hash, run;
      run = false;
      if ($('.nav-tabs').length !== 0) {
        hash = window.location.hash.split('#').join('');
        return $('.tab-pane').each(function() {
          var id;
          id = $(this).attr('id');
          if (id === hash) {
            return $('.nav-tabs a[href=#' + hash + ']').tab('show');
          }
        });
      }
    };

    Global.prototype.chooseBox = function() {
      return $('#choose_box a').click(function(event) {
        var box_id;
        event.preventDefault();
        box_id = $(this).attr('id');
        $('#box_choice').attr('value', box_id);
        return $('#choose_box').submit();
      });
    };

    Global.prototype.popover = function() {
      return $('.js-popover').popover();
    };

    Global.prototype.checkboxes = function() {
      $('input:not(.big)').iCheck({
        checkboxClass: 'icheckbox_flat-orange',
        radioClass: 'iradio_flat-orange'
      });
      $('input.big').each(function() {
        var id, text;
        id = $(this).attr('id');
        text = $('label[for=' + id + ']').text();
        return $(this).iCheck({
          checkboxClass: 'icheckbox_line-yellow',
          radioClass: 'iradio_line-yellow',
          insert: text
        });
      });
      return $('input[class^=frequency-]').each(function() {
        var id, key, text;
        key = $(this).attr('class').split('frequency-').join('');
        id = $(this).attr('id');
        text = $('label[for=' + id + ']').text();
        return $(this).iCheck({
          checkboxClass: 'icheckbox_line-yellow icheckbox_line-yellow-' + key,
          radioClass: 'iradio_line-yellow iradio_line-yellow-' + key,
          insert: text
        });
      });
    };

    Global.prototype.tooltips = function() {
      return $('[data-toggle=tooltip]').tooltip();
    };

    Global.prototype.scrollFrequency = function() {
      if ($('#js-page-box-frequency').length > 0) {
        return $('input[type=radio]').on('ifChecked', function() {
          return $('html, body').animate({
            scrollTop: $('#after-pipeline').offset().top
          }, 500);
        });
      }
    };

    Global.prototype.scrollMode = function() {
      if ($('#js-page-delivery-mode').length > 0) {
        return $('input[type=radio]').on('ifChecked', function() {
          return $('html, body').animate({
            scrollTop: $('#after-pipeline').offset().top
          }, 500);
        });
      }
    };

    Global.prototype.childrens = function() {
      if ($('#add-children').length > 0) {
        $('[data-name=children]').hide().removeClass('hidden');
        $('[data-name=children]').each(function() {
          var value;
          value = $(this).find('input:first').val();
          console.log(value);
          console.log(value);
          if (value.length > 0) {
            return $(this).show();
          }
        });
        if ($('[data-name=children]:visible').length === 0) {
          $('[data-name=children]').first().show();
        }
        $('#add-children').on('click', (function(_this) {
          return function(e) {
            e.preventDefault();
            $('[data-name=children]:visible').last().next().fadeIn('slow');
            return _this.children_add();
          };
        })(this));
        return $(document).on('click', '[data-toggle=remove-children]', (function(_this) {
          return function(e) {
            e.preventDefault();
            $(e.currentTarget).parent().parent().parent().find('select').val('');
            $(e.currentTarget).parent().parent().parent().find('input:first').val('');
            $(e.currentTarget).parent().parent().parent().hide();
            return _this.children_add();
          };
        })(this));
      }
    };

    Global.prototype.children_add = function() {
      var total, visible;
      total = $('[data-name=children]').length;
      visible = $('[data-name=children]:visible').length;
      if (visible === total) {
        return $('#add-children').hide();
      } else {
        return $('#add-children').show();
      }
    };

    return Global;

  })();

}).call(this);
