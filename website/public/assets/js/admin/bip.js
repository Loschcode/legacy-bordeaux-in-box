(function() {
  this.Bip = (function() {
    function Bip() {
      if (this.canRun()) {
        this.run();
      }
    }

    Bip.prototype.canRun = function() {
      return $('#js-page-bip').length;
    };

    Bip.prototype.run = function() {
      return this.initCounter();
    };

    Bip.prototype.initCounter = function() {
      return $.get('/api/orders-count', (function(_this) {
        return function(datas) {
          var result;
          result = datas.count;
          _this.changeCounter(result);
          return setInterval(function() {
            return _this.checkCounter();
          }, 10000);
        };
      })(this));
    };

    Bip.prototype.checkCounter = function() {
      var count;
      count = $('#counter').attr('data-value');
      return $.get('/api/orders-count', (function(_this) {
        return function(datas) {
          console.log('Debug new count : ' + datas.count);
          console.log('Debug old count : ' + count);
          if (parseInt(datas.count) > parseInt(count)) {
            _this.playMusic();
            return _this.changeCounter(datas.count);
          }
        };
      })(this));
    };

    Bip.prototype.changeCounter = function(value) {
      $('#counter').html('<h1>' + value + ' commandes</h1>');
      return $('#counter').attr('data-value', value);
    };

    Bip.prototype.playMusic = function() {
      var audio;
      audio = $("audio")[0];
      return audio.play();
    };

    return Bip;

  })();

}).call(this);
