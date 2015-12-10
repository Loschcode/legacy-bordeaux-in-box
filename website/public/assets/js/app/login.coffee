class @Login

  #--------------------------------------------------------------------------
  # Constructor
  #--------------------------------------------------------------------------
  #
  # Always called when the class is runned
  #
  constructor: ->

    if $('#js-page-login').length

      @init()


  init: ->

    @focusEmail()

  focusEmail: ->

    $('[name=email]').focus()

