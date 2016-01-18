(function() {
  this.Card = (function() {
    function Card(stripeKey) {
      this.stripeKey = stripeKey;
      if (this.canRun()) {
        this.init();
      }
    }

    Card.prototype.canRun = function() {
      return $('#js-page-card').length;
    };

    Card.prototype.init = function() {
      this.events();
      this.initRestrictInputs();
      return this.initStripe();
    };

    Card.prototype.events = function() {
      return $('#payment-form').submit((function(_this) {
        return function(event) {
          event.preventDefault();
          return _this.update();
        };
      })(this));
    };

    Card.prototype.initStripe = function() {
      return Stripe.setPublishableKey(this.stripeKey);
    };

    Card.prototype.initRestrictInputs = function() {
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

    Card.prototype.update = function() {
      $('#trigger-update').prop('disabled', true);
      if (this.validationForm()) {
        return this.stripeCreateToken();
      } else {
        return $('#trigger-update').prop('disabled', false);
      }
    };

    Card.prototype.validationForm = function() {
      var form, month, year;
      this.resetErrors();
      form = {
        card: $('#card').val(),
        expiration: $('#expiration').val(),
        cvc: $('#cvc').val(),
        password: $('#password').val()
      };
      if (!(form.password.length > 0)) {
        this.displayError('Le mot de passe est requis');
        return false;
      }
      if (!Stripe.card.validateCardNumber(form.card)) {
        this.displayError('Format de carte invalide');
        return false;
      }
      if (!Stripe.card.validateCVC(form.cvc)) {
        this.displayError('Format ccv invalide');
        return false;
      }
      if (form.expiration.indexOf('/') === '-1') {
        this.displayError('Format expiration invalide');
        return false;
      }
      if (form.expiration.length !== 5) {
        this.displayError('Format expiration invalide');
        return false;
      }
      month = form.expiration.slice(0, 2);
      year = form.expiration.slice(3, 5);
      if (!Stripe.card.validateExpiry(month, year)) {
        this.displayError('Expiration invalide');
        return false;
      }
      return true;
    };

    Card.prototype.stripeCreateToken = function() {
      var exp_month, exp_year;
      $('#trigger-update').html('<i class="fa fa-circle-o-notch fa-spin"></i>');
      exp_month = $('#expiration').val().slice(0, 2);
      exp_year = $('#expiration').val().slice(3, 5);
      return Stripe.card.createToken({
        number: $('#card').val(),
        cvc: $('#cvc').val(),
        exp_month: exp_month,
        exp_year: exp_year
      }, function(status, response) {
        var token;
        if (status === 200) {
          token = response.id;
          $('#stripe-token').val(token);
          return $('#payment-form').unbind('submit').submit();
        } else {

        }
      });
    };

    Card.prototype.displayError = function(error) {
      $('#errors').html('<div class="spyro-alert spyro-alert-danger">' + error + '</div>');
      return $('html, body').animate({
        scrollTop: $('#errors').offset().top
      });
    };

    Card.prototype.resetErrors = function() {
      return $('#errors').html('');
    };

    return Card;

  })();

}).call(this);
