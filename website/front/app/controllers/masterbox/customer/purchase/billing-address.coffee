# Require the controller library of Gotham
Controller = require 'core/controller'


class BillingAddress extends Controller

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

    @on 'click', '#copy', @copyFormDestination

  ##
  # Copy the form destination to the form billing
  ##
  copyFormDestination: (e) ->

    e.preventDefault()

    fields = ['city', 'zip', 'address']

    _.each fields, (field) =>

      # Get value of the field in the form
      value = $('[name=destination_' + field + ']').val()

      # Paste it
      $('[name=billing_' + field + ']').val(value)

# Export
module.exports = BillingAddress
