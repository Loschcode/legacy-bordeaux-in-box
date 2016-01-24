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
  stripe: {
    testing: 'pk_test_HNPpbWh3FV4Lw4RmIQqirqsj',
    production: 'pk_live_EhCVbntIqph3ppfNCiN6wq3x'
  },
  datatable: {
    language: {
      fr: {
        sProcessing: 'Traitement en cours...',
        sSearch: 'Rechercher&nbsp;:',
        sLengthMenu: 'Afficher _MENU_ &eacute;l&eacute;ments',
        sInfo: 'Affichage de l\'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments',
        sInfoEmpty: 'Affichage de l\'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment',
        sInfoFiltered: '(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)',
        sInfoPostFix: '',
        sLoadingRecords: '<i class="fa fa-spinner fa-spin"></i> Chargement en cours...',
        sZeroRecords: 'Aucun &eacute;l&eacute;ment &agrave; afficher',
        sEmptyTable: 'Aucune donn&eacute;e disponible dans le tableau',
        oPaginate: {
          sFirst: 'Premier',
          sPrevious: 'Pr&eacute;c&eacute;dent',
          sNext: 'Suivant',
          sLast: 'Dernier'
        },
        oAria: {
          sSortAscending: ': activer pour trier la colonne par ordre croissant',
          sSortDescending: ': activer pour trier la colonne par ordre d&eacute;croissant'
        }
      }
    }
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

;require.register("controllers/masterbox/admin/customers/index", function(exports, require, module) {
var Config, Controller, Index,
  bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
  extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

Controller = require('core/controller');

Config = require('config');

Index = (function(superClass) {
  extend(Index, superClass);

  function Index() {
    this.displayMore = bind(this.displayMore, this);
    return Index.__super__.constructor.apply(this, arguments);
  }

  Index.prototype.before = function() {
    return this.table = $('table').DataTable({
      length: false,
      language: Config.datatable.language.fr,
      ajax: $('table').data('request'),
      processing: true,
      serverSide: true,
      order: [[1, 'asc']],
      columns: [
        {
          orderable: false,
          className: 'more-details',
          data: null,
          defaultContent: '<a href="#" class="button button__table"><i class="fa fa-plus-square-o"></i></a>'
        }, {
          data: "id"
        }, {
          data: "full_name"
        }, {
          data: "email"
        }, {
          data: "phone_format"
        }, {
          sortable: false,
          render: (function(_this) {
            return function(data, type, full, meta) {
              var datas;
              datas = {
                link_edit: _.slash($('table').data('edit-customer')) + full.id
              };
              return _this.view('masterbox.admin.customers.actions', datas);
            };
          })(this)
        }
      ]
    });
  };

  Index.prototype.run = function() {
    return this.delayed('click', '.more-details', this.displayMore);
  };

  Index.prototype.displayMore = function(e) {
    var datas, html, row, tr;
    e.preventDefault();
    tr = $(e.currentTarget).closest('tr');
    row = this.table.row(tr);
    if (row.child.isShown()) {
      row.child.hide();
      return tr.find('.more-details i').removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
    } else {
      datas = row.data();
      datas['edit_profile'] = $('table').data('edit-profile');
      html = this.view('masterbox.admin.customers.more', datas);
      row.child(html).show();
      return tr.find('.more-details i').removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
    }
  };

  return Index;

})(Controller);

module.exports = Index;
});

;require.register("controllers/masterbox/customer/purchase/billing-address", function(exports, require, module) {
var BillingAddress, Controller,
  extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

Controller = require('core/controller');

BillingAddress = (function(superClass) {
  extend(BillingAddress, superClass);

  function BillingAddress() {
    return BillingAddress.__super__.constructor.apply(this, arguments);
  }

  BillingAddress.prototype.before = function() {};

  BillingAddress.prototype.run = function() {
    return this.on('click', '#copy', this.copyFormDestination);
  };

  BillingAddress.prototype.copyFormDestination = function(e) {
    var fields;
    e.preventDefault();
    fields = ['city', 'zip', 'address'];
    return _.each(fields, (function(_this) {
      return function(field) {
        var value;
        value = $('[name=destination_' + field + ']').val();
        return $('[name=billing_' + field + ']').val(value);
      };
    })(this));
  };

  return BillingAddress;

})(Controller);

module.exports = BillingAddress;
});

;require.register("controllers/masterbox/customer/purchase/choose-spot", function(exports, require, module) {
var ChooseSpot, Controller,
  extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

Controller = require('core/controller');

ChooseSpot = (function(superClass) {
  extend(ChooseSpot, superClass);

  function ChooseSpot() {
    return ChooseSpot.__super__.constructor.apply(this, arguments);
  }

  ChooseSpot.prototype.before = function() {};

  ChooseSpot.prototype.run = function() {
    return this.on('click', 'label', this.displayGoogleMap);
  };

  ChooseSpot.prototype.displayGoogleMap = function() {
    var id;
    $('[id^=gmap]').addClass('+hidden');
    id = $(this).attr('for');
    if ($('#gmap-' + id).hasClass('+hidden')) {
      return $('#gmap-' + id).stop().hide().removeClass('+hidden').fadeIn();
    }
  };

  return ChooseSpot;

})(Controller);

module.exports = ChooseSpot;
});

;require.register("controllers/masterbox/customer/purchase/payment", function(exports, require, module) {
var Controller, Payment,
  bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
  extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

Controller = require('core/controller');

Payment = (function(superClass) {
  extend(Payment, superClass);

  function Payment() {
    this.displayDefault = bind(this.displayDefault, this);
    this.afterPayment = bind(this.afterPayment, this);
    this.initStripe = bind(this.initStripe, this);
    return Payment.__super__.constructor.apply(this, arguments);
  }

  Payment.prototype.before = function() {
    return this.initStripe();
  };

  Payment.prototype.run = function() {
    return $('#trigger-payment').click((function(_this) {
      return function(e) {
        e.preventDefault();
        if ($(_this).prop('disabled') !== true) {
          _this.displayLoading('En cours de chargement');
          return _this.handler.open({
            name: 'Bordeaux in Box',
            description: 'Commande Box',
            currency: 'eur',
            amount: $('#gotham').data('amount'),
            email: $('#gotham').data('customer-email')
          });
        }
      };
    })(this));
  };

  Payment.prototype.initStripe = function() {
    return this.handler = StripeCheckout.configure({
      key: _.getStripeKey(),
      image: 'https://s3.amazonaws.com/stripe-uploads/acct_14e5CdIIyezb3ziumerchant-icon-1452677496121-bdxinbox.png',
      locale: 'fr',
      token: this.afterPayment,
      allowRememberMe: true,
      opened: (function(_this) {
        return function() {
          return _this.displayDefault();
        };
      })(this)
    });
  };

  Payment.prototype.afterPayment = function(token) {
    var secret;
    secret = token.id;
    this.displayLoading('En cours de redirection');
    $('#stripe-token').val(secret);
    return $('#payment-form').submit();
  };

  Payment.prototype.displayLoading = function(message) {
    return $('#trigger-payment').prop('disabled', true).addClass('--disabled').html('<i class="fa fa-spinner fa-spin"></i> ' + message);
  };

  Payment.prototype.displayDefault = function() {
    return $('#trigger-payment').prop('disabled', false).removeClass('--disabled').html('<i class="fa fa-credit-card"></i> Procéder au paiement sécurisé');
  };

  return Payment;

})(Controller);

module.exports = Payment;
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
var Config;

Config = require('config');

_.mixin({
  getStripeKey: function() {
    var environment;
    environment = $('body').data('environment');
    if (environment === 'production') {
      return Config.stripe.production;
    }
    return Config.stripe.testing;
  }
});

_.mixin({
  slash: function(string) {
    var last;
    last = string.slice(-1);
    if (last === '/') {
      return string;
    }
    return string + '/';
  }
});

_.mixin({
  euro: function(number) {
    return number.toFixed(2) + ' &euro;';
  }
});

_.mixin({
  profileStatus: function(status) {
    switch (status) {
      case 'in-progress':
        return 'En cours de création';
      case 'expired':
        return 'Expiré';
      case 'not-subscribed':
        return 'Non abonné';
      case 'subscribed':
        return 'Abonné';
      default:
        return status;
    }
  }
});

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
      title = 'Attention';
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

;require.register("libraries/admin-sidebar", function(exports, require, module) {
var AdminSidebar;

AdminSidebar = (function() {
  function AdminSidebar() {
    $('#sidebar').on('mouseenter', this.sidebarEnter);
    $('#sidebar').on('mouseleave', this.sidebarLeave);
  }

  AdminSidebar.prototype.sidebarEnter = function() {
    $(this).css('width', '270px');
    return $('#sidebar-brand').css('display', 'block');
  };

  AdminSidebar.prototype.sidebarLeave = function() {
    $(this).css('width', '60px');
    return $('#sidebar-brand').css('display', 'none');
  };

  return AdminSidebar;

})();

module.exports = AdminSidebar;
});

;require.register("start", function(exports, require, module) {
var AdminSidebar, Config;

AdminSidebar = require('libraries/admin-sidebar');

Config = require('config');

$('input, textarea').placeholder();

_.notificationFormErrors();

_.notificationSuccessMessage();

$('.js-chosen').chosen({
  disable_search_threshold: 30
});

$(':checkbox').labelauty();

$(':radio').labelauty();

if ($('#gotham-layout').data('layout') === 'masterbox-admin') {
  new AdminSidebar();
  $('.js-datatable-simple').DataTable({
    length: false,
    language: Config.datatable.language.fr
  });
  $(document).on('click', '.js-confirm-delete', function(e) {
    e.preventDefault();
    return swal({
      type: 'warning',
      title: 'Es-tu sûr ?',
      text: 'La ressource sera supprimé définitivement',
      showCancelButton: true,
      confirmButtonText: "Oui je suis sûr",
      cancelButtonText: "Annuler",
      closeOnConfirm: false,
      showLoaderOnConfirm: true
    }, (function(_this) {
      return function() {
        return window.location.href = $(_this).attr('href');
      };
    })(this));
  });
  $('.js-markdown').meltdown({
    openPreview: true,
    sidebyside: true
  });
}
});

;require.register("validators", function(exports, require, module) {
Validator.errors;

Validator.attributes;
});

;require.register("views/masterbox/admin/customers/actions", function(exports, require, module) {
var __templateData = function (__obj) {
  if (!__obj) __obj = {};
  var __out = [], __capture = function(callback) {
    var out = __out, result;
    __out = [];
    callback.call(this);
    result = __out.join('');
    __out = out;
    return __safe(result);
  }, __sanitize = function(value) {
    if (value && value.ecoSafe) {
      return value;
    } else if (typeof value !== 'undefined' && value != null) {
      return __escape(value);
    } else {
      return '';
    }
  }, __safe, __objSafe = __obj.safe, __escape = __obj.escape;
  __safe = __obj.safe = function(value) {
    if (value && value.ecoSafe) {
      return value;
    } else {
      if (!(typeof value !== 'undefined' && value != null)) value = '';
      var result = new String(value);
      result.ecoSafe = true;
      return result;
    }
  };
  if (!__escape) {
    __escape = __obj.escape = function(value) {
      return ('' + value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    };
  }
  (function() {
    (function() {
      __out.push('<a href="');
    
      __out.push(this.link_edit);
    
      __out.push('" class="button button__table"><i class="fa fa-pencil"></i></a>');
    
    }).call(this);
    
  }).call(__obj);
  __obj.safe = __objSafe, __obj.escape = __escape;
  return __out.join('');
};
if (typeof define === 'function' && define.amd) {
  define([], function() {
    return __templateData;
  });
} else if (typeof module === 'object' && module && module.exports) {
  module.exports = __templateData;
} else {
  __templateData;
}
});

;require.register("views/masterbox/admin/customers/more", function(exports, require, module) {
var __templateData = function (__obj) {
  if (!__obj) __obj = {};
  var __out = [], __capture = function(callback) {
    var out = __out, result;
    __out = [];
    callback.call(this);
    result = __out.join('');
    __out = out;
    return __safe(result);
  }, __sanitize = function(value) {
    if (value && value.ecoSafe) {
      return value;
    } else if (typeof value !== 'undefined' && value != null) {
      return __escape(value);
    } else {
      return '';
    }
  }, __safe, __objSafe = __obj.safe, __escape = __obj.escape;
  __safe = __obj.safe = function(value) {
    if (value && value.ecoSafe) {
      return value;
    } else {
      if (!(typeof value !== 'undefined' && value != null)) value = '';
      var result = new String(value);
      result.ecoSafe = true;
      return result;
    }
  };
  if (!__escape) {
    __escape = __obj.escape = function(value) {
      return ('' + value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    };
  }
  (function() {
    (function() {
      var i, len, profile, ref;
    
      __out.push('<div class="tablechild">\n  <div class="row">\n    <div class="grid-6">\n      <h3 class="tablechild__title">A propos</h3>\n      <strong>Role:</strong> ');
    
      __out.push(this.role_format);
    
      __out.push('<br/>\n      <strong>Total Payé:</strong> ');
    
      __out.push(_.euro(this.turnover));
    
      __out.push('<br/>\n      <strong>Adresse:</strong> \n      ');
    
      if (this.address.length > 0) {
        __out.push('\n        ');
        __out.push(this.address);
        __out.push(', ');
        __out.push(this.city);
        __out.push(' (');
        __out.push(this.zip);
        __out.push(')\n      ');
      } else {
        __out.push('\n        N/A\n      ');
      }
    
      __out.push('\n    </div>\n    <div class="grid-6">\n      <h3 class="tablechild__title">Abonnements</h3>\n      ');
    
      if (this.profiles.length > 0) {
        __out.push('\n        ');
        ref = this.profiles;
        for (i = 0, len = ref.length; i < len; i++) {
          profile = ref[i];
          __out.push('\n          <a class="tablechild__link" href="');
          __out.push(_.slash(this.edit_profile) + profile.id);
          __out.push('">Abonnement #');
          __out.push(__sanitize(profile.id));
          __out.push(' (');
          __out.push(_.profileStatus(profile.status));
          __out.push(')</a><br/>\n        ');
        }
        __out.push('\n      ');
      } else {
        __out.push('\n        Aucun abonnement\n      ');
      }
    
      __out.push('\n    </div>\n  </div>\n</div>');
    
    }).call(this);
    
  }).call(__obj);
  __obj.safe = __objSafe, __obj.escape = __escape;
  return __out.join('');
};
if (typeof define === 'function' && define.amd) {
  define([], function() {
    return __templateData;
  });
} else if (typeof module === 'object' && module && module.exports) {
  module.exports = __templateData;
} else {
  __templateData;
}
});

;
//# sourceMappingURL=app.js.map