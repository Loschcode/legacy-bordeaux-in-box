(function() {
  this.Billing = (function() {
    function Billing() {
      var self;
      self = this;
      if ($('#js-page-billing-address').length === 1) {
        if ($('#js-flag-billing-address').length === 1) {
          this.initValidationForm();
        }
        if ($('#gift').data('value') === false) {
          this.copyBilling();
        }
        $('#copy-billing').click(function(event) {
          event.preventDefault();
          return self.copyBilling();
        });
        $('input, textarea').focusout(function() {
          return self.initValidationForm();
        });
      }
    }

    Billing.prototype.copyBilling = function() {
      var datas;
      datas = {
        first_name: $('#billing_first_name').val(),
        last_name: $('#billing_last_name').val(),
        city: $('#billing_city').val(),
        zip: $('#billing_zip').val(),
        address: $('#billing_address').val()
      };
      $('#destination_first_name').val(datas.first_name);
      $('#destination_last_name').val(datas.last_name);
      $('#destination_city').val(datas.city);
      $('#destination_zip').val(datas.zip);
      $('#destination_address').val(datas.address);
      return this.initValidationForm();
    };

    Billing.prototype.initValidationForm = function() {
      var self;
      self = this;
      return $('input[type=text], textarea').each(function() {
        var value;
        console.log($(this).attr('id'));
        value = $.trim($(this).val());
        if (value === '') {
          return self.displayError(this);
        } else {
          return self.displaySuccess(this);
        }
      });
    };

    Billing.prototype.displayError = function(object) {
      var type;
      type = $(object).prop('tagName');
      if (!$(object).parent().find('i').hasClass('billing-error')) {
        $(object).parent().find('i').remove();
        if (type === 'TEXTAREA') {
          $(object).after('<i class="fa fa-times billing-error hidden type-textarea"></i>');
        } else {
          $(object).after('<i class="fa fa-times billing-error hidden"></i>');
        }
        return $(object).parent().find('i').hide().removeClass('hidden').fadeIn();
      }
    };

    Billing.prototype.displaySuccess = function(object) {
      var type;
      type = $(object).prop('tagName');
      if (!$(object).parent().find('i').hasClass('billing-success')) {
        $(object).parent().find('i').remove();
        if (type === 'TEXTAREA') {
          $(object).after('<i class="fa fa-check billing-success hidden type-textarea"></i>');
        } else {
          $(object).after('<i class="fa fa-check billing-success hidden"></i>');
        }
        return $(object).parent().find('i').hide().removeClass('hidden').fadeIn();
      }
    };

    return Billing;

  })();

}).call(this);
