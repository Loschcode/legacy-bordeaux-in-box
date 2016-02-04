##
# Bootstrap for the front section of the admin (guest, customer, etc ...)
##
class Front 

  constructor: ->

    @responsiveMenu()

  ##
  # Set responsive to the menu
  ##
  responsiveMenu: ->

    if $('.js-menu-sidebar').length > 0
      $('.js-menu-sidebar').slicknav
        label: "SECTIONS"
    else
      $('.js-menu').slicknav()

module.exports = Front