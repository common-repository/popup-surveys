<?php



$surveyTable = 'wp_wpSurveyTable';



// add the options page to the menu

add_action('admin_menu', 'wpsurvey_menu');



function wpsurvey_menu() {



  global $wpdb, $survey_table_name, $wps_settings_page;



  $options = get_option( 'wps_settings' );



	$wps_settings_page = add_menu_page('Popup Surveys Menu', 'MARE.io Surveys', 'manage_options' , 'wp-survey', 'build_survey_options');
	

	//call register settings function

	add_action( 'admin_init', 'register_popup_survey_options' );

	

}



function register_popup_survey_options() {



	register_setting( 'wpsSettingsPage', 'wps_settings' );

	$options = get_option( 'wps_settings' );

	add_settings_section(

		'wps_mare_settings_section', 

		__( '', 'wordpress' ), 

		'wps_mare_settings_callback', 

		'wpsSettingsPage'

	);





}


function build_survey_options() {


  include("scripts-styles.php"); 
  
  
  include("views/main-options-page.php");
  

}