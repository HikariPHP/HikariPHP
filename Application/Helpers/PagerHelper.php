<?php

namespace Application\Helpers;

use Core\View;

class PagerHelper {

	protected $intPage = 0;
	protected $intPages = NULL;
	protected $strTemplate = 'Helpers/Pager/Default.php';
	protected $strUrlBase = NULL;
	protected $strPageParam = 'page';
	protected $intFirstPage = 1;
	protected $intLateralLimit = 2; // * group size
	protected $intGroupSize = 0;
	protected $intResultsCount = NULL;
	protected $intResultsPerPage = 50;
	protected $intMaxPages = 100;
	protected $arrPerPage = array(
							  25 =>  25
							, 50 =>  50
							,100 => 100
							,200 => 200
						);
	protected $strPerPageParam = 'per_page';
	protected $strPageGroups = 'default';
	protected $strBackupParam;
	/**
	 * Set this param to false if you don't
	 * want that $arrPerPage to reset current page,
	 * true if you wanna reset it
	 * @var bool
	 */
	protected $blnOverWritePerPageNavigator = true;

	/**
	 * Get a new pager
	 *
	 * @return PagerHelper
	 */
	public static function getNewPager(){
		return new self();
	}
	
	/**
	 * Set the page groups style
	 *
	 * @version 1.0
	 * @since Feb 13, 2012 2:24:30 PM
	 * @param string $strPageGroupStyle
	 * @return PagerHelper
	 */
	public function setPageGroups($strPageGroupStyle){ 
		$this->strPageGroups = $strPageGroupStyle;
		return $this;
	}
	
	/**
	 * Set the per page param
	 *
	 * @version 1.0
	 * @since Feb 12, 2012 10:00:36 AM
	 * @param string $strParam
	 * @return PagerHelper
	 */
	public function setPerPageParam($strParam){
		$this->strPerPageParam = $strParam;
		return $this;
	}
		
	/**
	 * Set per page values
	 *
	 * @version 1.0
	 * @since Feb 12, 2012 9:58:08 AM
	 * @param array $arrPerPage
	 * @return PagerHelper
	 */
	public function setPerPageValues($arrPerPage){
		$this->arrPerPage = $arrPerPage;
		return $this;
	}
	
	/**
	 * Get the per page calculated array
	 *
	 * @version 1.0
	 * @since Feb 12, 2012 9:58:52 AM
	 * @return PagerHelper
	 */
	protected function getPerPage(){
		$arrReturn = array();
		$strBackupParam = $this->strPageParam;
		//Capture page param so we can reset it
		if ($this->blnOverWritePerPageNavigator){
			$this->strBackupParam = $this->strPageParam;
		}
		$this->strPageParam = $this->strPerPageParam;
		foreach ($this->arrPerPage as $intPerPage => $intPerPageLabel){
			$arrReturn[$intPerPage]['label'] = $intPerPageLabel;
			$arrReturn[$intPerPage]['url'] = $this->getUrlBase($intPerPage);
		}
		$this->strPageParam = $strBackupParam;
		//Turn back-up to null, not needed anymore
		$this->strBackupParam = null;
		return $arrReturn;
	}

	/**
	 * Set the maximum number of pages to display
	 *
	 * @version 1.0
	 * @since Feb 12, 2012 1:08:30 AM
	 * @param int $intMaxPages 
	 * @return PagerHelper
	 */
	public function setMaxPages($intMaxPages){ 
		$this->intMaxPages = $intMaxPages;
		return $this;
	}
	
	/**
	 * Set the base Url
	 *
	 * @version 1.0
	 * @since Feb 10, 2012 5:29:00 PM
	 * @param unknown_type $strUrlBase
	 */
	public function setUrlBase($strUrlBase){
		$this->strUrlBase = $strUrlBase;
		return $this;
	}

	/**
	 * Set the page parameter
	 *
	 * @version 1.0
	 * @since Feb 10, 2012 5:27:59 PM
	 * @param string $strPageParam
	 * @return PagerHelper
	 */
	public function setPageParam($strPageParam){
		$this->strPageParam = $strPageParam;
		return $this;
	}

	/**
	 * Set the number of pages
	 *
	 * @version 1.0
	 * @since Feb 8, 2012 3:54:37 PM
	 * @param int $intPages
	 * @return PagerHelper
	 */
	public function setPages($intPages){
		$this->intPages = $intPages;
		return $this;
	}

	
	/**
	 * Get the number of pages
	 *
	 * @version 1.0
	 * @since Feb 12, 2012 12:59:51 AM
	 * @throws PagerHelperException
	 * @return int 
	 */
	protected function getPages(){
		if (isset($this->intPages)){
			return max($this->intPages, 1);
		}
		if (isset($this->intResultsCount) && $this->intResultsCount === NULL){
			throw new Exception(
				 'You have to set the results count'
				,PagerExceptionHelper::ERROR_MISSING_RESULTS_COUNT
			);
		}
		if (empty($this->intResultsPerPage)){
			throw new Exception(
				'You have to set the number of results to display per page'
				,PagerExceptionHelper::ERROR_MISSING_RESULTS_PER_PAGE
			);
		}
		$this->intPages = min(
						ceil($this->intResultsCount / $this->intResultsPerPage)
					   ,$this->intMaxPages 
					);

		return $this->intPages;		
	}
	
	/**
	 * Set the current page
	 *
	 * @version 1.0
	 * @since Feb 8, 2012 3:55:27 PM
	 * @param int $intPage
	 * @return PagerHelper
	 */
	public function setPage($intPage) {
		$this->intPage = $intPage;
		return $this;
	}

	/**
	 * Set the template of the pager
	 *
	 * @version 1.0
	 * @since Feb 8, 2012 4:02:10 PM
	 * @param string $strTemplate
	 * @return PagerHelper
	 */
	public function setTemplate($strTemplate){
		$this->strTemplate = $strTemplate;
		return $this;
	}

	/**
	 * Fetches the pager
	 *
	 * @version 1.0
	 * @since Feb 8, 2012 4:03:14 PM
	 * @param string $strTemplate
	 * @return string
	 */
	public function fetch($strTemplate = NULL){
		if ($strTemplate === NULL){
			$strTemplate = $this->strTemplate;
		}
		$objView = new View();
		$objView->assign('intPage', $this->intPage);
		$objView->assign('intPages', $this->getPages());
		$objView->assign('strUrlBase', $this->getUrlBase());
		$objView->assign('intFirstPage', $this->intFirstPage);
		$objView->assign('strPageParam', $this->strPageParam);
		$objView->assign('strPrevUrl', $this->getUrlBase(max($this->intPage - 1, 1)));
		$objView->assign('strNextUrl', $this->getUrlBase(min($this->intPage + 1, $this->getPages())));
		$objView->assign('strFirstUrl', $this->getUrlBase($this->intFirstPage));
		$objView->assign('strLastUrl', $this->getUrlBase($this->getPages()));
		$objView->assign('arrPerPage', $this->getPerPage());
		$objView->assign('intPerPageSelected', $this->intResultsPerPage);
		if ($this->intGroupSize > 0){
			$objView->assign('arrPageGroups', $this->getPageGroups());
		}

		return $objView->fetch($strTemplate);
	}
	
	/**
	 * Set the group size
	 *
	 * @version 1.0
	 * @since Feb 12, 2012 12:45:22 AM
	 * @param int $intGroupSize
	 * @return PagerHelper
	 */
	public function setGroupSize($intGroupSize){ 
		$this->intGroupSize = $intGroupSize;
		return $this;
	}
	
	/**
	 * Set the size of the lateral limit 
	 *
	 * @version 1.0
	 * @since Feb 12, 2012 12:46:54 AM
	 * @param int $intLateralLimit - will be multiplied with the group size 
	 * @return PagerHelper
	 */
	public function setLateralLimit($intLateralLimit){
		$this->intLateralLimit = $intLateralLimit;
		return $this;
	}

	/**
	 * Get the page groups
	 *
	 * @version 1.0
	 * @since Feb 12, 2012 12:40:15 AM
	 * @return array
	 */
	protected function getPageGroups(){
		$strMethod = 'getPageGroups' . ucfirst($this->strPageGroups);
		if (!method_exists($this, $strMethod)){
			throw new Exception('Grouping method does not exist.', PagerExceptionHelper::ERROR_MISSING_GROUP_METHOD);
		}
		return $this->$strMethod();
	}
	
	/**
	 * Get the default page groups
	 *
	 * @version 1.0
	 * @since Feb 13, 2012 2:23:24 PM
	 * @return array
	 */
	protected function getPageGroupsDefault(){
		$intFirstPage = max(1, $this->intPage - ($this->intLateralLimit * $this->intGroupSize));
		$intLastPage = min($this->getPages(), $this->intPage + ($this->intLateralLimit * $this->intGroupSize));
		
		$arrGroups = array();
		
		for ($i = $intFirstPage; $i <= $intLastPage; $i++){
			$intGroupId = floor($i/$this->intGroupSize) * $this->intGroupSize;
			$intGroupId = $intGroupId ? $intGroupId : 1;
			$arrGroups[$intGroupId]['visible'] = (
							$intGroupId <= $this->intPage 
						&& $this->intPage < $intGroupId + $this->intGroupSize
					);
			$arrGroups[$intGroupId]['pages'][$i] = $this->getUrlBase($i);
		}
		return $arrGroups;
	}
	
	/**
	 * Get page groups as a small group
	 *
	 * @version 1.0
	 * @since Feb 13, 2012 3:28:09 PM
	 * @return array
	 */
	protected function getPageGroupsSmallgroup(){
		$arrGroups = array();
		$intPages = $this->getPages();

		$intFirstPage = max(1, $this->intPage - $this->intLateralLimit);
		$intLastPage = min($intPages, $this->intPage + $this->intLateralLimit);
		if ($intLastPage - $intFirstPage != 2 * $this->intLateralLimit){
			if ($intLastPage - $this->intPage != $this->intLateralLimit){
				$intFirstPage = max(1, $intFirstPage - ($this->intLateralLimit - ($intLastPage - $this->intPage + 1) ));
			} else {
				$intLastPage = min($intPages, $intLastPage + ($this->intLateralLimit - ($this->intPage - $intFirstPage + 1)));
			}
		}
		
		if ($intFirstPage > 1){
			$arrGroups[1]['pages'][1] = $this->getUrlBase(1);
		}
		
		for ($i = $intFirstPage; $i <= $intLastPage; $i++){
			$intGroupId = floor($i/$this->intGroupSize) * $this->intGroupSize;
			$intGroupId = $intGroupId ? $intGroupId : 1;
			$arrGroups[$intFirstPage]['pages'][$i] = $this->getUrlBase($i);
		}
		
		if ($intLastPage < $intPages){
			$arrGroups[$intPages]['pages'][$intPages] = $this->getUrlBase($intPages);
		}
		
		return $arrGroups;
	}
	
	/**
	 * Set the results count. 
	 * You will have to use the setResultsPerPage method too.  
	 *
	 * @version 1.0
	 * @since Feb 12, 2012 12:55:29 AM
	 * @param int $intResultsCount 
	 * @return PagerHelper 
	 */
	public function setResultsCount($intResultsCount){
		$this->intPages = NULL; 
		$this->intResultsCount = $intResultsCount;
		return $this;
	}
	
	/**
	 * Set the number of results to display per page. 
	 * You will have to use the setResultsCount method too.
	 *
	 * @version 1.0
	 * @since Feb 12, 2012 12:57:35 AM
	 * @param int $intResultsPerPage 
	 * @return $this;
	 */
	public function setResultsPerPage($intResultsPerPage) {
		$this->intPages = NULL;
		$this->intResultsPerPage = $intResultsPerPage;
		$this->intPerPageSelected = $intResultsPerPage; 
		return $this;
	}
	
	/**
	 * Get the base url
	 *
	 * @version 1.0
	 * @since Feb 10, 2012 5:44:21 PM
	 * @param int $intPage
	 * @return string
	 */
	protected function getUrlBase($intPage = ''){
		$strUrlBase = $this->strUrlBase;
		if ($strUrlBase === NULL){
			$strUrlBase = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$strUrlBase = preg_replace('/([\?&])'.$this->strPageParam.'=[0-9]*&?/','$1',$strUrlBase);
			//If calculating per_page we reset the page param
			if (!is_null($this->strBackupParam)){
				$strUrlBase = preg_replace('/([\?&])'.$this->strBackupParam.'=[0-9]*&?/', '$1',$strUrlBase);
			}
            if (substr($strUrlBase, -1) == '?' || substr($strUrlBase, -1) == '&')
                $strUrlBase = substr($strUrlBase, 0, -1);
			$strUrlBase .= (strpos(' ' . $strUrlBase, '?') ? '&' : '?') . $this->strPageParam .'=';
		}
		return $strUrlBase . $intPage;
	}
	
	
}

