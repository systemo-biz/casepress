<?php
// projects
function cp_update_projects_meta($post_id, $meta_key, $meta_value){
  if($meta_key=='responsible-cp-posts-sql'){
    $person = get_post(intval($meta_value));
    if($person){
      $comment_id = wp_insert_comment(array(
        'comment_post_ID' => $post_id,
        'comment_author' => 'sysinfo',
        'comment_content' => "Назначен новый руководитель проекта: <a href='".get_permalink($person->ID)."'>#$person->ID, $person->post_title</a>",
        'comment_approved' => 1,
      ));
      add_comment_meta($comment_id, 'new_pm_cp', 'new_pm_cp', true);
    }
  }
} add_action('add_post_meta', 'cp_update_projects_meta', 10, 3);


function cp_project_comment_text($text){
  if(get_comment_meta(get_comment_ID(), 'new_pm_cp', true)=='new_pm_cp') $text = "<span class='label label-success'>Назначение РП</span>$text";
  return $text;
} add_filter('comment_text', 'cp_project_comment_text');



function cp_project_calc_reserved_vacancy_positions($project_id){
  global $wpdb;
  $positions = array();
  $query = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='request_project' AND meta_value='a:1:{i:0;s:2:\"$project_id\";}'";
  foreach($wpdb->get_results($query) as $row){
    if(get_post_status($row->post_id)!='publish') continue;
    $title = get_field('request_vacancy_title', $row->post_id);
    if(!isset($positions[$title])) $positions[$title] = 0;
    $positions[$title] += intval(get_field('request_vacancy_positions', $row->post_id));
  }
  return $positions;
}



# requests
// function cp_requests_the_content($content){
//   global $post;
//   if($post->post_type!='cases') return $content;
//   return $post->post_excerpt.$content;
// } add_filter('the_content', 'cp_requests_the_content');


function cp_acf_update_value_request_project($value, $post_id, $field){
  $old_value = get_field($field['name'], $post_id)[0];
  if($value[0]==$old_value->ID) return $value;

  $project = get_post($value[0]);
  $excerpt = "<h4>В закрепленном проекте требуются вакансии:</h4><table><tr><th>Название вакансии</th><th>Количество позиций</th></tr>";
  while(have_rows('project_staff', $project->ID)): the_row();
    $excerpt .= "<tr><td>".get_sub_field('vacancy_title')."</td><td>".get_sub_field('vacancy_positions')."</td></tr>";
  endwhile;
  $excerpt .= "</table>";
  wp_update_post(array('ID'=>$post_id, 'post_excerpt'=>$excerpt));
  return $value;
} add_filter('acf/update_value/name=request_project', 'cp_acf_update_value_request_project', 10, 3);


function cp_acf_update_value_request_pm_check($value, $post_id, $field){
  if(!current_user_can('manage_options')) return 0;
  $old_value = get_field($field['name'], $post_id);
  if($value==$old_value) return $value;

  if($value>0){
    $comment_id = wp_insert_comment(array(
      'comment_post_ID' => $post_id,
      'comment_author' => 'sysinfo',
      'comment_content' => "Заявка утверждена зам директора по производству",
      'comment_approved' => 1,
    ));
    add_comment_meta($comment_id, 'pm_chk_cp', 'pm_chk_cp', true);
  }
  return $value;
} add_filter('acf/update_value/name=request_pm_check', 'cp_acf_update_value_request_pm_check', 10, 3);
function cp_acf_update_value_request_security_check($value, $post_id, $field){
  $old_value = get_field($field['name'], $post_id);
  if($value==$old_value) return $value;

  if($value>0){
    $comment_id = wp_insert_comment(array(
      'comment_post_ID' => $post_id,
      'comment_author' => 'sysinfo',
      'comment_content' => "Список кандидатов, прошедших отбор, отправлен на проверку в службу безопасности",
      'comment_approved' => 1,
    ));
    add_comment_meta($comment_id, 'seq_chk_cp', 'seq_chk_cp', true);
  }
  return $value;
} add_filter('acf/update_value/name=request_security_check', 'cp_acf_update_value_request_security_check', 10, 3);


function cp_request_comment_text($text){
  if(get_comment_meta(get_comment_ID(), 'pm_chk_cp', true)=='pm_chk_cp') $text = "<span class='label label-info'>Утверждена</span>$text";
  if(get_comment_meta(get_comment_ID(), 'seq_chk_cp', true)=='seq_chk_cp') $text = "<span class='label label-danger'>Проверка СБ</span>$text";
  return $text;
} add_filter('comment_text', 'cp_request_comment_text');


function cp_acf_requests_head(){
  global $post;
  if(!$post) return;
  if(!in_array('Komplektatsiya-personalom', wp_get_post_terms($post->ID, 'functions', array('fields'=>'slugs')))) return;
  ?><script type="text/javascript">
    jQuery().ready(function($){
      $(document).on('acf/validate_field', function(e, field){
        $field = $(field);
        if($field.attr('id')!='acf-request_vacancy_positions') return;
        if($field.find('input').val()<=0) $field.data('validation', false);
      });

      var field_project = $('#acf-request_project');
      var field_vacancy = $('#acf-field-request_vacancy_title');
      var field_positions = $('#acf-field-request_vacancy_positions');
      function acf_request_project_changed(){
        var project = field_project.find('.relationship_right .relationship_list li input').first().val();
        if(typeof project === 'undefined') return;
        field_vacancy.html("<option value=''>Подождите, определяются свободные вакансии...</option>")
        jQuery.ajax({type: 'POST', url: '<?php echo admin_url('admin-ajax.php') ?>',
          data: {
            action: 'request_project_changed',
            request: <?php echo $post->ID ?>,
            project: project
          },
          success: function(data){
            field_vacancy.html(data).trigger('change');
          }
        });
      }
      function acf_request_vacancy_changed(){
        var vacancy = field_vacancy.find('option:selected');
        if(vacancy.data('current')=='current'){
          var positions = vacancy.data('reserved');
          var max = vacancy.data('positions');
        }else{
          var positions = vacancy.data('positions')-vacancy.data('reserved');
          var max = positions;
        }
        if(positions<=0) field_positions.attr('max', 0).val(0);
        else field_positions.attr('max', max).val(positions);
      }
      $('input[name="fields['+field_project.data('field_key')+']"]').on('change', function(){acf_request_project_changed()});
      field_vacancy.on('change', function(){acf_request_vacancy_changed()});
      acf_request_project_changed();
    });
  </script><?php
} add_filter('wp_head', 'cp_acf_requests_head');
  add_filter('admin_head', 'cp_acf_requests_head');
function cp_wp_ajax_request_project_changed(){
  if(!isset($_POST['request'])) exit;
  if(!isset($_POST['project'])) exit;
  $request_id = intval($_POST['request']); if($request_id<=0) exit;
  $project_id = intval($_POST['project']); if($project_id<=0) exit;
  $current = get_field('request_vacancy_title', $request_id);
  $options = "";
  $reserved_positions = cp_project_calc_reserved_vacancy_positions($project_id);
  while(have_rows('project_staff', $project_id)): the_row();
    $title = get_sub_field('vacancy_title');
    $count = get_sub_field('vacancy_positions');
    $reserved = isset($reserved_positions[$title]) ? intval($reserved_positions[$title]) : 0;
    $selected = ($title==$current) ? "selected='selected' data-current='current'" : '';
    $options .= "<option $selected value='$title' data-positions='$count' data-reserved='$reserved'>$title, позиций: $count, занято: $reserved</option>";
  endwhile;
  echo $options;
  exit;
} add_action('wp_ajax_request_project_changed', 'cp_wp_ajax_request_project_changed');

function cp_content_filter_add_request_meta($excerpt){
  $project = get_field('request_project')[0];
  if(!isset($project)) return $excerpt;

  $owner = get_post(get_field('responsible-cp-posts-sql', $project->ID));
  $html = '';
  $html .= "<p>Проект: <a href='".get_the_permalink($project->ID)."'>$project->post_title</a></p>";
  $html .= "<p>Ответственный: <a href='".get_the_permalink($owner->ID)."'>$owner->post_title</a></p>";
  $html .= "<p>Плановая дата завершения: ".DateTime::createFromFormat('Ymd', get_field('plan_date_end', $project->ID))->format('d.m.Y')."</p>";
  return $html.$excerpt;
} add_filter('the_excerpt', 'cp_content_filter_add_request_meta');


# agreements
function cp_setup_cron_schedule(){
  if(!wp_next_scheduled('cp_hourly_event')) wp_schedule_event(time(), 'hourly', 'cp_hourly_event');
} add_action('wp', 'cp_setup_cron_schedule');

function cp_update_meta_agreements(){
  global $wpdb;
  $metas = $wpdb->get_results("SELECT post_id FROM $wpdb->postmeta WHERE meta_key LIKE 'agreements_required_%_agreement_checked' AND meta_value=0");
  $ids = array_unique(array_map(function($e){return $e->post_id;}, $metas));
  update_option('cp_cases_agreements_required', $ids);
} add_action('cp_hourly_event', 'cp_update_meta_agreements');

function cp_agreements_required_shortcode($atts){
  $atts = shortcode_atts( array(
    'title' => '',
  ), $atts);
  $ids = get_option('cp_cases_agreements_required');
  $count = count($ids);
  if($count>0){
    $html = "<strong>Требует согласования, дел: $count</strong>";
    if(!isset($_GET['agreements_required'])) $html .= "<br/><a class='btn btn-default' href='/cases/?agreements_required'>Посмотреть список</a>";
    return $html;
  }
  return '';
} add_shortcode('agreements_required', 'cp_agreements_required_shortcode');
  add_filter('widget_text', 'do_shortcode');

function cp_show_cases_agreements_required($query){
  if(isset($_GET['agreements_required']) and $query->query['post_type']=='cases'){
    $query->set('post__in', get_option('cp_cases_agreements_required'));
  }
} add_action('pre_get_posts', 'cp_show_cases_agreements_required');




# search
function cp_change_search_url_rewrite(){
  if(is_search() && !empty($_GET['s'])){
    $_GET['s'] = '';
    wp_redirect(home_url('/search/').urlencode(get_query_var('s')));
    exit;
  }	
} add_action('template_redirect', 'cp_change_search_url_rewrite');