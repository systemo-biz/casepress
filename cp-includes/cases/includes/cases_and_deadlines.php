<?php
/*
Plugin Name: CasePress. Работа с дидлайнами в навигации
Description: Плагин добавляет элементы для навигации по нарушению сроков у дел
*/



class Deadline_Nav_CP_Singltone {
private static $_instance = null;
private function __construct() {
    //Включаем фильтр по нарушению срока
    add_action('pre_get_posts', array($this, 'filter_cases_deadline'));
    add_action('add_navigation_item', array($this, 'add_case_items_deadline'));
}
    
    

//Добавляем элементы в навигацию
function add_case_items_deadline(){
	$user_id = get_current_user_id();
	$person_id = get_person_by_user($user_id);
	?>
    <li><a href="/cases?open=yes&deadline=yes&meta_responsible-cp-posts-sql=<?php echo $person_id; ?>">Срочные</a></li>
    <li><a href="/cases?open=yes&deadline=fail&meta_responsible-cp-posts-sql=<?php echo $person_id; ?>">Нарушен срок</a></li>
	<?php
} 


//Фильтруем дела по сроку, если стоит параментр deadline
function filter_cases_deadline($query) {

	if(empty($_REQUEST['deadline'])) return;

	if(! $query->is_main_query()) return;

    $meta_query = $query->get('meta_query');

    $deadline = $_REQUEST['deadline'];    
    if($deadline == 'fail') {
		$meta_query[] = array(
            'key'           =>'deadline_cp',
            'value'        =>current_time('mysql',0),
            'compare'   =>  '<=',
            'type' => 'DATETIME',
        );
	}
    if($deadline == 'yes') {
		$meta_query[] = array(
            'key'           =>'deadline_cp',
            'value'        => 0,
            'compare'   =>  '>',        );
        $query->set('meta_key', 'deadline_cp');
        $query->set('orderby', 'meta_value');
        $query->set('order', 'ASC');
	}
	$query->set('meta_query',$meta_query);

	return;

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
    
} $Deadline_Nav_CP = Deadline_Nav_CP_Singltone::getInstance();
    
