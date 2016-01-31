# Require the controller library of Gotham
Controller = require 'core/controller'


class BoxForm extends Controller

  ##
  # Before
  #
  # Executed before the run action. You can use
  # @stop() in this method to stop the execution
  # of the controller
  #
  ##
  before: ->
   
    # Display the first question
    @showQuestion(1, false)
    @currentQuestion = 1


  ##
  # Run
  #
  # The main entry of the controller.
  # Your code start here
  #
  ##
  run: ->

    @on 'submit', 'form', @formSubmited
    @on 'click', ':radio', @labelClicked

  formSubmited: (e) =>

    e.preventDefault()

    @postAddAnswer()

  labelClicked: (e) =>

    if @isQuestionRadioButton(@currentQuestion)

      @postAddAnswer()

  postAddAnswer: =>

    #@showLoading()

    datas = @fetchDatasCurrentQuestion()

    console.log datas

    $.post '/customer/purchase/box-form', datas, (response) ->

      console.log response


    #@showNextQuestion()


  showNextQuestion: =>

    if @hasNextQuestion(@currentQuestion)

      @hideQuestion(@currentQuestion)
      @showQuestion(@currentQuestion+1)
      @currentQuestion = @currentQuestion + 1

    else 
      alert "no more questions"


  showQuestion: (position, fadeIn) =>

    if fadeIn is false
      $('[id=question-' + position + ']').removeClass('+hidden')
    else
      $('[id=question-' + position + ']').fadeIn().removeClass('+hidden')


  hideQuestion: (position) =>

    $('[id=question-' + position + ']').addClass('+hidden')


  hasNextQuestion: (currentQuestion) =>

    nextQuestion = currentQuestion + 1

    if $('[id=question-' + nextQuestion + ']').length > 0

      return true

    return false

  isQuestionRadioButton: (question) =>

    type = $('#question-' + question).data('type')

    if type is 'radiobutton'
      return true

    return false

  showLoading: =>

    $('#question-' + @currentQuestion).find('button').prop('disabled', true).addClass('--disabled').html('<i class="fa fa-spin fa-circle-o-notch"></i> Enregistrer')

  fetchDatasCurrentQuestion: =>

    return @fetchDatasQuestion(@currentQuestion)

  fetchDatasQuestion: (question) =>

    syphon = new Syphon()

    return syphon.get('#question-' + question + ' form')

    

# Export
module.exports = BoxForm
