<?php

//Добавляем пользователей в список доступа, из списка участников
class ACL_Int {

    function __construct() {
        add_action('added_post_meta', array($this, 'update_acl_if_updated_members'), 10, 4);
        add_action('updated_postmeta', array($this, 'update_acl_if_updated_members'), 10, 4);
        add_action( 'deleted_post_meta', array($this, 'update_acl_if_updated_members'), 10, 4 );
        
        add_filter( 'acl_users_list',  array($this, 'update_acl_if_update_members'), 10, 2 );
    }
    
    //если изменили список участников, то обновить ACL
    function update_acl_if_updated_members($meta_ids, $object_id, $meta_key, $meta_value){
        
        if($meta_key != 'members-cp-posts-sql') return; // Проверяем нужный нам ключ
        
        $user_id = get_user_by_person($meta_value);
        if(empty($user_id)) return; //Если нет пользователя, то возврат
        
        $members = get_post_meta($object_id, 'members-cp-posts-sql');
        $acl_users = get_post_meta($object_id, 'acl_users');
        
        //Если персона еще в участника, то добавить ACL, иначе - удалить
        if(in_array($meta_value, $members)) {
            if(! in_array($user_id, $acl_users)) add_post_meta($object_id, 'acl_users', $user_id); // если пользователя нет в списке, то добавить
        } else {
            if(in_array($user_id, $acl_users)) delete_post_meta($object_id, 'acl_users', $user_id); // если пользовател в списке, то удалить
        }
        
        return;
    }
    
    //при попытке обновить ACL берем список пользователей из списка участников (не все участники могут иметь пользователей)
    function update_acl_if_update_members($users_ids, $post_id) {
        
        $users_ids_from_members = array();
        
        foreach ($members as $member){
            $user_id = get_user_by_person($member);
            
            if(empty($user_id)) continue;
            
            $users_ids_from_members[] = $user_id;

        }
        
        $users_ids = array_merge($users_ids, $users_ids_from_members); 
        $users_ids =  array_unique($users_ids);

               
        return $users_ids;
    }
}

$TheACL_Int = new ACL_Int;
