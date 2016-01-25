# Require the controller library of Gotham
Controller = require 'core/controller'


class Payment extends Controller
  
  afterPaymentProcessing: false

  ##
  # Before
  #
  # Executed before the run action. You can use
  # @stop() in this method to stop the execution
  # of the controller
  #
  ##
  before: ->

    @initStripe()

  ##
  # Run
  #
  # The main entry of the controller.
  # Your code start here
  #
  ##
  run: ->

    $('#trigger-payment').click (e) =>
      
      # Catch default behavior of click
      e.preventDefault()

      # Security
      unless $(this).prop('disabled') is true
        
        # Add state button
        @displayLoading('En cours de chargement')

        # Open modal
        @handler.open
          name: 'Bordeaux in Box'
          description: 'Commande Box'
          currency: 'eur'
          amount: $('#gotham').data('amount')
          email: $('#gotham').data('customer-email')

  initStripe: =>

    # Configure stripe modal
    @handler = StripeCheckout.configure
      key: _.getStripeKey()
      image: 'https://s3.amazonaws.com/stripe-uploads/acct_14e5CdIIyezb3ziumerchant-icon-1452677496121-bdxinbox.png'
      locale: 'fr'
      token: @afterPayment
      allowRememberMe: true
      
      opened: =>

        @displayLoading('Saisie en cours')

      closed: =>

        setTimeout =>
          unless @afterPaymentProcessing
            @displayDefault()
        , 1500

  afterPayment: (token) =>
    secret = token.id

    @afterPaymentProcessing = true

    @displayLoading('En cours de redirection')

    $('#stripe-token').val(secret)
    $('#payment-form').submit()

  displayLoading: (message) ->

    $('#trigger-payment').prop('disabled', true).addClass('--disabled').html('<i class="fa fa-spinner fa-spin"></i> ' + message)

  displayDefault: =>

    $('#trigger-payment').prop('disabled', false).removeClass('--disabled').html('<i class="fa fa-credit-card"></i> Procéder au paiement sécurisé')



# Export
module.exports = Payment
