<?php
class onepage_testimonial extends WidgetHandler
{

	function proc($args)
	{
		// The number of displayed lists
		$args->testimonial_count = (int)$args->testimonial_count;
		if(!$args->testimonial_count) $args->testimonial_count = 6;
		if(!$args->testimonial_sub1_icon) $args->testimonial_sub1_icon = 'fa fa-quote-left';
		if(!$args->testimonial_sub1_name) $args->testimonial_sub1_name = '서브 1 이름';
		if(!$args->testimonial_sub1_info) $args->testimonial_sub1_info = '서브 1 정보';
		if(!$args->testimonial_sub1_content) $args->testimonial_sub1_content = '서브 1 내용 입력';

		if(!$args->testimonial_sub2_icon) $args->testimonial_sub2_icon = 'fa fa-quote-left';
		if(!$args->testimonial_sub2_name) $args->testimonial_sub2_name = '서브 2 이름';
		if(!$args->testimonial_sub2_info) $args->testimonial_sub2_info = '서브 2 정보';
		if(!$args->testimonial_sub2_content) $args->testimonial_sub2_content = '서브 2 내용 입력';

		if(!$args->testimonial_sub3_icon) $args->testimonial_sub3_icon = 'fa fa-quote-left';
		if(!$args->testimonial_sub3_name) $args->testimonial_sub3_name = '서브 3 이름';
		if(!$args->testimonial_sub3_info) $args->testimonial_sub3_info = '서브 3 정보';
		if(!$args->testimonial_sub3_content) $args->testimonial_sub3_content = '서브 3 내용 입력';

		if(!$args->testimonial_sub4_icon) $args->testimonial_sub4_icon = 'fa fa-quote-left';
		if(!$args->testimonial_sub4_name) $args->testimonial_sub4_name = '서브 4 이름';
		if(!$args->testimonial_sub4_info) $args->testimonial_sub4_info = '서브 4 정보';
		if(!$args->testimonial_sub4_content) $args->testimonial_sub4_content = '서브 4 내용 입력';

		if(!$args->testimonial_sub5_icon) $args->testimonial_sub5_icon = 'fa fa-quote-left';
		if(!$args->testimonial_sub5_name) $args->testimonial_sub5_name = '서브 5 이름';
		if(!$args->testimonial_sub5_info) $args->testimonial_sub5_info = '서브 5 정보';
		if(!$args->testimonial_sub5_content) $args->testimonial_sub5_content = '서브 5 내용 입력';

		if(!$args->testimonial_sub6_icon) $args->testimonial_sub6_icon = 'fa fa-quote-left';
		if(!$args->testimonial_sub6_name) $args->testimonial_sub6_name = '서브 6 이름';
		if(!$args->testimonial_sub6_info) $args->testimonial_sub6_info = '서브 6 정보';
		if(!$args->testimonial_sub6_content) $args->testimonial_sub6_content = '서브 6 내용 입력';


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
		$widget_info->testimonial_count = $args->testimonial_count;

		$widget_info->testimonial_sub1_name = $args->testimonial_sub1_name;
		$widget_info->testimonial_sub1_icon = $args->testimonial_sub1_icon;
		$widget_info->testimonial_sub1_sub_name = $args->testimonial_sub1_sub_name;
		$widget_info->testimonial_sub1_info = $args->testimonial_sub1_info;
		$widget_info->testimonial_sub1_content = $args->testimonial_sub1_content;

		$widget_info->testimonial_sub2_name = $args->testimonial_sub2_name;
		$widget_info->testimonial_sub2_icon = $args->testimonial_sub2_icon;
		$widget_info->testimonial_sub2_sub_name = $args->testimonial_sub2_sub_name;
		$widget_info->testimonial_sub2_info = $args->testimonial_sub2_info;
		$widget_info->testimonial_sub2_content = $args->testimonial_sub2_content;

		$widget_info->testimonial_sub3_name = $args->testimonial_sub3_name;
		$widget_info->testimonial_sub3_icon = $args->testimonial_sub3_icon;
		$widget_info->testimonial_sub3_sub_name = $args->testimonial_sub3_sub_name;
		$widget_info->testimonial_sub3_info = $args->testimonial_sub3_info;
		$widget_info->testimonial_sub3_content = $args->testimonial_sub3_content;

		$widget_info->testimonial_sub4_name = $args->testimonial_sub4_name;
		$widget_info->testimonial_sub4_icon = $args->testimonial_sub4_icon;
		$widget_info->testimonial_sub4_sub_name = $args->testimonial_sub4_sub_name;
		$widget_info->testimonial_sub4_info = $args->testimonial_sub4_info;
		$widget_info->testimonial_sub4_content = $args->testimonial_sub4_content;

		$widget_info->testimonial_sub5_name = $args->testimonial_sub5_name;
		$widget_info->testimonial_sub5_icon = $args->testimonial_sub5_icon;
		$widget_info->testimonial_sub5_sub_name = $args->testimonial_sub5_sub_name;
		$widget_info->testimonial_sub5_info = $args->testimonial_sub5_info;
		$widget_info->testimonial_sub5_content = $args->testimonial_sub5_content;

		$widget_info->testimonial_sub6_name = $args->testimonial_sub6_name;
		$widget_info->testimonial_sub6_icon = $args->testimonial_sub6_icon;
		$widget_info->testimonial_sub6_sub_name = $args->testimonial_sub6_sub_name;
		$widget_info->testimonial_sub6_info = $args->testimonial_sub6_info;
		$widget_info->testimonial_sub6_content = $args->testimonial_sub6_content;



		Context::set('widget_info', $widget_info);

		$tpl_path = sprintf('%sskins/%s', $this->widget_path, $args->skin);
		return $oTemplate->compile($tpl_path, "content");
	}
}