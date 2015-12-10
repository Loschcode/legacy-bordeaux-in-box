$(document).ready ->

  # Key used by stripe to give a token
  stripe_key = 'pk_live_EhCVbntIqph3ppfNCiN6wq3x'
  #stripe_key = 'pk_test_HNPpbWh3FV4Lw4RmIQqirqsj'

  ###
  For test : pk_test_HNPpbWh3FV4Lw4RmIQqirqsj
  ###
    
  new Global()
  new Box()
  new Billing()
  new Payment(stripe_key)
  new Login()
  new Spot()
  new Card(stripe_key)