<?php

function add_panel_navigation_cp(){
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Навигация</h3>
  </div>
  <div class="panel-body">
      <?php echo do_shortcode('[search_context_form]'); ?>
  </div>
</div>
<?php
} add_action('add_item_to_empty_sidebar_commone', 'add_panel_navigation_cp');


function add_panel_actions_cp(){
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Действия</h3>
  </div>
  <div class="panel-body">
      <?php echo do_shortcode('[actions_box]'); ?>
  </div>
</div>
<?php
} add_action('add_item_to_empty_sidebar_commone', 'add_panel_actions_cp');


//Добавляем хук в тему для вызова наших сайдбаров
function add_text_to_commone_sidebar_cp($index){
    
    //Если есть виджеты - возврат
    if(is_active_sidebar($index)) return;
    
    // Если текущий сайдбар не общий - возврат
    if($index != 'commone') return;
    
    do_action('add_item_to_empty_sidebar_commone');
 
} add_action( 'dynamic_sidebar_after', 'add_text_to_commone_sidebar_cp' );