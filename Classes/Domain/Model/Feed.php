<?php
namespace Interfrog\IfFluidfeed\Domain\Model;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 * Feed
 */
class Feed extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * type
	 *
	 * @var string
	 */
	protected $type = '';

	/**
	 * url
	 *
	 * @var string
	 */
	protected $url = '';

	/**
	 * localfile
	 *
	 * @var string
	 */
	protected $localfile = '';

	/**
	 * identifier
	 *
	 * @var string
	 */
	protected $uidentifier = '';

	/**
	 * outerwrapper
	 *
	 * @var string
	 */
	protected $outerwrapper = '';

	/**
	 * wrapper
	 *
	 * @var string
	 */
	protected $wrapper = '';

	/**
	 * Returns the type
	 *
	 * @return string $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Sets the type
	 *
	 * @param string $type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Returns the url
	 *
	 * @return string $url
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Sets the url
	 *
	 * @param string $url
	 * @return void
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * Returns the localfile
	 *
	 * @return string $localfile
	 */
	public function getLocalfile() {
		return $this->localfile;
	}

	/**
	 * Sets the localfile
	 *
	 * @param string $localfile
	 * @return void
	 */
	public function setLocalfile($localfile) {
		$this->localfile = $localfile;
	}

	/**
	 * Returns the outerwrapper
	 *
	 * @return string $outerwrapper
	 */
	public function getOuterwrapper() {
		return $this->outerwrapper;
	}

	/**
	 * Sets the outerwrapper
	 *
	 * @param string $outerwrapper
	 * @return void
	 */
	public function setOuterwrapper($outerwrapper) {
		$this->outerwrapper = $wrapper;
	}

	/**
	 * Returns the uidentifier
	 *
	 * @return string $uidentifier
	 */
	public function getUidentifier() {
		return $this->uidentifier;
	}

	/**
	 * Sets the uidentifier
	 *
	 * @param string $uidentifier
	 * @return void
	 */
	public function setUidentifier($uidentifier) {
		$this->uidentifier = $uidentifier;
	}

	/**
	 * Returns the wrapper
	 *
	 * @return string $wrapper
	 */
	public function getWrapper() {
		return $this->wrapper;
	}

	/**
	 * Sets the wrapper
	 *
	 * @param string $wrapper
	 * @return void
	 */
	public function setWrapper($wrapper) {
		$this->wrapper = $wrapper;
	}
}
