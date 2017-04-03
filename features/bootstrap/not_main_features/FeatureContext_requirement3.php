<?php

require_once "vendor/autoload.php";
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

	public $artistSearchBar;
	public $artistSearchTextField;
    public $searchButton;

    // added for requirement_3 black-box testing
    public $paperListPage;
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

		$this->session->visit('http://localhost:80/LyricsCloud/');
		$this->page = $this->session->getPage();

		$this->artistSearchBar = $this->page->find("css", "#artistSearchBar");
		$this->artistSearchTextField = $this->artistSearchBar->find("css", "#artistSearchTextField");

        $this->searchButton = $this->page->find("css", "#search");

        // added for requirement_3 black-box testing
       	$this->paperListPage = $this->page->find("css", "#paperListPage"); // TODO: we may need to check this
       	$this->paperListTable = $this->paperListPage->find("css", "#paperList");
       	$this->titleColmunHeader = $this->paperListPage->find("css", "#titleColmunHeader");
       	$this->authorColumnHeader = $this->paperListPage->find("css", "#authorColumnHeader");
       	$this->conferenceColumnHeader = $this->paperListPage->find("css", "#conferenceColumnHeader");
       	$this->frequencyColumnHeader = $this->paperListPage->find("css", "#frequencyColumnHeader");
	}

	public function __destruct()
	{
		$this->session->stop();
	}

	/**
	* @Given the Paper Cloud is generated
	*/
	public function thePaperCloudGenerated()
	{

	}

	/**
	* @When a word in the Paper Cloud is clicked
	*/
	public function wordInThePaperCloudClicked()
	{

	}

	/**
	* @Then the paper list is displayed
	*/
	public function paperListDisplayed()
	{
		assertNotEquals(null, $this->page->find("css", "#paperListPage"));
	}

	/**
	* @Given the paper list is generated
	*/
	public function thePaperListGenerated()
	{

	}

	/**
	* @When the "Title" column header is clicked
	*/
	public function titleColumnHeaderClicked()
	{
		$this->titleColmunHeader->click();
	}

	/**
	* @Then the paper list is sorted in the ascending order of the "Title" column
	*/
	public function paperListsortInAscendingOrderOfTitleColumn()
	{
		$dom = new domDocument;
		$dom->loadHTML($page);
		$dom->preserveWhiteSpace = false;

		$tableArray = array();
		$table = $dom->getElementsByTagName('table');
		$rows = $table->item(1)->getElementsByTagName('tr');

		foreach ($rows as $row) {
			$rowArray = array();
			$cols = $row->getElementsByTagName('td');

			foreach ($cols as $col) {
				array_push($rowArray, $col);
			}

			array_push($tableArray, $rowArray);
		}

		assertEquals(true, $tableArray[0][0] < $tableArray[1][0]);
	}

	/**
	* @Given the paper list is generated
	*/
	public function thePaperListGenerated()
	{

	}

	/**
	* @When the "Author" column header is clicked
	*/
	public function authorColumnHeaderClicked()
	{
		$this->authorColmunHeader->click();
	}

	/**
	* @Then the paper list is sorted in the ascending order of the "Author" column
	*/
	public function paperListsortInAscendingOrderOfAuthorColumn()
	{
		$dom = new domDocument;
		$dom->loadHTML($page);
		$dom->preserveWhiteSpace = false;

		$tableArray = array();
		$table = $dom->getElementsByTagName('table');
		$rows = $table->item(1)->getElementsByTagName('tr');

		foreach ($rows as $row) {
			$rowArray = array();
			$cols = $row->getElementsByTagName('td');

			foreach ($cols as $col) {
				array_push($rowArray, $col);
			}

			array_push($tableArray, $rowArray);
		}

		assertEquals(true, $tableArray[0][1] < $tableArray[1][1]);
	}

	/**
	* @Given the paper list is generated
	*/
	public function thePaperListGenerated()
	{

	}

	/**
	* @When the "Conference" column header is clicked
	*/
	public function conferenceColumnHeaderClicked()
	{
		$this->conferenceColmunHeader->click();
	}

	/**
	* @Then the paper list is sorted in the ascending order of the "Conference" column
	*/
	public function paperListsortInAscendingOrderOfConferenceColumn()
	{
		$dom = new domDocument;
		$dom->loadHTML($page);
		$dom->preserveWhiteSpace = false;

		$tableArray = array();
		$table = $dom->getElementsByTagName('table');
		$rows = $table->item(1)->getElementsByTagName('tr');

		foreach ($rows as $row) {
			$rowArray = array();
			$cols = $row->getElementsByTagName('td');

			foreach ($cols as $col) {
				array_push($rowArray, $col);
			}

			array_push($tableArray, $rowArray);
		}

		assertEquals(true, $tableArray[0][2] < $tableArray[1][2]);
	}

	/**
	* @Given the paper list is generated
	*/
	public function thePaperListGenerated()
	{

	}

	/**
	* @When the "Frequency" column header is clicked
	*/
	public function frequencyColumnHeaderClicked()
	{
		$this->refquencyColmunHeader->click();
	}

	/**
	* @Then the paper list is sorted in the descending order of the "Frequency" column
	*/
	public function paperListsortInDescendingOrderOfFrequencyColumn()
	{
		$dom = new domDocument;
		$dom->loadHTML($page);
		$dom->preserveWhiteSpace = false;

		$tableArray = array();
		$table = $dom->getElementsByTagName('table');
		$rows = $table->item(1)->getElementsByTagName('tr');

		foreach ($rows as $row) {
			$rowArray = array();
			$cols = $row->getElementsByTagName('td');

			foreach ($cols as $col) {
				array_push($rowArray, $col);
			}

			array_push($tableArray, $rowArray);
		}

		assertEquals(true, $tableArray[0][3] > $tableArray[1][3]);
	}
	
}

?>