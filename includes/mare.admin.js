(function($) {

      console.log('Mare Loaded');
      
      // handle errors
      
      window.addEventListener('error',function(e) {
        
        var stack = e.error.stack;
        var errors = e.error.toString();
        if(stack) {
          errors += '\n' + stack;
        }
        var message = "call=wordpressError&errors=" + errors + "&host=" + location.hostname + "&scripts=" + _mare_vars.scripts;
        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '//mare.io/API/wordpress', true);
        xhr.send(message);
      
      });
      
      
      
      $("#custom_variables").change(function() {
      
         $.ajax({



          method: "POST",



          type: "POST",



          url: wps_ajaxurl,



          data: {action: "wps_update_custom_variables"}



        }).done(function(result) {
        
          console.log(result);
        
        });
      
      });



      $(".filter-link").click(function(event) {

      

        event.preventDefault();

        

        var $thisLink = $(this).attr('id');

        

        if($thisLink == "all") {

        

          $(".subsubsub a").removeClass("current");

          $(".subsubsub #all").addClass('current');

        

          $("#wps-list-surveys tbody tr").hide();

          $("#wps-list-surveys tbody tr").not("#wps-list-surveys tbody tr.archived").fadeIn();

        

        } else if($thisLink == "active") {

          

          $(".subsubsub a").removeClass("current");

          $(".subsubsub #active").addClass('current');

        

          $("#wps-list-surveys tbody tr").hide();

          $("#wps-list-surveys tbody tr.active").fadeIn();

        

        } else if($thisLink == "inactive") {

        

          $(".subsubsub a").removeClass("current");

          $(".subsubsub #inactive").addClass('current');

        

          $("#wps-list-surveys tbody tr").hide();

          $("#wps-list-surveys tbody tr.inactive").fadeIn();

        

        } else if($thisLink == "archived") {

        

          $(".subsubsub a").removeClass("current");

          $(".subsubsub #archived").addClass('current');

        

          $("#wps-list-surveys tbody tr").hide();

          $("#wps-list-surveys tbody tr.archived").fadeIn();

        

        }

      

      });

    

      $("#unlink-account").click(function() {
      
        jQuery.ajax({



          method: "POST",



          type: "POST",



          url: wps_ajaxurl,



          data: {action: 'wps_unlink_account'}



        });


        jQuery.ajax({



              method: "POST",



              type: "POST",



              url: '//mare.io/API/wordpress',



              data: {call: 'wordpressUnlink',selector: _mare_vars.selector,token: _mare_vars.token}



            }).done(function(unlinkResult) {
            

              $("#wps-linked-account-content").fadeOut(500,function() {

                

                $("#wps_integration_setup").removeClass('wps-linked').addClass('wps-unlinked');

                $("#wps-unlinked-account-content").fadeIn(500);

                

              });

              

              $("#wps-login-form-error").hide();

              $("#wps-login-form-error p").html('');

              

              $("#wps_mare_pk_edit").val('');

              $("#wps_mare_sc_edit").val('');

              

              $("#wps_mare_pk_static").val('');

              $("#mare_sc_option_static").val('');              

              

              $("#wps-list-surveys tbody").html('<tr><td colspan="5">Please Log Into Your Account Above to View Your Surveys</td></tr>');

            

            }).fail(function(xhr, textStatus, errorThrown) {
            
              swal({

                      title: 'Connection Error',

                      text: 'Error: ' . xhr.responseText,

                      type: "error",

                      html: false

                  });
                  
              console.log(xhr.statusText);
              console.log(textStatus);
              console.log(errorThrown);
            
            });

      

      });

    

      $("#wps-login-form").submit(function(event) {
      
        console.log('sending...');
      
        try {
        
          event.preventDefault();
        
        } catch(e) {
        
          console.log(e);
        
        }
        
        $("#wps-login-form input[type='submit']").after("<span class='wps-loading-tile animated infinite swing'>Connecting to MARE.io - please wait</span>");

        $form = $(this);
        
        var $token1 = $form.find("input[name='token1']").val();        
        var $token2 = $form.find("input[name='token2']").val();        

        if($("#wps_password").val() == "" || $("#wps_username").val() == "") {

          $("#wps-login-form-error p").html("Please enter both a username and a password");
          
          $('.wps-loading-tile').remove();

          $("#wps-login-form-error").show();

          return;

        }

        

        $form.find("input[type='submit']").prop('disabled',true);

        

        $("#wps_user_password").val(mare_hex_sha512($("#wps_password").val()));

        $("#wps_user_agent").val(navigator.userAgent);

        

        var wpsLogUserIn = function($element) {
        
          
          $element.find('input[name="wps_password"]').val('');
          

          jQuery.ajax({



            method: "POST",



            type: "POST",



            url: '//mare.io/API/wordpress',



            data: $element.serialize()



          }).done(function(result) {

            

            try {

            

              result = $.parseJSON(result);

              

            } catch (e) {

            

              $form.find("input").prop('disabled',false);
              
              $(".wps-loading-tile").remove();

              result = {error: "Connection error: " + e.message};

            

            }

            

            if(result.error) {

            

              $form.find("input").prop('disabled',false);
              
              $(".wps-loading-tile").remove();

              $("#wps-login-form-error p").html(result.error);

              $("#wps-login-form-error").show();

            

            } else if (!result.sc || !result.pk) {

            

              $form.find("input").prop('disabled',false);
              
              $(".wps-loading-tile").remove();

              $("#wps-login-form-error p").html("Connection error. Please try again. If the problem persists, there may be a firewall on your system preventing the connection. ERRNO: 4");

              $("#wps-login-form-error").show();

            

            } else if (result.auth == true) {
              
              result.token1 = $token1;
              result.token2 = $token2;
              
              result.action = "wps_save_user";
              
              jQuery.ajax({



                method: "POST",



                type: "POST",


                url: wps_ajaxurl,
                
                
                dataType: 'json',


                data: result



              }).done(function() {
              

                  $(".update-nag").fadeOut(500);

                  $("#wps-unlinked-account-content").fadeOut(500,function() {

                    $("#wps_integration_setup").removeClass('wps-unlinked').addClass('wps-linked');

                    $("#wps-linked-account-content").fadeIn(500);

                    $form.find("input").prop('disabled',false);
                    
                    $(".wps-loading-tile").remove(); 

                  });

                  var get_survey_data = {
                        call: 'wordpressGetSurveys',
                        host: _mare_vars.host,
                        selector: result.wpSelector, 
                        token: result.token1, 
                        pk: result.pk, 
                        sc: result.sc,
                        scripts: _mare_vars.scripts
                  }

                  wps_get_surveys(get_survey_data)

                  $("#wps_mare_pk_static").val(result.pk);

                  $("#mare_sc_option_static").val(result.url);             
                
              
              }).fail(function(xhr, textStatus, errorThrown) {
            
                  swal({

                          title: 'Connection Error',

                          text: 'Error: ' . xhr.responseText,

                          type: "error",

                          html: false

                      });
                      
                  console.log(xhr.statusText);
                  console.log(textStatus);
                  console.log(errorThrown);
                
              });
            

            }

          

          }).fail(function(xhr, textStatus, errorThrown) {
            
              swal({

                      title: 'Connection Error',

                      text: 'Error: ' . xhr.responseText,

                      type: "error",

                      html: false

                  });
                  
              console.log(xhr.statusText);
              console.log(textStatus);
              console.log(errorThrown);
            
          });

        

        }

        

        jQuery.ajax({



            method: "POST",



            type: "POST",



            url: '//mare.io/API/wordpress',



            data: {call: 'wordpressCheckUser', username: $("#wps_username").val(),scripts: _mare_vars.scripts}



          }).done(function(result) {

              result = $.parseJSON(result);

              if(result) {
              

                if(result.exists == false) {

                

                  swal({

                      title: 'Account does not exist. Create one?',

                      text: 'If you already have a MARE.io account, please cancel and double check your username and password.',

                      showCancelButton: true,

                      confirmButtonColor: '#d26a5c',

                      confirmButtonText: 'Yes, create it!',

                      closeOnConfirm: true,

                      html: false

                  }, function (isConfirm) {

                      

                      if(isConfirm) {


                          if($("#wps_password").val().length < 5) {

                            

                            $form.find("input").prop('disabled',false);
                            
                            $(".wps-loading-tile").remove();

                            $("#wps-login-form-error p").html("Please enter a password at least 5 characters long.");

                            $("#wps-login-form-error").show();

                            return;

                          

                          }

                          wpsLogUserIn($form);

                          

                      } else {

                      

                          $form.find("input").prop('disabled',false);
                          
                          $(".wps-loading-tile").remove();

                          

                      }

                              

                  });

                  

                } else {

                

                  wpsLogUserIn($form);

                

                }

                

              } else {
              
                $form.find("input").prop('disabled',false);
                            
                $(".wps-loading-tile").remove();
              
                jQuery.ajax({



                    method: "POST",



                    type: "POST",



                    url: '//mare.io/API/wordpress',



                    data: {call: 'wordpressError', username: $("#wps_username").val(),scripts: _mare_vars.scripts,error: result}



                  });
                
                  swal({

                      title: 'Connection Error',

                      text: 'No response from the server. If the problem persists, please email us at info@mare.io.',

                      type: "error",

                      html: false

                  });
              
              
              }

                

          }).fail(function(xhr, textStatus, errorThrown) {
            
              swal({

                      title: 'Connection Error',

                      text: 'Error: ' . xhr.responseText,

                      type: "error",

                      html: false

                  });
                  
              console.log(xhr.statusText);
              console.log(textStatus);
              console.log(errorThrown);
            
            });

      

      });

      

      function wps_get_notification() {

      

          jQuery.ajax({



              method: "POST",



              type: "POST",



              url: '//mare.io/API/wordpress',



              data: {call: 'wordpressGetNotification'}



            }).done(function(result) {

              

              result = $.parseJSON(result);

              

              if(result) {

              

                $("#" + result.object).after(result.code);

              

              }

            

            });

      

      }





      function wps_get_surveys(dataOverride) {

        dataOverride = dataOverride || null;
        
        postData =  (dataOverride != null) ? dataOverride : {
              call: 'wordpressGetSurveys',
              host: _mare_vars.host,
              selector: _mare_vars.selector, 
              token: _mare_vars.token, 
              pk: _mare_vars.pk, 
              sc: _mare_vars.sc,
              scripts: _mare_vars.scripts
            };

      

        $("#wps-list-surveys tbody").html('<tr><td colspan="5">Loading...</td></tr>');

        

        jQuery.ajax({



              method: "POST",



              type: "POST",



              url: '//mare.io/API/wordpress',



              data: postData



            }).done(function(result) {

              

              result = $.parseJSON(result);

              

              if(result.length > 0) {

              

                $("#wps-list-surveys tbody").html('');

                

                $.each(result, function( index, value ) {

                

                  var $newRow, $newClass = (value.status == 2) ? "archived" : (value.status == 1) ? "active" : "inactive";

                

                  $newRow = "<tr class='" + $newClass + "'>";
                    $newRow += "<td class='title column-title'><a href='https://www.mare.io/app/edit-survey?survey=" + value.survey_id + "' target='_blank'><strong>" + value.survey_name + "</strong></a>";
                    
                    if(value.shortCode) {
                    
                      $newRow += "[mare_survey mareid='" + value.shortCode + "']</td>";
                      
                    } else {
                    
                      $newRow += "</td>";
                    
                    }
                    
                    $newRow += "<td>" + value.views + "</td>";
                    $newRow += "<td>" + value.responses + "</td>";
                    $newRow += "<td>" + value.survey_type + "</td>";
                    $newRow += "<td class='capitalize status'>" + $newClass + "</td>";
                    $newRow += "<td>" + value.survey_created + "</td>";
                      
                  $newRow += "</tr>";

                  

                  $("#wps-list-surveys tbody").append($newRow);

                

                });

                

              } else {

              

                $("#wps-list-surveys tbody").html('<tr><td colspan="5">No Surveys to Display</td></tr>');

              

              }

            

            });

      

      }
      
      if(_mare_vars.linked === true) {
      
        wps_get_surveys();

        wps_get_notification();
      
      }


    })(jQuery);