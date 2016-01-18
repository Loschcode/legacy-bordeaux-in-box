(function() {
  this.Login = (function() {
    function Login() {
      if ($('#js-page-login').length) {
        this.init();
      }
    }

    Login.prototype.init = function() {
      return this.focusEmail();
    };

    Login.prototype.focusEmail = function() {
      return $('[name=email]').focus();
    };

    return Login;

  })();

}).call(this);
