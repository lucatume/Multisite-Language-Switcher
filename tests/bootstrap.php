<?php

require_once __DIR__ . '/../vendor/autoload.php';

WP_Mock::bootstrap();

WP_Mock::passthruFunction( 'get_option' );

require_once __DIR__ . '/../MultisiteLanguageSwitcher.php';


class Msls_UnitTestCase extends PHPUnit_Framework_TestCase {

	/**
	 * SetUp initial settings
	 */
	function setUp() {
		WP_Mock::setUp();
	}

	/**
	 * Break down for next test
	 */
	function tearDown() {
		WP_Mock::tearDown();
	}

	/**
	 * Create a Options mock object
	 * When trying to get one of its property, the full option value is retrieved
	 *
	 * @param array $values
	 *
	 * @return \Mockery\MockInterface
	 */
	protected function get_options( array $values = [] ) {
		$mock = \Mockery::mock( 'Options' );

		if ( count( $values ) > 0 ) {
			foreach ( $values as $key => $value ) {
				$mock->$key = $value;
				$mock->shouldReceive( 'get' )->with( $key )->andReturn( $value )->zeroOrMoreTimes();
			}
		}

		return $mock;
	}

}