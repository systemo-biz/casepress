<?php
	/**
	 * Functions Action Box
	 */
function actions_box_shortcode(){
    ob_start();
?>
<ul class="actions_box">
<?php do_action('add_action_cp'); ?>
</ul>

 <?php
    $html = ob_get_contents();
    ob_get_clean();

    return $html;
}
add_shortcode('actions_box', 'actions_box_shortcode');
	

function add_item_to_action_box_cp(){
    ?>
    <li><a href="<?php echo admin_url( 'post-new.php?post_type=cases'); ?>">Добавить дело</a></li>  
    <?php
    
    if (is_tax('functions') ) {
        global $wp_query;
        $queried_object = get_queried_object();
        $term_id = $queried_object->term_id; 
        ?>
        <li>
            <a href="<?php echo admin_url( 'post-new.php?post_type=cases&case_category_id='. $term_id ); ?>">Добавить дело в текущий список</a> 
        </li>               
        <?php
    }
		
    if (is_singular('cases')){ ?>
        <li>
            <a href="<?php echo admin_url( 'post-new.php?post_type=cases&case_parent_id='. get_the_ID() ); ?>">Добавить подзадачу</a>
        </li>
        <?php
    }
 
} add_action('add_action_cp', 'add_item_to_action_box_cp');