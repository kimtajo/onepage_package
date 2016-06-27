<?php
/* Copyright (C) NAVER <http://www.navercorp.com> */
/**
 * @class content
 * @author NAVER (developers@xpressengine.com)
 * @brief widget to display content
 * @version 0.1
 */
class onepage_features extends WidgetHandler
{
	/**
	 * @brief Widget handler
	 *
	 * Get extra_vars declared in ./widgets/widget/conf/info.xml as arguments
	 * After generating the result, do not print but return it.
	 */

	function proc($args)
	{
		// The number of displayed lists
		$args->features_count = (int)$args->features_count;
		if(!$args->features_count) $args->features_count = 6;
		if(!$args->features_sub1_icon) $args->features_sub1_icon = 'fa fa-book';
		if(!$args->features_sub1_title) $args->features_sub1_title = '서브 1 내용';
		if(!$args->features_sub1_content) $args->features_sub1_content = '서브 1 내용 입력';

		if(!$args->features_sub2_icon) $args->features_sub2_icon = 'fa fa-sitemap';
		if(!$args->features_sub2_title) $args->features_sub2_title = '서브 2 내용';
		if(!$args->features_sub2_content) $args->features_sub2_content = '서브 2 내용 입력';

		if(!$args->features_sub3_icon) $args->features_sub3_icon = 'fa fa-cloud';
		if(!$args->features_sub3_title) $args->features_sub3_title = '서브 3 내용';
		if(!$args->features_sub3_content) $args->features_sub3_content = '서브 3 내용 입력';

		if(!$args->features_sub4_icon) $args->features_sub4_icon = 'fa fa-bolt';
		if(!$args->features_sub4_title) $args->features_sub4_title = '서브 4 내용';
		if(!$args->features_sub4_content) $args->features_sub4_content = '서브 4 내용 입력';

		if(!$args->features_sub5_icon) $args->features_sub5_icon = 'xi-paper';
		if(!$args->features_sub5_title) $args->features_sub5_title = '서브 5 내용';
		if(!$args->features_sub5_content) $args->features_sub5_content = '서브 5 내용 입력';

		if(!$args->features_sub6_icon) $args->features_sub6_icon = 'fa fa-magnet';
		if(!$args->features_sub6_title) $args->features_sub6_title = '서브 6 내용';
		if(!$args->features_sub6_content) $args->features_sub6_content = '서브 6 내용 입력';

		if(!$args->use_features_button) $args->use_features_button = 'Y';
		if(!$args->features_button_name) $args->features_button_name = '버튼';
		if(!$args->features_button_link) $args->features_button_link = '#';

		$output = $this->_compile($args);
		return $output;
	}

	function _compile($args)
	{
		$oTemplate = &TemplateHandler::getInstance();
		// Set variables for widget
		$widget_info = new stdClass();

		$widget_info->section_title = $args->section_title;
		$widget_info->section_content = $args->section_content;
		$widget_info->section_name = $args->section_name;
		$widget_info->section_background_color = $args->section_background_color;
		$widget_info->features_count = $args->features_count;

		$widget_info->features_sub1_icon = $args->features_sub1_icon;
		$widget_info->features_sub1_title = $args->features_sub1_title;
		$widget_info->features_sub1_content = $args->features_sub1_content;

		$widget_info->features_sub2_icon = $args->features_sub2_icon;
		$widget_info->features_sub2_title = $args->features_sub2_title;
		$widget_info->features_sub2_content = $args->features_sub2_content;

		$widget_info->features_sub3_icon = $args->features_sub3_icon;
		$widget_info->features_sub3_title = $args->features_sub3_title;
		$widget_info->features_sub3_content = $args->features_sub3_content;

		$widget_info->features_sub4_icon = $args->features_sub4_icon;
		$widget_info->features_sub4_title = $args->features_sub4_title;
		$widget_info->features_sub4_content = $args->features_sub4_content;

		$widget_info->features_sub5_icon = $args->features_sub5_icon;
		$widget_info->features_sub5_title = $args->features_sub5_title;
		$widget_info->features_sub5_content = $args->features_sub5_content;

		$widget_info->features_sub6_icon = $args->features_sub6_icon;
		$widget_info->features_sub6_title = $args->features_sub6_title;
		$widget_info->features_sub6_content = $args->features_sub6_content;

		$widget_info->use_features_button = $args->use_features_button;
		$widget_info->features_button_name = $args->features_button_name;
		$widget_info->features_button_link = $args->features_button_link;


		Context::set('widget_info', $widget_info);

		$tpl_path = sprintf('%sskins/%s', $this->widget_path, $args->skin);
		return $oTemplate->compile($tpl_path, "content");
	}
}
/* End of file content.class.php */
/* Location: ./widgets/content/content.class.php */
