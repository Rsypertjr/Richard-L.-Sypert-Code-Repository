<?php
namespace usr\share\pear;

use \Behat\Mink\Driver\GoutteDriver;
use \Behat\Mink\Driver\SahiDriver;
use \Behat\Mink\Session;
use \Behat\Mink\Mink;
use \Behat\Mink\Element\NodeElement;

require_once 'mink/autoload.php';
require '/usr/share/pear/PHPUnit/Framework/Assert/Functions.php';

class WikipedaTest extends \PHPUnit_Framework_TestCase
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
$this->baseUrl = 'http://www.wikipedia.';  
$this->url2 = 'http://www.wikipedia.com';
$this->url3 = 'http://www.wikipedia.org';
$this->href = 'http://www.key-project.org/download/hoare/';
$this->goutteDriver = new GoutteDriver();
$this->sahiDriver = new SahiDriver('firefox');
$this->goutteSession = new Session($this->goutteDriver);
$this->sahiSession = new Session($this->sahiDriver);
$this->mink = new Mink();
$this->mink->registerSession('sahi',$this->sahiSession);

 
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
 

function testWikipedia()
{


$this->sahiSession = $this->mink->getSession('sahi');
$this->sahiSession->visit($this->url2);
AssertContains($this->baseUrl,$this->sahiSession->getCurrentUrl(),'Intended web page not reached!'); 

$this->gouttePage = $this->goutteSession->getPage();
$this->sahiPage = $this->sahiSession->getPage();


// find form
$this->form = $this->sahiPage->find('css','form#searchform');
AssertNotNull($this->form,'Input form search returned Null!');
AssertSame($this->form->getTagName(),'form','Input form not found!');

// find input field
$this->inputField = $this->form->findField('searchInput');
AssertSame($this->inputField->getTagName(),'input','Input field not found!');

// put search value into input field
$this->inputField->setValue('Hoare Logic');
AssertSame($this->inputField->getValue(),'Hoare Logic','Could not set value of input field!');

// find submit button

$this->submitButton = $this->sahiPage->find('css','input.searchButton');
AssertSame($this->submitButton->getTagName(),'input','Input field not found!');


// set or verify english language setting
$this->hrefElement = new NodeElement("//select[@id='language']/option[@lang='en']",$this->sahiSession);
AssertSame($this->hrefElement->getAttribute('selected'),'selected','Not English language search setting!!!');


// click search button and go to new page
$this->submitButton->press();
$this->newPageUrl = $this->sahiSession->getCurrentUrl();


//verify new page

AssertContains('HOARE',strtoupper($this->newPageUrl), 'May not be page searched for!!!');
AssertContains('LOGIC',strtoupper($this->newPageUrl), 'May not be page searched for!!!');
$this->firstHeadingHtml = $this->sahiSession->getPage()->findById('firstHeading','Did not get new Page!')->getHtml();
AssertContains('HOARE LOGIC',strtoupper($this->firstHeadingHtml), 'Page wrong according to first header');

//Verify external link
$this->hrefElement = new NodeElement("//a[@href= '$this->href']",$this->sahiSession);
AssertSame($this->hrefElement->getAttribute('class'),'external text','Link retrieved is not external.');
AssertSame($this->hrefElement->getAttribute('href'),$this->href,'Link retrieved is not the right link.');
  
}
	
}


?>
