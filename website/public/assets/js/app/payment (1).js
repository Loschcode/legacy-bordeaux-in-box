(function() {
  this.Payment = (function() {
    function Payment(stripeKey) {
      this.stripeKey = stripeKey;
      if (this.canRun()) {
        this.init();
      }
    }

    Payment.prototype.canRun = function() {
      return $('#js-page-payment').length;
    };

    Payment.prototype.init = function() {
      this.events();
      this.initStripe();
      return this.initRestrictInputs();
    };

    Payment.prototype.events = function() {
      var self;
      self = this;
      $('#trigger-payment').click((function(_this) {
        return function(event) {
          event.preventDefault();
          return _this.openModal();
        };
      })(this));
      $('.stripe-component .form-control').click(function() {
        return self.addFocus(this);
      });
      $('.stripe-component .form-control').focusout(function() {
        return self.removeFocus(this);
      });
      $('#trigger-close').click((function(_this) {
        return function(event) {
          event.preventDefault();
          return _this.closeModal();
        };
      })(this));
      return $('#payment-form').submit((function(_this) {
        return function(event) {
          event.preventDefault();
          return _this.pay();
        };
      })(this));
    };

    Payment.prototype.initStripe = function() {
      return Stripe.setPublishableKey(this.stripeKey);
    };

    Payment.prototype.initRestrictInputs = function() {
      $('#card').numeric({
        allowPlus: false,
        allowMinus: false,
        allowThouSep: false,
        allowDecSep: false,
        allowLeadingSpaces: false,
        maxDigits: 16
      });
      $('#cvc').numeric({
        allowPlus: false,
        allowMinus: false,
        allowThouSep: false,
        allowDecSep: false,
        allowLeadingSpaces: false,
        maxDigits: 3
      });
      return $('#expiration').alphanum({
        allowNumeric: true,
        allow: '/',
        disallow: 'abcdefghijklmnopqrstuvwxyz',
        allowSpace: false,
        allowUpper: false,
        maxLength: 5
      });
    };

    Payment.prototype.pay = function() {
      $('#trigger-pay').prop('disabled', true);
      if (this.validationForm()) {
        return this.stripeCreateToken();
      } else {
        return $('#trigger-pay').prop('disabled', false);
      }
    };

    Payment.prototype.validationForm = function() {
      var form, month, success, year;
      this.resetErrors();
      success = true;
      form = {
        card: $('#card').val(),
        expiration: $('#expiration').val(),
        cvc: $('#cvc').val()
      };
      if (!Stripe.card.validateCardNumber(form.card)) {
        this.displayError('#card');
        success = false;
      }
      if (!Stripe.card.validateCVC(form.cvc)) {
        this.displayError('#cvc');
        success = false;
      }
      if (form.expiration.indexOf('/') === '-1') {
        this.displayError('#expiration');
        success = false;
      }
      if (form.expiration.length !== 5) {
        this.displayError('#expiration');
        success = false;
      }
      month = form.expiration.slice(0, 2);
      year = form.expiration.slice(3, 5);
      if (!Stripe.card.validateExpiry(month, year)) {
        this.displayError('#expiration');
        success = false;
      }
      return success;
    };

    Payment.prototype.stripeCreateToken = function() {
      var exp_month, exp_year;
      exp_month = $('#expiration').val().slice(0, 2);
      exp_year = $('#expiration').val().slice(3, 5);
      return Stripe.card.createToken({
        number: $('#card').val(),
        cvc: $('#cvc').val(),
        exp_month: exp_month,
        exp_year: exp_year
      }, function(status, response) {
        var token;
        console.log(status);
        if (status === 200) {
          token = response.id;
          $('#stripe-token').val(token);
          $('#trigger-pay').html('<i class="fa fa-circle-o-notch fa-spin"></i>');
          return $('#payment-form').unbind('submit').submit();
        } else {

        }
      });
    };

    Payment.prototype.displayError = function(selector) {
      return $(selector).addClass('has-error state-default');
    };

    Payment.prototype.resetErrors = function() {
      return $('input').removeClass('has-error state-default');
    };

    Payment.prototype.addFocus = function(object) {
      $(object).addClass('focus');
      return $(object).removeClass('has-error state-default');
    };

    Payment.prototype.removeFocus = function(object) {
      return $(object).removeClass('focus');
    };

    Payment.prototype.openModal = function() {
      return $('#modal-payment').modal('show');
    };

    Payment.prototype.closeModal = function() {
      return $('#modal-payment').modal('hide');
    };

    return Payment;

  })();

}).call(this);
