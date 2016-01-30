# Require the controller library of Gotham
Controller = require 'core/controller'


class Orders extends Controller

  ##
  # Before
  #
  # Executed before the run action. You can use
  # @stop() in this method to stop the execution
  # of the controller
  #
  ##
  before: ->

    $('tr[data-href]').tooltipster
      position: 'right'
      theme: 'tooltipster-default'

  ##
  # Run
  #
  # The main entry of the controller.
  # Your code start here
  #
  ##
  run: ->

    @on 'click', 'tr[data-href]', @rowClicked

  rowClicked: (e) ->

    e.preventDefault()

    window.location.href = $(this).data('href')


# Export
module.exports = Orders
