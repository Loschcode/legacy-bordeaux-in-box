#--------------------------------------------------------------------------
# Config
#--------------------------------------------------------------------------
#
# Sometimes you need to put some configuration variables for your project,
# here it's the right place for that !
#
##

module.exports =

  ##
  # Stripe keys
  ##
  stripe:
    testing: 'pk_test_HNPpbWh3FV4Lw4RmIQqirqsj'
    production: 'pk_live_EhCVbntIqph3ppfNCiN6wq3x'

  ##
  # Translate datatable
  ##
  datatable:
    language:
      fr:      
        sProcessing: 'Traitement en cours...'
        sSearch: 'Rechercher&nbsp;:'
        sLengthMenu: 'Afficher _MENU_ &eacute;l&eacute;ments'
        sInfo: 'Affichage de l\'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments'
        sInfoEmpty: 'Affichage de l\'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment'
        sInfoFiltered: '(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)'
        sInfoPostFix: ''
        sLoadingRecords: '<i class="fa fa-spinner fa-spin"></i> Chargement en cours...'
        sZeroRecords: 'Aucun &eacute;l&eacute;ment &agrave; afficher'
        sEmptyTable: 'Aucune donn&eacute;e disponible dans le tableau'
        oPaginate: 
          sFirst: 'Premier'
          sPrevious: 'Pr&eacute;c&eacute;dent'
          sNext: 'Suivant'
          sLast: 'Dernier'
        oAria:
          sSortAscending: ': activer pour trier la colonne par ordre croissant',
          sSortDescending: ': activer pour trier la colonne par ordre d&eacute;croissant'
