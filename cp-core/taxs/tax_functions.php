<?php

global $cpanel;
function register_functions_tax() {
  global $cpanel;
  $n = array('Категория дел', 'Категории дел', 'Категорию дел');//in next versions this variable need move to options WP

  $labels = array(
    'name' => $n[1],
    'singular_name' => $n[0],
    'add_new' => 'Добавить',
    'add_new_item' => 'Добавить '.$n[2],
    'edit_item' => 'Редактировать '.$n[2],
    'new_item' => 'Новая '.$n[0],
    'view_item' => 'Просмотр '.$n[1],
    'search_items' => 'Поиск '.$n[1],
    'not_found' => $n[0].' не найдена',
    'not_found_in_trash' => 'В Корзине '.$n[0].' не найдена',
    );

  $pages = array('cases', 'wiki');

  $args = array(
    'labels' => $labels,
    'singular_label' => $n[0],
    'public' => true,
    'show_ui' => true,
    'hierarchical' => true,
    'show_tagcloud' => true,
    'show_in_nav_menus' => true,
    'rewrite' => array('slug' => 'functions', 'with_front' => false ),
  );

  register_taxonomy('functions', $pages, $args);
} add_action('init', 'register_functions_tax');



function cases_functions_taxonomy_custom_fields($tag){
  global $cpanel;
  $n = $cpanel->optm('case_tax_title');
  $t_id = $tag->term_id;
  $term_meta = get_option("tax_term_$t_id");
  ?>
  <tr class="form-field">
    <th scope="row" valign="top"><label for="responsible">Ответственный по умолчанию</label></th>
    <td>
      <select id="term_meta[responsible]" name="term_meta[responsible]">
      <?php foreach(get_posts(array('post_type'=>'persons','numberposts'=>-1,'orderby'=>'title','order'=>'ASC')) as $person){
        $sel = ($person->ID==$term_meta['responsible']) ? 'selected' : '';
        echo "<option $sel value='$person->ID'>$person->post_title</option>";
      }?>
      </select>
      <span class="description">Выберите персону, которая будет назначаться ответственной по умолчанию для дел в данной <?php echo $n[1]?></span>
    </td>
  </tr>
  <?php
} //add_action('functions_edit_form_fields', 'cases_functions_taxonomy_custom_fields', 10, 2);

function save_taxonomy_custom_fields($term_id){
  if(isset($_POST['term_meta'])){
    $t_id = $term_id;
    $term_meta = get_option("tax_term_$t_id");
    foreach(array_keys($_POST['term_meta']) as $key) if(isset($_POST['term_meta'][$key])) $term_meta[$key] = $_POST['term_meta'][$key];
    update_option("tax_term_$t_id", $term_meta);
  }
} //add_action('edited_functions', 'save_taxonomy_custom_fields', 10, 2);