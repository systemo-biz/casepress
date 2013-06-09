<?php
//include_once '/home/lopan/work/repositories/dev/wp-load.php';
//include_once 'template_post_type.php';
class cases_loud_heiler {
    var $siteurl;
    var $dbprefix;
    var $events_table = 'cases_notifier_events';
    var $deliver_table = 'cases_notifier_deliveries';
    var $delivery_methods = array('email','sms','timeline');

    function __construct() {
        //Prefixes and urls
        global $wpdb;
        $this->dbprefix = $wpdb->prefix;
        $this->siteurl = get_option('siteurl');
        $this->deliver_table = $this->dbprefix.$this->deliver_table;
        $this->events_table  = $this->dbprefix.$this->events_table;

        //Add some actions
        add_action( 'set_object_terms', array( &$this, 'added_term_relationship' ),10,6);
        add_filter( 'cron_schedules', array( &$this,'cron_add_minutes'),15);
        add_filter( 'cron_schedules', array( &$this,'cron_add_dayly'),16);
        add_action( 'notifier_delivery_hook', array( &$this,'notifier_sender_function'));
        add_action( 'notifier_overdue_delivery_hook', array( &$this,'notifier_overdue_sender_function'));
        add_action( 'updated_post_meta', array (&$this,'updated_post_meta'),10,4);
        add_action( 'transition_post_status', array (&$this,'case_published'),10,3);
        add_action( 'wp_insert_comment', array (&$this,'comment_added'),10,2);
        add_action( 'update_post_meta',array(&$this,'update_postmeta'),10,4);
	add_action( 'add_post_meta',array(&$this,'add_postmeta'),10,3);
        if ( is_admin() ) {
		add_action('cp_activate',array( &$this, 'activate' ));
	 	add_action('cp_deactivate',array( &$this, 'deactivate' ));            
        }
    }

    function cron_add_minutes( $schedules ) {
        $schedules['minutes'] = array(
          'interval' => 5,
          'display' => __( 'Раз в 5 секунд' )
        );
        return $schedules;
    }
    function cron_add_dayly( $schedules ) {
        $schedules['dayly'] = array(
          'interval' => 86400,
          'display' => __( 'Раз в 24 часа' )
        );
        return $schedules;
    }
    //Поиск просроченных задач и отправка уведомлений по ним
    function notifier_overdue_sender_function(){
        $args = array(
            'numberposts' => -1,
            'post_type' => 'cases',
            'post_status' => 'publish',
            'meta_query' =>
                array(
                        'relation'=>'AND',
                        array('key'=>'date_deadline','value'=>array('',0),'compare'=>'NOT IN'),
                        array('key'=>'date_end','compare'=>'NOT EXISTS')
                )
        );
        $posts = get_posts($args);
        $personslist = array();
        foreach ($posts as $post){
            $date_end = get_post_meta($post->ID,'date_end',true);
            if (($date_end == false) || ($date_end == ''))
            {
                $deadline = get_post_meta($post->ID,'date_deadline',true);
                if ($deadline){
                    if (strtotime($deadline)<time()) {
                        $responsible = get_post_meta($post->ID,'responsible',true);
                        $personslist[] = $responsible;
                    }
                }
            }
        }
        foreach (array_unique($personslist) as $person){
           if (get_user_by_person($person)){
               foreach ($this->delivery_methods as $delivery_method){
                    //echo $person.' - да'.PHP_EOL;
                    $this->save_event($person, 'person', 'person_overdue_notify','У вас есть просроченные задачи!',$delivery_method,$this->generate_content($person,'person_overdue_notify',$person,$delivery_method,get_user_by_person($person)),get_user_by_person($person));
               }
           }
        }

    }
    //Функция отправки уведомлений по сгенерированным событиям
    function notifier_sender_function(){
        global $wpdb;

        $results = $wpdb->get_results("SELECT * FROM " . $this->events_table . " WHERE delivered = 0 AND delivery_method = 'email'");
        if ($results){
            foreach ($results as $result){
                if ($result->object_type == 'post')
                {
                    $symbolic_array = array('#187'=>'»','#171'=>'«','#8212'=>'-','amp'=>'&','#038'=>'&');
                    $title = get_the_title($result->object_id);
                    $headers = 'From: ACM <'.$this->siteurl.'>' . '\r\n';
                    foreach ($symbolic_array as $attk => $attv){
                        $title = preg_replace('/&'.$attk.';/i', $attv, $title);
                    }
                    $wpdb->update($this->events_table,array('delivered'=>1),array('event_id'=>$result->event_id));
                    $emaillist = array_unique($this->hailer_get_responsible_mail_list($result->event_id,$result->object_id,$result->user_id));
                    foreach ($emaillist as $userid=>$email){
                        //echo $email.' '.$title.' ['.$result->object_id.']'.' '.$result->delivery_content.PHP_EOL;
                        add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
                        wp_mail($email,$title.' ['.$result->object_id.']',$result->delivery_content);
                        $wpdb->update($this->deliver_table,array('delivered'=>1),array('event_id'=>$result->event_id,'user_id'=>$userid));
                    }
                }
                if ($result->object_type == 'person'){
                    $user = get_userdata(intval($result->user_id));                    
                    $title = $result->noticemeta;
                    $headers = 'From: ACM <'.$this->siteurl.'>' . '\r\n';
                    $wpdb->update($this->events_table,array('delivered'=>1),array('event_id'=>$result->event_id));
                    //echo $result->user_id.' '.$user->user_email.' '.$title.' '.$result->delivery_content.PHP_EOL;
                    add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
                    wp_mail($user->user_email,$title,$result->delivery_content);
                }
            }
        }
    }

    function hailer_get_responsible_mail_list($event_id,$post_id,$user_id){
        global $wpdb;
        $noneeduser = get_userdata($user_id);
        $noneedmail = $noneeduser->user_email;
        foreach((array)explode(",", get_post_meta($post_id, 'participant', true)) as $person){
            $curmail = trim(get_post_meta($person, 'email', true));
            if ($curmail != $noneedmail) $email[get_user_by_person($person)] = $curmail;
        }
        $person = get_post_meta($post_id,'initiator',true);
        $curmail = get_post_meta($person,'email',true);
        if ($curmail != $noneedmail) $email[get_user_by_person($person)] = $curmail;
        $person = get_post_meta($post_id,'responsible',true);
        $curmail = get_post_meta($person,'email',true);
        if ($curmail != $noneedmail) $email[get_user_by_person($person)] = $curmail;
        $email = array_unique($email);
        foreach($email as $userid => $emailvalue){
            if ($userid != 0){
                $wpdb->insert( $this->deliver_table, array(
                    'event_id' => $event_id,
                    'user_id' => $userid,
                    'delivered' => 0
                ));
            }
        }
        return $email;
    }

    function deactivate(){
        wp_clear_scheduled_hook('notifier_delivery_hook');
        wp_clear_scheduled_hook('notifier_overdue_delivery_hook');
    }

    function activate(){
        if (!wp_next_scheduled('notifier_delivery_hook')) wp_schedule_event( time(),'minutes','notifier_delivery_hook' );
        if (!wp_next_scheduled('notifier_overdue_delivery_hook')) wp_schedule_event( time(),'dayly','notifier_overdue_delivery_hook' );
        global $wpdb;
        if ( ! $wpdb->get_var( "SHOW TABLES LIKE '{$this->events_table}'" ) ) {
            $charset_collate = '';
            if ( ! empty( $wpdb->charset ) )
                    $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
            if ( ! empty( $wpdb->collate ) )
                    $charset_collate .= " COLLATE $wpdb->collate";
            $wpdb->query(
                "CREATE TABLE {$this->events_table} (
                    event_id bigint(20) unsigned NOT NULL auto_increment,
                    user_id bigint(20) unsigned NOT NULL,
                    event_type varchar(20) NOT NULL,
                    delivery_method varchar(20) NOT NULL,
                    delivery_content longtext NOT NULL,
                    date datetime NOT NULL,
                    object_id bigint(20) unsigned NOT NULL,
                    object_type varchar(20) NOT NULL,
                    noticemeta longtext NOT NULL,
                    delivered BOOL,
                    PRIMARY KEY  (event_id)
                ) $charset_collate"
            );
        }
        if ( ! $wpdb->get_var( "SHOW TABLES LIKE '{$this->deliver_table}'" ) ) {
            $charset_collate = '';
            if ( ! empty( $wpdb->charset ) )
                    $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
            if ( ! empty( $wpdb->collate ) )
                    $charset_collate .= " COLLATE $wpdb->collate";
            $wpdb->query(
                "CREATE TABLE {$this->deliver_table} (
                    deliver_id bigint(20) unsigned NOT NULL auto_increment,
                    event_id bigint(20) unsigned NOT NULL,
                    user_id bigint(20) unsigned NOT NULL,
                    delivered BOOL,
                    PRIMARY KEY  (deliver_id)
                ) $charset_collate"
            );
        }
    }

    function comment_added($id,$comment) {
        if ($comment->comment_type == ''){
            foreach ($this->delivery_methods as $delivery_method){
                $this->save_event($comment->comment_post_ID, 'post', 'comment_add',$comment->comment_content,$delivery_method,$this->generate_content($comment->comment_post_ID,'comment_add',$comment->comment_content.'<br/><hr/><a href="'.$this->siteurl.'/'.$comment->comment_post_ID.'#respond">Ответить</a> | <a href="'.$this->siteurl.'/'.$comment->comment_post_ID.'#comment-'.$comment->comment_ID.'">Просмотреть комментарий</a>',$delivery_method));
            }
        }
    }

    function case_published($new,$old,$post){
        global $wpdb;
	$post_id = $post->ID;
	//$post = get_post($post_id);
	//update_post_meta(15667,'post_id_test',$post_id.' '.$post->ID.' '.$post->post_status.' '.$post->post_type);
        if (($new == 'publish') && ($post->post_type == 'cases') && ($old == 'draft')){
            $results = $wpdb->get_results("SELECT * FROM " . $this->events_table . " WHERE object_id = ".$post->ID." AND event_type = 'post_add'");
            if (!$results){
                foreach ($this->delivery_methods as $delivery_method){
                    $this->save_event($post_id, 'post', 'post_add',$post->post_title,$delivery_method,$this->generate_content($post_id,'post_add','<hr/>'.$post->post_content.'<br/><hr/><a href="'.$this->siteurl.'/'.$post_id.'#respond">Ответить</a>',$delivery_method));
                }
            }
        }
    }
    function add_postmeta($object_id, $meta_key, $meta_value){
            foreach ($this->delivery_methods as $delivery_method){ 
	if ($meta_key == 'date_start'){
                $this->save_event($object_id, 'post', 'post_add_state',$meta_value,$delivery_method,$this->generate_content($object_id,'post_add_state',$meta_value,$delivery_method));
            }
}
    }
    function update_postmeta($meta_id, $object_id, $meta_key, $meta_value){
        $old_meta = get_post_meta($object_id,'responsible',true);
        if (($meta_key == 'responsible') && ($old_meta != false) && ($old_meta != '')){
            $person = $meta_value;
            $post_id = $object_id;
            foreach ($this->delivery_methods as $delivery_method){
                $this->save_event($person, 'person', 'person_responsible_notify','Вас назначили ответственным в задаче ['.$post_id.']',$delivery_method,$this->generate_content($post_id,'person_responsible_notify',$post_id,$delivery_method,get_user_by_person($person)),get_user_by_person($person));
            }
        }
		if (($meta_key == 'participant')){
			$resp = 1;
			if ($old_meta == false){
				$resp = get_post_meta($object_id,'responsible',true);
			}
			if ($resp)
			{
				$participiants = explode(',',$meta_value);
				$o_participiants = explode(',',$old_meta);
				$post_id = $object_id;
				foreach(array_diff($participiants,$o_participiants) as $person){
					foreach ($this->delivery_methods as $delivery_method){
						$this->save_event($person, 'person', 'person_participiant_notify','Вас назначили соисполниетелем в задаче ['.$post_id.']',$delivery_method,$this->generate_content($post_id,'person_participiant_notify',$post_id,$delivery_method,get_user_by_person($person)),get_user_by_person($person));
					}
				}
			}
        }
    }
    function updated_post_meta($meta_id, $object_id, $meta_key, $meta_value){
        foreach ($this->delivery_methods as $delivery_method){
            if ($meta_key == 'date_deadline'){
                $this->save_event($object_id, 'post', 'post_change_deadline',$meta_value,$delivery_method,$this->generate_content($object_id,'post_change_deadline',$meta_value,$delivery_method));
            }            
            if ($meta_key == 'date_start'){
                $this->save_event($object_id, 'post', 'post_add_state',$meta_value,$delivery_method,$this->generate_content($object_id,'post_add_state',$meta_value,$delivery_method));
            }
        }
    }
    function added_term_relationship($post_id,$terms,$tt_ids,$taxonomy,$append,$old_tt_ids){
        if ($taxonomy == 'results'){
            $term = get_term($terms[0],'results');
            if ($term){
                if ($term->name != ''){
                    foreach ($this->delivery_methods as $delivery_method){
                            $this->save_event($post_id, 'post', 'post_add_result',$term->name,$delivery_method,$this->generate_content($post_id,'post_add_result',$term->name,$delivery_method));
                    }
                }
            }
        } 
    }
    function save_event( $object_id,$object_type, $event_type, $noticemeta,$delivery_method,$delivery_content,$user_id = false ) {
        global $wpdb;
        if ($user_id) $id = $user_id;else $id = get_current_user_id ();
        $wpdb->insert( $this->events_table, array(
            'user_id' => $id,
            'event_type' => $event_type,
            'date' => current_time( 'mysql' ),
            'object_id' => $object_id,
            'delivery_method'=> $delivery_method,
            'delivery_content'=> $delivery_content,
            'object_type' => $object_type,
            'noticemeta' => $noticemeta,
            'delivered' => 0
        ));
    }
    function generate_content($object_id,$event_type, $noticemeta,$delivery_method,$user = 0){
        if ($user == 0) $cur_pers = get_post(get_person_by_user(get_current_user_id())); else $cur_pers = get_post(get_person_by_user($user));
        $object = get_post($object_id);
        $args = array(
            'post_type' => 'notify_templates',
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'notify_template_action',
                    'field' => 'slug',
                    'terms' => $event_type
                ),
                array(
                    'taxonomy' => 'notify_template_method',
                    'field' => 'slug',
                    'terms' => $delivery_method
                )
            )
        );
        $posts = get_posts( $args );
        if (!$posts)
            return 0;
        else {
            foreach ($posts as $post){
                $return = $post->post_content;
            }
            $att = array(
                'user'          =>  '<a href="'.$this->siteurl.'/'.$cur_pers->ID.'">'.$cur_pers->post_title.'</a>',
                'object_id'     =>  $object_id,
                'object_link'   =>  $object->guid,
                'object_title'  =>  $object->post_title,
                'noticemeta'    =>  $noticemeta,
                'content'       =>  wpautop($noticemeta),
                'object_href'   =>  '<a href="'.$object->guid.'">'.$object->post_title.'</a>'
            );
            foreach ($att as $attk => $attv){
                $return = preg_replace('/{'.$attk.'}/i', $attv, $return);
            }
            $return = str_replace('&lt;','<',$return);
            $return = str_replace('&gt;','>',$return);
            return $return;
        }
        return 0;
    }
}
$cases_loud_heiler = new cases_loud_heiler();
?>
