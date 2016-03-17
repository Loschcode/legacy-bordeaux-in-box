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
    @freewallPartners()
    @freewallBoxes()
    @showcase()
    @slider()

  ##
  # Run
  #
  # The main entry of the controller.
  # Your code start here
  #
  ##
  run: ->

  ##
  # When an user click on an anchor, we do an auto
  # smooth scroll.
  ##
  smoothScroll: ->

    smoothScroll.init
      selector: '.js-anchor'

  ##
  # Freewall layout for boxes
  ##
  freewallBoxes: =>

    wall = new freewall("#freewall-boxes")

    wall.reset
      selector: '.js-brick'
      animate: true
      cellW: 220
      cellH: 'auto'


      onResize: ->
        wall.fitWidth()
          
    wall.container.find('.js-brick img').load ->
      wall.fitWidth()
    
    $(window).trigger('resize')

  ##
  # Freewall partners layout
  ##
  freewallPartners: =>

    wall = new freewall("#freewall-partners")

    wall.reset
      selector: '.js-brick',
      animate: true,
      cellW: 220,
      cellH: 'auto',

      onResize: ->
        wall.fitWidth()
          
    wall.container.find('.js-brick img').load ->
      wall.fitWidth()
    
    $(window).trigger('resize')

  ##
  # Init fancybox
  ##
  showcase: =>
    
    $('.js-showcase').fancybox
      helpers:
        overlay:
          locked: false

  ##
  # Init the home slider
  ##
  slider: =>
    $('#slider').lightSlider
      item: 1
      loop: true
      slideMargin: 0
      pager: false
      auto: true
      pause: 5000
      speed: 1000





# Export
module.exports = Index
