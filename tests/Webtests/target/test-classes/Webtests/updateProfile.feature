Feature: Update User Profile

    Scenario: valid data

    Scenario Outline: valid credentials
        Given I navigate to "https://eventlab-dev.herokuapp.com/"
        And I wait for 1 sec
        And I click on element having id "login"
        And I wait for 1 sec
        And I enter "john.doe@e-mail.com" into input field having id "email"
        And I enter "123456" into input field having id "password"
        And I click on element having id "btn_login"
        And I wait for 2 sec
        And I click on element having id "navbarDropdown"
        And I wait for 1 sec
        And I click on element having id "update_profile"
        And I wait for 1 sec
        When I enter "<first_name>" into input field having id "first_name"
        And I click on element having id "btn_submit"
        Then element having class "alert-success" should contain text as "Your profile was successfully updated."
        And element having id "navbarDropdown" should have partial text as "<first_name>"

        Examples:
            | first_name |
            | Webtest    |
            | John       |
