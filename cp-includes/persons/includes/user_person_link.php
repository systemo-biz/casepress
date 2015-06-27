<?php

//Профиль пользователя. Выводим данные о связанной персоне
function show_person_data($user) {
	echo "<h3>Данные о связанной Персоне</h3>";
	
	$user_email = $user->data->user_email;
	$user_id =  $user->data->ID;
	$linked_post_id = get_user_meta($user_id,'id_person',true);	
	if ($linked_post_id)		
		{
			$linked_post = get_post($linked_post_id);
			echo "			
			<a href='".$linked_post->guid."'>".$linked_post->post_title."</a><br>
			<a href='/wp-admin/post.php?post=".$linked_post->ID."&action=edit'>Редактировать Персону</a>";
		}
	else echo "<span>Отсутствуют связанные персоны</span><br><a href='/wp-admin/post-new.php?post_type=persons&linkfrom=user'>Создать Персону</a><br>";
}

add_action( 'show_user_profile', 'show_person_data' );
add_action( 'edit_user_profile', 'show_person_data' );


//Персона. В режиме редактирования можем подключить в систему или связать с пользователем.
class PersonRelationshipUser_CP_Singleton {

private static $_instance = null;
    
private function __construct() {
    add_action( 'add_meta_boxes', array( &$this, 'add' ) );
    add_action( 'save_post', array( &$this, 'save' ), 1, 2 );
}
    
//init metabox
function add() {
    add_meta_box('person_select_user', __('User', 'casepress'), array(&$this, 'person_select_user_callback'), 'persons', 'side');

}

    //print HTML add_contacts
    function person_select_user_callback($post){
        
        $post = get_post();
        
        wp_nonce_field( basename( __FILE__ ), 'person_select_user-nonce' );
        
        $email = get_post_meta($post->ID, 'email',true);

        // Get user for person
		$user_id = get_user_by_person( $post->ID);
        if($user_id) {
            $profile_link = add_query_arg( 'user_id', $user_id, self_admin_url( 'user-edit.php'));
            echo '<a href="' . $profile_link . '">Ссылка на профиль</a>';
        } else {
            ?>

                <label for="user_email_for_added">Укажите адрес эл.почты для приглашения:</label><br/>
                <input type="text" id="user_email_for_added" name="user_email_for_add_cp" class="field_cp" value="<?php echo $email ?>" size="30">
                <label for="add_user_by_email_cp"><input type="checkbox" name="add_user_by_email_cp" id="add_user_by_email_cp" class="field_cp"> Подключить пользователя</label>
                <p>
                    <small>Внимание! Эти данные указываются только если требуется предоставить персоне доступ к системе.</small>
                </p>
            <?php
        }
    } 



    //save meta data
    function save($post_id) {

        // if autosave then cancel
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;

        // check wpnonce
        if ( !isset( $_POST['person_select_user-nonce'] ) || !wp_verify_nonce( $_POST['person_select_user-nonce'], basename( __FILE__ ) ) ) return $post_id;

        //user can?
        if ( !current_user_can( 'edit_post', $post_id ) ) return $post_id;

        $post = get_post($post_id);

        //Проверяем наличие отметки о подключении персоны
        if ($post->post_type == 'persons' and isset($_POST['add_user_by_email_cp'])) {
            
            //Проверка email на адекватность или прекращаем выполнение
            if(is_email( $_POST['user_email_for_add_cp'] )){
                $email = $_POST['user_email_for_add_cp'];
            } else {
                return $post_id;
            }
            
            //Проверяем есть ли пользователь или создаем на основе переданного адреса почты
            $user_id = email_exists($email);
            if($user_id) {
                
                //привязываем персону к найденному пользователю
                update_user_meta($user_id, 'id_person', $post_id);
                
            } else {
                //создаем нового пользователя и привязываем к персоне
                $user_name = str_replace(array(".", "@", "+"), "-", $email);
                $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                $user_id = wp_create_user( $user_name, $random_password, $email );
                wp_new_user_notification($user_id, $random_password);
                update_user_meta($user_id, 'id_person', $post_id);

            }
            //update_post_meta($post_id, 'person_user_id', esc_attr($_POST['user_email_for_add_cp']));
        }
        return $post_id;
    }
    
     
/**
 * Служебные функции одиночки
 */
protected function __clone() {
	// ограничивает клонирование объекта
}
static public function getInstance() {
	if(is_null(self::$_instance))
	{
	self::$_instance = new self();
	}
	return self::$_instance;
}    
    
} $PersonRelationshipUser_CP = PersonRelationshipUser_CP_Singleton::getInstance();