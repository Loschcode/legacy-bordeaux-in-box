class @Bip

  constructor: ->

    if @canRun()
      @run()

  canRun: ->

    return $('#js-page-bip').length

  run: ->

    @initCounter()


  initCounter: ->

    $.get '/api/orders-count', (datas) =>

      result = datas.count

      @changeCounter(result)

      # Run the bip system
      setInterval =>
        @checkCounter()
      , 10000

  checkCounter: ->

    count = $('#counter').attr('data-value')

    $.get '/api/orders-count', (datas) => 

      console.log 'Debug new count : ' + datas.count
      console.log 'Debug old count : ' + count

      if parseInt(datas.count) > parseInt(count)

        @playMusic()
        @changeCounter(datas.count)

  changeCounter: (value) ->

    $('#counter').html('<h1>' + value + ' commandes</h1>')
    $('#counter').attr('data-value', value)

  playMusic: ->

    audio = $("audio")[0]
    audio.play()
