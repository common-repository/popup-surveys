<?php



$protocol = (is_ssl()) ? "https://" : "http://";

$apiurl = $protocol . "mare.io/API/wordpress";





// Ajax functions to deal with forms

add_action('wp_ajax_wps_update_custom_variables', 'wps_update_custom_variables');

function wps_update_custom_variables() {

  $wpsoptions = get_option( 'wps_settings' );
  
  if(isset($wpsoptions)) {
  
    $wpsoptions['custom_variables'] = isset($wpsoptions['custom_variables']) ? ($wpsoptions['custom_variables'] === true) ? false : true : true;
    
    update_option('wps_settings',$wpsoptions);
    
    echo 'success';
  
  } else {
  
    echo 'false';
    
  }
  
  die();

}



add_action('wp_ajax_get_mare_sites', 'get_mare_sites_callback');







function get_mare_sites_callback() {



  global $apiurl;



  $postdata = http_build_query(



      array(



          'call' => 'getSites',



          'pk' => $_POST['pk'],



          'host' => $_POST['host'],



          'selected' => $_POST['selected']



      )



   );



      



  $opts = array('http' =>



      array(



          'method'  => 'POST',



          'header'  => 'Content-type: application/x-www-form-urlencoded',



          'content' => $postdata



      )



   );



   



  $context  = stream_context_create($opts);







  $url = $apiurl;



  $json = file_get_contents($url, false, $context);



  echo $json;



  



  die();







}


add_action('wp_ajax_wps_save_user', 'mare_save_user_info');

function mare_save_user_info() {

  $auth = $_POST['userSelector'] . "." . $_POST['token2'];

  setCookie("_mare_at",$auth,time()+3600*24*30,"/","mare.io");

  $wpsoptions = get_option( 'wps_settings' );

  $wpsoptions['wps_selector'] = $_POST['wpSelector'];

  $wpsoptions['wps_token'] = $_POST['token1'];

  $wpsoptions['wps_mare_pk'] = $_POST['pk'];

  $wpsoptions['wps_mare_sc'] = $_POST['sc'];

  $wpsoptions['wps_site'] = $_POST['url'];

  $wpsoptions['wps_linked_account'] = true;

  update_option('wps_settings',$wpsoptions);

}



add_action('wp_ajax_wps_unlink_account', 'mare_unlink_callback');



function mare_unlink_callback() {

  $options = get_option( 'wps_settings' );

  $wpsoptions['wps_selector'] = null;

  $wpsoptions['wps_token'] = null;

  $wpsoptions['wps_site'] = null;

  $wpsoptions['wps_linked_account'] = null;

  update_option('wps_settings',$wpsoptions);

  

  die();



}



add_action('wp_ajax_wps_get_surveys', 'mare_get_surveys_callback');



function mare_get_surveys_callback() {



  global $apiurl;



  $options = get_option( 'wps_settings' );

  

  if(isset($options['wps_linked_account']) && $options['wps_linked_account'] == true) {

  

    $postdata = http_build_query(



        array(



            'call' => 'wordpressGetSurveys',



            'host' => $_SERVER['HTTP_HOST'],

            

            'selector' => $options['wps_selector'],

            

            'token' => $options['wps_token'],

            

            'pk' => $options['wps_mare_pk'],

            

            'sc' => $options['wps_mare_sc']



        )



     );



        



    $opts = array('http' =>



        array(



            'method'  => 'POST',



            'header'  => 'Content-type: application/x-www-form-urlencoded',



            'content' => $postdata



        )



     );



     



    $context  = stream_context_create($opts);



    $url = $apiurl;



    $json = file_get_contents($url, false, $context);

    

    echo $json;

    

  }



  die();



}



add_action('wp_ajax_wps_mare_login_check', 'mare_login_check_callback');



function mare_login_check_callback() {



  global $apiurl;

  

  $postdata = http_build_query(



      array(



          'call' => 'wordpressCheckUser',

          'username' => $_POST['username']



      )



   );



      



  $opts = array('http' =>



      array(



          'method'  => 'POST',



          'header'  => 'Content-type: application/x-www-form-urlencoded',



          'content' => $postdata



      )



   );



   



  $context  = stream_context_create($opts);



  $url = $apiurl;



  $json = file_get_contents($url, false, $context);

  

  echo $json;



  die();

  

}