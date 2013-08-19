<?php

$TheACL_Settings = new ACL_Settings;

class ACL_Settings {

    function __construct() {
        add_action('admin_menu', array($this, 'acl_settings_page'));
        add_action( 'wp_ajax_ref_acl', array($this, 'ref_acl_callback') );
        
        add_action('added_post_meta', array($this, 'chg_members'), 10, 4);
        add_action('updated_postmeta', array($this, 'chg_members'), 10, 4);
        add_action( 'delete_post_meta', array($this, 'del_members'), 10, 4 );
    }

    function chg_members($meta_id, $object_id, $meta_key, $meta_value) {
        if (!($meta_key == 'members-cp-posts-sql')) return;
        
        add_post_meta($object_id, 'acl_users_read', get_user_by_person($meta_value));
    }
    
    function del_members($meta_id, $object_id, $meta_key, $meta_value) {
        if (!($meta_key == 'members-cp-posts-sql')) return;
        error_log($meta_key);
        delete_post_meta($object_id, 'acl_users_read', get_user_by_person($meta_value));
    }
    
    function ref_acl_callback() {
        $ids = get_posts("fields=ids&post_type=cases&post_status=publish");
            
        
        foreach ($ids as $post_id){
            $key = 'members-cp-posts-sql';
            $members = get_post_meta( $post_id, $key);
            
            foreach ($members as $member) {
                add_post_meta($post_id, 'acl_users_read', $member);
            }

        }
        
        return true;
    }
    
    function acl_settings_page() {
        add_submenu_page( 'casepress_menu_settings', 'Параметры ACL', 'Параметры ACL', 'manage_options', 'acl_settings', array($this, 'acl_settings_page_callback'));
    }
    
    function acl_settings_page_callback($param) {
    ?>
        <h3>ACL</h3>
        <a href="#ref" id="ref_acl">Обновить данные участников и ACL</a>
        <script>
                (function($) {

                    $("#ref_acl").click(function(){
                        
                        $.ajax({
                            data: ({
                                action: 'ref_acl'
                            }),
                            url: "<?php echo admin_url('admin-ajax.php') ?>",
                            success: function(data) {
                                $("#ref_acl_div").append("<br /><strong>Обновлено</strong>");
                            }                                
                        });
                    });

                })(jQuery);              
        </script>
        <div id="ref_acl_div">

        </div>
    <?php
    }
}

?>