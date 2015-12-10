(function() {
  this.Contact = (function() {
    function Contact() {
      if (this.canRun()) {
        this.read();
      }
    }

    Contact.prototype.canRun = function() {
      return $('#js-page-contact').length;
    };

    Contact.prototype.read = function() {
      return $.get('/api/contacts', function(response) {
        var datas;
        datas = $.parseJSON(response);
        datas = _.indexBy(datas, 'id');
        return $(document).on('click', '[data-contact]', function() {
          var id;
          id = $(this).attr('data-contact');
          if (id in datas) {
            $('#contact-title').html('Prise de contact #' + datas[id].id);
            $('#contact-from').html(datas[id].email).attr('href', 'mailto:' + datas[id].email);
            $('#contact-to').html(datas[id].recipient).attr('href', 'mailto' + datas[id].recipient);
            $('#contact-message').html(_.unescape(datas[id].clean_message));
            $('#contact-archive').attr('href', '/admin/logs/delete/' + datas[id].id);
            return $('#contact-modal').modal('show');
          }
        });
      });
    };

    return Contact;

  })();

}).call(this);
