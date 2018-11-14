Feature: Create Event

    Scenario Outline: valid credentials
        Given I navigate to "https://eventlab-dev.herokuapp.com/"
        And I wait for 1 sec
        And I click on element having id "login"
        And I wait for 1 sec
        And I wait for 1 sec
        And I enter "john.doe@e-mail.com" into input field having id "email"
        And I enter "123456" into input field having id "password"
        And I click on element having id "btn_login"
        And I wait for 2 sec
        When I click on element having id "btn_createEvent"
        And I enter "<name>" into input field having id "name"
        And I enter "<description>" into input field having id "description"
        And I enter "<date>" into input field having id "date"
        And I click on element having id "btn_createEvent"
        And I wait for 2 sec
        Then alert with class "alert-success" should have eventname "<name>"

        Examples:
            | name                   | description | date       |
            | Softwareengineering I  |             | 14.11.2018 |
            | Softwareengineering II | DHBW        | 14.11.2018 |
