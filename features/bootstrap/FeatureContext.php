<?php

require_once "vendor/phpunit/phpunit/src/Framework/Assert/Functions.php";

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

    // added for requirement_1 black-box testing
    public $searchBar;
    public $searchField;
    public $sizeField;
    public $searchButton;
    public $wordCloud;
    public $g;
    public $words;

    // added for requirement_3 black-box testing    
    //public $paperListPage;
    public $paperListTable;
    public $titleColmunHeader;
    public $authorColumnHeader;
    public $conferenceColumnHeader;
    public $frequencyColumnHeader;

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

        $this->session->visit('http://localhost:80/PaperCloud');
        $this->page = $this->session->getPage();

        // added for requirement_1 black-box testing, reference: searchPage.html
        $this->searchBar = $this->page->find("css", "#searchBar");
        $this->searchField = $this->page->find("css", "#searchTextField");
        $this->sizeField = $this->page->find("css", "#limitTextField");
        $this->searchButton = $this->page->find("css", "#search");

        // added for requirement_3 black-box testing, reference: paperListPage.html
        // $this->paperListPage = $this->page->find("css", "#paperListPage");
        // $this->paperListTable = $this->page->find("css", "#paperList");
        // $this->titleColmunHeader = $this->paperListTable->find("css", "#titleColmunHeader");
        // $this->authorColumnHeader = $this->paperListTable->find("css", "#authorColumnHeader");
        // $this->conferenceColumnHeader = $this->paperListTable->find("css", "#conferenceColumnHeader");
        // $this->frequencyColumnHeader = $this->paperListTable->find("css", "#frequencyColumnHeader");

    }

    public function __destruct()
    {
        $this->session->stop();
    }
    /**
     * @Given that the user opens the webpage with a web browser
     */
    public function thatTheUserOpensTheWebpageWithAWebBrowser()
    {
        assertNotEquals(null, $this->searchBar);
    }

    /**
     * @When the artist search bar should be empty
     */
    public function theArtistSearchBarShouldBeEmpty()
    {
        assertEquals("", $this->searchField->getValue());
    }

    /**
     * @Then the search button is not clickable
     */
    public function theSearchButtonIsNotClickable()
    {
        assertNotEquals('disabled', $this->searchButton->getAttribute('disabled'));
    }

    /**
     * @Given there are three characters in the textbox
     */
    public function thereAreThreeCharactersInTheTextbox()
    {
        $this->searchField->setValue('Halfond');
        sleep(3);
    }

    /**
     * @Then the search button is clickable
     */
    public function theSearchButtonIsClickable()
    {
        assertEquals('disabled', $this->searchButton->getAttribute('disabled'));
    }

    /**
     * @Given X is set to 20 before searching
     */
    public function xIsSetToBeforeSearching()
    {
        $this->sizeField->setValue('20');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
    }

    /**
     * @Then number of papers in the word cloud is 20
     */
    public function numberOfPapersInTheWordCloudIs()
    {
        $this->session->wait(
            10000,
            "papers.length"
        );

        $papersLength = $this->session->evaluateScript(
            "return papers.length;"
        );

        assertEquals(20, $papersLength);
    }

    /**
     * @Given that the user searches for a valid last name
     */
    public function thatTheUserSearchesForAValidLastName()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);
    }

    /**
     * @Given that the user searches 
     */
    public function thatTheUserSearches()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(2);
    }    

    /**
     * @Then a progress bar is shown
     */
    public function aProgressBarIsShown()
    {
        $this->wordCloudPage = $this->session->getPage();
        $this->progressBar = $this->wordCloudPage->find("css", "#wordCloudLoading");
        assertNotEquals(null, $this->progressBar);
    }

    /**
     * @Then the appropriate top papers in the ACM and IEEE libraries are shown in the word cloud
     */
    public function theAppropriateTopPapersInTheAcmAndIeeeLibrariesAreShownInTheWordCloud()
    {
        $this->wordCloudPage = $this->session->getPage();
        $this->wordCloud = $this->wordCloudPage->find("css", "#wordCloudSVG");
        $this->g = $this->wordCloud->find("css", "#g");
        $this->words = $this->g->findAll("css", "#text");
        array_pop($this->words)->click();
        sleep(3);
        $this->paperListPage = $this->session->getPage();
        $this->table = $this->paperListPage->find("css", "#paperList");
        $this->authors = $this->table->findAll("css", "#author");

        $author = array_pop($this->authors);

        $containsAuthor = strpos($author->getText(), "Halfond");
        assertEquals(true, $containsAuthor);
    }

    /**
     * @Given that the user searches for an invalid last name
     */
    public function thatTheUserSearchesForAnInvalidLastName()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Banananana');
        $this->searchButton->click();
        sleep(10);
    }

    /**
     * @Then a label is shown where the word cloud would be that there are no papers for this user
     */
    public function aLabelIsShownWhereTheWordCloudWouldBeThatThereAreNoPapersForThisUser()
    {
        $this->wordCloudPage = $this->session->getPage();
        $this->wordCloud = $this->wordCloudPage->find("css", "#wordCloud");
        $this->noResultsLabel = $this->wordCloud->find("css", "#noResultsID");

        assertEquals("No Results Found", $this->noResultsLabel->getText());
    }

    /**
    * @Given the Paper Cloud is generated
    */
    public function thePaperCloudGenerated()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);
    }

    /**
    * @When a word in the Paper Cloud is clicked
    */
    public function wordInThePaperCloudClicked()
    {
        $this->page = $this->session->getPage();
        $this->wordCloud = $this->page->find("css", "#wordCloudSVG");
        $this->g = $this->wordCloud->find("css", "#g");
        $this->words = $this->g->findAll("css", "#text");
        $this->words[0]->click();
        sleep(3);
    }

    /**
    * @Then the paper list is displayed
    */
    public function paperListDisplayed()
    {
        assertNotEquals(null, $this->page->find("css", "#paperListPage"));
    }

    /**
    * @Given the paper list is generated for the "Title" column
    */
    public function thePaperListGeneratedForTitleColumn()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);

        $this->page = $this->session->getPage();
        $this->wordCloud = $this->page->find("css", "#wordCloudSVG");
        $this->g = $this->wordCloud->find("css", "#g");
        $this->words = $this->g->findAll("css", "#text");
        $this->words[0]->click();
        sleep(3);
    }

    /**
    * @When the "Title" column header is clicked
    */
    public function titleColumnHeaderClicked()
    {
        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $this->titleColmunHeader = $this->paperListTable->find("css", "#titleColumnHeader");
        $this->titleColmunHeader->click();
        sleep(1);
    }

    /**
    * @Then the paper list is sorted in the ascending order of the "Title" column
    */
    public function paperListsortInAscendingOrderOfTitleColumn()
    {
        $rows = $this->paperListTable->findAll("css", "#row");
        $firstTitle = $rows[0]->find("css", "#title");
        $secondTitle = $rows[1]->find("css", "#title");

        assertEquals(true, $firstTitle <= $secondTitle);
    }

    /**
    * @Given the paper list is generated for the "Author" column
    */
    public function thePaperListGeneratedForAuthorColumn()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);

        $this->page = $this->session->getPage();
        $this->wordCloud = $this->page->find("css", "#wordCloudSVG");
        $this->g = $this->wordCloud->find("css", "#g");
        $this->words = $this->g->findAll("css", "#text");
        $this->words[0]->click();
        sleep(3);
    }

    /**
    * @When the "Author" column header is clicked
    */
    public function authorColumnHeaderClicked()
    {
        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $this->authorColmunHeader = $this->paperListTable->find("css", "#authorColumnHeader");
        $this->authorColmunHeader->click();
        sleep(1);
    }

    /**
    * @Then the paper list is sorted in the ascending order of the "Author" column
    */
    public function paperListsortInAscendingOrderOfAuthorColumn()
    {
        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $rows = $this->paperListTable->findAll("css", "#row");
        $firstAuthor = $rows[0]->find("css", "#author");
        $secondAuthor = $rows[1]->find("css", "#author");

        assertEquals(true, $firstAuthor <= $secondAuthor);
    }

    /**
    * @Given the paper list is generated for the "Conference" column
    */
    public function thePaperListGeneratedForConferenceColumn()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);

        $this->page = $this->session->getPage();
        $this->wordCloud = $this->page->find("css", "#wordCloudSVG");
        $this->g = $this->wordCloud->find("css", "#g");
        $this->words = $this->g->findAll("css", "#text");
        $this->words[0]->click();
        sleep(3);
    }

    /**
    * @When the "Conference" column header is clicked
    */
    public function conferenceColumnHeaderClicked()
    {
        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $this->conferenceColmunHeader = $this->paperListTable->find("css", "#conferenceColumnHeader");
        $this->conferenceColmunHeader->click();
        sleep(1);
    }

    /**
    * @Then the paper list is sorted in the ascending order of the "Conference" column
    */
    public function paperListsortInAscendingOrderOfConferenceColumn()
    {
        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $rows = $this->paperListTable->findAll("css", "#row");
        $firstConference = $rows[0]->find("css", "#conference");
        $secondConference = $rows[1]->find("css", "#conference");

        assertEquals(true, $firstConference <= $secondConference);
    }

    /**
    * @Given the paper list is generated for the "Frequency" column
    */
    public function thePaperListGeneratedForFrequencyColumn()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);

        $this->page = $this->session->getPage();
        $this->wordCloud = $this->page->find("css", "#wordCloudSVG");
        $this->g = $this->wordCloud->find("css", "#g");
        $this->words = $this->g->findAll("css", "#text");
        $this->words[0]->click();
        sleep(3);
    }

    /**
    * @When the "Frequency" column header is clicked
    */
    public function frequencyColumnHeaderClicked()
    {
        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $this->conferenceColmunHeader = $this->paperListTable->find("css", "#conferenceColumnHeader");
        $this->conferenceColmunHeader->click();
        $this->frequencyColmunHeader = $this->paperListTable->find("css", "#frequencyColumnHeader");
        $this->frequencyColmunHeader->click();
        sleep(1);
    }

    /**
    * @Then the paper list is sorted in the ascending order of the "Frequency" column
    */
    public function paperListsortInAscendingOrderOfFrequencyColumn()
    {
        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $rows = $this->paperListTable->findAll("css", "#row");
        $firstFrequency = $rows[0]->find("css", "#frequency");
        $secondFrequency = $rows[1]->find("css", "#frequency");

        assertEquals(true, $firstFrequency <= $secondFrequency);
    }

    /**
    * @Given the paper list is generated for the "Full Text" column
    */
    public function givenPaperListGeneratedForFullTextColumn()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);

        $this->page = $this->session->getPage();
        $this->wordCloud = $this->page->find("css", "#wordCloudSVG");
        $this->g = $this->wordCloud->find("css", "#g");
        $this->words = $this->g->findAll("css", "#text");
        $this->words[0]->click();
        sleep(3);
    }

    /**
    * @Then the paper list contains a "Full Text" column
    */
    public function thenPaperListContainsFullTextColumn()
    {
        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $fullTextColumnHeader = $this->paperListTable->find("css", "#fullTextColumnHeader");
        
        assertNotEquals(null, $fullTextColumnHeader);
    }

    /**
    * @Then each paper has a link for its full text
    */
    public function thenEachPaperHasLinkForItsFullText()
    {
        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $rows = $this->paperListTable->findAll("css", "#row");
        $firstFullText = $rows[0]->find("css", "#fullText");
        $firstFullTextLink = $firstFullText->find("css", "a");

        assertNotEquals(null, $firstFullTextLink);
    }

    /**
    * @Given the paper list is generated for exporting as PDFs
    */
    public function givenPaperListGeneratedForExportingPDF()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);

        $this->page = $this->session->getPage();
        $this->wordCloud = $this->page->find("css", "#wordCloudSVG");
        $this->g = $this->wordCloud->find("css", "#g");
        $this->words = $this->g->findAll("css", "#text");
        $this->words[0]->click();
        sleep(3);
    }

    /**
    * @Then the "Download this list as a PDF" button is clickable
    */
    public function thenDownloadThisListPDFButtonClickable()
    {
        $this->page = $this->session->getPage();
        $listDownloadLinks = $this->page->find("css", "#listDownloadLinks");
        $downloadListAsPDF = $listDownloadLinks->find("css", "#downloadListAsPDF");
        assertNotEquals('pointer', $downloadListAsPDF->find("css", "a"));
    }

    /**
    * @Given the paper list is generated for exporting as plain text
    */
    public function givenPaperListGeneratedForExportingPlainText()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);

        $this->page = $this->session->getPage();
        $this->wordCloud = $this->page->find("css", "#wordCloudSVG");
        $this->g = $this->wordCloud->find("css", "#g");
        $this->words = $this->g->findAll("css", "#text");
        $this->words[0]->click();
        sleep(3);
    }

    /**
    * @Then the "Download this list as a text file" button is clickable
    */
    public function thenDownloadThisListTextFileButtonClickable()
    {
        $this->page = $this->session->getPage();
        $listDownloadLinks = $this->page->find("css", "#listDownloadLinks");
        $downloadListAsTXT = $listDownloadLinks->find("css", "#downloadListAsTXT");
        assertNotEquals(null, $downloadListAsTXT->find("css", "a"));

        sleep(10);
    }

    /**
    * @Given the paper list is generated for the "Bib Text" column
    */
    public function givenPaperListGeneratedForBibTextColumn()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);

        $this->page = $this->session->getPage();
        $this->wordCloud = $this->page->find("css", "#wordCloudSVG");
        $this->g = $this->wordCloud->find("css", "#g");
        $this->words = $this->g->findAll("css", "#text");
        $this->words[0]->click();
        sleep(3);
    }

    /**
    * @Then the paper list contains a "Bib Text" column
    */
    public function thenPaperListContainsBibTextColumn()
    {
        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $bibTextColumnHeader = $this->paperListTable->find("css", "#bibtexColumnHeader");

        assertNotEquals(null, $bibTextColumnHeader);
    }

    /**
    * @Then each paper has a link for its Bib Text
    */
    public function thenEachPaperHasLinkForItsBibText()
    {
        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $rows = $this->paperListTable->findAll("css", "#row");
        $firstBibText = $rows[0]->find("css", "#bibtex");
        $firstBibTextLink = $firstBibText->find("css", "a");

        assertNotEquals(null, $firstBibTextLink);
    }

    /**
    * @Given the last search is "Redekopp"
    */
    public function givenLastSearchRedekopp()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Redekopp');
        $this->searchButton->click();
        sleep(10);
    }

    /**
    * @When the Search Page is reopened
    */
    public function whenSearchPageReopened()
    {
        $this->session->visit('http://localhost:80/PaperCloud');
        $this->page = $this->session->getPage();
        sleep(3);
    }

    /**
    * @Then the list of previous search shows "Redekopp" on the top
    */
    public function thenPreviousSearchListShowsRedekopp()
    {
        $searchTextFieldDropDown = $this->searchBar->find("css", "#searchTextFieldDropDown");
        $searchTextFieldDropDown->click();
        sleep(1);
        $previousSearches = $this->page->find("css", "#previousSearches");
        $topOption = $previousSearches->find("css", "option");
        
        assertEquals("Redekopp", $topOption->getAttribute('value'));
    }

    /**
    * @Given the paper list is generated for checking each paper selectable
    */
    public function givenPaperListGeneratedForCheckingEachPaperSelectable()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);

        $this->page = $this->session->getPage();
        $this->wordCloud = $this->page->find("css", "#wordCloudSVG");
        $this->g = $this->wordCloud->find("css", "#g");
        $this->words = $this->g->findAll("css", "#text");
        $this->words[0]->click();
        sleep(3);
    }

    /**
    * @When the "Search Selected Subset" button is clicked
    */
    public function whenSearchSelectedSubsetButtonClicked()
    {
        $this->page = $this->session->getPage();
        $listDownloadLinks = $this->page->find("css", "#listDownloadLinks");
        $subsetLink = $listDownloadLinks->find("css", "#subsetLink");
        $subsetLink->click();
        sleep(1);
    }

    /**
    * @Then each paper is selectable on the Paper List Page
    */
    public function thenEachPaperSelectableOnPaperListPage()
    {
        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $rows = $this->paperListTable->findAll("css", "#row");
        $firstRowData = $rows[0]->findAll("css", "td");
        $firstSubsetCheckbox = $firstRowData[0]->find("css", "input");

        assertEquals("subsetCheckbox", $firstSubsetCheckbox->getAttribute('class'));
    }

    /**
    * @Given the paper list is generated for selecting papers
    */
    public function givenPaperListGeneratedForSelectingPapers()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);

        $this->page = $this->session->getPage();
        $this->wordCloud = $this->page->find("css", "#wordCloudSVG");
        $this->g = $this->wordCloud->find("css", "#g");
        $this->words = $this->g->findAll("css", "#text");
        $this->words[0]->click();
        sleep(3);
    }

    /**
    * @When the first paper is selected
    */
    public function whenFirstPaperSelected()
    {
        $this->page = $this->session->getPage();
        $listDownloadLinks = $this->page->find("css", "#listDownloadLinks");
        $subsetLink = $listDownloadLinks->find("css", "#subsetLink");
        $subsetLink->click();
        sleep(1);

        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $rows = $this->paperListTable->findAll("css", "#row");
        $firstRowData = $rows[0]->findAll("css", "td");
        $firstSubsetCheckbox = $firstRowData[0]->find("css", "input");
        $firstSubsetCheckbox->click();
        $subsetLink->click();
        sleep(5);
    }

    /**
    * @Then a new Paper Cloud is generated
    */
    public function thenNewPaperCloudGenerated()
    {
        assertNotEquals(null, $this->page->find("css", "#wordCloudPage"));
    }

    /**
    * @Given the paper list is already generated for clicking author
    */
    public function givenPaperListGeneratedForClickingAuthor()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);

        $this->page = $this->session->getPage();
        $this->wordCloud = $this->page->find("css", "#wordCloudSVG");
        $this->g = $this->wordCloud->find("css", "#g");
        $this->words = $this->g->findAll("css", "#text");
        $this->words[0]->click();
        sleep(3);
    }

    /**
    * @When an author is clicked
    */
    public function whenAuthorClicked()
    {
        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $rows = $this->paperListTable->findAll("css", "#row");
        $firstAuthor = $rows[0]->find("css", "#author");
        $firstAuthorSearch = $firstAuthor->find("css", "a");
        $firstAuthorSearch->click();
        sleep(10);
    }

    /**
    * @Then a new word cloud is displayed based on that author
    */
    public function thenNewWordCloudDisplayedBasedOnThatAuthor()
    {
        assertNotEquals(null, $this->page->find("css", "#wordCloudPage"));
    }

    /**
    * @Given the paper list is already generated for clicking conference
    */
    public function givenPaperListGeneratedForClickingConference()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);

        $this->page = $this->session->getPage();
        $this->wordCloud = $this->page->find("css", "#wordCloudSVG");
        $this->g = $this->wordCloud->find("css", "#g");
        $this->words = $this->g->findAll("css", "#text");
        $this->words[0]->click();
        sleep(3);
    }

    /**
    * @When a conference is clicked
    */
    public function whenConferenceClicked()
    {
        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $rows = $this->paperListTable->findAll("css", "#row");
        $firstConference = $rows[0]->find("css", "#conference");
        $firstConferenceSearch = $firstConference->find("css", "a");
        $firstConferenceSearch->click();
        sleep(10);
    }

    /**
    * @Then a new word cloud is displayed based on that conference
    */
    public function thenNewWordCloudDisplayedBasedOnThatConference()
    {
        assertNotEquals(null, $this->page->find("css", "#wordCloudPage"));
    }
    /**
    * @Given the paper list is already generated
    */
    public function givenPaperListGeneratedForClickingTitles()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);

        $this->page = $this->session->getPage();
        $this->wordCloud = $this->page->find("css", "#wordCloudSVG");
        $this->g = $this->wordCloud->find("css", "#g");
        $this->words = $this->g->findAll("css", "#text");
        $this->words[0]->click();
        sleep(3);
    }

    /**
    * @When a paper's title is clicked
    */
    public function whenTheTitleIsClicked()
    {
        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $rows = $this->paperListTable->findAll("css", "#row");
        $firstTitle = $rows[0]->find("css", "#title");
        $firstTitleClick = $firstTitle->find("css", "a");
        $firstTitleClick->click();
        sleep(5);
    }

    /**
    * @Then the words in that paper's abstract are highlighted
    */
    public function thenTheWordIsHighlighted()
    {
        assertEquals(null, $this->page->find("css", "#wordCloudPage"));
    }

    /**
    * @Given the search page is opened for checking keyword phrase search option
    */
    public function givenSearchPageOpenedForCheckingKeywordSeaechOption()
    {
        sleep(1);
    }

    /**
    * @Then keyword phrase search option is selectable
    */
    public function thenKeywordSearchOptionSelectable()
    {
        $this->page = $this->session->getPage();
        $fieldSelect = $this->page->find("css", "#fieldSelect");
        $fieldSelectOptions = $fieldSelect->findAll("css", "option");

        assertEquals("keyword", $fieldSelectOptions[1]->getAttribute('value'));
    }

    /**
    * @Given the search page is opened for searching for keyword phrase "data structure" 
    */
    public function givenSearchPageOpenedForSearchingKeywordPhraseDataStructure()
    {
        sleep(1);
    }

    /**
    * @When search for keyword phrase "data structure" 
    */
    public function whenSearchForKeywordPhraseDataStructure()
    {
        $this->page = $this->session->getPage();
        $fieldSelect = $this->page->find("css", "#fieldSelect");
        $fieldSelectOptions = $fieldSelect->findAll("css", "option");
        $fieldSelectOptions[1]->click();
        sleep(1);

        $this->sizeField->setValue('10');
        $this->searchField->setValue('data structure');
        $this->searchButton->click();
        sleep(10);
    }

    /**
    * @Then the word cloud is displayed for keyword phrase "data structure"
    */
    public function thenWordCloudDisplayedForKeywordPhraseDataStructure()
    {
        assertNotEquals(null, $this->page->find("css", "#wordCloudPage"));
    }

    /**
    * @Given the paper list is generated for downloading the highlighted paper
    */
    public function givenPaperListGeneratedForDownloadingHighlightedPaper()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);

        $this->page = $this->session->getPage();
        $this->wordCloud = $this->page->find("css", "#wordCloudSVG");
        $this->g = $this->wordCloud->find("css", "#g");
        $this->words = $this->g->findAll("css", "#text");
        $this->words[0]->click();
        sleep(3);
    }

    /**
    * @Then a "Hightlighted" link is displayed
    */
    public function thenHighlightedLinkDisplayed()
    {
        $this->page = $this->session->getPage();
        $this->paperListTable = $this->page->find("css", "#paperList");
        $rows = $this->paperListTable->findAll("css", "#row");
        $firstFullText = $rows[0]->find("css", "#fullText");
        $firstFullTextLinks = $firstFullText->findAll("css", "a");
        assertEquals("highlightedFullTextLink", $firstFullTextLinks[1]->getAttribute('class'));
    }

    /**
    * @Given the word cloud is generated for checking the Download button
    */
    public function givenWordCloudGeneratedForCheckingDownloadButton()
    {
        $this->sizeField->setValue('10');
        $this->searchField->setValue('Halfond');
        $this->searchButton->click();
        sleep(10);
    }

    /**
    * @Then a Download button exists on the Word Cloud Page
    */
    public function thenDownloadButtonExistsOnWordCloudPage()
    {
        $this->page = $this->session->getPage();
        $downloadButton = $this->page->find("css", "#download");
        assertEquals("button", $downloadButton->getAttribute('class'));
    }

    /**
    * @Then a download link exists behind the scene of the Download button
    */
    public function thenDownloadLinkExistsBehindSceneOfDownloadButton()
    {
        $this->page = $this->session->getPage();
        $downloadLink = $this->page->find("css", "#downloadLink");
        assertNotEquals(null, $downloadLink);
    }

}
