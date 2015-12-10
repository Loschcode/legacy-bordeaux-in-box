Gotham = require 'core/gotham'

class Front_Home extends Gotham.Controller

  ##
  # Dom References
  #
  # Don't use a raw string in your jquery selectors.
  # Stock them here.
  #  
  ##
  el:
    anchorsScroll: 'a[href*=#]:not([href=#])'

  ##
  # Before
  #
  # It's the first method called by the controller.
  # You can init some jquery plugins, provide an 
  # init state for your controller, etc ...
  # You can use the method @stop() to not continue
  # the execution of the controller.
  # 
  ##
  before: ->

  ##
  # Run
  #
  # This method is executed just after the before() method. 
  # It's the right place to put your events via the @on()
  # method.
  #
  ##
  run: ->

    @on 'click', @el.anchorsScroll, @smoothScroll

  ##
  # SmoothScroll
  #
  # Run a smoothscroll for all hashtags (#)
  #
  ##
  smoothScroll: ->

    if location.pathname.replace(/^\//, "") is @pathname.replace(/^\//, "") or location.hostname is @hostname

      target = $(@hash)
      target = (if target.length then target else $("[name=" + @hash.slice(1) + "]"))
      if target.length
        $("html,body").animate
          scrollTop: target.offset().top
        , 1000

module.exports = Front_Home