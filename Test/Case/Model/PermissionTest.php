<?php
App::uses('Permission', 'FlexAuth.Model');

/**
 * Permission Test Case
 *
 */
class PermissionTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.flex_auth.permission',
		'plugin.flex_auth.role_permission'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Permission = ClassRegistry::init('FlexAuth.Permission');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Permission);

		parent::tearDown();
	}

}
