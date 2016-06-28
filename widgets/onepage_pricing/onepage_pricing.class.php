<?php
class onepage_pricing extends WidgetHandler
{

	function proc($args)
	{
		// The number of displayed lists
		$args->pricing_count = (int)$args->pricing_count;
		if(!$args->pricing_count) $args->pricing_count = 4;

		if(!$args->pricing_grade_popular) $args->pricing_grade_popular = 3;
		if(!$args->pricing_popular_name) $args->pricing_popular_name = '추천';

		if(!$args->pricing_grade1_name) $args->pricing_grade1_name = '등급 1';
		if(!$args->pricing_grade1_money_type) $args->pricing_grade1_money_type = 'fa fa-won';
		if(!$args->pricing_grade1_pay_type) $args->pricing_grade1_pay_type = '월';
		if(!$args->pricing_grade1_money) $args->pricing_grade1_money = '7000';
		if(!$args->pricing_grade1_description) $args->pricing_grade1_description = '섹션 4 등급 1 설명';
		if(empty($args->pricing_grade1_detail))
			$args->grade1_data = array('상세정보 1', '상세정보 2', '상세정보 3');
		else if($args->pricing_grade1_detail)
			$args->grade1_data = explode("\n", $args->pricing_grade1_detail);
		if(!$args->pricing_grade1_button_name) $args->pricing_grade1_button_name = '버튼';
		if(!$args->pricing_grade1_button_link) $args->pricing_grade1_button_link = '#';

		if(!$args->pricing_grade2_name) $args->pricing_grade2_name = '등급 2';
		if(!$args->pricing_grade2_money_type) $args->pricing_grade2_money_type = 'fa fa-won';
		if(!$args->pricing_grade2_pay_type) $args->pricing_grade2_pay_type = '월';
		if(!$args->pricing_grade2_money) $args->pricing_grade2_money = '7000';
		if(!$args->pricing_grade2_description) $args->pricing_grade2_description = '섹션 4 등급 2 설명';
		if(empty($args->pricing_grade2_detail))
			$args->grade2_data = array('상세정보 1', '상세정보 2', '상세정보 3');
		else if($args->pricing_grade2_detail) 
			$args->grade2_data = explode("\n", $args->pricing_grade2_detail);
		if(!$args->pricing_grade2_button_name) $args->pricing_grade2_button_name = '버튼';
		if(!$args->pricing_grade2_button_link) $args->pricing_grade2_button_link = '#';

		if(!$args->pricing_grade3_name) $args->pricing_grade3_name = '등급 3';
		if(!$args->pricing_grade3_money_type) $args->pricing_grade3_money_type = 'fa fa-won';
		if(!$args->pricing_grade3_pay_type) $args->pricing_grade3_pay_type = '월';
		if(!$args->pricing_grade3_money) $args->pricing_grade3_money = '7000';
		if(!$args->pricing_grade3_description) $args->pricing_grade3_description = '섹션 4 등급 3 설명';
		if(empty($args->pricing_grade3_detail))
			 $args->grade3_data = array('상세정보 1', '상세정보 2', '상세정보 3');
		if($args->pricing_grade3_detail)
			 $args->grade3_data = explode("\n", $args->pricing_grade3_detail);
		if(!$args->pricing_grade3_button_name) $args->pricing_grade3_button_name = '버튼';
		if(!$args->pricing_grade3_button_link) $args->pricing_grade3_button_link = '#';

		if(!$args->pricing_grade4_name) $args->pricing_grade4_name = '등급 4';
		if(!$args->pricing_grade4_money_type) $args->pricing_grade4_money_type = 'fa fa-won';
		if(!$args->pricing_grade4_pay_type) $args->pricing_grade4_pay_type = '월';
		if(!$args->pricing_grade4_money) $args->pricing_grade4_money = '7000';
		if(!$args->pricing_grade4_description) $args->pricing_grade4_description = '섹션 4 등급 4 설명';
		if(empty($args->pricing_grade4_detail))
			 $args->grade4_data = array('상세정보 1', '상세정보 2', '상세정보 3');
		if($args->pricing_grade4_detail)
			 $args->grade4_data = explode("\n", $args->pricing_grade4_detail);
		if(!$args->pricing_grade4_button_name) $args->pricing_grade4_button_name = '버튼';
		if(!$args->pricing_grade4_button_link) $args->pricing_grade4_button_link = '#';

		$output = $this->_compile($args);
		return $output;
	}

	function _compile($args)
	{
		$oTemplate = &TemplateHandler::getInstance();
		// Set variables for widget

		$widget_info = $args;
		$widget_info->section_title = $args->section_title;
		$widget_info->section_content = $args->section_content;
		$widget_info->section_name = $args->section_name;
		$widget_info->section_background_color = $args->section_background_color;
		$widget_info->pricing_count = $args->pricing_count;
		$widget_info->new_window_grade1_button = $args->new_window_grade1_button;
		$widget_info->new_window_grade2_button = $args->new_window_grade2_button;
		$widget_info->new_window_grade3_button = $args->new_window_grade3_button;
		$widget_info->new_window_grade4_button = $args->new_window_grade4_button;

		Context::set('widget_info', $widget_info);

		$tpl_path = sprintf('%sskins/%s', $this->widget_path, $args->skin);
		return $oTemplate->compile($tpl_path, "content");
	}
}