(function() {
  'use strict';

  var globals = typeof window === 'undefined' ? global : window;
  if (typeof globals.require === 'function') return;

  var modules = {};
  var cache = {};
  var aliases = {};
  var has = ({}).hasOwnProperty;

  var endsWith = function(str, suffix) {
    return str.indexOf(suffix, str.length - suffix.length) !== -1;
  };

  var _cmp = 'components/';
  var unalias = function(alias, loaderPath) {
    var start = 0;
    if (loaderPath) {
      if (loaderPath.indexOf(_cmp) === 0) {
        start = _cmp.length;
      }
      if (loaderPath.indexOf('/', start) > 0) {
        loaderPath = loaderPath.substring(start, loaderPath.indexOf('/', start));
      }
    }
    var result = aliases[alias + '/index.js'] || aliases[loaderPath + '/deps/' + alias + '/index.js'];
    if (result) {
      return _cmp + result.substring(0, result.length - '.js'.length);
    }
    return alias;
  };

  var _reg = /^\.\.?(\/|$)/;
  var expand = function(root, name) {
    var results = [], part;
    var parts = (_reg.test(name) ? root + '/' + name : name).split('/');
    for (var i = 0, length = parts.length; i < length; i++) {
      part = parts[i];
      if (part === '..') {
        results.pop();
      } else if (part !== '.' && part !== '') {
        results.push(part);
      }
    }
    return results.join('/');
  };

  var dirname = function(path) {
    return path.split('/').slice(0, -1).join('/');
  };

  var localRequire = function(path) {
    return function expanded(name) {
      var absolute = expand(dirname(path), name);
      return globals.require(absolute, path);
    };
  };

  var initModule = function(name, definition) {
    var module = {id: name, exports: {}};
    cache[name] = module;
    definition(module.exports, localRequire(name), module);
    return module.exports;
  };

  var require = function(name, loaderPath) {
    var path = expand(name, '.');
    if (loaderPath == null) loaderPath = '/';
    path = unalias(name, loaderPath);

    if (has.call(cache, path)) return cache[path].exports;
    if (has.call(modules, path)) return initModule(path, modules[path]);

    var dirIndex = expand(path, './index');
    if (has.call(cache, dirIndex)) return cache[dirIndex].exports;
    if (has.call(modules, dirIndex)) return initModule(dirIndex, modules[dirIndex]);

    throw new Error('Cannot find module "' + name + '" from '+ '"' + loaderPath + '"');
  };

  require.alias = function(from, to) {
    aliases[to] = from;
  };

  require.register = require.define = function(bundle, fn) {
    if (typeof bundle === 'object') {
      for (var key in bundle) {
        if (has.call(bundle, key)) {
          modules[key] = bundle[key];
        }
      }
    } else {
      modules[bundle] = fn;
    }
  };

  require.list = function() {
    var result = [];
    for (var item in modules) {
      if (has.call(modules, item)) {
        result.push(item);
      }
    }
    return result;
  };

  require.brunch = true;
  require._cache = cache;
  globals.require = require;
})();
require.register("config", function(exports, require, module) {
module.exports = {
  app: {
    name: 'My Gotham Application',
    version: 0.1
  }
};
});

;require.register("controllers/example-controller", function(exports, require, module) {
var Controller, Example,
  extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

Controller = require('core/controller');

Example = (function(superClass) {
  extend(Example, superClass);

  function Example() {
    return Example.__super__.constructor.apply(this, arguments);
  }

  Example.prototype.before = function() {};

  Example.prototype.run = function() {};

  return Example;

})(Controller);

module.exports = Example;
});

;require.register("controllers/masterbox/guest/home/index", function(exports, require, module) {
var Controller, Index,
  bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
  extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

Controller = require('core/controller');

Index = (function(superClass) {
  extend(Index, superClass);

  function Index() {
    this.alertNoBoxes = bind(this.alertNoBoxes, this);
    return Index.__super__.constructor.apply(this, arguments);
  }

  Index.prototype.before = function() {
    return this.smoothScroll();
  };

  Index.prototype.run = function() {
    return this.on('click', '.js-no-boxes', this.alertNoBoxes);
  };

  Index.prototype.alertNoBoxes = function(e) {
    e.preventDefault();
    return swal({
      title: $('#gotham').data('no-boxes-title'),
      text: $('#gotham').data('no-boxes-text'),
      type: 'error',
      confirmButtonColor: '#D83F66',
      html: true
    });
  };

  Index.prototype.smoothScroll = function() {
    return smoothScroll.init({
      selector: '.js-anchor'
    });
  };

  return Index;

})(Controller);

module.exports = Index;
});

;require.register("core/bootstrap", function(exports, require, module) {
var Bootstrap;

Bootstrap = (function() {
  function Bootstrap() {}

  Bootstrap.prototype.run = function() {
    var controller, pathController;
    require('helpers');
    require('validators');
    require('start');
    controller = $('#gotham').data('controller');
    if (controller == null) {
      return;
    }
    if (_.isEmpty(controller)) {
      return;
    }
    pathController = this._formatPath(controller);
    controller = require('controllers/' + pathController);
    controller = new controller();
    if (controller['before'] != null) {
      controller.before();
    }
    if (!controller._gothamStop) {
      return controller.run();
    }
  };

  Bootstrap.prototype._formatPath = function(str) {
    return str.split('.').join('/');
  };

  return Bootstrap;

})();

module.exports = Bootstrap;
});

;require.register("core/controller", function(exports, require, module) {
var Controller, View;

View = require('core/view');

Controller = (function() {
  Controller.prototype._gothamStop = false;

  function Controller() {}

  Controller.prototype.stop = function() {
    return this._gothamStop = true;
  };

  Controller.prototype.log = function(value) {
    return console.log(value);
  };

  Controller.prototype.on = function(trigger, selector, handler) {
    return $(selector).on(trigger, handler);
  };

  Controller.prototype.off = function(trigger, selector, handler) {
    return $(selector).off(trigger, handler);
  };

  Controller.prototype.delayed = function(trigger, selector, handler) {
    return $(document).on(trigger, selector, handler);
  };

  Controller.prototype.view = function(template, datas) {
    var view;
    view = new View();
    return view.render(template, datas);
  };

  return Controller;

})();

module.exports = Controller;
});

;require.register("core/view", function(exports, require, module) {
var View;

View = (function() {
  function View() {}

  View.prototype.render = function(template, datas) {
    template = template.split('.').join('/');
    template = require('views/' + template);
    return template(datas);
  };

  return View;

})();

module.exports = View;
});

;require.register("helpers", function(exports, require, module) {
_.mixin({
  notificationFormErrors: function() {
    var hasErrors, text, textErrors, title, titleErrors;
    hasErrors = _.trim($('#gotham').data('form-errors'));
    if (_.isEmpty(hasErrors)) {
      return;
    }
    if (hasErrors !== '1') {
      return;
    }
    titleErrors = _.trim($('#gotham').data('form-errors-title'));
    textErrors = _.trim($('#gotham').data('form-errors-text'));
    if (!_.isEmpty(titleErrors)) {
      title = titleErrors;
    } else {
      title = 'Erreur';
    }
    if (!_.isEmpty(textErrors)) {
      text = textErrors;
    } else {
      text = 'Des erreurs sont présentes dans le formulaire';
    }
    return swal({
      title: title,
      text: text,
      type: 'error',
      confirmButtonColor: '#D83F66',
      html: true
    });
  }
});

_.mixin({
  notificationSuccessMessage: function() {
    var successMessage;
    successMessage = _.trim($('#gotham').data('success-message'));
    if (_.isEmpty(successMessage)) {
      return;
    }
    return swal({
      title: 'Bravo !',
      text: successMessage,
      type: 'success',
      confirmButtonColor: '#A5DC86',
      html: true
    });
  }
});
});

;require.register("initialize", function(exports, require, module) {
var Bootstrap;

Bootstrap = require('core/bootstrap');

$(function() {
  var bootstrap;
  bootstrap = new Bootstrap({
    request: window.location.pathname
  });
  return bootstrap.run();
});
});

;require.register("start", function(exports, require, module) {
$('input, textarea').placeholder();

_.notificationFormErrors();

_.notificationSuccessMessage();

$('.js-chosen').chosen({
  disable_search_threshold: 30
});

$(':checkbox').labelauty();

$(':radio').labelauty();
});

;require.register("validators", function(exports, require, module) {
Validator.errors;

Validator.attributes;
});

;
//# sourceMappingURL=app.js.map