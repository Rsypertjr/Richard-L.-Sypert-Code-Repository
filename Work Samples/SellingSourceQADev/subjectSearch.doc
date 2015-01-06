# Content of file subjectSearch.feature
Feature: subjectSearch
  As a website user
  I need to be able to search for a subject
  In order to see the subject displayed
   
  @javascript
  Scenario: Searching for Hoare Logic in English on Wikipedia
    Given I am an anonymous user on "http://www.wikipedia.com" page
    When I select an English Language Search on Wiki
      And I fill in search on Wiki with "Hoare Logic" entry
      And I press the Search Button on Wiki
    Then I should see display on Wiki of the "Hoare" and "Logic" subject 
      And I should see the "http://www.key-project.org/download/hoare/" external link

  @javascript
  Scenario: Searching for The Selling Source on Google
    Given I am an anonymous user on "http://www.google.com" page
    When I fill in search on Google with "The Selling Source" entry
    Then I should see on Google is "sellingsource.com" subject as first entry

  
