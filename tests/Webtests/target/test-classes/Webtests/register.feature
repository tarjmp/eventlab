Feature: Register

    Scenario: invalid email
        Given I navigate to "https://eventlab-dev.herokuapp.com/"
        And I wait for 1 sec
    #enter email without @ symbol
        Then I enter "usertest.com" into input field having id "email"
        And I wait for 1 sec
        Then element having xpath "tbd" should have text as "Your email is invalid."

    Scenario: Password confirmation does not match
        Given I navigate to register page
        And I wait for 1 sec
         #register with different passwords
        When I enter "user@test.com" into input field having id "email"
        And I enter "testpassword" into input field having id "password"
        And I enter "testpassword12345" into input field having id "confirmPassword"
        And I click on element having class "btn-register" and text "Register"
        And I wait for 1 sec
        Then element having xpath "tbd" should have text as "The password and its confirmation do not match!"

    Scenario: Registration successful
        Given I navigate to register page
        And I wait for 1 sec
        #register
        When I enter "user@test.com" into input field having id "email"
        And I enter "testpassword" into input field having id "password"
        And I enter "testpassword" into input field having id "confirmPassword"
        And I click on element having class "btn-register" and text "Register"
        And I wait for 2 sec
        Then element having xpath "tbd" should have partial text as "Registration saved!"
