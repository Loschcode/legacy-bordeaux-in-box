#--------------------------------------------------------------------------
# Start
#--------------------------------------------------------------------------
#
# Gotham will run after that file the "router" system. It's the right place
# to put some code to execute globally like the init of jQuery plugins, etc.
##
#
AdminSidebar = require 'libraries/admin-sidebar'

#--------------------------------------------------------------------------
# Global elements
#--------------------------------------------------------------------------
#

##
# Polify placeholders for old browsers
##
$('input, textarea').placeholder()

##
# Notify errors from froms validated by Laravel
##
_.notificationFormErrors()

##
# Notify success message returned by Laravel
##
_.notificationSuccessMessage()

##
# Chosen select form
##
$('.js-chosen').chosen
  disable_search_threshold: 30

##
# Labelauty (Prettify checkboxes and radios html tags)
##
$(':checkbox').labelauty()
$(':radio').labelauty()


#--------------------------------------------------------------------------
# MasterBox admin elements
#--------------------------------------------------------------------------
#
if $('#gotham-layout').data('layout') is 'masterbox-admin'

  # Manage sidebar hover / unhover
  new AdminSidebar()

  # Datatable 
  $('.js-datatable-simple').DataTable
    length: false
    language:
      lengthMenu: "Afficher _MENU_ résultats par page"
      zeroRecords: "Aucun enregistrement trouvé"
      info: "Page _PAGE_ sur _PAGES_"
      infoEmpty: "Aucun enregistrement disponible"
      infoFiltered: "(filtré sur _MAX_ enregistrements)"
      search: 'Chercher: '

  # Delete confirm
  $('.js-confirm-delete').on 'click', (e) ->

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


