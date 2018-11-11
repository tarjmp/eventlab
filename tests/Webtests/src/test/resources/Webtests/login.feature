Feature: Login

    Scenario: Right Credentials
        Given I navigate to "https://eventlab-dev.herokuapp.com/"
        When I wait for 1 sec
        And I click on element having id "login"
        And I wait for 1 sec
        And I enter "hello@world.com" into input field having id "email"
        And I enter "123456" into input field having id "password"
        And I click on element having id "btn_login"
        #see welcome screen
        And I wait for 2 sec
        Then element having class "card-header" should have partial text as "Calendar"
