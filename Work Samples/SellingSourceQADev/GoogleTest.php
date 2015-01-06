<?php
namespace usr\share\pear;

use \Behat\Mink\Driver\GoutteDriver;
use \Behat\Mink\Driver\SahiDriver;
use \Behat\Mink\Session;
use \Behat\Mink\Mink;

use \Behat\Mink\Element\NodeElement;

require_once 'mink/autoload.php';
require '/usr/share/pear/PHPUnit/Framework/Assert/Functions.php';


class GoogleTest extends \PHPUnit_Framework_TestCase
{
private $baseUrl;
private $url2;
private $url3;
private $client;
private $goutteDriver;
private $sahiDriver;
private $goutteSession;
private $sahiSession;
private $mink;
private $gouttePage;
private $sahiPage;
private $el;
private $namedSelector;
private $cssSelector;
private $handler;


protected function setup()
{
$this->baseUrl = 'http://www.google.';  
$this->url = 'http://www.google.com';
$this->href = 'http://www.sellingsource.com/';

$this->goutteDriver = new GoutteDriver();
$this->sahiDriver = new SahiDriver('firefox');
$this->goutteSession = new Session($this->goutteDriver);
$this->sahiSession = new Session($this->sahiDriver);
$this->mink = new Mink();
$this->mink->registerSession('sahi',$this->sahiSession);
$this->mink->registerSession('goutte',$this->goutteSession);

 
}

protected function teardown()
{
$this->goutteDriver= NULL;
$this->SahiDriver= NULL;
$this->goutteSession= NULL;
$this->sahiSession= NULL;
$this->mink= NULL;
$this->namedSelector= NULL;
$this->cssSelector= NULL;
$this->handler=NULL;
}
 

function testGoogle()
{

$this->Session = $this->mink->getSession('sahi');
$this->Session->visit($this->url);
AssertContains($this->baseUrl,$this->Session->getCurrentUrl(),'Intended web page not reached!'); 

$this->Page = $this->Session->getPage();
$this->pageContent = $this->Page->getContent();


// find input field
$this->inputField = $this->Page->find('css','input[title="Search"]');
AssertSame($this->inputField->getTagName(),'input','Input field not found!');


// put search value into input field
$this->inputField->setValue('Selling Source');
AssertSame($this->inputField->getValue(),'Selling Source','Could not set value of input field!');


// find search button

$this->searchButton = $this->Page->find('css','input[value="Google Search"]');
AssertSame($this->searchButton->getTagName(),'input','Submit button not found!');


// set or verify english language setting

// click search button and Verify page change 
$this->searchButton->press();
$this->newPage = $this->Session->getPage();
$this->newPageContent = $this->newPage->getContent();
AssertNotSame($this->newPageContent,$this->pageContent,'Page did not change!!');


// Verify first Entry
$this->hrefElement = new NodeElement('//div/h3[@class="r"]/a',$this->Session);
AssertContains('sellingsource.com',$this->hrefElement->getAttribute('href'),'First entry not verified');

}
	
}


?>
