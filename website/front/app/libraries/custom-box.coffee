##
# Manage the module to answer some questions
##
class CustomBox

  processingAjax: false

  constructor: ->
   
    # Display the first question
    @showQuestion(1, false)
    @currentQuestion = 1

    # Start to listen the events
    @events()

  ##
  # Listen the events
  ##
  events: ->

    $('form').on 'submit', @formSubmited
    $(':radio').on 'click', @labelClicked
    $('.js-skip').on 'click', @skipClicked

  skipClicked: (e) =>

    e.preventDefault()

    @showNextQuestion()


  ##
  # When we submit the form
  ##
  formSubmited: (e) =>

    e.preventDefault()

    @postAddAnswer()

  ##
  # When we click on the label
  ##
  labelClicked: (e) =>

    if @isQuestionRadioButton(@currentQuestion)

      unless @processingAjax
        @postAddAnswer()
      else

        # Block the selection
        e.preventDefault()

  ##
  # Process ajax to add the new answer in the database
  ##
  postAddAnswer: =>

    unless @processingAjax

      @showLoading()
      @cleanError()

      datas = @fetchDatasCurrentQuestion()

      console.log datas

      @processingAjax = true

      $.post '/service/api/box-question-customer-answer', datas, (response) =>

        @processingAjax = false
        @showDefault()

        unless response.success

          @showError(response.errors) 

        else
          
          @showNextQuestion()

  ##
  # Show the next question
  ##
  showNextQuestion: =>

    if @hasNextQuestion(@currentQuestion)

      @hideQuestion(@currentQuestion)
      @showQuestion(@currentQuestion+1)
      @currentQuestion = @currentQuestion + 1

    else 
      alert "no more questions"

  ##
  # Show the question wanted
  # @param integer Which question we want to display
  # @param boolean Do we want a fadeIn transition
  ##
  showQuestion: (position, fadeIn) =>

    if fadeIn is false
      $('[id=question-' + position + ']').removeClass('+hidden')
    else
      $('[id=question-' + position + ']').fadeIn().removeClass('+hidden')


  ##
  # Hide the question wanted
  # @param integer Which question we want to hide
  ##
  hideQuestion: (position) =>

    $('[id=question-' + position + ']').addClass('+hidden')

  ##
  # Check if the question X has a next question
  # @param integer The question X
  # @return boolean
  ##
  hasNextQuestion: (currentQuestion) =>

    nextQuestion = currentQuestion + 1

    if $('[id=question-' + nextQuestion + ']').length > 0

      return true

    return false

  ##
  # Check if the question X is a type of radio button
  # @param integer The question X
  ##
  isQuestionRadioButton: (question) =>

    type = $('#question-' + question).data('type')

    if type is 'radiobutton'
      return true

    return false

  ##
  # Display a loading status
  ##
  showLoading: =>

    if @isQuestionRadioButton(@currentQuestion)
      $('#question-' + @currentQuestion).find('#loader').html('<i class="fa fa-spin fa-circle-o-notch"></i> En cours d\'enregistrement')
    else
      $('#question-' + @currentQuestion).find('button').prop('disabled', true).addClass('--disabled').html('<i class="fa fa-spin fa-circle-o-notch"></i> Enregistrer')

  ##
  # Back to the default state 
  ## 
  showDefault: =>
    if @isQuestionRadioButton(@currentQuestion)
      $('#question-' + @currentQuestion).find('#loader').html ''
    else
      $('#question-' + @currentQuestion).find('button').prop('disabled', false).removeClass('--disabled').html('<i class="fa fa-check"></i> Enregistrer')

  ##
  # Fetch the form datas of the current question
  ##
  fetchDatasCurrentQuestion: =>

    return @fetchDatasQuestion(@currentQuestion)

  ##
  # Fetch the form datas of the question X
  # @return object
  ##
  fetchDatasQuestion: (question) =>

    syphon = new Syphon()

    return syphon.get('#question-' + question + ' form')

  ##
  # Display the error given
  ##
  showError: (error) => 

    $('#question-' + @currentQuestion).find('#error').html(error)

  ##
  # Clean error displayed
  ##
  cleanError: =>
    
    $('#question-' + @currentQuestion).find('#error').html ''


# Export
module.exports = CustomBox
