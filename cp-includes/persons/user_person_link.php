<?php

class Cases_Widget_User extends WP_Widget {

    function Cases_Widget_User() {
		$widget_ops = array(
			'classname' => 'cases_widget_user',
			'description' => 'Размещает информацию о текущем пользователе.'
		);
		$this->WP_Widget( 'cases_widget_user', 'Cases.Widget.User', $widget_ops);
    }

    function form($instance) {  
    $title = esc_attr($instance['title']);
		echo '
		<p>
			<label for="'.$this->get_field_id("title").'">'._e("Title:").'</label> 
			<input 	class="widefat"
					id="'.$this->get_field_id("title").'"
					name="'.$this->get_field_name("title").'"
					type="text" value="'.esc_attr($title).'"
			/>
		</p>';
    }  
    
	/**
	 * @see WP_Widget::update()
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 * @return array Updated safe values to be saved.
	 */  
    function update($new_instance, $old_instance) {  
		$instance=$old_instance;
		$instance['title'] = strip_tags($new_instance['title']);  
		return $instance;  
    }  
    
	/**
	 * @see WP_Widget::widget()
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */  
    function widget($args, $instance) {
		if (!is_singular('persons')) return ''; //if not person page - exit
		
		global $wpdb;
		$wpdb->show_errors();			
		global $post;

		$to_get_user_args = array(
			'meta_key' => 'id_person',
			'meta_value' => $post->ID		
		);		
		$user_id = get_users($to_get_user_args);
		
		$person_data = get_post($post->ID);
		
		
		$person_email = get_post_meta($post->ID,'email',true);
		$person_name = $person_data->post_name;
		$person_url = $person_data->guid;
		$person_title_full = $person_data->post_title;
		$person_title1 = strtok($person_title_full," ");
		$person_title2 = strtok(" ");
		$person_title3 = strtok(" ");
		$person_title = $person_title_full;
		
		/*extract($args);
		echo $before_widget; 
		echo $before_title;
		echo $instance['title'];
		echo $after_title;*/
		
		if ($user_id){
			//foreach ($user_id as $user_id[0]){
			echo 'Логин: '.$user_id[0]->user_login.'<br>';
			echo 'Ник: '.$user_id[0]->display_name.'<br>';
			echo 'Почта: '.$user_id[0]->user_email.'<br>';
			echo 'Дата регистрации: '.$user_id[0]->user_registered.'<br>';
			echo '<a href="/wp-admin/user-edit.php?user_id='.$user_id[0]->ID.'">Редактировать профиль</a>';
			//}		
		}
		else{			
			echo "
				<div id='create_user'>
					<form  onsubmit='return false;' method='post'>
						<div style='padding:0px 0px'>Введите логин:</div>
						<div style='padding:5px 0px'><input type='text' name='name' id='name' value='".$person_name."'></div>				
						<div style='padding:0px 0px'>Введите почту:</div>
						<div style='padding:5px 0px'><input type='text' name='email' id='email' value='".$person_email."'></div>
						<div style='padding:0px 0px;display: none;'>GUID:</div>
						<div style='padding:5px 0px;display: none;'><input type='text' name='user_url' id='user_url' value='".$person_url."'></div>
						<div style='padding:0px 0px;display: none;'>NAME:</div>
						<div style='padding:5px 0px;display: none;'><input type='text' name='user_name' id='user_name' value='".$person_title."'></div>
						<div style='padding:0px 0px;display: none;'>PID:</div>
						<div style='padding:5px 0px;display: none;'><input type='text' name='p_post_id' id='p_post_id' value='".$post->ID."'></div>
						<div style='padding:0px 0px'>Введите пароль:</div>
						<div style='padding:5px 0px'><input type='password' name='password' id='password' value=''></div>
						<div style='padding:5px 0px'><input type='submit' value='создать пользователя' onClick='saveform(this.form);return false;'></div>
						
					</form>
				</div>
				";
				echo "
					<script>
						function myalert(){
							alert('myalert');
						}
						function saveform(data){
							jQuery('#create_user').html('');
							var aj_name = data.name.value;
							var aj_email = data.email.value;
							var aj_password = data.password.value;
							var aj_user_url = data.user_url.value;
							var aj_user_name = data.user_name.value;
							var aj_p_post_id = data.p_post_id.value;
							jQuery.ajax({
								type:'POST',
								url: ajaxurl,			
								data: 
								{
									action: 'cases_insert_user', 
									name: aj_name,
									email: aj_email,
									password: aj_password,
									user_url: aj_user_url,
									user_name: aj_user_name,
									p_post_id: aj_p_post_id
								},
								success: function(result) 
								{						
									jQuery('#create_user').html('<span>'+result+'</span>');
								},
								dataType: 'html'													
							});				
							
						}
					</script>
				";
		}	
    }
}

function cases_insert_user(){
	if (isset($_POST["email"])) { $new_user_mail = $_POST["email"]; } else { $new_user_mail = 'No mail';}
	if (isset($_POST["password"])) { $new_user_pass = $_POST["password"]; } else { $new_user_pass = '123';}
	if (isset($_POST["name"])) { $new_user_name = $_POST["name"]; } else { $new_user_name = "new user";}
	if (isset($_POST["user_url"])) { $new_user_url = $_POST["user_url"]; } else { $new_user_url = "new url";}
	if (isset($_POST["user_name"])) { $new_user_display_name = $_POST["user_name"];} 
	
	$user_data = array(		
		'user_login' => $new_user_name,
		'user_email' => $new_user_mail,
		'user_pass' => $new_user_pass,
		'user_url' => $new_user_url,
		'display_name' => $new_user_display_name
	);
	if ( username_exists( $new_user_name ) ) { echo "Это имя пользователя уже используется!"; die();}
	if ( $user = email_exists($new_user_mail) ) 
	{
		$exist_person_id = get_user_meta($user,'id_person',true);
		$exist_person_guid = get_the_guid($exist_person_id);
		$exist_person_name = get_the_title($exist_person_id);
		echo "Пользователь с таким e-mail уже зарегистрирован: <a href='".$exist_person_guid."'>".$exist_person_name."</a>"; die(); 
	
	}
	if (isset($_POST["p_post_id"])) 
	{	
		$linked_post_id = $_POST["p_post_id"];
		$added_id = wp_insert_user($user_data);
		if ($added_id == 1) {echo "Ошибка код - 1, свяжитесь с администратором!"; die();}
		add_user_meta($added_id, 'id_person',$linked_post_id);
		add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
		wp_mail( $new_user_mail, 'Регистрация на сайте '.get_option('siteurl'), 'Добро пожаловать на сайт <a href="'. get_option('siteurl') .'">' . get_option('blogname') .'</a>. Ваш логин:'.$new_user_name.', пароль:'.$new_user_pass.', email: ' . $new_user_mail);
		echo "Пользователь успешно добавлен!";
		die();
	} 
	else 
	{  
		echo "Ошибка код - 2, свяжитесь с администратором!";
	} 
}


function cases_widget_user_load() {
	register_widget('Cases_Widget_User');  
}

function show_person_data($user) {
	echo "<h3>Данные о связанной Персоне</h3>";
	global $wpdb;
	$wpdb->show_errors();			
	global $post;
	$user_email = $user->data->user_email;
	$current_user_id =  $user->data->ID;
	$linked_post_id = get_user_meta($current_user_id,'id_person',true);	
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

add_action(	'wp_ajax_cases_insert_user', 'cases_insert_user');
add_action( 'widgets_init', 'cases_widget_user_load' );
?>