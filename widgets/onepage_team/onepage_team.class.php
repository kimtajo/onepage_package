<?php
class onepage_team extends WidgetHandler
{

	function proc($args)
	{
		// Targets to sort
		if(!in_array($args->order_target, array('regdate','update_order'))) $args->order_target = 'regdate';
		// Sort order
		if(!in_array($args->order_type, array('asc','desc'))) $args->order_type = 'desc';

		// Cut the length of the title
		if(!$args->subject_cut_size) $args->subject_cut_size = 0;
		// Cut the length of contents
		if(!$args->content_cut_size) $args->content_cut_size = 1000;
		// The number of displayed lists
		$args->list_count = (int)$args->list_count;
		if(!$args->list_count) $args->list_count = 3;



		// Set variables used internally
		$oModuleModel = getModel('module');
		$module_srls = $args->modules_info = $args->module_srls_info = $args->mid_lists = array();
		$site_module_info = Context::get('site_module_info');

		$obj = new stdClass();
		if(!$args->module_srls)
		{
			$obj->site_srl = (int)$site_module_info->site_srl;
			$output = executeQueryArray('widgets.onepage_team.getMids', $obj);
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
		}
		else
		{
			$obj->module_srls = $args->module_srls;
			$output = executeQueryArray('widgets.onepage_team.getMids', $obj);
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

		$content_items = $this->_getDocumentItems($args);
		$output = $this->_compile($args,$content_items);
		return $output;
	}

	function _getDocumentItems($args)
	{
		// Get model object from the document module
		$oDocumentModel = getModel('document');

		$obj = new stdClass();
		$obj->module_srl = $args->module_srl;

		// Get a list of documents
		$obj->module_srl = $args->module_srl;
		$obj->sort_index = $args->order_target;
		if($args->order_target == 'list_order' || $args->order_target == 'update_order')
		{
			$obj->order_type = $args->order_type=="desc"?"asc":"desc";
		}
		else
		{
			$obj->order_type = $args->order_type=="desc"?"desc":"asc";
		}
		$obj->statusList = array('PUBLIC');
		$obj->list_count = $args->list_count;
		$output = executeQueryArray('widgets.onepage_team.getNewestDocuments', $obj);
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
				$sub_title = $oDocument->getExtraEidValue('sub_title');
				$oDocument = $GLOBALS['XE_DOCUMENT_LIST'][$document_srls[$i]];
				$module_srl = $oDocument->get('module_srl');
				$thumbnail = $oDocument->getThumbnail(150, 150, 'crop');

				$content_item = new onepageTeamItem( $args->module_srls_info[$module_srl]->browser_title );
				$content_item->adds($oDocument->getObjectVars());
				$content_item->add('original_content', $oDocument->get('content'));
				$content_item->setTitle(htmlspecialchars($oDocument->getTitleText()));
				$content_item->setContent($oDocument->getSummary($args->content_cut_size));
				$content_item->setSubTitle($sub_title);
				$content_item->setThumbnail($thumbnail, $this->widget_path, $args->skin);
				$content_item->add('mid', $args->mid_lists[$module_srl]);
				$content_items[] = $content_item;
			}

		}

		$oSecurity = new Security($content_items);
		$oSecurity->encodeHTML('..variables.content', '..variables.user_name', '..variables.nick_name');

		return $content_items;
	}

	function _compile($args,$content_items)
	{
		$oTemplate = &TemplateHandler::getInstance();
		// Set variables for widget
		$widget_info = new stdClass();
		$widget_info->modules_info = $args->modules_info;
		$widget_info->subject_cut_size = $args->subject_cut_size;
		$widget_info->content_cut_size = $args->content_cut_size;
		$widget_info->new_window = $args->new_window;

		$widget_info->mid_lists = $args->mid_lists;

		$widget_info->section_title = $args->section_title;
		$widget_info->section_content = $args->section_content;
		$widget_info->section_name = $args->section_name;
		$widget_info->section_background_color = $args->section_background_color;
		$widget_info->list_count = $args->list_count;

		$widget_info->content_items = $content_items;


		unset($args->modules_info);

		Context::set('colorset', $args->colorset);
		Context::set('widget_info', $widget_info);

		$tpl_path = sprintf('%sskins/%s', $this->widget_path, $args->skin);
		return $oTemplate->compile($tpl_path, "content");
	}
}

class onepageTeamItem extends Object
{
	var $browser_title = null;
	var $contents_link = null;
	var $domain = null;

	function onepageTeamItem($browser_title='')
	{
		$this->browser_title = $browser_title;
	}

	function setTitle($title)
	{
		$this->add('title', strip_tags($title));
	}
	function setContent($content)
	{
		$this->add('content', removeHackTag($content));
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
	function setSubTitle($sub_title){
		$this->add('subTitle', $sub_title);
	}
	function getSubTitle(){
		return $this->get('subTitle');
	}
	function setThumbnail($thumbnail, $widget_path, $skin)
	{
		$this->add('thumbnail', (empty($thumbnail)?sprintf('%sskins/%s/images/person.jpg', $widget_path, $skin):$thumbnail));
	}
	function getThumbnail()
	{
		return $this->get('thumbnail');
	}
}