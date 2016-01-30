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
    @showQuestion(1)
    @currentQuestion = 1


  ##
  # Run
  #
  # The main entry of the controller.
  # Your code start here
  #
  ##
  run: ->

    @on 'click', 'button', @buttonClicked
    @on 'click', 'label', @labelClicked

  buttonClicked: =>

    @showNextQuestion()

  labelClicked: =>

    #console.log @isQuestionRadioButton(@currentQuestion)


  showNextQuestion: =>

    if @hasNextQuestion(@currentQuestion)

      @hideQuestion(@currentQuestion)
      @showQuestion(@currentQuestion+1)
      @currentQuestion = @currentQuestion + 1

    else 
      alert "no more questions"


  showQuestion: (position) =>

    $('[id=question-' + position + ']').removeClass('+hidden')

  hideQuestion: (position) =>

    $('[id=question-' + position + ']').addClass('+hidden')


  hasNextQuestion: (currentQuestion) =>

    nextQuestion = currentQuestion + 1

    if $('[id=question-' + nextQuestion + ']').length > 0

      return true

    return false

  isQuestionRadioButton: =>



    

# Export
module.exports = BoxForm
