(function() {
  this.Dashboard = (function() {
    function Dashboard() {
      this.run();
    }

    Dashboard.prototype.run = function() {
      $('#resumes').hide();
      return $('#hide').click(function(e) {
        e.preventDefault();
        if ($('#resumes').css('display') === 'none') {
          $('#resumes').show();
          return $('#hide').html('Cacher les résumés');
        } else {
          $('#resumes').hide();
          return $('#hide').html('Afficher les résumés');
        }
      });
    };

    return Dashboard;

  })();

  new Dashboard();

}).call(this);
