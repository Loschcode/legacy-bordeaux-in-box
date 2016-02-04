###
# Admin Bootstrap for masterbox section
###
AdminSidebar = require 'libraries/admin-sidebar'
Config = require 'config'

class Admin 

  constructor: ->

    @sidebar()
    @modal()
    @datatable()
    @deleteConfirm()
    @markdownEditor()

  ##
  # Handle the effects of the sidebar
  ##
  sidebar: ->

    new AdminSidebar()

  ##
  # Init the modal system
  ##
  modal: ->

    $.modal.defaults =
      escapeClose: true
      clickClose: true
      closeText: 'Close'
      closeClass: ''
      showClose: true
      modalClass: "modal"
      spinnerHtml: '<i class="fa fa-refresh fa-spin"></i>'
      showSpinner: true
      fadeDuration: null
      fadeDelay: 1.0
    
    $('[data-modal]').on 'click', (e) ->
      e.preventDefault();

      $(this).modal()

  ##
  # Init default datable
  ##
  datatable: ->

    $('.js-datatable-simple').DataTable
      length: false
      language: Config.datatable.language.fr

  ##
  # Popup a sweet alert when you want to delete 
  # something 
  ##
  deleteConfirm: ->

    $(document).on 'click', '.js-confirm-delete', (e) ->

      e.preventDefault();

      swal
        type: 'warning'
        title: 'Es-tu sûr ?'
        text: 'La ressource sera supprimé définitivement'
        showCancelButton: true
        confirmButtonText: "Oui je suis sûr", 
        cancelButtonText: "Annuler"
        closeOnConfirm: false
        showLoaderOnConfirm: true
      , =>

        window.location.href = $(this).attr('href')

  ##
  # Renders a markdown editor where you want
  ##
  markdownEditor: ->

    if $('.js-markdown').length > 0
      new SimpleMDE
        element: $('.js-markdown')[0]
        spellChecker: false


module.exports = Admin