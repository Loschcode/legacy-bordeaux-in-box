class @Card

  #--------------------------------------------------------------------------
  # Constructor
  #--------------------------------------------------------------------------
  #
  # Runned always when class called
  #
  constructor: (stripeKey) ->

    @stripeKey = stripeKey

    # Check if this controller must be run or not
    if @canRun()
      @init()

  #--------------------------------------------------------------------------
  # Can Run
  #--------------------------------------------------------------------------
  #
  # Check if we run this controller, based of the id "js-page-card"
  #
  canRun: ->

    return $('#js-page-card').length

  #--------------------------------------------------------------------------
  # Init
  #--------------------------------------------------------------------------
  #
  # Controller runned, we will run listeners (events) and we will init stripe
  #
  init: ->

    @events()
    @initRestrictInputs()
    @initStripe()

  #--------------------------------------------------------------------------
  # Events
  #--------------------------------------------------------------------------
  #
  # Some listeners
  #
  events: ->

    # When the user added his credit card and wants really to
    # update
    $('#payment-form').submit (event) =>

      event.preventDefault()
      @update()

  #--------------------------------------------------------------------------
  # Init stripe
  #--------------------------------------------------------------------------
  #
  # Init stripe with the key
  #
  initStripe: ->

    Stripe.setPublishableKey(@stripeKey)

  #--------------------------------------------------------------------------
  # Init restricts inputs
  #--------------------------------------------------------------------------
  #
  # Restricts inputs of the stripe modal
  #
  initRestrictInputs: ->

    $('#card').numeric
      allowPlus           : false
      allowMinus          : false
      allowThouSep        : false
      allowDecSep         : false
      allowLeadingSpaces  : false
      maxDigits           : 16

    $('#cvc').numeric
      allowPlus           : false
      allowMinus          : false
      allowThouSep        : false
      allowDecSep         : false
      allowLeadingSpaces  : false
      maxDigits           : 3

    $('#expiration').alphanum
      allowNumeric: true
      allow: '/'
      disallow: 'abcdefghijklmnopqrstuvwxyz'
      allowSpace: false
      allowUpper: false
      maxLength: 5

  #--------------------------------------------------------------------------
  # Pay
  #--------------------------------------------------------------------------
  #
  # When the form to pay is submited this method is runned
  #
  update: ->

    # Disable button to avoid repeated clicks
    $('#trigger-update').prop('disabled', true)

    # Run validation form
    if @validationForm()

      # Create stripe token to collect datas
      @stripeCreateToken()

    else

      $('#trigger-update').prop('disabled', false)


  #--------------------------------------------------------------------------
  # Validation form
  #--------------------------------------------------------------------------
  #
  # Will check if the stripe form is valid
  #
  validationForm: ->

    @resetErrors()

    # Collect datas of the form
    form = 
      card: $('#card').val()
      expiration: $('#expiration').val()
      cvc: $('#cvc').val()
      password: $('#password').val()

    unless form.password.length > 0

      @displayError('Le mot de passe est requis')
      return false

    # Check valid card number
    unless Stripe.card.validateCardNumber form.card

      @displayError('Format de carte invalide')
      return false

    # Check ccv
    unless Stripe.card.validateCVC form.cvc

      @displayError('Format ccv invalide')
      return false

    # Check if we can find "/" in expiration
    if form.expiration.indexOf('/') is '-1'

      @displayError('Format expiration invalide')
      return false

    # Check length of expiration (must be 5)
    unless form.expiration.length is 5

      @displayError('Format expiration invalide')
      return false

    # Check expiraton 
    month = form.expiration.slice(0, 2)
    year = form.expiration.slice(3, 5)

    unless Stripe.card.validateExpiry(month, year)

      @displayError('Expiration invalide')
      return false

    return true

  #--------------------------------------------------------------------------
  # Stripe create token
  #--------------------------------------------------------------------------
  #
  # Create a token via stripe api js and add in the form a new hidden input
  # with the token generated.
  #
  stripeCreateToken: ->

    $('#trigger-update').html('<i class="fa fa-circle-o-notch fa-spin"></i>')

    exp_month = $('#expiration').val().slice(0, 2)
    exp_year = $('#expiration').val().slice(3, 5)

    Stripe.card.createToken

      number: $('#card').val()
      cvc: $('#cvc').val()
      exp_month: exp_month
      exp_year: exp_year

    , (status, response) ->

      # Response good
      if status is 200

        # Add hidden stripe token into the form
        token = response.id
        $('#stripe-token').val(token)

        $('#payment-form').unbind('submit').submit()

      else
        # Manage here errors ...

  #--------------------------------------------------------------------------
  # Display error
  #--------------------------------------------------------------------------
  #
  # Add for the selector wanted the error
  #
  displayError: (error) ->

    $('#errors').html('<div class="spyro-alert spyro-alert-danger">' + error + '</div>')
    $('html, body').animate
      scrollTop: $('#errors').offset().top

  #--------------------------------------------------------------------------
  # Reset errors
  #--------------------------------------------------------------------------
  #
  # Clean all errors
  #
  resetErrors: ->

    $('#errors').html('')

