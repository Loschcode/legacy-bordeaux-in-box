class @Spot

  #--------------------------------------------------------------------------
  # Constructor
  #--------------------------------------------------------------------------
  #
  # Always called when the class is runned
  #
  constructor: ->

    if $('#js-page-spot').length

      @init()


  init: ->

    @checkboxes()

  checkboxes: ->

    # Hide all google maps buttons
    $('[id^=gm]').hide().removeClass('hidden')

    # Apply style checkbox for all checkboxes
    $('input.choose-spot').each ->

      id = $(this).attr('id')
      text =  $('label[for=' + id + ']').text()

      $(this).iCheck
        checkboxClass: 'icheckbox_line-yellow',
        radioClass: 'iradio_line-yellow',
        insert: text

    # Init google maps for the spot already checked
    $('.choose-spot').each ->

      checked = $(this).attr('checked')

      if checked is 'checked'

        spotId = $(this).parent().parent().attr('id').split('spot-').join('')

        $('#gm-' + spotId).fadeIn()

    # When we click on a spot we will display the google maps
    $('input.choose-spot').on 'ifChecked', (event) ->

      $('[id^=gm]').hide()

      spotId = $(this).parent().parent().attr('id').split('spot-').join('') 

      $('#gm-' + spotId).fadeIn()