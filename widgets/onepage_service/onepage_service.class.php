<?php
class onepage_service extends WidgetHandler
{

	function proc($args)
	{
		// The number of displayed lists
		$args->service_count = (int)$args->service_count;
		if(!$args->service_count) $args->service_count = 6;
		if(!$args->service_sub1_icon) $args->service_sub1_icon = 'fa fa-book';
		if(!$args->service_sub1_icon_color) $args->service_sub1_icon_color = '#fff';
		if(!$args->service_sub1_icon_bgcolor) $args->service_sub1_icon_bgcolor = '#2aaf67';
		if(!$args->service_sub1_title) $args->service_sub1_title = '서브 1 내용';
		if(!$args->service_sub1_content) $args->service_sub1_content = '서브 1 내용 입력';

		if(!$args->service_sub2_icon) $args->service_sub2_icon = 'fa fa-sitemap';
		if(!$args->service_sub2_icon_color) $args->service_sub2_icon_color = '#fff';
		if(!$args->service_sub2_icon_bgcolor) $args->service_sub2_icon_bgcolor = '#00bff3';
		if(!$args->service_sub2_title) $args->service_sub2_title = '서브 2 내용';
		if(!$args->service_sub2_content) $args->service_sub2_content = '서브 2 내용 입력';

		if(!$args->service_sub3_icon) $args->service_sub3_icon = 'fa fa-cloud';
		if(!$args->service_sub3_icon_color) $args->service_sub3_icon_color = '#fff';
		if(!$args->service_sub3_icon_bgcolor) $args->service_sub3_icon_bgcolor = '#f26522';
		if(!$args->service_sub3_title) $args->service_sub3_title = '서브 3 내용';
		if(!$args->service_sub3_content) $args->service_sub3_content = '서브 3 내용 입력';

		if(!$args->service_sub4_icon) $args->service_sub4_icon = 'fa fa-bolt';
		if(!$args->service_sub4_icon_color) $args->service_sub4_icon_color = '#fff';
		if(!$args->service_sub4_icon_bgcolor) $args->service_sub4_icon_bgcolor = '#e52b50';
		if(!$args->service_sub4_title) $args->service_sub4_title = '서브 4 내용';
		if(!$args->service_sub4_content) $args->service_sub4_content = '서브 4 내용 입력';

		if(!$args->service_sub5_icon) $args->service_sub5_icon = 'xi-paper';
		if(!$args->service_sub5_icon_color) $args->service_sub5_icon_color = '#fff';
		if(!$args->service_sub5_icon_bgcolor) $args->service_sub5_icon_bgcolor = '#2fc5cc';
		if(!$args->service_sub5_title) $args->service_sub5_title = '서브 5 내용';
		if(!$args->service_sub5_content) $args->service_sub5_content = '서브 5 내용 입력';

		if(!$args->service_sub6_icon) $args->service_sub6_icon = 'fa fa-magnet';
		if(!$args->service_sub6_icon_color) $args->service_sub6_icon_color = '#fff';
		if(!$args->service_sub6_icon_bgcolor) $args->service_sub6_icon_bgcolor = '#6173f4';
		if(!$args->service_sub6_title) $args->service_sub6_title = '서브 6 내용';
		if(!$args->service_sub6_content) $args->service_sub6_content = '서브 6 내용 입력';

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
		$widget_info->service_count = $args->service_count;

		$widget_info->service_sub1_icon = $args->service_sub1_icon;
		$widget_info->service_sub1_icon_color = $args->service_sub1_icon_color;
		$widget_info->service_sub1_icon_bgcolor = $args->service_sub1_icon_bgcolor;
		$widget_info->service_sub1_title = $args->service_sub1_title;
		$widget_info->service_sub1_content = $args->service_sub1_content;

		$widget_info->service_sub2_icon = $args->service_sub2_icon;
		$widget_info->service_sub2_icon_color = $args->service_sub2_icon_color;
		$widget_info->service_sub2_icon_bgcolor = $args->service_sub2_icon_bgcolor;
		$widget_info->service_sub2_title = $args->service_sub2_title;
		$widget_info->service_sub2_content = $args->service_sub2_content;

		$widget_info->service_sub3_icon = $args->service_sub3_icon;
		$widget_info->service_sub3_icon_color = $args->service_sub3_icon_color;
		$widget_info->service_sub3_icon_bgcolor = $args->service_sub3_icon_bgcolor;
		$widget_info->service_sub3_title = $args->service_sub3_title;
		$widget_info->service_sub3_content = $args->service_sub3_content;

		$widget_info->service_sub4_icon = $args->service_sub4_icon;
		$widget_info->service_sub4_icon_color = $args->service_sub4_icon_color;
		$widget_info->service_sub4_icon_bgcolor = $args->service_sub4_icon_bgcolor;
		$widget_info->service_sub4_title = $args->service_sub4_title;
		$widget_info->service_sub4_content = $args->service_sub4_content;

		$widget_info->service_sub5_icon = $args->service_sub5_icon;
		$widget_info->service_sub5_icon_color = $args->service_sub5_icon_color;
		$widget_info->service_sub5_icon_bgcolor = $args->service_sub5_icon_bgcolor;
		$widget_info->service_sub5_title = $args->service_sub5_title;
		$widget_info->service_sub5_content = $args->service_sub5_content;

		$widget_info->service_sub6_icon = $args->service_sub6_icon;
		$widget_info->service_sub6_icon_color = $args->service_sub6_icon_color;
		$widget_info->service_sub6_icon_bgcolor = $args->service_sub6_icon_bgcolor;
		$widget_info->service_sub6_title = $args->service_sub6_title;
		$widget_info->service_sub6_content = $args->service_sub6_content;


		Context::set('widget_info', $widget_info);

		$tpl_path = sprintf('%sskins/%s', $this->widget_path, $args->skin);
		return $oTemplate->compile($tpl_path, "content");
	}
}