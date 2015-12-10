class @Box

  #--------------------------------------------------------------------------
  # Constructor
  #--------------------------------------------------------------------------
  #
  # Check if it's possible to run this class. Always called when you invoke
  # the class.
  #
  #
	constructor: ->

    # Flags
    @_currentBox = false

    if @page('#js-page-box')
      @init()
      @events()

    if @page('#js-page-box-form')
      @dateAllow()



  #--------------------------------------------------------------------------
  # Page
  #--------------------------------------------------------------------------
  #
  # Check if page id exists in the page.
  #
  #
  page: (page) ->
    return $(page).length

  init: ->

    # Find the first box
    boxes = @_boxesJson()
    first = _.first(boxes)

    @displayBox(first.id)


  #--------------------------------------------------------------------------
  # Events
  #--------------------------------------------------------------------------
  #
  # Some events associated to this class.
  #
  #
  events: ->

    self = @

    # When you click on a picture of box
    $('.js-box-picture').click (event) ->

      event.preventDefault()
        
      # Fetch id and split useless informations
      id = $(this).attr('id').split('box-').join('')

      # Display the right box 
      self.displayBox(id)

      # Scroll
      $('html, body').animate({
        scrollTop: $("#after-pipeline").offset().top
      }, 500);


    $('#box-buy').click (event) ->

      event.preventDefault()

      $('#box_choice').attr('value', self._currentBox)

      $('#choose_box').submit()


  #--------------------------------------------------------------------------
  # Display Box
  #--------------------------------------------------------------------------
  #
  # Will manage the display of a box
  #
  #
  displayBox: (id) ->

    unless @_currentBox is id

      boxes = @_boxesJson()
      boxes = _.indexBy boxes, 'id'

      # Set flag
      @_currentBox = id

      # Add to all pictures boxes an inactive class
      $('.js-box-picture').find('img').addClass('inactive')

      # Set active state for the picture
      $('#box-' + id).find('img').removeClass('inactive')
      
      @setName(boxes[id].title)
      @setTitle(boxes[id].title)
      @setDescription(boxes[id].description)
      @setButton()

  setTitle: (title) ->

    $('#box-title').stop().hide().html(title).fadeIn('fast')

  setDescription: (description) ->

    $('#box-description').stop().hide().html(description).fadeIn('slow')

  setName: (name) ->

    $('#box-name').html(name)

  setButton: ->

    $('#box-buy').removeClass('hidden')



  #--------------------------------------------------------------------------
  # Boxes Json
  #--------------------------------------------------------------------------
  #
  # Extract json datas from the div #boxes-json, parse as an object indexed
  # by id.
  #
  #
  _boxesJson: ->

    boxes = $('#boxes-json').html()
    boxes = $.parseJSON(boxes)

    return boxes

  dateAllow: ->


    # Block numeric
    $('[data-type=date]').alphanum
      disallow: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"
      allow: "/"

    # Block max length
    $('[data-type=date]').attr
      maxLength: 10

    $('[data-type=date]').keyup (e) ->

      value = $(this).val()

      # When it's not a backspace
      unless e.keyCode is 8

        # Auto add /
        if value.length is 2 or value.length is 5

          $(this).val(value + '/')

 


  effectsForm: ->



    #
    # Note : Uglycode, really not DRY, lot of copy/paste, i must refacto this one day
    #

    ###
    if $('#already-answered').length is 1

      #
      # Init
      #  
      $('.js-block-form').each ->

        # Resolve type
        type = $(this).find('[id^=type-]').attr('id').split('type-').join('')

        if (type == 'textarea')

          # Check textarea
          value = $(this).find('textarea').val()
          name = $(this).find('textarea').attr('name')

          unless value? and value

            console.log 'error'
            $('#success-' + name).hide()
            $('#error-' + name).fadeIn()
            $(this).find('textarea').addClass('error')

          else

            console.log 'success'
            $('#error-' + name).hide()
            $('#success-' + name).fadeIn()


        if (type == 'text')

          # Check textarea
          value = $(this).find('input[type=text]').val()
          name = $(this).find('input[type=text]').attr('name')

          unless value? and value

            console.log 'error'
            $('#success-' + name).hide()
            $('#error-' + name).fadeIn()
            $(this).find('input[type=text]').addClass('error')

          else

            console.log 'success'
            $('#error-' + name).hide()
            $('#success-' + name).fadeIn()

        if (type == 'radiobutton')

          countChecked = $(this).find('input[type=radio]:checked').length
          name = $(this).find('input[type=radio]').attr('name')

          if countChecked is 0

            # error
            $('#success-' + name).hide()
            $('#error-' + name).fadeIn()
            
          else

            # success
            $('#error-' + name).hide()
            $('#success-' + name).fadeIn()

        if (type == 'checkbox')


          name = $(this).find('input[type=checkbox]').attr('name')

          # error
          $('#success-' + name).fadeIn()
          

    #
    # Text
    #
    $('input[type=text], textarea').focusout ->

      value = $(this).val()
      name = $(this).attr('name')

      value = $.trim(value)

      unless value? and value

        $('#success-' + name).hide()
        $('#error-' + name).fadeIn()
        $(this).addClass('error')

      else

        console.log 'success'
        $('#error-' + name).hide()
        $('#success-' + name).fadeIn()
        $(this).removeClass('error')

    #
    # Radio
    #
    $('input[type="radio"]').on 'ifChanged', ->

      countChecked = $(this).parent().parent().parent().find('input[type=radio]:checked').length

      console.log(countChecked)

      name = $(this).attr('name')

      if countChecked is 0

        # error
        $('#success-' + name).hide()
        $('#error-' + name).fadeIn()

      else

        # success
        $('#error-' + name).hide()
        $('#success-' + name).fadeIn()

    #
    # Checkboxes
    #
    $('input[type="checkbox"]').on 'ifChanged', ->

      # success
      $(this).parent().parent().parent().find('[id^=error]').hide()
      $(this).parent().parent().parent().find('[id^=success]').fadeIn()

    ###





