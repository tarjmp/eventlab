Feature: Register

    Scenario Outline: invalid credentials
        #Given I navigate to "https://eventlab-dev.herokuapp.com/"
        Given I navigate to "http://localhost:8000/"
        And I wait for 1 sec
        And I click on element having id "register"
        And I wait for 1 sec
        When I enter "<first_name>" into input field having id "first_name"
        And I enter "<last_name>" into input field having id "last_name"
        And I enter "<email>" into input field having id "email"
        And I enter "<password>" into input field having id "password"
        And I enter "<confirm_password>" into input field having id "password-confirm"
        And I click on element having id "btn_register"
        And I wait for 2 sec
        Then element having class "invalid-feedback" should have partial text as "<msg>"

        Examples:
            | first_name | last_name | email         | password | confirm_password | msg                                         |
            | John       | Doe       | user@test.com | 123      | 123              | The password must be at least 6 characters. |
            | John       | Doe       | user@test.com | 123456   | 1234567          | The password confirmation does not match.   |

    Scenario: Registration successful
        #Given I navigate to "https://eventlab-dev.herokuapp.com/"
        Given I navigate to "http://localhost:8000/"
        And I wait for 1 sec
        And I click on element having id "register"
        And I wait for 1 sec
        When I enter "John" into input field having id "first_name"
        And I enter "Doe" into input field having id "last_name"
        And I enter "john.doe@e-mail.com" into input field having id "email"
        And I enter "123456" into input field having id "password"
        And I enter "123456" into input field having id "password-confirm"
        And I click on element having id "btn_register"
        And I wait for 2 sec
        Then element having id "navbarDropdown" should have partial text as "John"
        And element having class "card-header" should have partial text as "Calendar"
