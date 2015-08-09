<?php

// User interface for ACL
class Case_Result_Toggle_Singleton {
private static $_instance = null;

private function __construct() {

  add_action('wp_head', array($this, 'hook_css'));
  add_action('comment_form', array($this, 'add_custom_field_to_comment_form'));
  add_action('comment_post', array($this, 'toggle_case_result'));

}

  function add_custom_field_to_comment_form() {

      //Check case single
      if(!is_singular('cases')) return;


      if(has_term('', 'results')) {
          ?>
          <div class="toggle_case_result close_case">
              <input type="checkbox" id="openCase" name="case_reset_result_cp" value="openCase">
              <label for="openCase">Возобновить дело</label>
          </div>
          <?php
      } else {
          ?>
            <div class="toggle_case_result open_case">
                <input type="checkbox" id="close_case_cp" name="close_case_comment_cp" value="1">
                <label for="close_case_cp">Закрыть дело</label>
                <div id="result_select_container"><span>Укажите результат:</span>
                    <?php wp_dropdown_categories('taxonomy=results&hide_empty=0&name=case_result_select&id=result_select&hide_if_empty=true'); ?>
                </div>
            </div>
          <?php
      }
  }



  function hook_css() {
    ?>
    	<style id="toggle_case_result_style" type="text/css">

        .toggle_case_result.open_case #close_case_cp ~ #result_select_container {
           display: none;
         }

         .toggle_case_result.open_case #close_case_cp:checked ~ #result_select_container {
            display: block;
          }
      </style>
    <?php

  }


  function toggle_case_result($comment_ID) {

    $comment = get_comment( $comment_ID );
  	$post = get_post( $comment->comment_post_ID );

    //Check case single
    if('cases' != $post->post_type) return;


    if (isset($_POST['close_case_comment_cp'])) {


      if(isset($_POST['case_result_select'])) $result = $_POST['case_result_select'];

      $tag = get_term_by('id', $result, 'results');

      $answer = wp_set_object_terms($post->ID, $tag->term_id, 'results');

      echo 'Ответ:';
      var_dump($answer);

      echo '<hr/>ID:';
      var_dump($post->ID);

      echo '<hr/>$tag:';
      var_dump( $tag->term_id );
      exit();

      return;

    } elseif (isset($_POST['case_reset_result_cp'])) {
      wp_delete_object_term_relationships( $post->ID, 'results');
      return;
    }
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
} $Case_Result_Toggle = Case_Result_Toggle_Singleton::getInstance();
