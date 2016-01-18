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
  app_name: "Your app name",
  app_version: "0.0.1",
  app_author: "Your name"
};
});

;require.register("controllers/front/home", function(exports, require, module) {
var Front_Home, Gotham,
  extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

Gotham = require('core/gotham');

Front_Home = (function(superClass) {
  extend(Front_Home, superClass);

  function Front_Home() {
    return Front_Home.__super__.constructor.apply(this, arguments);
  }

  Front_Home.prototype.el = {
    anchorsScroll: 'a[href*=#]:not([href=#])'
  };

  Front_Home.prototype.before = function() {};

  Front_Home.prototype.run = function() {
    return this.on('click', this.el.anchorsScroll, this.smoothScroll);
  };

  Front_Home.prototype.smoothScroll = function() {
    var target;
    if (location.pathname.replace(/^\//, "") === this.pathname.replace(/^\//, "") || location.hostname === this.hostname) {
      target = $(this.hash);
      target = (target.length ? target : $("[name=" + this.hash.slice(1) + "]"));
      if (target.length) {
        return $("html,body").animate({
          scrollTop: target.offset().top
        }, 1000);
      }
    }
  };

  return Front_Home;

})(Gotham.Controller);

module.exports = Front_Home;
});

;require.register("core/application", function(exports, require, module) {
var Application, router;

router = require('core/router');

Application = (function() {
  function Application() {}

  Application.prototype.construct = function() {};

  Application.prototype.start = function() {
    var response;
    require('helpers');
    require('views');
    require('validators');
    router = new router();
    require('routes')(router);
    require('start');
    router.run();
    if (router.passes()) {
      response = router.response();
      return this._controller(response);
    }
  };

  Application.prototype._controller = function(response) {
    var controller;
    controller = require('controllers/' + response.controller);
    controller = new controller();
    if (controller['before'] != null) {
      controller.before(response.params);
    }
    if (!controller._gotham_stop) {
      return controller.run(response.params);
    }
  };

  return Application;

})();

module.exports = Application;
});

;require.register("core/controller", function(exports, require, module) {
var Controller, view;

view = require('core/view');

Controller = (function() {
  Controller.prototype._gotham_stop = false;

  function Controller() {}

  Controller.prototype.stop = function() {
    return this._gotham_stop = true;
  };

  Controller.prototype.log = function(value) {
    if (_.isObject(value) || _.isArray(value)) {
      return console.table(value);
    }
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
    view = new view();
    return view.render(template, datas);
  };

  return Controller;

})();

module.exports = Controller;
});

;require.register("core/gotham", function(exports, require, module) {
module.exports = {
  Application: require('core/application'),
  Controller: require('core/controller'),
  Router: require('core/router'),
  Syphon: require('core/syphon'),
  Validator: require('core/validator'),
  View: require('core/view')
};
});

;require.register("core/router", function(exports, require, module) {
var Router;

Router = (function() {
  Router.prototype._routes = [];

  Router.prototype._request = '';

  Router.prototype._success = false;

  Router.prototype._response = {};

  function Router() {
    this._request = this._slashes(window.location.pathname);
  }

  Router.prototype.match = function(pattern, controller, constraint) {
    pattern = this._slashes(pattern);
    return this._routes.push({
      pattern: pattern,
      controller: controller,
      parsed: this._parse_pattern(pattern),
      variables: this._fetch_variables(pattern),
      constraint: constraint
    });
  };

  Router.prototype.run = function() {
    var i, index, j, len, len1, params, params_request, ref, ref1, results, route, success, success_constraint, variable;
    ref = this._routes;
    results = [];
    for (i = 0, len = ref.length; i < len; i++) {
      route = ref[i];
      if (route.parsed.test(this._request)) {
        success = true;
        params = {};
        if (route.variables != null) {
          params_request = route.parsed.exec(this._request);
          ref1 = route.variables;
          for (index = j = 0, len1 = ref1.length; j < len1; index = ++j) {
            variable = ref1[index];
            params[variable] = params_request[index + 1];
          }
        }
        if (route.constraint != null) {
          if (_.isFunction(route.constraint)) {
            success_constraint = route.constraint(params);
            if (success_constraint === false) {
              success = false;
            }
          }
        }
        if (success === true) {
          this._success = true;
          this._response = {
            controller: this._decode(route.controller),
            params: params
          };
          break;
        } else {
          results.push(void 0);
        }
      } else {
        results.push(void 0);
      }
    }
    return results;
  };

  Router.prototype.passes = function() {
    return this._success;
  };

  Router.prototype.fails = function() {
    if (!this._success) {
      return true;
    }
    return false;
  };

  Router.prototype.response = function() {
    return this._response;
  };

  Router.prototype._decode = function(controller) {
    return controller.split('#').join('/');
  };

  Router.prototype._slashes = function(str) {
    if (str) {
      if (str[str.length - 1] === '/') {
        str = str.substr(0, str.length - 1);
      }
      while (str.charAt(0) === '/') {
        str = str.substr(1);
      }
    }
    return str;
  };

  Router.prototype._parse_pattern = function(pattern) {
    var variables;
    variables = /(:[a-zA-Z_]*)/g;
    pattern = pattern.replace(variables, '([a-zA-Z0-9-_]*)');
    return new RegExp('^' + pattern + '$');
  };

  Router.prototype._fetch_variables = function(pattern) {
    var i, index, len, variable, variables;
    variables = /(:[a-zA-Z_]*)/g;
    variables = pattern.match(variables);
    if (variables != null) {
      for (index = i = 0, len = variables.length; i < len; index = ++i) {
        variable = variables[index];
        variables[index] = variable.replace(':', '');
      }
    }
    return variables;
  };

  return Router;

})();

module.exports = Router;
});

;require.register("core/syphon", function(exports, require, module) {
var Syphon,
  indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

Syphon = (function() {
  Syphon.prototype._exclude = [];

  Syphon.prototype._keep = [];

  function Syphon() {}

  Syphon.prototype.exclude = function() {
    var i, len, to_exclude, value;
    to_exclude = arguments;
    if (_.isArray(to_exclude[0])) {
      to_exclude = arguments[0];
    }
    for (i = 0, len = to_exclude.length; i < len; i++) {
      value = to_exclude[i];
      this._exclude.push(value);
    }
    return this;
  };

  Syphon.prototype.keep = function() {
    var i, len, to_keep, value;
    to_keep = arguments;
    if (_.isArray(to_keep[0])) {
      to_keep = arguments[0];
    }
    for (i = 0, len = to_keep.length; i < len; i++) {
      value = to_keep[i];
      this._keep.push(value);
    }
    return this;
  };

  Syphon.prototype.get = function(selector) {
    var datas, datas_serialized;
    datas_serialized = $(selector).serializeArray();
    datas = {};
    _.each(datas_serialized, (function(_this) {
      return function(data) {
        var ref;
        if (ref = data.name, indexOf.call(_this._exclude, ref) < 0) {
          return datas[data.name] = data.value;
        }
      };
    })(this));
    if (!_.isEmpty(this._keep)) {
      return _.pick(datas, this._keep);
    }
    this._exclude = [];
    this._keep = [];
    return datas;
  };

  return Syphon;

})();

module.exports = Syphon;
});

;require.register("core/validator", function(exports, require, module) {
var Validator;

Validator = (function() {
  Validator.prototype._rules_to_validate = {};

  Validator.prototype._datas_to_validate = {};

  Validator.prototype._success = true;

  Validator.prototype._errors = {};

  Validator.prototype._rules = {};

  Validator.prototype._messages = {};

  Validator.prototype._attributes = {};

  function Validator() {}

  Validator.prototype.make = function(datas, rules) {
    this._errors = {};
    this._datas_to_validate = datas;
    _.each(rules, (function(_this) {
      return function(value, index) {
        if (value !== '') {
          return _this._rules_to_validate[index] = _this._parse_params(value);
        }
      };
    })(this));
    return this._run();
  };

  Validator.prototype.errors = function(type, attribute) {
    var errors;
    switch (type) {
      case 'first':
        if (this._errors[attribute] != null) {
          return _.first(this._errors[attribute]);
        }
        break;
      case 'last':
        if (this._errors[attribute]) {
          return _.last(this._errors[attribute]);
        }
        break;
      case 'all':
        errors = [];
        _.each(this._errors, (function(_this) {
          return function(attributes) {
            var error, i, len, results;
            results = [];
            for (i = 0, len = attributes.length; i < len; i++) {
              error = attributes[i];
              results.push(errors.push(error));
            }
            return results;
          };
        })(this));
        return errors;
      case 'get':
        if (this._errors[attribute] != null) {
          return this._errors[attribute];
        }
    }
  };

  Validator.prototype.passes = function() {
    return this._success;
  };

  Validator.prototype.fails = function() {
    if (!this._success) {
      return true;
    }
    return false;
  };

  Validator.prototype.error = function(rule, message) {
    return this._messages[rule] = message;
  };

  Validator.prototype.rule = function(name, callback) {
    return this._rules[name] = callback;
  };

  Validator.prototype.attributes = function(attributes) {
    return _.each(attributes, (function(_this) {
      return function(attribute, index) {
        return _this._attributes[index] = attribute;
      };
    })(this));
  };

  Validator.prototype._run = function() {
    return _.each(this._rules_to_validate, (function(_this) {
      return function(rule, input) {
        return _.each(rule, function(value, index) {
          var error, result;
          if ((_this._rules[index] != null) && (_this._datas_to_validate[input] != null)) {
            result = _this._rules[index](input, _this._datas_to_validate[input], value, _this._datas_to_validate);
            if (!result) {
              _this._success = false;
              if (_this._messages[index] != null) {
                error = _this._create_error_message(_this._messages[index], input, value);
              } else {
                error = '[Rule ' + index + '] No error message for this rule';
              }
              if (_this._errors[input] != null) {
                return _this._errors[input].push(error);
              } else {
                return _this._errors[input] = [error];
              }
            }
          }
        });
      };
    })(this));
  };

  Validator.prototype._create_error_message = function(string, attribute, value) {
    var i, index, len, option;
    if (this._attributes[attribute] != null) {
      attribute = this._attributes[attribute];
    }
    string = string.split(':attribute').join(attribute);
    if (!_.isEmpty(value)) {
      if (value.indexOf(',') !== -1) {
        string = string.split(':options').join(value.join(', '));
      } else {
        string = string.split(':options').join(value);
      }
      for (index = i = 0, len = value.length; i < len; index = ++i) {
        option = value[index];
        string = string.split(':option' + index).join(option);
      }
    }
    return string;
  };

  Validator.prototype._parse_params = function(str) {
    var attribute, i, len, options, parsed, rule, rules;
    parsed = {};
    rules = str.split('|');
    for (i = 0, len = rules.length; i < len; i++) {
      rule = rules[i];
      if (rule.indexOf(':') !== -1) {
        attribute = rule.split(':');
        parsed[attribute[0]] = [attribute[1]];
        if (attribute[1].indexOf(',') !== -1) {
          options = attribute[1].split(',');
          parsed[attribute[0]] = options;
        }
      } else {
        parsed[rule] = {};
      }
    }
    return parsed;
  };

  return Validator;

})();

module.exports = Validator;
});

;require.register("core/view", function(exports, require, module) {
var View;

View = (function() {
  function View() {}

  View.prototype.render = function(template, datas) {
    template = require('views/' + template);
    return template(datas);
  };

  return View;

})();

module.exports = View;
});

;require.register("helpers", function(exports, require, module) {
_.mixin({
  capitalize: function(string) {
    return string.charAt(0).toUpperCase() + string.substring(1).toLowerCase();
  }
});
});

;require.register("initialize", function(exports, require, module) {
var Gotham;

Gotham = require('core/gotham');

$(function() {
  return new Gotham.Application().start();
});
});

;require.register("routes", function(exports, require, module) {
module.exports = function(route) {
  return route.match('/', 'front/home');
};
});

;require.register("start", function(exports, require, module) {
if ($('[data-gotham=tooltipster]').length > 0) {
  $('[data-gotham=tooltipster').tooltipster();
}
});

;require.register("validators", function(exports, require, module) {
var Validator;

Validator = require('core/validator');

Validator.prototype.attributes;

Validator.prototype.rule('accepted', function(attribute, value, params) {
  if (value === 'yes' || value === 'on' || value === '1') {
    return true;
  }
  return false;
});

Validator.prototype.rule('alpha', function(attribute, value, params) {
  if (value === void 0 || value === '') {
    return true;
  }
  if (value.match(/^[a-zA-Z]+$/)) {
    return true;
  }
  return false;
});

Validator.prototype.rule('alpha_dash', function(attribute, value, params) {
  if (value === void 0 || value === '') {
    return true;
  }
  if (value.match(/^[a-zA-Z0-9_-]+$/)) {
    return true;
  }
  return false;
});

Validator.prototype.rule('alpha_num', function(attribute, value, params) {
  if (value === void 0 || value === '') {
    return true;
  }
  if (value.match(/^[a-zA-Z0-9]+$/)) {
    return true;
  }
  return false;
});

Validator.prototype.rule('array', function(attribute, value, params) {
  if (_.isArray(value)) {
    return true;
  }
  return false;
});

Validator.prototype.rule('between', function(attribute, value, params) {
  var length;
  if (value === void 0 || value === '') {
    return true;
  }
  length = value.toString().length;
  if (length >= params[0] && length <= params[1]) {
    return true;
  }
  return false;
});

Validator.prototype.rule('boolean', function(attribute, value, params) {
  if (value === true || value === false) {
    return true;
  }
  return false;
});

Validator.prototype.rule('required', function(attribute, value, params) {
  if (!value) {
    return false;
  }
  if (value.length === 0) {
    return false;
  }
  return true;
});

Validator.prototype.rule('email', function(attribute, value, params) {
  var valid_email;
  if (value === void 0 || value === '') {
    return true;
  }
  valid_email = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return valid_email.test(value);
});

Validator.prototype.rule('in', function(attribute, value, params) {
  var i, len, param, success;
  if (value === void 0 || value === '') {
    return true;
  }
  value = value.toString();
  success = false;
  for (i = 0, len = params.length; i < len; i++) {
    param = params[i];
    if (value === param) {
      success = true;
      break;
    }
  }
  return success;
});

Validator.prototype.rule('max', function(attribute, value, params) {
  var constraint;
  if (value === void 0 || value === '') {
    return true;
  }
  value = parseInt(value);
  constraint = parseInt(params[0]);
  if (value > constraint) {
    return false;
  }
  return true;
});

Validator.prototype.rule('min', function(attribute, value, params) {
  var constraint;
  if (value === void 0 || value === '') {
    return true;
  }
  value = parseInt(value);
  constraint = parseInt(params[0]);
  if (value < constraint) {
    return false;
  }
  return true;
});

Validator.prototype.rule('size', function(attribute, value, params) {
  if (value.length !== params[0]) {
    return false;
  }
  return true;
});

Validator.prototype.rule('match', function(attribute, value, params, datas) {
  var field, value_of_field;
  field = params[0];
  if (_.has(datas, field)) {
    value = value.toString();
    value_of_field = datas[field].toString();
    if (value === value_of_field) {
      return true;
    }
  }
  return false;
});

Validator.prototype.rule('different', function(attribute, value, params, datas) {
  var field, value_of_field;
  field = params[0];
  if (_.has(datas, field)) {
    value = value.toString();
    value_of_field = datas[field].toString();
    if (value !== value_of_field) {
      return true;
    }
  }
  return false;
});

Validator.prototype.error('accepted', 'The :attribute must be accepted');

Validator.prototype.error('alpha', 'The :attribute may only contain letters.');

Validator.prototype.error('alpha_dash', 'The :attribute may only contain letters, numbers, and dashes.');

Validator.prototype.error('alpha_num', 'The :attribute may only contain letters and numbers.');

Validator.prototype.error('array', 'The :attribute must be an array.');

Validator.prototype.error('between', 'The :attribute must be between :option0 and :option1 characters.');

Validator.prototype.error('boolean', 'The :attribute must be a boolean.');

Validator.prototype.error('required', 'The :attribute is required.');

Validator.prototype.error('email', 'The :attribute must be a valid email.');

Validator.prototype.error('in', 'The :attribute must be in :options.');

Validator.prototype.error('max', 'The :attribute can\'t be superior to :option0.');

Validator.prototype.error('min', 'The :attribute can\'t be inferior to :option0.');

Validator.prototype.error('size', 'The :attribute must contain :option0 chars.');

Validator.prototype.error('match', 'The :attribute does not match.');

Validator.prototype.error('different', 'The :attribute isn\'t different.');
});

;require.register("views", function(exports, require, module) {
Handlebars.registerHelper('example', function() {
  return 'example';
});
});

;
//# sourceMappingURL=app.js.map