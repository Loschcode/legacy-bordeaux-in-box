class Masterbox

  constructor: ->

    @responsiveMenu()
    @alertNoBoxes()

  ##
  # Set responsive to the menu
  ##
  responsiveMenu: ->

    if $('.js-menu-sidebar').length > 0
      $('.js-menu-sidebar').slicknav
        label: "SECTIONS"
    else
      $('.js-menu').slicknav()


  ##
  # When we don't have anymore boxes and the user clicks 
  # on the button to order, we display a sweet alert
  ##
  alertNoBoxes: (e) =>

    $('.js-no-boxes').on 'click', =>

      swal
        title: 'Désolé'
        text: 'Il ne reste plus aucune box pour ce mois-ci'
        type: 'error'
        confirmButtonColor: '#D83F66'
        html: true

module.exports = Masterbox