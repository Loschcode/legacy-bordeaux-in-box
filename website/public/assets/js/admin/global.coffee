class @Global

  constructor: ->

    @datatables()
    @autoTab()
    @alertRemove()
    @tooltip()
    @summernote()
    @navbar()
    @line_chart()
    @donut_chart()
    @bar_chart()
    @area_chart()
    @lightbox()
    @loading_buttons()
    @bootstrap_confirmation()
    @persistent_tabs()
    @childrens()
    @products()
    @chosen()
    @search_filters()

  search_filters: ->

    if $('#search-filters').length > 0

      $('#search-filters').keyup ->

        $('[data-entry]').show()

        search = $(this).val()

        $('[data-entry]').each ->

          label = $(this).find('label').html()

          unless label.toLowerCase().indexOf(search) >= 0

            console.log "hide"
            $(this).hide()

          
  chosen: ->

    if $('[data-toggle=chosen]').length > 0

      $('[data-toggle=chosen]').chosen()
      $('.chosen-container').css 'width', '200px'
      # When change tab must reload
      ###
      $('a[data-toggle="tab"]').on 'shown.bs.tab', ->
        
        alert 
        $('[data-toggle=chosen]').trigger('chosen:close')
      ###


  products: ->

    if $('#master_partner_product_id').length > 0

      $('#checkbox-similar').hide().find('[name=past_advanced_filters]').prop('checked', false)

      if $('#master_partner_product_id').val() != '0'

        $('#checkbox-similar').show().find('[name=past_advanced_filters]').prop('checked', true)


      $('#master_partner_product_id').on 'change', ->

        # Reset all fields
        $('#partner_id').val('')
        $('#name').val('')
        $('#description').val('')
        $('#weight').val('')
        $('#size').val($('#size option:first').val())
        $('#category').val('')
        $('#checkbox-similar').hide().find('[name=past_advanced_filters]').prop('checked', false)

        # Fetch id
        id = $(this).val()

        unless id is '0'

          # Request
          $.post '/api/get-partner-product/' + id, (response) ->

            if response.success

              datas = response.datas

              $('#partner_id').val(datas.partner_id)
              $('#name').val(datas.name)
              $('#description').val(datas.description)
              $('#weight').val(datas.weight)
              $('#size').val(datas.size)
              $('#category').val(datas.category)

              # Display the checkbox for filters
              $('#checkbox-similar').show().find('[name=past_advanced_filters]').prop('checked', true)



  childrens: ->
    
    if $('#add-children').length > 0

      # Loop all forms and hide and remove the secure hidden class
      $('[data-name=children]').hide().removeClass('hidden')
      
      $('[data-name=children]').each ->

        value = $(this).find('input:first').val()
        console.log value
        console.log(value)
        
        if value.length > 0

          # Show the first
          $(this).show()

      if $('[data-name=children]:visible').length is 0

        # Show the first one
        $('[data-name=children]').first().show()


      $('#add-children').on 'click', (e) =>

        e.preventDefault()

        $('[data-name=children]:visible').last().next().show()

        @children_add()


      $(document).on 'click', '#remove-children', (e) =>

        e.preventDefault()

        # Remove details
        $(e.currentTarget).parent().parent().parent().find('select').val('')
        $(e.currentTarget).parent().parent().parent().find('input:first').val('')

        # Hide
        $(e.currentTarget).parent().parent().parent().hide()

        @children_add()



  children_add: ->

    total = $('[data-name=children]').length
    visible = $('[data-name=children]:visible').length

    if visible is total

      # Hide link
      $('#add-children').hide()

    else 

      $('#add-children').show()
  persistent_tabs: ->


    $('a[data-toggle="tab"]').on 'shown.bs.tab', (e) ->

      href = $(e.target).attr("href").substr(1)

      $('#' + href).attr('id', href + '-fake')

      window.location.hash = $(e.target).attr("href")

      $('#' + href + '-fake').attr('id', href)


  navbar: ->

    # Init
    @navbar_hide()

    $('#sidebar-wrapper').hover =>
      @navbar_display()
    , =>
      @navbar_hide()

  navbar_hide: ->

    # Hide admin title
    $('.sidebar-brand a').hide()
    $('#sidebar-wrapper').css('width', '68px')
    $('#wrapper').css('padding-left', '68px')

  navbar_display: ->

    # Display admin navbar
    $('#sidebar-wrapper').css('width', '250px')
    $('.sidebar-brand a').show()

  datatables: ->

    # General options for datables
    base_options = 
      language:
        processing:     "Traitement en cours..."
        search:         "Rechercher&nbsp;:"
        lengthMenu:    "Afficher _MENU_ &eacute;l&eacute;ments"
        info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments"
        infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments"
        infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)"
        infoPostFix:    ""
        loadingRecords: "Chargement en cours..."
        zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher"
        emptyTable:     "Aucune donnée disponible dans le tableau"
        paginate:
            first:      "Premier"
            previous:   "Pr&eacute;c&eacute;dent"
            next:       "Suivant"
            last:       "Dernier"
      aria: 
          sortAscending:  ": activer pour trier la colonne par ordre croissant"
          sortDescending: ": activer pour trier la colonne par ordre décroissant"
      bInfo: true
      bDeferRender: true
      bLengthChange: false
      bSort: true
      aaSorting: []


    # Options for boxes
    boxes_options = 
      bAutoWidth: false
      aoColumns: [
        { "sWidth": "10%"},
        { "sWidth": "30%"},
        { "sWidth": "15%"},
        { "sWidth": "25%"},
        { "sWidth": "20%"}
      ]

    # Options for profiles
    profiles_options = 
      bAutoWidth: false
      aoColumns: [
        { "sWidth": "10%"},
        { "sWidth": "20%"},
        { "sWidth": "5%"},
        { "sWidth": "5%"},
        { "sWidth": "15%"},
        { "sWidth": "15%"},
        { "sWidth": "15%"},
        { "sWidth": "15%"},
        { "sWidth": "15%"},
        { "sWidth": "15%"}
      ]

    customize_options =
      bPaginate: false

    # Draw datatables
    $('.js-datas').DataTable base_options

    # Boxes table width
    $('#table-boxes').DataTable _.extend base_options, boxes_options

    # Profiles table width
    $('#table-profiles').DataTable _.extend base_options, profiles_options
    
    $('#table-customize-products').DataTable
      bPaginate: false
        
  autoTab: ->
    
    unless $('.nav-tabs').length is 0

      hash = window.location.hash.split('#').join('')

      # Fetch all tab-pane
      $('.tab-pane').each ->
        id = $(this).attr('id')

        if id is hash

          $('.nav-tabs a[href=#' + hash + ']').tab('show')

  alertRemove: ->

    if $('.js-alert-remove').length > 0

      setTimeout (->
        $('.js-alert-remove').fadeOut()

        return
      ), 4000

  tooltip: ->

    $('[data-toggle=tooltip]').tooltip()

  line_chart: ->

    list_options = [

      'lineColors',
      'lineWidth',
      'pointSize',
      'pointFillColors',
      'pointStrokeColors',
      'ymax',
      'ymin',
      'smooth',
      'hideHover',
      'parseTime',
      'units',
      'postUnits',
      'preUnits',
      'xLabels',
      'xLabelAngle',
      'goals',
      'goalStrokeWidth',
      'goalLineColors',
      'events',
      'eventStrokeWidth',
      'eventLineColors',
      'continuousLine',
      'axes',
      'grid',
      'gridTextColor',
      'gridTextSize',
      'gridTextFamily',
      'gridTextWidth',
      'fillOpacity'
    ]

    # Check if we have some line chart graphs to render
    if $('[data-graph=line-chart]').length > 0

      $('[data-graph=line-chart').each ->

        # Fetch the config
        config = $(this).attr('data-config')

        # Encode the config
        config = $.parseJSON(config)

        # Set the height of the graph based on the config
        if _.has(config, 'height')
          $('#' + config.id).css('height', config.height)

        # Basic graph, just after we will set options if they exists
        base_graph =
          resize: true
          element: config.id
          data: config.data
          xkey: config.xkey
          ykeys: config.ykeys
          labels: config.labels

        for option in list_options

          if _.has(config, option)  

            object = {}
            object[option] = config[option]

            base_graph = _.extend(base_graph, object)

        graph = new Morris.Line(base_graph)

        # Morris bug with bootstrap tabs, here it's a hack :)
        $('a[data-toggle="tab"]').on 'shown.bs.tab', =>

          graph.redraw()

      # Another hack
      setTimeout ->
        $(document).trigger('resize')
      , 200

  donut_chart: ->

    list_options = [
      'colors'
    ]

    # Check if we have some donut chart graphs to render
    if $('[data-graph=donut-chart]').length > 0

      $('[data-graph=donut-chart').each ->

        # Fetch the config
        config = $(this).attr('data-config')

        # Encode the config
        config = $.parseJSON(config)

        # Set the height of the graph based on the config
        if _.has(config, 'height')
          $('#' + config.id).css('height', config.height)

        # Basic graph, just after we will set options if they exists
        base_graph =
          resize: true
          element: config.id
          data: config.data

        for option in list_options

          if _.has(config, option)  

            object = {}
            object[option] = config[option]

            base_graph = _.extend(base_graph, object)

        graph = new Morris.Donut(base_graph)

        # Morris bug with bootstrap tabs, here it's a hack :)
        $('a[data-toggle="tab"]').on 'shown.bs.tab', =>

          graph.redraw()

      # Another hack
      setTimeout ->
        $(document).trigger('resize')
      , 200

  bar_chart: ->

    list_options = [
      'barColors',
      'stacked',
      'hideHover',
      'axes',
      'grid',
      'gridTextColor',
      'gridTextSize',
      'gridTextFamily',
      'gridTextWeight'
    ]

    # Check if we have some bar chart graphs to render
    if $('[data-graph=bar-chart]').length > 0

      $('[data-graph=bar-chart').each ->

        # Fetch the config
        config = $(this).attr('data-config')

        # Encode the config
        config = $.parseJSON(config)

        # Set the height of the graph based on the config
        if _.has(config, 'height')
          $('#' + config.id).css('height', config.height)

        # Basic graph, just after we will set options if they exists
        base_graph =
          resize: true
          element: config.id
          data: config.data
          xkey: config.xkey
          ykeys: config.ykeys
          labels: config.labels

        for option in list_options

          if _.has(config, option)  

            object = {}
            object[option] = config[option]

            base_graph = _.extend(base_graph, object)

        graph = new Morris.Bar(base_graph)

        # Morris bug with bootstrap tabs, here it's a hack :)
        $('a[data-toggle="tab"]').on 'shown.bs.tab', =>

          graph.redraw()

      # Another hack
      setTimeout ->
        $(document).trigger('resize')
      , 200


  area_chart: ->

    list_options = [

      'lineColors',
      'lineWidth',
      'pointSize',
      'pointFillColors',
      'pointStrokeColors',
      'ymax',
      'ymin',
      'smooth',
      'hideHover',
      'parseTime',
      'units',
      'postUnits',
      'preUnits',
      'xLabels',
      'xLabelAngle',
      'goals',
      'goalStrokeWidth',
      'goalLineColors',
      'events',
      'eventStrokeWidth',
      'eventLineColors',
      'continuousLine',
      'axes',
      'grid',
      'gridTextColor',
      'gridTextSize',
      'gridTextFamily',
      'gridTextWidth',
      'fillOpacity',
      'behaveLikeLine'
    ]

    # Check if we have some line chart graphs to render
    if $('[data-graph=area-chart]').length > 0

      $('[data-graph=area-chart').each ->

        # Fetch the config
        config = $(this).attr('data-config')

        # Encode the config
        config = $.parseJSON(config)

        # Set the height of the graph based on the config
        if _.has(config, 'height')
          $('#' + config.id).css('height', config.height)

        # Basic graph, just after we will set options if they exists
        base_graph =
          resize: true
          element: config.id
          data: config.data
          xkey: config.xkey
          ykeys: config.ykeys
          labels: config.labels

        for option in list_options

          if _.has(config, option)  

            object = {}
            object[option] = config[option]

            base_graph = _.extend(base_graph, object)

        graph = new Morris.Area(base_graph)
        console.log graph

        # Morris bug with bootstrap tabs, here it's a hack :)
        $('a[data-toggle="tab"]').on 'shown.bs.tab', =>

          graph.redraw()

      # Another hack
      setTimeout ->
        $(document).trigger('resize')
      , 200


  lightbox: ->


    $(document).on 'create-lightboxes', ->

      template = """
        
        <div class="modal fade" id="building-modal">
          <div class="modal-dialog">
            <div class="modal-content">
            </div>
          </div>
        </div>

      """

      if $('[data-lightbox]').length > 0

        $('[data-lightbox]').each ->

          # Fetch lightbox id
          id = $(@).data('lightbox-id')

          # Check if id doesn't exist
          unless $('#' + id).length > 0 

            # Create html lightbox
            $('body').append(template)

            # Change id modal
            $('#building-modal').attr('id', id)


    $(document).trigger('create-lightboxes')

    $(document).on 'click', '[data-lightbox]', ->

      console.log 'open dude'
      id = $(@).data('lightbox-id')
      url = $(@).data('lightbox-url')

      $.get url, (data) =>

        $('#' + id).find('.modal-content').html(data)

        # Maybe the lightbox have lightboxes to open too
        $(document).trigger('create-lightboxes')

        $('#' + id).modal('show')


  loading_buttons: ->

    $(document).on 'click', 'a.spyro-btn', (e) ->

      unless $(this).hasClass('no-loader')
        unless $(this).attr('disabled') is "disabled"

          value = $(this).html()

          $(this).attr('disabled', true);

          $(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');

          setTimeout =>

            $(this).attr('disabled', false);
            $(this).html(value)

          , 5000



  summernote: ->

    $(".js-summernote").summernote toolbar: [
      
      #[groupname, [button list]]
      [
        "style"
        [
          "bold"
          "italic"
          "underline"
        ]
      ]
      [
        "font"
        ["strikethrough"]
      ]
      [
        "fontsize"
        ["fontsize"]
      ]
      [
        "color"
        ["color"]
      ]
      [
        "para"
        [
          "ul"
          "ol"
          "paragraph"
        ]
      ]
      [
        "height"
        ["height"]
      ]
      [
        "insert" 
        ['link']
      ]

    ]

  bootstrap_confirmation: ->

    $('[data-toggle=confirmation]').confirmation
      btnOkLabel: "Oui"
      btnCancelLabel: "Non"
      btnOkClass: "spyro-btn spyro-btn-sm spyro-btn-primary no-loader"
      btnCancelClass: "spyro-btn spyro-btn-sm spyro-btn-default no-loader"

