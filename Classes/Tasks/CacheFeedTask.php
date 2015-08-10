<?php

  namespace Interfrog\IfFluidfeed\Tasks;

  /**
   * Cache all feed files in typo3temp
   */

  class CacheFeedTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask {
    public function execute() {
      $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\Extbase\Object\ObjectManager');
      $feedRepository= $objectManager->get('\Interfrog\IfFluidfeed\Domain\Repository\FeedRepository');
      $querySettings = new \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings();
      $querySettings->setRespectStoragePage(FALSE);
      $feedRepository->setDefaultQuerySettings($querySettings);
      $feeds = $feedRepository->findAll();
      foreach($feeds as $feed) {
        if ($feed->getLocalfile() == 0) {
          $url = $feed->getUrl();
          $method = $feed->getMethod();

          //USED before changed to httpRequest Library
          //$content = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($feed->getUrl());

          $config = array(
            'follow_redirects' => TRUE,
            'strict_redirects' => TRUE
          );
          $httpRequest = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Http\HttpRequest');
          $httpRequest->__construct(
            $url,
            $method
          );
          // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($httpRequest);
          $requestResult = $httpRequest->send();
          $content = $requestResult->getBody();

          if($content) {
            $ext = pathinfo($url, PATHINFO_EXTENSION);
            if (strlen($ext) == 0) {
              $ext = $feed->getType();
            }
            $path = PATH_site.'typo3temp/tx_iffluidfeed/'.md5($url).'.'.$ext;
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
      }
      return TRUE;
    }
  }

?>
