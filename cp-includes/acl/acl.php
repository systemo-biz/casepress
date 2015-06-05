<?php
/*Добавляет мета-поля acl_users_s с ID каждого участника дела. При загрузке списка дел с помощью запроса к БД выбираются только те дела,
участником которых является данный пользователь.
*/
class ACL {
        function __construct() {
        add_action('added_post_meta', array($this, 'update_acl_if_updated_members'), 10, 4);
        add_action('updated_post_meta', array($this, 'update_acl_if_updated_members'), 10, 4);
        add_action('deleted_post_meta', array($this, 'update_acl_if_updated_members'), 10, 4);
        add_filter('posts_where', array($this, 'acl_filter_where'), 10, 1);
    }
    //если изменили список участников, то обновить ACL
    function update_acl_if_updated_members($meta_ids, $object_id, $meta_key, $meta_value){
        
        if($meta_key != 'members-cp-posts-sql') return; // Проверяем нужный нам ключ
        
        $user_id = get_user_by_person($meta_value);
        if(empty($user_id)) return; //Если нет пользователя, то возврат
        
        $members = get_post_meta($object_id, 'members-cp-posts-sql');
        $acl_users_s = get_post_meta($object_id, 'acl_users_s');
        
        //Если персона еще в участниках, то добавить ACL, иначе - удалить
        if(in_array($meta_value, $members)) {
            if(! in_array($user_id, $acl_users)) add_post_meta($object_id, 'acl_users_s', $user_id); // если пользователя нет в списке, то добавить
        } else {
            if(in_array($user_id, $acl_users)) delete_post_meta($object_id, 'acl_users_s', $user_id); // если пользовател в списке, то удалить
        }
        
        return;
    }
    function acl_filter_where($where){
        
    global $wpdb;
        
    $current_user_id = get_current_user_id();
    //Если это администратор, редактор или кто то с правом доступа, то отменяем контроль
    if (user_can($current_user_id, 'full_access_to_posts') or user_can($current_user_id, 'editor') or user_can($current_user_id, 'administrator')) return $where;
        
    $where .= " AND 
        if(" . $wpdb->posts . ".post_type = 'cases', 
            if(" . $wpdb->posts . ".ID IN (
                    SELECT post_id 
                    FROM " . $wpdb->postmeta ." 
                    WHERE 
                        " . $wpdb->postmeta .".meta_key = 'acl_users_s' 
                        AND " . $wpdb->postmeta .".post_id = " . $wpdb->posts . ".ID
                        AND " . $wpdb->postmeta .".meta_value = " . $current_user_id ."
                )
            ,1,0),
        1)=1";
        return $where;
}
}
$TheACL = new ACL;