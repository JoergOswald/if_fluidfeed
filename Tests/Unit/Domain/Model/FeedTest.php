<?php

namespace Interfrog\IfMrnStepstone\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for class \Interfrog\IfMrnStepstone\Domain\Model\Feed.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class FeedTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \Interfrog\IfMrnStepstone\Domain\Model\Feed
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = new \Interfrog\IfMrnStepstone\Domain\Model\Feed();
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getUrlReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getUrl()
		);
	}

	/**
	 * @test
	 */
	public function setUrlForStringSetsUrl() {
		$this->subject->setUrl('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'url',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getWrapperReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getWrapper()
		);
	}

	/**
	 * @test
	 */
	public function setWrapperForStringSetsWrapper() {
		$this->subject->setWrapper('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'wrapper',
			$this->subject
		);
	}
}
