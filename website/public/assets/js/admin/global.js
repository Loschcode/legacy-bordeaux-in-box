(function() {
  this.Global = (function() {
    function Global() {
      this.datatables();
      this.autoTab();
      this.alertRemove();
      this.tooltip();
      this.summernote();
      this.navbar();
      this.line_chart();
      this.donut_chart();
      this.bar_chart();
      this.area_chart();
      this.lightbox();
      this.loading_buttons();
      this.bootstrap_confirmation();
      this.persistent_tabs();
      this.childrens();
      this.products();
      this.chosen();
      this.search_filters();
    }

    Global.prototype.search_filters = function() {
      if ($('#search-filters').length > 0) {
        return $('#search-filters').keyup(function() {
          var search;
          $('[data-entry]').show();
          search = $(this).val();
          return $('[data-entry]').each(function() {
            var label;
            label = $(this).find('label').html();
            if (!(label.toLowerCase().indexOf(search) >= 0)) {
              console.log("hide");
              return $(this).hide();
            }
          });
        });
      }
    };

    Global.prototype.chosen = function() {
      if ($('[data-toggle=chosen]').length > 0) {
        $('[data-toggle=chosen]').chosen();
        return $('.chosen-container').css('width', '200px');

        /*
        $('a[data-toggle="tab"]').on 'shown.bs.tab', ->
          
          alert 
          $('[data-toggle=chosen]').trigger('chosen:close')
         */
      }
    };

    Global.prototype.products = function() {
      if ($('#master_partner_product_id').length > 0) {
        $('#checkbox-similar').hide().find('[name=past_advanced_filters]').prop('checked', false);
        if ($('#master_partner_product_id').val() !== '0') {
          $('#checkbox-similar').show().find('[name=past_advanced_filters]').prop('checked', true);
        }
        return $('#master_partner_product_id').on('change', function() {
          var id;
          $('#partner_id').val('');
          $('#name').val('');
          $('#description').val('');
          $('#weight').val('');
          $('#size').val($('#size option:first').val());
          $('#category').val('');
          $('#checkbox-similar').hide().find('[name=past_advanced_filters]').prop('checked', false);
          id = $(this).val();
          if (id !== '0') {
            return $.post('/api/get-partner-product/' + id, function(response) {
              var datas;
              if (response.success) {
                datas = response.datas;
                $('#partner_id').val(datas.partner_id);
                $('#name').val(datas.name);
                $('#description').val(datas.description);
                $('#weight').val(datas.weight);
                $('#size').val(datas.size);
                $('#category').val(datas.category);
                return $('#checkbox-similar').show().find('[name=past_advanced_filters]').prop('checked', true);
              }
            });
          }
        });
      }
    };

    Global.prototype.childrens = function() {
      if ($('#add-children').length > 0) {
        $('[data-name=children]').hide().removeClass('hidden');
        $('[data-name=children]').each(function() {
          var value;
          value = $(this).find('input:first').val();
          console.log(value);
          console.log(value);
          if (value.length > 0) {
            return $(this).show();
          }
        });
        if ($('[data-name=children]:visible').length === 0) {
          $('[data-name=children]').first().show();
        }
        $('#add-children').on('click', (function(_this) {
          return function(e) {
            e.preventDefault();
            $('[data-name=children]:visible').last().next().show();
            return _this.children_add();
          };
        })(this));
        return $(document).on('click', '#remove-children', (function(_this) {
          return function(e) {
            e.preventDefault();
            $(e.currentTarget).parent().parent().parent().find('select').val('');
            $(e.currentTarget).parent().parent().parent().find('input:first').val('');
            $(e.currentTarget).parent().parent().parent().hide();
            return _this.children_add();
          };
        })(this));
      }
    };

    Global.prototype.children_add = function() {
      var total, visible;
      total = $('[data-name=children]').length;
      visible = $('[data-name=children]:visible').length;
      if (visible === total) {
        return $('#add-children').hide();
      } else {
        return $('#add-children').show();
      }
    };

    Global.prototype.persistent_tabs = function() {
      return $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var href;
        href = $(e.target).attr("href").substr(1);
        $('#' + href).attr('id', href + '-fake');
        window.location.hash = $(e.target).attr("href");
        return $('#' + href + '-fake').attr('id', href);
      });
    };

    Global.prototype.navbar = function() {
      this.navbar_hide();
      return $('#sidebar-wrapper').hover((function(_this) {
        return function() {
          return _this.navbar_display();
        };
      })(this), (function(_this) {
        return function() {
          return _this.navbar_hide();
        };
      })(this));
    };

    Global.prototype.navbar_hide = function() {
      $('.sidebar-brand a').hide();
      $('#sidebar-wrapper').css('width', '68px');
      return $('#wrapper').css('padding-left', '68px');
    };

    Global.prototype.navbar_display = function() {
      $('#sidebar-wrapper').css('width', '250px');
      return $('.sidebar-brand a').show();
    };

    Global.prototype.datatables = function() {
      var base_options, boxes_options, customize_options, profiles_options;
      base_options = {
        language: {
          processing: "Traitement en cours...",
          search: "Rechercher&nbsp;:",
          lengthMenu: "Afficher _MENU_ &eacute;l&eacute;ments",
          info: "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
          infoEmpty: "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
          infoFiltered: "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
          infoPostFix: "",
          loadingRecords: "Chargement en cours...",
          zeroRecords: "Aucun &eacute;l&eacute;ment &agrave; afficher",
          emptyTable: "Aucune donnée disponible dans le tableau",
          paginate: {
            first: "Premier",
            previous: "Pr&eacute;c&eacute;dent",
            next: "Suivant",
            last: "Dernier"
          }
        },
        aria: {
          sortAscending: ": activer pour trier la colonne par ordre croissant",
          sortDescending: ": activer pour trier la colonne par ordre décroissant"
        },
        bInfo: true,
        bDeferRender: true,
        bLengthChange: false,
        bSort: true,
        aaSorting: []
      };
      boxes_options = {
        bAutoWidth: false,
        aoColumns: [
          {
            "sWidth": "10%"
          }, {
            "sWidth": "30%"
          }, {
            "sWidth": "15%"
          }, {
            "sWidth": "25%"
          }, {
            "sWidth": "20%"
          }
        ]
      };
      profiles_options = {
        bAutoWidth: false,
        aoColumns: [
          {
            "sWidth": "10%"
          }, {
            "sWidth": "20%"
          }, {
            "sWidth": "5%"
          }, {
            "sWidth": "5%"
          }, {
            "sWidth": "15%"
          }, {
            "sWidth": "15%"
          }, {
            "sWidth": "15%"
          }, {
            "sWidth": "15%"
          }, {
            "sWidth": "15%"
          }, {
            "sWidth": "15%"
          }
        ]
      };
      customize_options = {
        bPaginate: false
      };
      $('.js-datas').DataTable(base_options);
      $('#table-boxes').DataTable(_.extend(base_options, boxes_options));
      $('#table-profiles').DataTable(_.extend(base_options, profiles_options));
      return $('#table-customize-products').DataTable({
        bPaginate: false
      });
    };

    Global.prototype.autoTab = function() {
      var hash;
      if ($('.nav-tabs').length !== 0) {
        hash = window.location.hash.split('#').join('');
        return $('.tab-pane').each(function() {
          var id;
          id = $(this).attr('id');
          if (id === hash) {
            return $('.nav-tabs a[href=#' + hash + ']').tab('show');
          }
        });
      }
    };

    Global.prototype.alertRemove = function() {
      if ($('.js-alert-remove').length > 0) {
        return setTimeout((function() {
          $('.js-alert-remove').fadeOut();
        }), 4000);
      }
    };

    Global.prototype.tooltip = function() {
      return $('[data-toggle=tooltip]').tooltip();
    };

    Global.prototype.line_chart = function() {
      var list_options;
      list_options = ['lineColors', 'lineWidth', 'pointSize', 'pointFillColors', 'pointStrokeColors', 'ymax', 'ymin', 'smooth', 'hideHover', 'parseTime', 'units', 'postUnits', 'preUnits', 'xLabels', 'xLabelAngle', 'goals', 'goalStrokeWidth', 'goalLineColors', 'events', 'eventStrokeWidth', 'eventLineColors', 'continuousLine', 'axes', 'grid', 'gridTextColor', 'gridTextSize', 'gridTextFamily', 'gridTextWidth', 'fillOpacity'];
      if ($('[data-graph=line-chart]').length > 0) {
        $('[data-graph=line-chart').each(function() {
          var base_graph, config, graph, object, option, _i, _len;
          config = $(this).attr('data-config');
          config = $.parseJSON(config);
          if (_.has(config, 'height')) {
            $('#' + config.id).css('height', config.height);
          }
          base_graph = {
            resize: true,
            element: config.id,
            data: config.data,
            xkey: config.xkey,
            ykeys: config.ykeys,
            labels: config.labels
          };
          for (_i = 0, _len = list_options.length; _i < _len; _i++) {
            option = list_options[_i];
            if (_.has(config, option)) {
              object = {};
              object[option] = config[option];
              base_graph = _.extend(base_graph, object);
            }
          }
          graph = new Morris.Line(base_graph);
          return $('a[data-toggle="tab"]').on('shown.bs.tab', (function(_this) {
            return function() {
              return graph.redraw();
            };
          })(this));
        });
        return setTimeout(function() {
          return $(document).trigger('resize');
        }, 200);
      }
    };

    Global.prototype.donut_chart = function() {
      var list_options;
      list_options = ['colors'];
      if ($('[data-graph=donut-chart]').length > 0) {
        $('[data-graph=donut-chart').each(function() {
          var base_graph, config, graph, object, option, _i, _len;
          config = $(this).attr('data-config');
          config = $.parseJSON(config);
          if (_.has(config, 'height')) {
            $('#' + config.id).css('height', config.height);
          }
          base_graph = {
            resize: true,
            element: config.id,
            data: config.data
          };
          for (_i = 0, _len = list_options.length; _i < _len; _i++) {
            option = list_options[_i];
            if (_.has(config, option)) {
              object = {};
              object[option] = config[option];
              base_graph = _.extend(base_graph, object);
            }
          }
          graph = new Morris.Donut(base_graph);
          return $('a[data-toggle="tab"]').on('shown.bs.tab', (function(_this) {
            return function() {
              return graph.redraw();
            };
          })(this));
        });
        return setTimeout(function() {
          return $(document).trigger('resize');
        }, 200);
      }
    };

    Global.prototype.bar_chart = function() {
      var list_options;
      list_options = ['barColors', 'stacked', 'hideHover', 'axes', 'grid', 'gridTextColor', 'gridTextSize', 'gridTextFamily', 'gridTextWeight'];
      if ($('[data-graph=bar-chart]').length > 0) {
        $('[data-graph=bar-chart').each(function() {
          var base_graph, config, graph, object, option, _i, _len;
          config = $(this).attr('data-config');
          config = $.parseJSON(config);
          if (_.has(config, 'height')) {
            $('#' + config.id).css('height', config.height);
          }
          base_graph = {
            resize: true,
            element: config.id,
            data: config.data,
            xkey: config.xkey,
            ykeys: config.ykeys,
            labels: config.labels
          };
          for (_i = 0, _len = list_options.length; _i < _len; _i++) {
            option = list_options[_i];
            if (_.has(config, option)) {
              object = {};
              object[option] = config[option];
              base_graph = _.extend(base_graph, object);
            }
          }
          graph = new Morris.Bar(base_graph);
          return $('a[data-toggle="tab"]').on('shown.bs.tab', (function(_this) {
            return function() {
              return graph.redraw();
            };
          })(this));
        });
        return setTimeout(function() {
          return $(document).trigger('resize');
        }, 200);
      }
    };

    Global.prototype.area_chart = function() {
      var list_options;
      list_options = ['lineColors', 'lineWidth', 'pointSize', 'pointFillColors', 'pointStrokeColors', 'ymax', 'ymin', 'smooth', 'hideHover', 'parseTime', 'units', 'postUnits', 'preUnits', 'xLabels', 'xLabelAngle', 'goals', 'goalStrokeWidth', 'goalLineColors', 'events', 'eventStrokeWidth', 'eventLineColors', 'continuousLine', 'axes', 'grid', 'gridTextColor', 'gridTextSize', 'gridTextFamily', 'gridTextWidth', 'fillOpacity', 'behaveLikeLine'];
      if ($('[data-graph=area-chart]').length > 0) {
        $('[data-graph=area-chart').each(function() {
          var base_graph, config, graph, object, option, _i, _len;
          config = $(this).attr('data-config');
          config = $.parseJSON(config);
          if (_.has(config, 'height')) {
            $('#' + config.id).css('height', config.height);
          }
          base_graph = {
            resize: true,
            element: config.id,
            data: config.data,
            xkey: config.xkey,
            ykeys: config.ykeys,
            labels: config.labels
          };
          for (_i = 0, _len = list_options.length; _i < _len; _i++) {
            option = list_options[_i];
            if (_.has(config, option)) {
              object = {};
              object[option] = config[option];
              base_graph = _.extend(base_graph, object);
            }
          }
          graph = new Morris.Area(base_graph);
          console.log(graph);
          return $('a[data-toggle="tab"]').on('shown.bs.tab', (function(_this) {
            return function() {
              return graph.redraw();
            };
          })(this));
        });
        return setTimeout(function() {
          return $(document).trigger('resize');
        }, 200);
      }
    };

    Global.prototype.lightbox = function() {
      $(document).on('create-lightboxes', function() {
        var template;
        template = "\n<div class=\"modal fade\" id=\"building-modal\">\n  <div class=\"modal-dialog\">\n    <div class=\"modal-content\">\n    </div>\n  </div>\n</div>\n";
        if ($('[data-lightbox]').length > 0) {
          return $('[data-lightbox]').each(function() {
            var id;
            id = $(this).data('lightbox-id');
            if (!($('#' + id).length > 0)) {
              $('body').append(template);
              return $('#building-modal').attr('id', id);
            }
          });
        }
      });
      $(document).trigger('create-lightboxes');
      return $(document).on('click', '[data-lightbox]', function() {
        var id, url;
        console.log('open dude');
        id = $(this).data('lightbox-id');
        url = $(this).data('lightbox-url');
        return $.get(url, (function(_this) {
          return function(data) {
            $('#' + id).find('.modal-content').html(data);
            $(document).trigger('create-lightboxes');
            return $('#' + id).modal('show');
          };
        })(this));
      });
    };

    Global.prototype.loading_buttons = function() {
      return $(document).on('click', 'a.spyro-btn', function(e) {
        var value;
        if (!$(this).hasClass('no-loader')) {
          if ($(this).attr('disabled') !== "disabled") {
            value = $(this).html();
            $(this).attr('disabled', true);
            $(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
            return setTimeout((function(_this) {
              return function() {
                $(_this).attr('disabled', false);
                return $(_this).html(value);
              };
            })(this), 5000);
          }
        }
      });
    };

    Global.prototype.summernote = function() {
      return $(".js-summernote").summernote({
        toolbar: [["style", ["bold", "italic", "underline"]], ["font", ["strikethrough"]], ["fontsize", ["fontsize"]], ["color", ["color"]], ["para", ["ul", "ol", "paragraph"]], ["height", ["height"]], ["insert", ['link']]]
      });
    };

    Global.prototype.bootstrap_confirmation = function() {
      return $('[data-toggle=confirmation]').confirmation({
        btnOkLabel: "Oui",
        btnCancelLabel: "Non",
        btnOkClass: "spyro-btn spyro-btn-sm spyro-btn-primary no-loader",
        btnCancelClass: "spyro-btn spyro-btn-sm spyro-btn-default no-loader"
      });
    };

    return Global;

  })();

}).call(this);
