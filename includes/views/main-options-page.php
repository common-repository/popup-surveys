<?php $options = get_option( 'wps_settings' ); ?>



  <div id="wps_mare_banner"><img src="https://www.mare.io/app/assets/img/logo-transparent.png" alt="Mare Logo" /></div>



  <div id="wps_integration_setup" class="wrap <?php echo isset($options['wps_linked_account']) ? "wps-linked" : "wps-unlinked"; ?>">



    <div id="tab_content">



        <div id="tab2_content" class="tab_content clearfix">



            <div id="wps-linked-account-content" class="form-control<?php echo (!isset($options['wps_linked_account']) || $options['wps_linked_account'] == false) ? " hidden" : ""; ?>">

            

              <h3>Linked Account:</h3>

              

              <div id="mare_api_key" class="inline">

                <span>Api Key:</span><input id="wps_mare_pk_static" type='text' value='<?php echo isset($options['wps_mare_pk']) ? $options['wps_mare_pk'] : ""; ?>' disabled />

              </div>

              

              <div id="mare_site_options">

              

                <input type="text" id="mare_sc_option_static" value="<?php echo isset($options['wps_site']) ? $options['wps_site'] : ""; ?>" disabled />

                

              </div>

              

              <div class="inline">

                <button id="unlink-account" class="button button-default">Unlink Account</button>

              </div>
              
              <div class="form-control">
            
                <input type="checkbox" id="custom_variables" value="include" <?php echo (isset($options['custom_variables']) && $options['custom_variables'] === true) ? 'checked' : ''; ?>/>
                
                <label style="vertical-align: inherit;" for="custom_variables">Send Wordpress custom variables to MARE.io for additional segmentation (required MARE Insights subscription)</label>
              
              </div> 

              

            </div>

          

            <div id="wps-unlinked-account-content"<?php echo (isset($options['wps_linked_account']) && $options['wps_linked_account'] == true) ? " class='hidden'" : ""; ?>>

            

              <h2>MARE.io Integration Options</h2>

            

              <div id="wps-link-account" class="col-6 grey-box">

              

                <form id="wps-login-form" class="form-control" action="javascript:void(0);">

                

                    <input type="hidden" name="call" value="wordpressRegister" />
                    <input type="hidden" name="token1" value="<?php echo hash('sha256',uniqid(mt_rand(1, mt_getrandmax()), true)); ?>" />
                    <input type="hidden" name="token2" value="<?php echo hash('sha256',uniqid(mt_rand(1, mt_getrandmax()), true)); ?>" />
                    
                    <?php if(isset($options['wps_mare_pk'])) : ?>

                      <input type='hidden' name='pk' value='<?php echo $options['wps_mare_pk']; ?>' />
                    
                    <?php endif; ?>
                    
                    <?php if(isset($options['wps_mare_sc'])) : ?>
                    
                      <input type='hidden' name='pk' value='<?php echo $options['wps_mare_sc']; ?>' />
                    
                    <?php endif; ?>

                    <input type="hidden" name="host" value="<?php echo get_site_url(); ?>" />

                    <input type="hidden" name="user_agent" id="wps_user_agent" value="" />

                    <input type="hidden" name="password" id="wps_user_password" value="" />

                

                    <h3>Create or Link your MARE.io account</h3>

                    <p>Enter a new username/password or your existing username/password:</p>

                    <p><span>Email Address: </span><input type="text" name="email_address" id="wps_username" /></p>

                    <p><span>Password: </span><input type="password" name="wps_password" id="wps_password" /></p>

                    <div id="wps-login-form-error" class="alert" style="display: none;"><p></p></div>

                    <p><input type="submit" value="Login/Create Account" class="button button-primary" /></p>
                    
                    <noscript><div class="alert"><p>Script Error! Something is preventing javascript from being loaded on this page</p></div></noscript>

                    <p><em><strong>NOTE:</strong> if you had previously setup this plugin, it will continue to work without linking your account, but you will not see your surveys below.</em></p>

                

                </form>

              

              </div>     

            

              <div id="wps_mare_settings" class="form-control col-6">

                

                <div class="form-group" style="display: none;">

              

                  <div id="mare_api_key" class="inline">

                    <span>Api Key:</span><br /><input id="wps_mare_pk_edit" type='text' name='wps_settings[wps_mare_pk]' value='<?php echo isset($options['wps_mare_pk']) ? $options['wps_mare_pk'] : ""; ?>' disabled />

                  </div>

                  

                  <div id="mare_sc_key" class="inline">

                    <span>Site Code:</span><br /><input id="wps_mare_sc_edit" type='text' name='wps_settings[wps_mare_sc]' value='<?php echo isset($options['wps_mare_sc']) ? $options['wps_mare_sc'] : ""; ?>' disabled />

                  </div>

                  

                  <div id="mare_site_options">

                  

                    <div id="mare_sc_enter">Please log in to sync your account</div>

                    

                    <select id="mare_sc_option" name='wps_settings[wps_mare_sc]' style="display: none;">



                      <option value="">Select a site</option>



                    </select>

                    

                    <div id="mare_sc_error" style="display: none;">Invalid Key</div>

                    

                  </div>

                  

                </div>

                

              </div>

              

            </div>



        </div>



    </div>



  </div>

  

  <div class="wrap">

  

    <h2>

      Your Surveys

      <a class="page-title-action" href="https://www.mare.io/app/edit-survey?cid=200&utm_source=App%20Link&utm_medium=Wordpress&utm_campaign=Plugin" target="_blank">Add New</a>
      <a class="page-title-action" href="https://www.mare.io/app/view-surveys?cid=200&utm_source=App%20Link&utm_medium=Wordpress&utm_campaign=Plugin" target="_blank">View All</a>

    </h2>

    

    <ul class="subsubsub">

      <li><a id="all" href="javascript:void(0)" class="filter-link current">All</a> |</li>

      <li><a id="active" href="javascript:void(0)" class="filter-link">Active</a> |</li>

      <li><a id="inactive" href="javascript:void(0)" class="filter-link">Inactive</a> |</li>

      <li><a id="archived" href="javascript:void(0)" class="filter-link">Archived</a></li>

    </ul>

              

    <table id="wps-list-surveys" class="wp-list-table widefat fixed striped users">

      <thead>

        <tr>

          <th width='410'>Survey</th>

          <th>Views</th>

          <th>Responses</th>
          
          <th>Survey Type</th>

          <th>Status</th>

          <th>Created</th>

        </tr>

      </thead>

      <tbody>

        <tr>

          <td colspan="5"><strong><em>Please Log In to Your Account Above to View Your Surveys</em></strong></td>

        </tr>

      </tbody>

      <tfoot>

        <tr>

          <th>Survey</th>

          <th>Views</th>

          <th>Responses</th>
          
          <th>Survey Type</th>

          <th>Status</th>

          <th>Created</th>

        </tr>

      </tfoot>

    </table>

  

  </div>



  <div id="footerlinks"><p>WP Surveys Powered By <a target="_blank" href="http://www.mare.io/plugin">MARE.io</a></p></div>
  

<script>
<?php
  global $wp_scripts; 
  $scripts = http_build_query($wp_scripts->queue) . "&jquery_ver=" . $wp_scripts->registered['jquery-core']->ver;
?>
_mare_vars = {
    selector: '<?php echo isset($options['wps_selector']) ? $options['wps_selector'] : ''; ?>',
    token: '<?php echo isset($options['wps_token']) ? $options['wps_token'] : ''; ?>',
    host: '<?php echo isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ""; ?>',
    pk: '<?php echo isset($options['wps_mare_pk']) ? $options['wps_mare_pk'] : ""; ?>',
    sc: '<?php echo isset($options['wps_mare_sc']) ? $options['wps_mare_sc'] : ""; ?>',
    linked: <?php echo (isset($options['wps_linked_account']) && $options['wps_linked_account'] == true) ? 'true' : 'false'; ?>,
    scripts: '<?php echo $scripts; ?>'
}
</script>

