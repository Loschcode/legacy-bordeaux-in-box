# Require the controller library of Gotham
Controller = require 'core/controller'


class Index extends Controller

  ##
  # Before
  #
  # Executed before the run action. You can use
  # @stop() in this method to stop the execution
  # of the controller
  #
  ##
  before: ->

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

    @on 'submit', '#form-edit-email', @askPassword
    @on 'submit', '#form-edit-password', @askPassword
    @on 'submit', '#form-edit-billing', @askPassword
    @on 'submit', '#form-edit-destination', @askPassword
    @on 'submit', '#form-edit-spot', @askPassword
    @on 'click', 'label', @displayGoogleMap

  displayGoogleMap: ->

    # Hide each google map buttons
    $('[id^=gmap]').addClass('+hidden')

    id = $(this).attr('for')

    # If it's not already displayed
    if $('#gmap-' + id).hasClass('+hidden')

      # Display it
      $('#gmap-' + id).stop().hide().removeClass('+hidden').fadeIn()

  askPassword: (e) ->

    # Catch default action
    e.preventDefault()

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
      $(this).find('[name=old_password]').val(value)

      # Submit
      $(this).off('submit').submit()



# Export
module.exports = Index
