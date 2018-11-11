package Webtests;

import cucumber.api.PendingException;
import cucumber.api.java.en.And;
import cucumber.api.java.en.Given;
import cucumber.api.java.en.Then;
import cucumber.api.java.en.When;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.chrome.ChromeDriver;

import static junit.framework.TestCase.assertEquals;

public class LoginStepDef {
    private String pathToChromeDriver = "..\\webtests\\driver\\chromedriver.exe";
    private WebDriver driver;

    @Given("^I navigate to \"([^\"]*)\"$")
    public void iNavigateTo(String url) throws Throwable {
        System.setProperty("webdriver.chrome.driver", pathToChromeDriver);
        driver = new ChromeDriver();
        driver.manage().window().maximize();
        driver.get(url);
    }

    @When("^I wait for (\\d+) sec$")
    public void iWaitForSec(int time) throws Throwable {
        Thread.sleep(time * 1000);
    }

    @And("^I click on element having id \"([^\"]*)\"$")
    public void iClickOnElementHavingId(String id) throws Throwable {
        driver.findElement(By.id(id)).click();
        Thread.sleep(1000);
    }

    @And("^I enter \"([^\"]*)\" into input field having id \"([^\"]*)\"$")
    public void iEnterIntoInputFieldHavingId(String email, String id) throws Throwable {
        driver.findElement(By.id(id)).sendKeys(email);
        Thread.sleep(1000);
    }

    @Then("^element having class \"([^\"]*)\" should have partial text as \"([^\"]*)\"$")
    public void elementHavingClassShouldHavePartialTextAs(String cl, String text) throws Throwable {
        assertEquals(text, driver.findElement(By.className(cl)).getText());
    }
}
