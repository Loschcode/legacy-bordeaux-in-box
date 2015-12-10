(function() {
  this.Box = (function() {
    function Box() {
      this._currentBox = false;
      if (this.page('#js-page-box')) {
        this.init();
        this.events();
      }
      if (this.page('#js-page-box-form')) {
        this.dateAllow();
      }
    }

    Box.prototype.page = function(page) {
      return $(page).length;
    };

    Box.prototype.init = function() {
      var boxes, first;
      boxes = this._boxesJson();
      first = _.first(boxes);
      return this.displayBox(first.id);
    };

    Box.prototype.events = function() {
      var self;
      self = this;
      $('.js-box-picture').click(function(event) {
        var id;
        event.preventDefault();
        id = $(this).attr('id').split('box-').join('');
        self.displayBox(id);
        return $('html, body').animate({
          scrollTop: $("#after-pipeline").offset().top
        }, 500);
      });
      return $('#box-buy').click(function(event) {
        event.preventDefault();
        $('#box_choice').attr('value', self._currentBox);
        return $('#choose_box').submit();
      });
    };

    Box.prototype.displayBox = function(id) {
      var boxes;
      if (this._currentBox !== id) {
        boxes = this._boxesJson();
        boxes = _.indexBy(boxes, 'id');
        this._currentBox = id;
        $('.js-box-picture').find('img').addClass('inactive');
        $('#box-' + id).find('img').removeClass('inactive');
        this.setName(boxes[id].title);
        this.setTitle(boxes[id].title);
        this.setDescription(boxes[id].description);
        return this.setButton();
      }
    };

    Box.prototype.setTitle = function(title) {
      return $('#box-title').stop().hide().html(title).fadeIn('fast');
    };

    Box.prototype.setDescription = function(description) {
      return $('#box-description').stop().hide().html(description).fadeIn('slow');
    };

    Box.prototype.setName = function(name) {
      return $('#box-name').html(name);
    };

    Box.prototype.setButton = function() {
      return $('#box-buy').removeClass('hidden');
    };

    Box.prototype._boxesJson = function() {
      var boxes;
      boxes = $('#boxes-json').html();
      boxes = $.parseJSON(boxes);
      return boxes;
    };

    Box.prototype.dateAllow = function() {
      $('[data-type=date]').alphanum({
        disallow: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz",
        allow: "/"
      });
      $('[data-type=date]').attr({
        maxLength: 10
      });
      return $('[data-type=date]').keyup(function(e) {
        var value;
        value = $(this).val();
        if (e.keyCode !== 8) {
          if (value.length === 2 || value.length === 5) {
            return $(this).val(value + '/');
          }
        }
      });
    };

    Box.prototype.effectsForm = function() {

      /*
      if $('#already-answered').length is 1
      
         *
         * Init
         *  
        $('.js-block-form').each ->
      
           * Resolve type
          type = $(this).find('[id^=type-]').attr('id').split('type-').join('')
      
          if (type == 'textarea')
      
             * Check textarea
            value = $(this).find('textarea').val()
            name = $(this).find('textarea').attr('name')
      
            unless value? and value
      
              console.log 'error'
              $('#success-' + name).hide()
              $('#error-' + name).fadeIn()
              $(this).find('textarea').addClass('error')
      
            else
      
              console.log 'success'
              $('#error-' + name).hide()
              $('#success-' + name).fadeIn()
      
      
          if (type == 'text')
      
             * Check textarea
            value = $(this).find('input[type=text]').val()
            name = $(this).find('input[type=text]').attr('name')
      
            unless value? and value
      
              console.log 'error'
              $('#success-' + name).hide()
              $('#error-' + name).fadeIn()
              $(this).find('input[type=text]').addClass('error')
      
            else
      
              console.log 'success'
              $('#error-' + name).hide()
              $('#success-' + name).fadeIn()
      
          if (type == 'radiobutton')
      
            countChecked = $(this).find('input[type=radio]:checked').length
            name = $(this).find('input[type=radio]').attr('name')
      
            if countChecked is 0
      
               * error
              $('#success-' + name).hide()
              $('#error-' + name).fadeIn()
              
            else
      
               * success
              $('#error-' + name).hide()
              $('#success-' + name).fadeIn()
      
          if (type == 'checkbox')
      
      
            name = $(this).find('input[type=checkbox]').attr('name')
      
             * error
            $('#success-' + name).fadeIn()
            
      
       *
       * Text
       *
      $('input[type=text], textarea').focusout ->
      
        value = $(this).val()
        name = $(this).attr('name')
      
        value = $.trim(value)
      
        unless value? and value
      
          $('#success-' + name).hide()
          $('#error-' + name).fadeIn()
          $(this).addClass('error')
      
        else
      
          console.log 'success'
          $('#error-' + name).hide()
          $('#success-' + name).fadeIn()
          $(this).removeClass('error')
      
       *
       * Radio
       *
      $('input[type="radio"]').on 'ifChanged', ->
      
        countChecked = $(this).parent().parent().parent().find('input[type=radio]:checked').length
      
        console.log(countChecked)
      
        name = $(this).attr('name')
      
        if countChecked is 0
      
           * error
          $('#success-' + name).hide()
          $('#error-' + name).fadeIn()
      
        else
      
           * success
          $('#error-' + name).hide()
          $('#success-' + name).fadeIn()
      
       *
       * Checkboxes
       *
      $('input[type="checkbox"]').on 'ifChanged', ->
      
         * success
        $(this).parent().parent().parent().find('[id^=error]').hide()
        $(this).parent().parent().parent().find('[id^=success]').fadeIn()
       */
    };

    return Box;

  })();

}).call(this);
