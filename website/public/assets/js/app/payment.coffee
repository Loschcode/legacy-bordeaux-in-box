class @Payment

  #--------------------------------------------------------------------------
  # Constructor
  #--------------------------------------------------------------------------
  #
  # Runned always when class called
  #
  constructor: (stripeKey) ->

    # Key used by stripe to give a token
    @stripeKey = stripeKey
    
    # Check if this controller must be run or not
    if @canRun()
      @init()

  #--------------------------------------------------------------------------
  # Can Run
  #--------------------------------------------------------------------------
  #
  # Check if we run this controller, based of the id "js-page-payment"
  #
  canRun: ->

    return $('#js-page-payment').length

  #--------------------------------------------------------------------------
  # Init
  #--------------------------------------------------------------------------
  #
  # Controller runned, we will run listeners (events) and we will init stripe
  #
  init: ->

    @initStripe()
    @events()

  #--------------------------------------------------------------------------
  # Events
  #--------------------------------------------------------------------------
  #
  # Some listeners
  #
  events: ->
    $('#trigger-payment').click (e) =>
      
      # Add state button
      @displayLoading()

      # Catch default behavior of click
      e.preventDefault()

      # Open modal
      @handler.open
        name: 'Bordeaux in Box'
        description: 'Commande Box'
        currency: 'eur'
        amount: $('#payment-form').data('price')

  initStripe: =>

    # Configure stripe modal
    @handler = StripeCheckout.configure
      key: @stripeKey
      image: 'https://s3.amazonaws.com/stripe-uploads/acct_14e5CdIIyezb3ziumerchant-icon-1452677496121-bdxinbox.png'
      locale: 'fr'
      token: @afterPayment
      allowRememberMe: false
      closed: ->
        $('#trigger-payment').html $('#trigger-payment').data('text')

  afterPayment: (token) =>
    secret = token.id

    $('#stripe-token').val(secret)
    $('#payment-form').submit()

  displayLoading: () ->

    $('#trigger-payment').html('<i class="fa fa-spinner fa-spin"></i>')


  ###
      var handler = StripeCheckout.configure({
         key: 'pk_test_HNPpbWh3FV4Lw4RmIQqirqsj',
         image: 'https://s3.amazonaws.com/stripe-uploads/acct_14e5CdIIyezb3ziumerchant-icon-1452677496121-bdxinbox.png',
         locale: 'auto',
         token: function(token) {
           // Use the token to create the charge with a server-side script.
           // You can access the token ID with `token.id`
         }
       });

       $('#customButton').on('click', function(e) {
         // Open Checkout with further options
         handler.open({
           name: 'La Petite Box',
           description: '2 widgets',
           currency: "eur",
           amount: 2000
         });
         e.preventDefault();
       });

       // Close Checkout on page navigation
       $(window).on('popstate', function() {
         handler.close();
       });
  ###




###
class @Payment

  #--------------------------------------------------------------------------
  # Constructor
  #--------------------------------------------------------------------------
  #
  # Runned always when class called
  #
  constructor: (stripeKey) ->

    # Key used by stripe to give a token
    @stripeKey = stripeKey
    
    # Check if this controller must be run or not
    if @canRun()
      @init()

  #--------------------------------------------------------------------------
  # Can Run
  #--------------------------------------------------------------------------
  #
  # Check if we run this controller, based of the id "js-page-payment"
  #
  canRun: ->

    return $('#js-page-payment').length

  #--------------------------------------------------------------------------
  # Init
  #--------------------------------------------------------------------------
  #
  # Controller runned, we will run listeners (events) and we will init stripe
  #
  init: ->

    @events()
    @initStripe()
    @initRestrictInputs()

  #--------------------------------------------------------------------------
  # Events
  #--------------------------------------------------------------------------
  #
  # Some listeners
  #
  events: ->

    self = @

    # Open modal stripe-like when the user wants to pay
    $('#trigger-payment').click (event) =>

      event.preventDefault()
      @openModal()

    # Simple trick to manage focuses
    $('.stripe-component .form-control').click ->

      self.addFocus(@)

    # Simple trick to manage focuses
    $('.stripe-component .form-control').focusout ->

      self.removeFocus(@)

    # When the wants to close the stripe-like modal
    $('#trigger-close').click (event) =>

      event.preventDefault()
      @closeModal()

    # When the user added his credit card and wants really to
    # pay
    $('#payment-form').submit (event) =>

      event.preventDefault()
      @pay()

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
  pay: ->

    # Disable button to avoid repeated clicks
    $('#trigger-pay').prop('disabled', true)

    # Run validation form
    if @validationForm()

      # Create stripe token to collect datas
      @stripeCreateToken()

    else

      $('#trigger-pay').prop('disabled', false)


  #--------------------------------------------------------------------------
  # Validation form
  #--------------------------------------------------------------------------
  #
  # Will check if the stripe form is valid
  #
  validationForm: ->

    @resetErrors()

    success = true

    # Collect datas of the form
    form = 
      card: $('#card').val()
      expiration: $('#expiration').val()
      cvc: $('#cvc').val()

    # Check valid card number
    unless Stripe.card.validateCardNumber form.card

      @displayError('#card')
      success = false

    # Check ccv
    unless Stripe.card.validateCVC form.cvc

      @displayError('#cvc')
      success = false

    # Check if we can find "/" in expiration
    if form.expiration.indexOf('/') is '-1'

      @displayError('#expiration')
      success = false

    # Check length of expiration (must be 5)
    unless form.expiration.length is 5

      @displayError('#expiration')
      success = false

    # Check expiraton 
    month = form.expiration.slice(0, 2)
    year = form.expiration.slice(3, 5)

    unless Stripe.card.validateExpiry(month, year)

      @displayError('#expiration')
      success = false


    return success

  #--------------------------------------------------------------------------
  # Stripe create token
  #--------------------------------------------------------------------------
  #
  # Create a token via stripe api js and add in the form a new hidden input
  # with the token generated.
  #
  stripeCreateToken: ->

    exp_month = $('#expiration').val().slice(0, 2)
    exp_year = $('#expiration').val().slice(3, 5)

    Stripe.card.createToken

      number: $('#card').val()
      cvc: $('#cvc').val()
      exp_month: exp_month
      exp_year: exp_year

    , (status, response) ->

      console.log status 
      # Response good
      if status is 200

        # Add hidden stripe token into the form
        token = response.id
        $('#stripe-token').val(token)

        $('#trigger-pay').html('<i class="fa fa-circle-o-notch fa-spin"></i>')
        $('#payment-form').unbind('submit').submit()

      else
        # Manage here errors ...

  #--------------------------------------------------------------------------
  # Display error
  #--------------------------------------------------------------------------
  #
  # Add for the selector wanted a new error state
  #
  displayError: (selector) ->

    $(selector).addClass('has-error state-default')

  #--------------------------------------------------------------------------
  # Reset errors
  #--------------------------------------------------------------------------
  #
  # Remove the state error for every inputs
  #
  resetErrors: ->

    $('input').removeClass('has-error state-default')

  #--------------------------------------------------------------------------
  # Add Focus
  #--------------------------------------------------------------------------
  #
  # Add class "focus" to a selector
  #
  addFocus: (object) ->

    $(object).addClass 'focus'
    $(object).removeClass 'has-error state-default'

  #--------------------------------------------------------------------------
  # Remove focus
  #--------------------------------------------------------------------------
  #
  # Remove focus from an object
  #
  removeFocus: (object) ->

    $(object).removeClass 'focus'

  #--------------------------------------------------------------------------
  # Open modal
  #--------------------------------------------------------------------------
  #
  # Display the modal with bootstrap js api
  #
  openModal: ->

    $('#modal-payment').modal 'show'

  #--------------------------------------------------------------------------
  # Close modal
  #--------------------------------------------------------------------------
  #
  # Close modal via the bootstrap js api
  #
  closeModal: ->

    $('#modal-payment').modal 'hide'
###





