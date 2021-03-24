<?php
use Codeception\Util\Drupal\FormField;
use Drupal\node\Entity\Node;

class DrupalDemoCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
      // Check web page
      $I->amOnPage('/');
      $I->see('Drush');
      $I->logInAs('admin');
      $I->makeScreenshot();

      // via UI
      $I->amOnPage('/node/add/article');
      $I->fillTextField(FormField::title(), 'My article');
      $I->fillWysiwygEditor(FormField::body(), 'My body');
      $I->makeScreenshot();
      $I->clickOn(FormField::submit());
      $I->see('My article');
      $I->see('My body');
      $node_id = $I->grabFromCurrentUrl('~^/node/(\d+)~');
      Node::load($node_id)->delete();

      // Functional
      $node = $I->createEntity([
        'title' => 'My node',
        'body' => 'My body',
        'type' => 'article'
      ]);
      $I->amOnPage('/node/' . $node->id());
      $I->see('My node');
      $I->see('My body');

      // Test user
      $user = $I->createUserWithRoles(['editor', 'authenticated'], 'password');
      $I->amOnPage('/user/' . $user->id());
      $I->see($user->getUsername());
      $I->makeScreenshot();
    }

    public function _after(AcceptanceTester $I)
    {
    }

}
