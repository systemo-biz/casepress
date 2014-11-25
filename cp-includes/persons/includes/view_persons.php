<?php





class PersonsViewSingltone {
	private static $_instance = null;

	private function __construct() {

        add_action('content_before_wrapper_cp', array($this, 'add_meta_persons_top'));
        add_action('person_meta_top_add_li', array($this, 'add_person_category'));
    }
    
    
    function add_person_category(){
        global $post;

        ?>
            <li>
                <span>Категории персоны: </span>
                <span>
                <?php 
                    $terms = wp_get_post_terms($post->ID, 'subjects_category', array("fields" => "all")); 
                    $i = 0;
                    foreach ($terms as $term){
                        $i++;
                        if($i >1) echo ', ';
                        echo '<a href="' . get_term_link($term->term_id, 'subjects_category')  . '">' . $term->name . '</a>';
                    }
                ?>
                </span>
        </li>
        <?php
    }
    
    
    
    //Доавляем секцию с мета данными
    function add_meta_persons_top(){
        global $post;

        if (!(is_singular('persons') or (is_search() and get_post_type($post->ID) == 'persons') or (get_post_type($post->ID) == 'persons' and is_archive()))) return;

        ?>
        <section id='person-meta'>
            <ul class="list-inline">
                <?php do_action('person_meta_top_add_li'); ?>
            </ul> 
        </section>
        <?php

    }

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

} $PersonsView = PersonsViewSingltone::getInstance();

