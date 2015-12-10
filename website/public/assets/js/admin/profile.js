(function() {
  this.Profile = (function() {
    function Profile() {
      if (this.canRun()) {
        this.read();
        this.datatable();
      }
    }

    Profile.prototype.canRun = function() {
      return $('#js-page-profile').length;
    };

    Profile.prototype.read = function() {
      var profiles;
      profiles = $('#profiles-json').html();
      profiles = $.parseJSON(profiles);
      profiles = _.indexBy(profiles, 'id');
      return $(document).on('click', '[data-profile]', function() {
        var id;
        id = $(this).attr('data-profile');
        if (id in profiles) {
          $('#profile-title').html('Abonnement #' + profiles[id].id);
          if (_.isEmpty(profiles[id].stripe_customer)) {
            profiles[id].stripe_customer = 'Aucun pour le moment';
          }
          $('#profile-stripe').html(profiles[id].stripe_customer);
          $('#profile-contract').html(profiles[id].contract_id);
          $('#profile-edit').attr('href', '/admin/profiles/edit/' + profiles[id].id);
          $('#profile-archive').attr('href', '/admin/profiles/delete/' + profiles[id].id);
          return $('#profile-modal').modal('show');
        }
      });
    };

    Profile.prototype.datatable = function() {
      return $('[data-filter]').click(function(e) {
        var search;
        e.preventDefault();
        if (!$(this).find('i').hasClass('hidden')) {
          $(this).find('i').addClass('hidden');
          return $('#table-profiles').DataTable().column(6).search('').draw();
        } else {
          $('[data-filter]').each(function() {
            return $(this).find('i').addClass('hidden');
          });
          search = $(this).data('filter');
          $(this).find('i').removeClass('hidden');
          return $('#table-profiles').DataTable().column(6).search('^' + search + '$', true, false).draw();
        }
      });
    };

    return Profile;

  })();

}).call(this);
