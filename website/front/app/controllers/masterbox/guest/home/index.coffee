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

    @smoothScroll()

  ##
  # Run
  #
  # The main entry of the controller.
  # Your code start here
  #
  ##
  run: ->

    @on 'click', '.js-no-boxes', @alertNoBoxes

  ##
  # When we don't have anymore boxes and the user clicks 
  # on the button to order, we display a sweet alert
  ##
  alertNoBoxes: (e) =>

    e.preventDefault()

    swal
      title: $('#gotham').data('no-boxes-title')
      text: $('#gotham').data('no-boxes-text')
      type: 'error'
      confirmButtonColor: '#D83F66'
      html: true

  ##
  # When an user click on an anchor, we do an auto
  # smooth scroll.
  ##
  smoothScroll: ->

    smoothScroll.init
      selector: '.js-anchor'



# Export
module.exports = Index
