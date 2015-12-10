class @Global

  constructor: ->
    @clouds()
    @autoTab()
    @popover()
    @checkboxes()
    @tooltips()
    @scrollFrequency()
    @scrollMode()
    @childrens()
    @fancyselect()

  fancyselect: ->

    $('[data-toggle=fancyselect]').fancySelect()

  #--------------------------------------------------------------------------
  # Clouds
  #--------------------------------------------------------------------------
  #
  # Animate clouds in the footer
  #
  #
  clouds: ->

    console.log 'clouds'
    # Init position of clouds (px)
    position = 0

    loopClouds = ->

      # Move
      position = position - 180

      # Animate
      $('.footer-clouds').stop().animate
        backgroundPosition: position + "px"
      , 10000, "linear", loopClouds
      return

    loopClouds()

  #--------------------------------------------------------------------------
  # Auto Tab
  #--------------------------------------------------------------------------
  #
  # Auto tab system when you add a hash in the url of the browser
  #
  #
  autoTab: ->
    
    run = false

    unless $('.nav-tabs').length is 0

      hash = window.location.hash.split('#').join('')

      # Fetch all tab-pane
      $('.tab-pane').each ->
        id = $(this).attr('id')

        if id is hash

          $('.nav-tabs a[href=#' + hash + ']').tab('show')

  #--------------------------------------------------------------------------
  # Choose box
  #--------------------------------------------------------------------------
  #
  # When the user choosed the box to buy
  #
  chooseBox: ->

    $('#choose_box a').click (event) ->

      # Block click
      event.preventDefault()

      # Fetch the box id
      box_id = $(this).attr 'id'

      # Change input hidden of the box
      # for php treatment
      $('#box_choice').attr 'value', box_id

      # Submit the form
      $('#choose_box').submit()

  #--------------------------------------------------------------------------
  # Popover
  #--------------------------------------------------------------------------
  #
  # For all .js-popover init the popover system
  #
  popover: ->

    $('.js-popover').popover()

  #--------------------------------------------------------------------------
  # Checkboxes
  #--------------------------------------------------------------------------
  #
  # Init iCheck plugin for different classes 
  #
  checkboxes: ->

    $('input:not(.big)').iCheck
      checkboxClass: 'icheckbox_flat-orange'
      radioClass: 'iradio_flat-orange'

    $('input.big').each ->

      id = $(this).attr('id')
      text =  $('label[for=' + id + ']').text()

      $(this).iCheck
        checkboxClass: 'icheckbox_line-yellow',
        radioClass: 'iradio_line-yellow',
        insert: text

    $('input[class^=frequency-]').each ->

      key = $(this).attr('class').split('frequency-').join('')

      id = $(this).attr('id')
      text =  $('label[for=' + id + ']').text()

      $(this).iCheck
        checkboxClass: 'icheckbox_line-yellow icheckbox_line-yellow-' + key
        radioClass: 'iradio_line-yellow iradio_line-yellow-' + key
        insert: text



  #--------------------------------------------------------------------------
  # Tooltips
  #--------------------------------------------------------------------------
  #
  # Init tooltips of bootstrap for specific elements
  #
  tooltips: ->

    $('[data-toggle=tooltip]').tooltip()

  scrollFrequency: ->

    if $('#js-page-box-frequency').length > 0

      $('input[type=radio]').on 'ifChecked', ->
        # Scroll
        $('html, body').animate({
          scrollTop: $('#after-pipeline').offset().top
        }, 500);

  scrollMode: ->


    if $('#js-page-delivery-mode').length > 0

      $('input[type=radio]').on 'ifChecked', ->
        # Scroll
        $('html, body').animate({
          scrollTop: $('#after-pipeline').offset().top
        }, 500);

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

        $('[data-name=children]:visible').last().next().fadeIn 'slow'

        @children_add()


      $(document).on 'click', '[data-toggle=remove-children]', (e) =>

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
