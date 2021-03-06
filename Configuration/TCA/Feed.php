<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TCA']['tx_iffluidfeed_domain_model_feed'] = array(
	'ctrl' => $GLOBALS['TCA']['tx_iffluidfeed_domain_model_feed']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, type, url, method, localfile, outerwrapper, wrapper, uidentifier',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title, type, url, method, localfile, outerwrapper, wrapper, uidentifier, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
	
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_iffluidfeed_domain_model_feed',
				'foreign_table_where' => 'AND tx_iffluidfeed_domain_model_feed.pid=###CURRENT_PID### AND tx_iffluidfeed_domain_model_feed.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),

		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
	
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),

		'title' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:if_fluidfeed/Resources/Private/Language/locallang_db.xlf:tx_iffluidfeed_domain_model_feed.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
			),
		),

		'type' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:if_fluidfeed/Resources/Private/Language/locallang_db.xlf:tx_iffluidfeed_domain_model_feed.type',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:if_fluidfeed/Resources/Private/Language/locallang_db.xlf:tx_iffluidfeed_domain_model_feed.type.xml', 'xml'),
					array('LLL:EXT:if_fluidfeed/Resources/Private/Language/locallang_db.xlf:tx_iffluidfeed_domain_model_feed.type.json', 'json')
				)
			),
		),

		'url' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:if_fluidfeed/Resources/Private/Language/locallang_db.xlf:tx_iffluidfeed_domain_model_feed.url',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),

		'method' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:if_fluidfeed/Resources/Private/Language/locallang_db.xlf:tx_iffluidfeed_domain_model_feed.method',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:if_fluidfeed/Resources/Private/Language/locallang_db.xlf:tx_iffluidfeed_domain_model_feed.method.get', 'GET'),
					array('LLL:EXT:if_fluidfeed/Resources/Private/Language/locallang_db.xlf:tx_iffluidfeed_domain_model_feed.method.post', 'POST')
				)
			),
		),

		'localfile' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:if_fluidfeed/Resources/Private/Language/locallang_db.xlf:tx_iffluidfeed_domain_model_feed.localfile',
			'config' => array(
				'type' => 'check',
			),
		),

		'outerwrapper' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:if_fluidfeed/Resources/Private/Language/locallang_db.xlf:tx_iffluidfeed_domain_model_feed.outerwrapper',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),

		'wrapper' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:if_fluidfeed/Resources/Private/Language/locallang_db.xlf:tx_iffluidfeed_domain_model_feed.wrapper',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),

		'uidentifier' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:if_fluidfeed/Resources/Private/Language/locallang_db.xlf:tx_iffluidfeed_domain_model_feed.uidentifier',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		
	),
);
