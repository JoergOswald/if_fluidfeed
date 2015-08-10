<?php
if (!defined('TYPO3_MODE')) {
  die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
  $_EXTKEY,
  'Output',
  'Fluidfeed Ausgabe'
);

$pluginSignature = str_replace('_','',$_EXTKEY) . '_output';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_output.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Interfrog Fluidfeed');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iffluidfeed_domain_model_feed', 'EXT:if_fluidfeed/Resources/Private/Language/locallang_csh_tx_iffluidfeed_domain_model_feed.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_iffluidfeed_domain_model_feed');
$GLOBALS['TCA']['tx_iffluidfeed_domain_model_feed'] = array(
  'ctrl' => array(
    'title'	=> 'LLL:EXT:if_fluidfeed/Resources/Private/Language/locallang_db.xlf:tx_iffluidfeed_domain_model_feed',
    'label' => 'url',
    'tstamp' => 'tstamp',
    'crdate' => 'crdate',
    'cruser_id' => 'cruser_id',
    'dividers2tabs' => TRUE,

    'versioningWS' => 2,
    'versioning_followPages' => TRUE,

    'languageField' => 'sys_language_uid',
    'transOrigPointerField' => 'l10n_parent',
    'transOrigDiffSourceField' => 'l10n_diffsource',
    'delete' => 'deleted',
    'enablecolumns' => array(
      'disabled' => 'hidden',
      'starttime' => 'starttime',
      'endtime' => 'endtime',
    ),
    'searchFields' => 'url,wrapper,outerwrapper,',
    'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Feed.php',
    'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_fluidfeed_domain_model_feed.gif'
  ),
);
