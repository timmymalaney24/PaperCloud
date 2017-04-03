<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    public $driver;
    public $session;
    
    public $page;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->driver = new \Behat\Mink\Driver\Selenium2Driver();
        $this->session = new \Behat\Mink\Session($this->driver);

        $this->session->start();






        // added for requirement_3 black-box testing
        $this->paperListPage = $this->page->find("css", "#paperListPage"); // TODO: we may need to check this
        $this->paperListTable = $this->paperListPage->find("css", "#paperList");
        $this->titleColmunHeader = $this->paperListPage->find("css", "#titleColmunHeader");
        $this->authorColumnHeader = $this->paperListPage->find("css", "#authorColumnHeader");
        $this->conferenceColumnHeader = $this->paperListPage->find("css", "#conferenceColumnHeader");
        $this->frequencyColumnHeader = $this->paperListPage->find("css", "#frequencyColumnHeader");
    }

    /**
     * @Given that the user opens the webpage with a web browser
     */
    public function thatTheUserOpensTheWebpageWithAWebBrowser()
    {
        throw new PendingException();
    }

    /**
     * @When the artist search bar should be empty
     */
    public function theArtistSearchBarShouldBeEmpty()
    {
        throw new PendingException();
    }

    /**
     * @Then the search button is not clickable
     */
    public function theSearchButtonIsNotClickable()
    {
        throw new PendingException();
    }

    /**
     * @Given there are three characters in the textbox
     */
    public function thereAreThreeCharactersInTheTextbox()
    {
        throw new PendingException();
    }

    /**
     * @Then the search button is clickable
     */
    public function theSearchButtonIsClickable()
    {
        throw new PendingException();
    }

    /**
     * @Given X is set to :arg1 before searching
     */
    public function xIsSetToBeforeSearching($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then number of items in the word cloud is :arg1
     */
    public function numberOfItemsInTheWordCloudIs($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given that the user searches for a valid last name
     */
    public function thatTheUserSearchesForAValidLastName()
    {
        throw new PendingException();
    }

    /**
     * @Then the appropriate top papers in the ACM and IEEE libraries are shown in the word cloud
     */
    public function theAppropriateTopPapersInTheAcmAndIeeeLibrariesAreShownInTheWordCloud()
    {
        throw new PendingException();
    }

    /**
     * @Then there are no papers in the word cloud that do not belong to a user with that last name
     */
    public function thereAreNoPapersInTheWordCloudThatDoNotBelongToAUserWithThatLastName()
    {
        throw new PendingException();
    }

    /**
     * @Given that the user searches for an invalid last name
     */
    public function thatTheUserSearchesForAnInvalidLastName()
    {
        throw new PendingException();
    }

    /**
     * @Then a label is shown where the word cloud would be that there are no papers for this user
     */
    public function aLabelIsShownWhereTheWordCloudWouldBeThatThereAreNoPapersForThisUser()
    {
        throw new PendingException();
    }

    /**
     * @Given the Paper Cloud is generated
     */
    public function thePaperCloudIsGenerated()
    {
        throw new PendingException();
    }

    /**
     * @When a word in the Paper Cloud is clicked
     */
    public function aWordInThePaperCloudIsClicked()
    {
        throw new PendingException();
    }

    /**
     * @Then the paper list is displayed, and ranked by the word frequency
     */
    public function thePaperListIsDisplayedAndRankedByTheWordFrequency()
    {
        throw new PendingException();
    }

    /**
     * @Given the paper list is generated
     */
    public function thePaperListIsGenerated()
    {
        throw new PendingException();
    }

    /**
     * @When the :arg1 column header is clicked
     */
    public function theColumnHeaderIsClicked($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then the paper list is sorted in the ascending order of the :arg1 column
     */
    public function thePaperListIsSortedInTheAscendingOrderOfTheColumn($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then the paper list is is sorted in the descending order of the :arg1 column
     */
    public function thePaperListIsIsSortedInTheDescendingOrderOfTheColumn($arg1)
    {
        throw new PendingException();
    }
}
