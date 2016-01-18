(function() {
  this.Spot = (function() {
    function Spot() {
      if ($('#js-page-spot').length) {
        this.init();
      }
    }

    Spot.prototype.init = function() {
      return this.checkboxes();
    };

    Spot.prototype.checkboxes = function() {
      $('[id^=gm]').hide().removeClass('hidden');
      $('input.choose-spot').each(function() {
        var id, text;
        id = $(this).attr('id');
        text = $('label[for=' + id + ']').text();
        return $(this).iCheck({
          checkboxClass: 'icheckbox_line-yellow',
          radioClass: 'iradio_line-yellow',
          insert: text
        });
      });
      $('.choose-spot').each(function() {
        var checked, spotId;
        checked = $(this).attr('checked');
        if (checked === 'checked') {
          spotId = $(this).parent().parent().attr('id').split('spot-').join('');
          return $('#gm-' + spotId).fadeIn();
        }
      });
      return $('input.choose-spot').on('ifChecked', function(event) {
        var spotId;
        $('[id^=gm]').hide();
        spotId = $(this).parent().parent().attr('id').split('spot-').join('');
        return $('#gm-' + spotId).fadeIn();
      });
    };

    return Spot;

  })();

}).call(this);
