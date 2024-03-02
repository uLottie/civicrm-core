<?php

use Civi\Api4\Product;

/**
 * E2E Mink tests for Search Premium Products screen.
 * 
 * @group e2e
 * @see cv
 */
class CRM_CivicrmAdminUi_SearchPremiumProductsTest extends \Civi\Test\MinkBase {

  use Civi\Test\Api4TestTrait;

  public static function setUpBeforeClass(): void {
    // Example: Install this extension. Don't care about anything else.
    \Civi\Test::e2e()->installMe(__DIR__)->apply();
  }

  public function testPremiumProducts() {
    // Message says 0 Assertions even when assertions are performed. 
    $this->expectNotToPerformAssertions();
    $session = $this->mink->getSession();
    $page = $session->getPage();

    $this->login($GLOBALS['_CV']['ADMIN_USER']);
    $id1 = $this->createTestRecord('Product', [
        'currency' => 'USD', 
        'is_active' => 1,
        'price' => 10.00,
    ])['id'];
    $id2 = $this->createTestRecord('Product', [
      'currency' => 'EUR', 
      'is_active' => 1,
      'price' => 20.00,
    ])['id'];


    $this->visit(Civi::url('backend://civicrm/admin/contribute/managePremiums'));
    $session->wait(5000, 'document.querySelectorAll("tr[data-entity-id]").length > 0');
    $this->createScreenshot('/tmp/test-searchProductPage.png');

    $this->assertSession()->elementTextContains('xpath', "//tr[@data-entity-id = '$id1']", "$");
    $this->assertSession()->elementTextContains('xpath', "//tr[@data-entity-id = '$id2']", "€");
  }

  public function tearDown(): void {
    $this->deleteTestRecords();
    parent::tearDown();
  }

}
