# Require the controller library of Gotham
Controller = require 'core/controller'


class Order extends Controller

  ##
  # Before
  #
  # Executed before the run action. You can use
  # @stop() in this method to stop the execution
  # of the controller
  #
  ##
  before: ->

    # Init the card widget
    @initCard()

    # Set the key for stripe
    Stripe.setPublishableKey(_.getStripeKey());

    # Autoscroll
    if window.location.hash
      
      if $(window.location.hash).length > 0
        smoothScroll.animateScroll(null, window.location.hash)

          
  ##
  # Run
  #
  # The main entry of the controller.
  # Your code start here
  #
  ##
  run: ->

    @on 'submit', '#form-edit-card', @submitCard

  ##
  # Setup the widget for the card form
  ##
  initCard: ->

    # Fetch last digits of the default card used
    lastDigits = _.trim($('#gotham').data('card-last-digits'))

    # If it exists
    if _.isEmpty(lastDigits)
      number = '**** **** **** ****'
    else
      number = '**** **** **** ' + lastDigits

    # Setup the card "widget"
    new Card
      form: '#form-edit-card'
      container: '.card'
      debug: false
      formSelectors:
        numberInput: '[name=card]',
        expiryInput: '[name=expiration]'
        cvcInput: '[name=ccv]'
      messages:
        validDate: 'EXPIRE\nA FIN',
        monthYear: 'MM/AA'
      placeholders: 
        name: ''
        number: number

  submitCard: (e) =>

    e.preventDefault()

    # Use syphon library of gotham
    syphon = new Syphon()

    # Fetch inputs
    card = syphon.exclude('_token').get '#form-edit-card'

    # Clean old errors if any
    @cleanErrors(card)
    @cleanErrorStripe()

    # Set rules
    rules = 
      card: 'required|valid_card_number'
      expiration: 'required|valid_card_expiry'
      ccv: 'required|valid_card_cvc'

    # Make validation
    validation = new Validator()
    validation.make(card, rules)

    if validation.fails()
      
      @displayErrors(card, validation)

    else

      # Generate stripe token
      @generateStripeToken()

  generateStripeToken: =>

    # Use syphon library of gotham
    syphon = new Syphon()

    # Fetch inputs
    card = syphon.exclude('_token').get '#form-edit-card'

    # Display loader to avoid repeated click
    @displayLoader()

    Stripe.card.createToken
      number: card.card
      cvc: card.ccv
      exp_month: _.trim(card.expiration.split('/')[0])
      exp_year: _.trim(card.expiration.split('/')[1])

    , (status, response) =>

      console.log response

      # Hide loader
      @hideLoader()

      # Error
      unless status is 200

        @displayErrorStripe(response)
        return

      # Success, fill the stripe token form
      $('[name=stripeToken]').val response.id

      # Now ask for the password
      @askPassword()


  askPassword:  =>

    # Ask for old password
    swal
      title: 'Mot de passe'
      text: 'Veuillez renseigner votre mot de passe'
      type: 'input'
      confirmButtonColor: '#D83F66'
      showCancelButton: true
      cancelButtonText: 'Annuler'
      closeOnConfirm: false
      showLoaderOnConfirm: true
      inputType: 'password'

    , (value) =>

      if value is false
        return false

      if value is ""
        swal.showInputError('Le mot de passe est requis')
        return false

      # Change value 
      $('#form-edit-card').find('[name=old_password]').val(value)

      # Submit form
      $('#form-edit-card').get(0).submit()


  displayLoader: ->

    $('#commit').prop('disabled', true).addClass('--disabled')

  hideLoader: ->

    $('#commit').prop('disabled', false).removeClass('--disabled')


  displayErrorStripe: (response) ->

    $('#error-stripe').text 'Un problème est survenue, veuillez vérifier votre saisie.'

  cleanErrorStripe: ->

    $('#error-stripe').text ''

  displayErrors: (inputs, validation) ->

    _.forEach inputs, (value, key) ->
      
      if validation.errors.has(key) and $('#errors-' + key).length > 0

        $('#errors-' + key).text(validation.errors.first(key))

  cleanErrors: (inputs) ->

    _.forEach inputs, (value, key) ->
      
      if $('#errors-' + key).length > 0

        $('#errors-' + key).text ''






# Export
module.exports = Order
