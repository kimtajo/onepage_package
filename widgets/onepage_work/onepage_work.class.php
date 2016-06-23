<?php
/* Copyright (C) NAVER <http://www.navercorp.com> */
/**
 * @class content
 * @author NAVER (developers@xpressengine.com)
 * @brief widget to display content
 * @version 0.1
 */
class onepage_work extends WidgetHandler
{
	/**
	 * @brief Widget handler
	 *
	 * Get extra_vars declared in ./widgets/widget/conf/info.xml as arguments
	 * After generating the result, do not print but return it.
	 */

	function proc($args)
	{
		// Targets to sort
		if(!in_array($args->order_target, array('regdate','update_order'))) $args->order_target = 'regdate';
		// Sort order
		if(!in_array($args->order_type, array('asc','desc'))) $args->order_type = 'asc';
		// The number of displayed lists
		$args->list_count = (int)$args->list_count;
		if(!$args->list_count) $args->list_count = 9;
		// Cut the length of the title
		if(!$args->subject_cut_size) $args->subject_cut_size = 0;
		// Cut the length of contents
		if(!$args->content_cut_size) $args->content_cut_size = 100;
		// Cut the length of nickname
		if(!$args->nickname_cut_size) $args->nickname_cut_size = 0;
		



		// Set variables used internally
		$oModuleModel = getModel('module');
		$module_srls = $args->modules_info = $args->module_srls_info = $args->mid_lists = array();
		$site_module_info = Context::get('site_module_info');

		$obj = new stdClass();
		// Apply to all modules in the site if a target module is not specified
		if(!$args->module_srls)
		{
			$obj->site_srl = (int)$site_module_info->site_srl;
			$output = executeQueryArray('widgets.onepage_work.getMids', $obj);
			if($output->data)
			{
				foreach($output->data as $key => $val)
				{
					$args->modules_info[$val->mid] = $val;
					$args->module_srls_info[$val->module_srl] = $val;
					$args->mid_lists[$val->module_srl] = $val->mid;
					$module_srls[] = $val->module_srl;
				}
			}

			$args->modules_info = $oModuleModel->getMidList($obj);
			// Apply to the module only if a target module is specified
		}
		else
		{
			$obj->module_srls = $args->module_srls;
			$output = executeQueryArray('widgets.onepage_work.getMids', $obj);
			if($output->data)
			{
				foreach($output->data as $key => $val)
				{
					$args->modules_info[$val->mid] = $val;
					$args->module_srls_info[$val->module_srl] = $val;
					$module_srls[] = $val->module_srl;
				}
				$idx = explode(',',$args->module_srls);
				for($i=0,$c=count($idx);$i<$c;$i++)
				{
					$srl = $idx[$i];
					if(!$args->module_srls_info[$srl]) continue;
					$args->mid_lists[$srl] = $args->module_srls_info[$srl]->mid;
				}
			}
		}
		// Exit if no module is found
		if(!count($args->modules_info)) return Context::get('msg_not_founded');
		$args->module_srl = implode(',',$module_srls);


		/**
		 * Method is separately made because content extraction, articles, comments, trackbacks, RSS and other elements exist
		 */

		$content_items = $this->_getDocumentItems($args);
		$output = $this->_compile($args,$content_items);
		return $output;
	}

	function _getDocumentItems($args)
	{
		// Get model object from the document module
		$oDocumentModel = getModel('document');
		$oTagModel = getModel('tag');
		// Get categories
		$obj = new stdClass();
		$obj->module_srl = $args->module_srl;
		$output = executeQueryArray('widgets.onepage_work.getCategories',$obj);
		if($output->toBool() && $output->data)
		{
			foreach($output->data as $key => $val)
			{
				$category_lists[$val->module_srl][$val->category_srl] = $val;
			}
		}
		// Get a list of documents
		$obj->module_srl = $args->module_srl;
		$obj->category_srl = $args->category_srl;
		$obj->sort_index = $args->order_target;
		if($args->order_target == 'list_order' || $args->order_target == 'update_order')
		{
			$obj->order_type = $args->order_type=="desc"?"asc":"desc";
		}
		else
		{
			$obj->order_type = $args->order_type=="desc"?"desc":"asc";
		}
		$obj->list_count = $args->list_count;
		$obj->statusList = array('PUBLIC');
		$output = executeQueryArray('widgets.onepage_work.getNewestDocuments', $obj);
		if(!$output->toBool() || !$output->data) return;
		// If the result exists, make each document as an object
		$content_items = array();
		if(count($output->data))
		{
			foreach($output->data as $key => $attribute)
			{
				$oDocument = new documentItem();
				$oDocument->setAttribute($attribute, false);
				$GLOBALS['XE_DOCUMENT_LIST'][$oDocument->document_srl] = $oDocument;
				$document_srls[] = $oDocument->document_srl;
			}

			$oDocumentModel->setToAllDocumentExtraVars();

			for($i=0,$c=count($document_srls);$i<$c;$i++)
			{
				$oDocument = $GLOBALS['XE_DOCUMENT_LIST'][$document_srls[$i]];
				$document_srl = $oDocument->document_srl;
				$module_srl = $oDocument->get('module_srl');
				$category_srl = $oDocument->get('category_srl');
				$content_item = new onepageWorkItem( $args->module_srls_info[$module_srl]->browser_title );
				$content_item->adds($oDocument->getObjectVars());
				$content_item->add('original_content', $oDocument->get('content'));
				$content_item->setTitle(htmlspecialchars($oDocument->getTitleText()));
				$content_item->setCategory( $category_lists[$module_srl][$category_srl]->title );
				$content_item->setDomain( $args->module_srls_info[$module_srl]->domain );
				$content_item->setContent($oDocument->getSummary($args->content_cut_size));
				$content_item->setLink( getSiteUrl($domain,'','document_srl',$document_srl) );
				$output = $oTagModel->getDocumentsTagList($oDocument);
				if(!$output->toBool() || !$output->data) $tags = '';
				else $tags = $output->data;

				$content_item->setTags($tags);


				$content_item->add('mid', $args->mid_lists[$module_srl]);
				$content_item->setFirstRealImage($oDocument->get('content'), $this->widget_path,$args->skin);
				$content_items[] = $content_item;
			}

		}

		$oSecurity = new Security($content_items);
		$oSecurity->encodeHTML('..variables.content', '..variables.user_name', '..variables.nick_name');

		return $content_items;
	}

	function _getImageItems($args)
	{
		$oDocumentModel = getModel('document');

		$obj->module_srls = $obj->module_srl = $args->module_srl;
		$obj->direct_download = 'Y';
		$obj->isvalid = 'Y';
		// Get categories
		$output = executeQueryArray('widgets.onepage_work.getCategories',$obj);
		if($output->toBool() && $output->data)
		{
			foreach($output->data as $key => $val)
			{
				$category_lists[$val->module_srl][$val->category_srl] = $val;
			}
		}
		// Get a file list in each document on the module
		$obj->list_count = $args->list_count;
		$files_output = executeQueryArray("file.getOneFileInDocument", $obj);
		$files_count = count($files_output->data);
		if(!$files_count) return;

		$content_items = array();

		for($i=0;$i<$files_count;$i++) $document_srl_list[] = $files_output->data[$i]->document_srl;

		$tmp_document_list = $oDocumentModel->getDocuments($document_srl_list);

		if(!count($tmp_document_list)) return;

		foreach($tmp_document_list as $oDocument)
		{
			$attribute = $oDocument->getObjectVars();
			$browser_title = $args->module_srls_info[$attribute->module_srl]->browser_title;
			$domain = $args->module_srls_info[$attribute->module_srl]->domain;
			$category = $category_lists[$attribute->module_srl]->text;
			$content = $oDocument->getSummary($args->content_cut_size);
			$url = sprintf("%s#%s",$oDocument->getPermanentUrl() ,$oDocument->getCommentCount());

			$content_item = new onepageWorkItem($browser_title);
			$content_item->adds($attribute);
			$content_item->setCategory($category);
			$content_item->setContent($content);
			$content_item->setLink($url);
			$content_item->setDomain($domain);
			$content_item->add('mid', $args->mid_lists[$attribute->module_srl]);

			$content_item->setFirstRealImage($oDocument->get('content'), $this->widget_path, $args->skin);
			$content_items[] = $content_item;
		}

		return $content_items;
	}
	
	function _compile($args,$content_items)
	{
		$oTemplate = &TemplateHandler::getInstance();
		// Set variables for widget
		$widget_info = new stdClass();
		$widget_info->modules_info = $args->modules_info;
		$widget_info->list_count = $args->list_count;
		$widget_info->subject_cut_size = $args->subject_cut_size;
		$widget_info->content_cut_size = $args->content_cut_size;
		$widget_info->nickname_cut_size = $args->nickname_cut_size;
		$widget_info->new_window = $args->new_window;

		$widget_info->mid_lists = $args->mid_lists;

		$widget_info->show_browser_title = $args->show_browser_title;
		$widget_info->show_category = $args->show_category;
		
		$widget_info->section_title = $args->section_title;
		$widget_info->section_content = $args->section_content;
		$widget_info->section_name = $args->section_name;
		$widget_info->section_background_color = $args->section_background_color;


		$widget_info->content_items = $content_items;

		
		unset($args->modules_info);

		Context::set('colorset', $args->colorset);
		Context::set('widget_info', $widget_info);

		$tpl_path = sprintf('%sskins/%s', $this->widget_path, $args->skin);
		return $oTemplate->compile($tpl_path, "content");
	}

	function _getSummary($content, $str_size = 50)
	{
		$content = preg_replace('!(<br[\s]*/{0,1}>[\s]*)+!is', ' ', $content);
		// Replace tags such as </p> , </div> , </li> and others to a whitespace
		$content = str_replace(array('</p>', '</div>', '</li>'), ' ', $content);
		// Remove Tag
		$content = preg_replace('!<([^>]*?)>!is','', $content);
		// Replace tags to <, >, " and whitespace
		$content = str_replace(array('&lt;','&gt;','&quot;','&nbsp;'), array('<','>','"',' '), $content);
		// Delete  a series of whitespaces
		$content = preg_replace('/ ( +)/is', ' ', $content);
		// Truncate string
		$content = trim(cut_str($content, $str_size, $tail));
		// Replace back <, >, " to the original tags
		$content = str_replace(array('<','>','"'),array('&lt;','&gt;','&quot;'), $content);
		// Fixed to a newline bug for consecutive sets of English letters
		$content = preg_replace('/([a-z0-9\+:\/\.\~,\|\!\@\#\$\%\^\&\*\(\)\_]){20}/is',"$0-",$content);
		return $content;
	}
}

class onepageWorkItem extends Object
{
	var $browser_title = null;
	var $contents_link = null;
	var $domain = null;

	function onepageWorkItem($browser_title='')
	{
		$this->browser_title = $browser_title;
	}
	function setContentsLink($link)
	{
		$this->contents_link = $link;
	}

	
	function setDomain($domain)
	{
		static $default_domain = null;
		if(!$domain)
		{
			if(is_null($default_domain)) $default_domain = Context::getDefaultUrl();
			$domain = $default_domain;
		}
		$this->domain = $domain;
	}
	function setLink($url)
	{
		$this->add('url', strip_tags($url));
	}
	function setTitle($title)
	{
		$this->add('title', strip_tags($title));
	}
	function setContent($content)
	{
		$this->add('content', removeHackTag($content));
	}
	function setRegdate($regdate)
	{
		$this->add('regdate', strip_tags($regdate));
	}
	function setNickName($nick_name)
	{
		$this->add('nick_name', strip_tags($nick_name));
	}
	// Save author's homepage url. By misol
	function setAuthorSite($site_url)
	{
		$this->add('author_site', strip_tags($site_url));
	}
	function setCategory($category)
	{
		$this->add('category', strip_tags($category));
	}
	function getBrowserTitle()
	{
		return $this->browser_title;
	}
	function getDomain()
	{
		return $this->domain;
	}
	function getContentsLink()
	{
		return $this->contents_link;
	}

	function getLink()
	{
		return $this->get('url');
	}
	function getModuleSrl()
	{
		return $this->get('module_srl');
	}
	function getTitle($cut_size = 0, $tail='...')
	{
		$title = strip_tags($this->get('title'));

		if($cut_size) $title = cut_str($title, $cut_size, $tail);

		$attrs = array();
		if($this->get('title_bold') == 'Y') $attrs[] = 'font-weight:bold';
		if($this->get('title_color') && $this->get('title_color') != 'N') $attrs[] = 'color:#'.$this->get('title_color');

		if(count($attrs)) $title = sprintf("<span style=\"%s\">%s</span>", implode(';', $attrs), htmlspecialchars($title));

		return $title;
	}
	function getContent()
	{
		return $this->get('content');
	}
	function getCategory()
	{
		return $this->get('category');
	}
	function getNickName($cut_size = 0, $tail='...')
	{
		if($cut_size) $nick_name = cut_str($this->get('nick_name'), $cut_size, $tail);
		else $nick_name = $this->get('nick_name');

		return $nick_name;
	}
	function getAuthorSite()
	{
		return $this->get('author_site');
	}
	function getCommentCount()
	{
		$comment_count = $this->get('comment_count');
		return $comment_count>0 ? $comment_count : '';
	}
	function getTrackbackCount()
	{
		$trackback_count = $this->get('trackback_count');
		return $trackback_count>0 ? $trackback_count : '';
	}
	function getRegdate($format = 'Y.m.d H:i:s')
	{
		return zdate($this->get('regdate'), $format);
	}

	function getMemberSrl() 
	{
		return $this->get('member_srl');
	}
	function setFirstRealImage($content, $widget_path, $skin){
		preg_match_all("!<img[^>]*src=(?:\"|\')([^\"\']*?)(?:\"|\')!is", $content, $matches, PREG_SET_ORDER);
		$this->add('realImage', (!empty($matches[0][1]))?$matches[0][1]:sprintf('%sskins/%s/img/work_1.jpg', $widget_path, $skin));
	}

	function getRealImage(){
		return $this->get('realImage');
	}
	function setTags($tags){
		$this->add('tags', $tags);

	}
	function getTags(){
		return $this->get('tags');
	}
}
/* End of file content.class.php */
/* Location: ./widgets/content/content.class.php */
