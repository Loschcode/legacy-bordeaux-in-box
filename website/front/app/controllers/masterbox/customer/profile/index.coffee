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
