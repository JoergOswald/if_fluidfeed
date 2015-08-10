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
    switch($args["mode"]) {
      case 'events':
        $mode = "events";
        $feed = $this->feedRepository->findByUid($this->settings['flexform']['events']);
        break;
      case 'customerhouses':
        $mode = "customerhouses";
        $feed = $this->feedRepository->findByUid($this->settings['flexform']['customerhousefeed']);
        break;
      default:
        $mode = "info";
        $feed = $this->feedRepository->findByUid($this->settings['flexform']['feed']);
    }

    // If not info mode, find out stuetzpunkt ID since we only have the name in the URL
    if($mode != "info") {
      $stuetzpunktfeed = $this->feedRepository->findByUid($this->settings['flexform']['feed']);
      $stuetzpunkte = FeedController::loadFeed($stuetzpunktfeed);
      if($stuetzpunkte) {
        $stuetzpunkt = FeedController::filter($stuetzpunkte[$stuetzpunktfeed->getOuterwrapper()][$stuetzpunktfeed->getWrapper()], function($n) use ($id, $stuetzpunktfeed) {
          return stristr($n[$stuetzpunktfeed->getUidentifier()],$id);
        });

        if(count($stuetzpunkt) != 1) {
          $this->view->assign('error', 'Could not reliably find item');
          return;
        }

        $item = $stuetzpunkt;
        $id = $stuetzpunkt[0]["ID"];
      }
    }

    $rawItems = FeedController::loadFeed($feed);
    if(!isset($rawItems[$feed->getOuterwrapper()])) {
      $filter = $rawItems;
    } else {
      $filter = $rawItems[$feed->getOuterwrapper()][$feed->getWrapper()];
    }
    $rawItems = FeedController::filter($filter, function($n) use ($id, $feed) {
      return stristr($n[$feed->getUidentifier()],$id);
    });

    if($mode == "info") {
      if(count($rawItems) !== 1) {
        $this->view->assign('error', 'Could not reliably find item');
        return;
      }
      $this->view->assign('item',$rawItems[0]);
      return;
    }

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

		$feed = $this->feedRepository->findByUid($this->settings['flexform']['feed']);
    $args = $this->request->getArguments();
    if($feed) {
      $url = $feed->getUrl();
      $path = $_SERVER['DOCUMENT_ROOT'].'/typo3temp/tx_iffluidfeed/'.md5($url).'.xml';
      $content = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($path);
      if($content) {
        $xml = simplexml_load_string($content,null,LIBXML_NOCDATA);
        $itemsArray = FeedController::xmlToArray($xml, true, $feed);
        $rawItems = $itemsArray[$feed->getOuterwrapper()][$feed->getWrapper()];

        // Search result
        // TODO: Implement general search function
        //if($args['sword']) {
        //  $rawItems = FeedController::filter($rawItems, function($n) use ($args) {
        //    return stristr($n['value']['title'],$args['sword']) || stristr($n['value']['description'],$args['sword']) || stristr($n['value']['location'],$args['sword']);
        //  });
        //}

        if ($this->settings['templateLayout'] == 3) {
          $date = array();
          foreach ($rawItems as $key => $row)
          {
            $today = date('Y-m-d H:i');
            if ($today < $row['Datum']['Start']) {
              $date[$key] = $row['Datum']['Start'];              
            } else {
              unset($rawItems[$key]);
            }
            
          }
          array_multisort($date, SORT_ASC, $rawItems);
        } else {
          $city = array();
          foreach ($rawItems as $key => $row)
          {
              $city[$key] = $row['Name'];            
          }
          array_multisort($city, SORT_ASC, $rawItems);
        }
        

        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($rawItems);
        
        // Current Page
        if($this->settings["flexform"]["pagination"]) {
          $perpage = (!empty($this->settings["flexform"]["perpage"])? $this->settings["flexform"]["perpage"] : 5);
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
