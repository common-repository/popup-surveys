<?php

/*

Plugin Name: Popup Surveys & Polls for Wordpress (Mare.io)

Plugin URI: http://www.mare.io

Description: Popup Surveys & Polls links your Wordpress website with your MARE.io account. Create survey questions, pop-up links and pop-up email optin forms.

Version: 1.36

Author: dusthazard

Author URI: http://www.mare.io

License: GPL2



    Copyright 2016  Eric Sloan  (contact : http://www.mare.io)



    This software is released under GPL



*/



$versionNumber = "1.36";

include(dirname( __FILE__ ) . "/includes/optionspage.php");

include(dirname( __FILE__ ) . "/config.php");

include(dirname( __FILE__ ) . "/includes/functions.php");




//**************************************
// add the code to the footer
//**************************************




function wps_survey_code() {

  $options = get_option( 'wps_settings' );
  
    ?>

    

<script>

  <?php if(isset($options) && isset($options['custom_variables']) && $options['custom_variables'] === true) : ?>
  
    var _mare_v = {}
    
    <?php if($wps_post_type = get_post_type()) : ?>
    _mare_v['$wp_post_type'] = '<?php echo $wps_post_type; ?>';
    <?php endif; ?>
    
    <?php 
    
    if($wps_user_data = wp_get_current_user()) : 
    
      if(!empty($wps_user_data->user_email)) :
      
        $wps_user_roles = $wps_user_data->roles;
      
        $wps_send_user_roles = "";
        
        // user roles
        
        if(isset($wps_user_roles)) {
        
          foreach($wps_user_roles as $wps_user_role) {
          
            $wps_send_user_roles = ($wps_send_user_roles == "") ? $wps_user_role : $wps_send_user_roles . ", " . $wps_user_role;
          
          }
          
          ?>
          
          _mare_v.wp_user_roles = '<?php echo $wps_send_user_roles; ?>';
          
          <?php 
          
        }
      
      ?>
        
        <?php if(isset($wps_user_data->user_email)) : ?>
        _mare_v['$email'] = '<?php echo $wps_user_data->user_email; ?>';
        <?php endif; ?>
        
        <?php if(isset($wps_user_data->ID)) : ?>
        _mare_v.wp_user_id = '<?php echo $wps_user_data->ID; ?>';
        <?php endif; ?>
        
        <?php if(is_user_logged_in()) : ?>
          _mare_v.wp_user_logged_in = 'true';
        <?php else : ?>
          _mare_v.wp_user_logged_in = 'false';
        <?php endif; ?>
        
      <?php endif; ?>
      
    <?php endif; ?>
  
  <?php endif; ?>

  var _mare_pk = '<?php echo $options['wps_mare_pk']; ?>'; 

  var _mare_wp_sc = '<?php echo $options['wps_mare_sc']; ?>';

  (function() {

    var mare = document.createElement('script'); mare.type = "text/javascript"; mare.async = true;

    mare.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'mare.io/API/script.js';

    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(mare, s);

  })();

</script>    

    

    <?php

}

add_action('wp_footer', 'wps_survey_code', 100);



//**************************************
// add all of the admin scripts
//**************************************



function wpsurvey_admin_scripts($hook) {

  global $wps_settings_page;

  if($hook != $wps_settings_page)
    return;

  wp_enqueue_script( 'wpsurvey_hash', plugins_url() . '/popup-surveys/includes/vendor/sha512.js', array('jquery'), '1.0.0', true );
  
  wp_enqueue_style( 'sweetalert_styles', plugins_url() . '/popup-surveys/includes/vendor/sweetalert.min.css' );
  
  wp_enqueue_script( 'sweetalert', plugins_url() . '/popup-surveys/includes/vendor/sweetalert.min.js', array('jquery'), '1.0.0', true );
  
  wp_enqueue_script( 'wpsurvey_admin', plugins_url() . '/popup-surveys/includes/mare.admin.js', array('jquery'), '1.0.17', true );

}

add_action( 'admin_enqueue_scripts','wpsurvey_admin_scripts');


//**************************************
// make sure we can access the ajax 
// url via javascript
//**************************************


function wpsurvey_ajaxurl() {

?>

<script type="text/javascript">var wps_ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';</script>

<?php

}

add_action('admin_head','wpsurvey_ajaxurl');



//**************************************
// add the shortcode for embedded surveys
//**************************************



function mare_survey_embed( $atts ){
  
  if(!isset($atts['mareid']))
    return false;

  $code = "<div class='_mare_embed' data-mareid='" . $atts['mareid'] . "'";

	if(isset($atts['width'])) {
	
    $code .= " data-marewidth='{$atts['width']}'";
	
	}
	
	$code .= "></div>";
	
	return $code;
	
}

add_shortcode( 'mare_survey', 'mare_survey_embed' );



//**************************************
// check for the survey options value, 
// and if it doesn't exist then create 
// a link to link account with MARE
//**************************************



$options = get_option( 'wps_settings' );

if(!isset($options['wps_linked_account']) || $options['wps_linked_account'] == "") {

  function wps_mare_admin_notice() {

      ?>

      <div class="update-nag">

          <p><strong>Poup surveys and polls is not linked</strong> - <a href="<?php echo admin_url(); ?>admin.php?page=wp-survey">click here to link account</a></p>

      </div>

      <?php

  }

  add_action( 'admin_notices', 'wps_mare_admin_notice' );


}