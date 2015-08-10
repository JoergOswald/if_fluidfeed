<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Interfrog.' . $_EXTKEY,
	'output',
	array(
		'Feed' => 'list',
    	'Feed' => 'detail'
	),
	// non-cacheable actions
	array(
		'Feed' => 'list',
    	'Feed' => 'detail'
	)
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Interfrog\\IfFluidfeed\\Tasks\\CacheFeedTask'] = array(
    'extension'        => $_EXTKEY,
    'title'            => 'Cache Feed files',
    'description'      => 'Caches all feeds as static temporary files inside typo3temp',
);
