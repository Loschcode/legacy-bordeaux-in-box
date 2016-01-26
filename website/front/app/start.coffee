#--------------------------------------------------------------------------
# Start
#--------------------------------------------------------------------------
#
# Gotham will run after that file the "router" system. It's the right place
# to put some code to execute globally like the init of jQuery plugins, etc.
##
#
AdminSidebar = require 'libraries/admin-sidebar'
Config = require 'config'

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

##
# Tooltipster
##
$('.js-tooltip').tooltipster()

#--------------------------------------------------------------------------
# MasterBox admin elements
#--------------------------------------------------------------------------
#
if $('#gotham-layout').data('layout') is 'masterbox-admin'

  ##
  # Manage sidebar hover / unhover
  ##
  new AdminSidebar()

  ##
  # Datatable 
  ##
  $('.js-datatable-simple').DataTable
    length: false
    language: Config.datatable.language.fr

  ##
  # Delete confirm
  ##
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
  # Markdown
  ##
  $('.js-markdown').meltdown
    openPreview: true
    sidebyside: true


