<?php

namespace Claxifieds\Forms;

/**
 * Class AdminForm
 */
class AdminForm extends Form
{
    
    /**
     * @param $admin
     */
    public static function primary_input_hidden($admin)
    {
        parent::generic_input_hidden('id', (isset($admin['pk_i_id']) ? $admin['pk_i_id'] : ''));
    }
    
    /**
     * @param null $admin
     */
    public static function name_text($admin = null)
    {
        parent::generic_input_text('s_name', isset($admin['s_name']) ? $admin['s_name'] : '');
    }
    
    /**
     * @param null $admin
     */
    public static function username_text($admin = null)
    {
        parent::generic_input_text('s_username', isset($admin['s_username']) ? $admin['s_username'] : '');
    }
    
    /**
     * @param null $admin
     */
    public static function old_password_text($admin = null)
    {
        parent::generic_password('old_password', '');
    }
    
    /**
     * @param null $admin
     */
    public static function password_text($admin = null)
    {
        parent::generic_password('s_password', '');
    }
    
    /**
     * @param null $admin
     */
    public static function check_password_text($admin = null)
    {
        parent::generic_password('s_password2', '');
    }
    
    /**
     * @param null $admin
     */
    public static function email_text($admin = null)
    {
        parent::generic_input_text('s_email', isset($admin['s_email']) ? $admin['s_email'] : '');
    }
    
    /**
     * @param null $admin
     */
    public static function type_select($admin = null)
    {
        $options = array(
            array('i_value' => '0', 's_text' => __('Administrator'))
        ,
            array('i_value' => '1', 's_text' => __('Moderator'))
        );
        
        parent::generic_select('b_moderator', $options, 'i_value', 's_text', null,
            isset($admin['b_moderator']) ? $admin['b_moderator'] : null);
    }
    
    public static function js_validation()
    {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                // Code for form validation
                $("form[name=admin_form]").validate({
                    rules: {
                        s_name: {
                            required: true,
                            minlength: 3,
                            maxlength: 50
                        },
                        s_username: {
                            required: true,
                            minlength: 3,
                            maxlength: 50
                        },
                        s_email: {
                            required: true,
                            email: true
                        },
                        old_password: {
                            required: false
                        },
                        s_password: {
                            required: false,
                            minlength: 5
                        },
                        s_password2: {
                            required: false,
                            minlength: 5,
                            equalTo: "#s_password"
                        }
                    },
                    messages: {
                        s_name: {
                            required: "<?php _e('Name: this field is required'); ?>.",
                            minlength: "<?php _e('Name: enter at least 3 characters'); ?>.",
                            maxlength: "<?php _e('Name: no more than 50 characters'); ?>."
                        },
                        s_username: {
                            required: "<?php _e('Username: this field is required'); ?>.",
                            minlength: "<?php _e('Username: enter at least 3 characters'); ?>.",
                            maxlength: "<?php _e('Username: no more than 50 characters'); ?>."
                        },
                        s_email: {
                            required: "<?php _e('Email: this field is required'); ?>.",
                            email: "<?php _e('Invalid email address'); ?>."
                        },
                        s_password: {
                            minlength: "<?php _e('Password: enter at least 5 characters'); ?>."
                        },
                        s_password2: {
                            equalTo: "<?php _e("Passwords don't match"); ?>."
                        }
                    },
                    errorLabelContainer: "#error_list",
                    wrapper: "li",
                    invalidHandler: function (form, validator) {
                        $('html,body').animate({scrollTop: $('h1').offset().top}, {duration: 250, easing: 'swing'});
                    },
                    submitHandler: function (form) {
                        $('button[type=submit], input[type=submit]').attr('disabled', 'disabled');
                        form.submit();
                    }
                });
            });
        </script>
        <?php
    }
    
}
