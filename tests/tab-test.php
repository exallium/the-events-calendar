<?php

add_action( 'tribe_settings_tabs_after_help', 'tribe_test_tab' );
function tribe_test_tab() {
	$testTabArgs = array(
		'fields' => array(
			'heading_test' => array( 'type' => 'heading', 'label' => 'Heading Test'),
			'html_test' => array( 'type' => 'html', 'label' => 'Html Test', 'html' => 'some random html'),
			'text_test_small' => array( 'type' => 'text', 'label' => 'This is a small text field', 'size' => 'small', 'validation_type' => 'positive_int'),
			'text_test_medium' => array( 'type' => 'text', 'label' => 'This is a medium text field'),
			'text_test_large' => array( 'type' => 'text', 'label' => 'This is a large text field', 'size' => 'large'),
			'textarea_test' => array( 'type' => 'textarea', 'label' => 'This is a textarea field', 'size' => 'large'),
			'radio_test' => array( 'type' => 'radio', 'label' => 'This is a radio field', 'options' => array('1' => 'one', '2' => 'two')),
			'checkbox_text' => array( 'type' => 'checkbox_bool', 'label' => 'This is a checkbox field'),
			'dropdown_test' => array( 'type' => 'dropdown', 'label' => 'This is a dropdown field', 'options' => array('1' => 'one', '2' => 'two')),
			'dropdown_chosen_test' => array( 'type' => 'dropdown_chosen', 'label' => 'This is a chosen dropdown field', 'options' => array('1' => 'one', '2' => 'two')),
		),
	);
	new TribeSettingsTab( 'test', __('Test', 'tribe-events-calendar'), $testTabArgs );
}