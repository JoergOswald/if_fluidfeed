<?php

  namespace Interfrog\IfFluidfeed\Tasks;

  /**
   * Task der die XML Datensätze ausliest und die XML Files in typo3temp sichert.
   */

  class CacheXmlTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask {
    public function execute() {
      $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\Extbase\Object\ObjectManager');
      $feedRepository= $objectManager->get('\Interfrog\IfFluidfeed\Domain\Repository\FeedRepository');
      $querySettings = new \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings();
      $querySettings->setRespectStoragePage(FALSE);
      $feedRepository->setDefaultQuerySettings($querySettings);
      $feeds = $feedRepository->findAll();
      foreach($feeds as $feed) {
        $url = $feed->getUrl();
        $content = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($feed->getUrl());
        if($content) {
          $path = PATH_site.'typo3temp/tx_iffluidfeed/'.md5($url).'.xml';
          $error = \TYPO3\CMS\Core\Utility\GeneralUtility::writeFileToTypo3tempDir($path, $content);
          if($error != NULL) {
            echo $error;
            return FALSE;
          }
        } else {
          echo $url. " not found";
          return FALSE;
        }
      }
      return TRUE;
    }
  }

?>
