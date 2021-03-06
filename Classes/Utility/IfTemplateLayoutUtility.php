<?php

namespace Interfrog\IfFluidfeed\Utility;

class IfTemplateLayoutUtility implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * Get available template layouts for a certain page
	 *
	 * @param int $pageUid
	 * @return array
	 */
	public function getAvailableTemplateLayouts($pageUid) {
		$templateLayouts = array();

		// Check if the layouts are extended by ext_tables
		if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['if_fluidfeed']['templateLayouts'])
			&& is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['if_fluidfeed']['templateLayouts'])) {
			$templateLayouts = $GLOBALS['TYPO3_CONF_VARS']['EXT']['if_fluidfeed']['templateLayouts'];
		}

		// Add TsConfig values
		foreach($this->getTemplateLayoutsFromTsConfig($pageUid) as $templateKey => $title) {
			$templateLayouts[] = array($title, $templateKey);
		}

		return $templateLayouts;
	}

	/**
	 * Get template layouts defined in TsConfig
	 *
	 * @param $pageUid
	 * @return array
	 */
	protected function getTemplateLayoutsFromTsConfig($pageUid) {
		$templateLayouts = array();
		$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($pageUid);
		if (isset($pagesTsConfig['if_fluidfeed.']['templateLayouts.']) && is_array($pagesTsConfig['if_fluidfeed.']['templateLayouts.'])) {
			$templateLayouts = $pagesTsConfig['if_fluidfeed.']['templateLayouts.'];
		}
		return $templateLayouts;
	}
}