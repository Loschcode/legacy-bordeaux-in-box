class @Profile

  constructor: ->
    
    if @canRun()
      @read()
      @datatable()

  canRun: ->

    return $('#js-page-profile').length


  read: ->

    # Fetch profiles from the html 
    profiles = $('#profiles-json').html()
    
    # Parse
    profiles = $.parseJSON(profiles)

    # Filter
    profiles = _.indexBy profiles, 'id'

    # When click on the data-profile, display a modal box
    $(document).on 'click', '[data-profile]', ->

      id = $(this).attr('data-profile')

      if id of profiles

        $('#profile-title').html('Abonnement #' + profiles[id].id)

        if _.isEmpty profiles[id].stripe_customer
          profiles[id].stripe_customer = 'Aucun pour le moment'

        $('#profile-stripe').html(profiles[id].stripe_customer)

        $('#profile-contract').html(profiles[id].contract_id)

        $('#profile-edit').attr('href', '/admin/profiles/edit/' + profiles[id].id)
        $('#profile-archive').attr('href', '/admin/profiles/delete/' + profiles[id].id)

        $('#profile-modal').modal('show')

  datatable: ->

    $('[data-filter]').click (e) ->

      e.preventDefault()

      # Check if we reclick on a filter already clicked
      unless $(this).find('i').hasClass('hidden')

        $(this).find('i').addClass('hidden')
        $('#table-profiles').DataTable().column(6).search('').draw()

      else
      
        $('[data-filter]').each ->

          $(this).find('i').addClass('hidden')

        # Fetch search
        search = $(this).data('filter')

        # Display icon
        $(this).find('i').removeClass('hidden')

        # Search and draw the table
        $('#table-profiles').DataTable().column(6).search('^' + search + '$', true, false).draw()



