# Require the controller library of Gotham
Controller = require 'core/controller'


class BoxForm extends Controller

  processingAjax: false

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

      unless @processingAjax
        @postAddAnswer()
      else

        # Block the selection
        e.preventDefault()

  postAddAnswer: =>

    unless @processingAjax

      @showLoading()
      @cleanError()

      datas = @fetchDatasCurrentQuestion()

      console.log datas

      @processingAjax = true

      $.post '/customer/purchase/box-form', datas, (response) =>

        @processingAjax = false
        @showDefault()

        unless response.success

          @showError(response.errors) 

        else
          
          @showNextQuestion()


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

    if @isQuestionRadioButton(@currentQuestion)
      $('#question-' + @currentQuestion).find('#loader').html('<i class="fa fa-spin fa-circle-o-notch"></i> En cours d\'enregistrement')
    else
      $('#question-' + @currentQuestion).find('button').prop('disabled', true).addClass('--disabled').html('<i class="fa fa-spin fa-circle-o-notch"></i> Enregistrer')

  showDefault: =>
    if @isQuestionRadioButton(@currentQuestion)
      $('#question-' + @currentQuestion).find('#loader').html ''
    else
      $('#question-' + @currentQuestion).find('button').prop('disabled', false).removeClass('--disabled').html('<i class="fa fa-check"></i> Enregistrer')

  fetchDatasCurrentQuestion: =>

    return @fetchDatasQuestion(@currentQuestion)

  fetchDatasQuestion: (question) =>

    syphon = new Syphon()

    return syphon.get('#question-' + question + ' form')

  showError: (error) => 

    console.log error

    $('#question-' + @currentQuestion).find('#error').html(error)

  cleanError: =>
    
    $('#question-' + @currentQuestion).find('#error').html ''



    

# Export
module.exports = BoxForm
