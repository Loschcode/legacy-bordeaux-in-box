class @Contact

  constructor: ->
    
    if @canRun()
      @read()

  canRun: ->

    return $('#js-page-contact').length


  read: ->

    $.get '/api/contacts', (response) ->


      datas = $.parseJSON(response)

      datas = _.indexBy datas, 'id'

      $(document).on 'click', '[data-contact]', ->

        id = $(this).attr('data-contact')

        if id of datas
          
          $('#contact-title').html('Prise de contact #' + datas[id].id)
          $('#contact-from').html(datas[id].email).attr('href', 'mailto:' + datas[id].email)
          $('#contact-to').html(datas[id].recipient).attr('href', 'mailto' + datas[id].recipient)
          $('#contact-message').html(_.unescape datas[id].clean_message)
          $('#contact-archive').attr('href', '/admin/logs/delete/' + datas[id].id)
          $('#contact-modal').modal('show')

