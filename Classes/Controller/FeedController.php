<?php
namespace Interfrog\IfFluidfeed\Controller;

class FeedController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * feedRepository
	 *
	 * @var \Interfrog\IfFluidfeed\Domain\Repository\FeedRepository
	 * @inject
	 */
	protected $feedRepository = NULL;

  /**
   * Convert a SimpleXML object into an array (last resort).
   *
   * @access public
   * @param object $xml
   * @param boolean $root - Should we append the root node into the array
   * @return array
   */
  static function xmlToArray($xml, $root = true, $feed = null, $debug = false) {

    if($feed != null) {
      $array = json_decode(json_encode($xml), 1);
      if($debug) {
        var_dump($array);
      } 
      if(count($array) == 1 && !isset($array[$feed->getWrapper()][0])) {
        return $array;
      }
    }

    if (!$xml->children()) {
      return (string)$xml;
    }

    $array = array();
    foreach ($xml->children() as $element => $node) {
      $totalElement = count($xml->{$element});
      
      if (!isset($array[$element])) {
        $array[$element] = "";
      }

      // Has attributes
      if ($attributes = $node->attributes()) {
        $data = array(
          'attributes' => array(),
          'value' => (count($node) > 0) ? FeedController::xmlToArray($node, false) : (string)$node
          // 'value' => (string)$node (old code)
        );

        foreach ($attributes as $attr => $value) {
          $data['attributes'][$attr] = (string)$value;
        }

        if ($totalElement > 1) {
          $array[$element][] = $data;
        } else {
          $array[$element] = $data;
        }

      // Just a value
      } else {
        if ($totalElement > 1) {
          $array[$element][] = FeedController::xmlToArray($node, false);
        } else {
          $array[$element] = FeedController::xmlToArray($node, false);
        }
      }
    }

    if ($root) {
      return array($xml->getName() => $array);
    } else {
      return $array;
    }
  }

  /**
   * Walks recursivley through an array
   */
  static function filter(array $array = array(), \Closure $closure) {
      if(!$closure)
      {   
          return \arrays\compact($array);
      }else{
          $result = array();
          foreach($array as $key => $value)
          {
              if(\call_user_func($closure, $value)){
                  $result[] = $value;
              }
          }
          return $result;
      }
  }

  /**
   * Loads a feed object and returns the element array
   *
   * @param \Interfrog\IfFluidfeed\Domain\Model\Feed $feed
   * @return array
   */
  static function loadFeed(\Interfrog\IfFluidfeed\Domain\Model\Feed $feed, $debug = false) {
    $url = $feed->getUrl();
    $path = $_SERVER['DOCUMENT_ROOT'].'/typo3temp/tx_iffluidfeed/'.md5($url).'.xml';
    $content = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($path);

    if(!$content) {
      return false;
    }
    
    $xml = simplexml_load_string($content,null,LIBXML_NOCDATA);
    $itemsArray = FeedController::xmlToArray($xml, true, $feed, $debug);

    return $itemsArray;
    
  }

	/**
	 * search action	 
   *
   * Renders the search form
   * TODO: Implement general search function
	 * @return void
	 */
	public function searchAction() {
    if(isset($_GET['tx_iffluidfeed_search']['sword'])) {
      $this->view->assign('sword',$_GET['tx_iffluidfeed_search']['sword']);
    }
	}

  /**
   * action detail
   *
   * @param string $id
   */
  public function detailAction($id) {
    
    // Determine current mode and load corresponding feed
    $args = $this->request->getArguments();
    $feed = $this->feedRepository->findByUid($this->settings['feed']);

    $rawItems = FeedController::loadFeed($feed);
    
    $this->view->assign('item',$item[0]);
    $this->view->assign('items',$rawItems);
    $this->view->assign('mode',$mode);
  }
	/**
	 * action list
   * TODO: Make items per page and pagination configurable
	 *
	 * @return void
	 */
	public function listAction() {

		$feed = $this->feedRepository->findByUid($this->settings['feed']);
    $args = $this->request->getArguments();
    if($feed) {
      $url = $feed->getUrl();
      $isLocal = $feed->getLocalfile();
      
      if ($isLocal == 1) {
        if (substr($url,0,1) !== '/') {
          $url = '/'.$url;
        }
        $path = $_SERVER['DOCUMENT_ROOT'].$url;
      } else {
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        if (strlen($ext) == 0) {
          $ext = $feed->getType();
        }
        $path = $_SERVER['DOCUMENT_ROOT'].'/typo3temp/tx_iffluidfeed/'.md5($url).'.'.$ext;
      }

      $content = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($path);

      if($content) {
        switch ($feed->getType()) {
          case "xml":
            $xml = simplexml_load_string($content,null,LIBXML_NOCDATA);
            $itemsArray = FeedController::xmlToArray($xml, true, $feed);
            $rawItems = $itemsArray[$feed->getOuterwrapper()][$feed->getWrapper()];
            break;
          case "json":
            $itemsArray = json_decode($content, true);
            $rawItems = $itemsArray[$feed->getOuterwrapper()];
            break;
        }
        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($rawItems);
        
        // Current Page
        if($this->settings["pagination"]) {
          $perpage = (!empty($this->settings["perpage"])? $this->settings["perpage"] : 5);
          $page = (int)$args["page"];
          $offset = ($page > 0) ? ($page-1)* $perpage : 0;
          $slice = array_slice($rawItems, $offset, $perpage);
        } else {
          $slice = $rawItems;
        }

  
        // Pager variables
        $totalPages = ceil(count($rawItems) / 5);
        $currentPage = $page? $page : 1;
        $nextPage = ($currentPage < $totalPages) ? $currentPage + 1 : false;
        $prevPage = ($currentPage != 1)? $currentPage - 1 : false;

        // View assignment
        $this->view->assign('items',$slice);
        $this->view->assign('totalpages', $totalPages);
        $this->view->assign('currentpage', $currentPage);
        $this->view->assign('nextpage', $nextPage);
        $this->view->assign('prevpage', $prevPage);
        $this->view->assign('sword', $args['sword']);
      } else {
        $this->view->assign('error', 'Feed at URL '.$path.' could not be fetched. Run scheduler task first to cache the file first.');
      }
    } else {
      $this->view->assign('error', 'No feed configuration was found. Please set this in the plugin.');
    }
	}
}
