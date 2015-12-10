class @Dashboard

  constructor: ->

    @run()

  run: ->

    $('#resumes').hide()
    
    $('#hide').click (e) ->

      e.preventDefault()

      if $('#resumes').css('display') is 'none'
        $('#resumes').show()
        $('#hide').html('Cacher les résumés')
      else
        $('#resumes').hide()
        $('#hide').html('Afficher les résumés')


new Dashboard();