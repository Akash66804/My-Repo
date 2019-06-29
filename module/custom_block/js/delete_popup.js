Drupal.behaviors.confirm = {
        attach: function(context, settings) {

            $.each($('.form-submit'), function(){
                var str = $(this).attr('id');
                //find the id for each button
                var remove_btn_id = '#' + $(this).attr('id');

                //apply only to remove buttons and only to those that the functionality is not applied yet. 
                if (str.indexOf("-remove-button") >= 0 && !$(remove_btn_id).hasClass('custom-handler')){

                    //Drupal.behaviours are triggered every time my ajax was submitted and I ended up with multiple events to each button. 
                    //In order to avoid it, add a class to check if the button already has that functionality. 
                    $(remove_btn_id).addClass('custom-handler');
                    $(remove_btn_id).bindFirst('mousedown', function(e, next) {

                       //Display popup and get user's decision.
                        var choice = confirm('Are you sure you want to delete that?');
                        if(!choice){
                            //if user clicks cancel then do nothing
                            e.stopImmediatePropagation();
                        }
                    });
                }
            });
        }
    }