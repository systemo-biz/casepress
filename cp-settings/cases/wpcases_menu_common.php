<?php
  global $cpanel;
  $params = array(
    'case_title' => 'Название экземпляра класса процесса',
    'case_tax_title' => 'Название категории дел',
  );
?>

<form id="wpcases_menu" class="form-table" action="" method="post">
  <input type="hidden" id="action" name="action" value="save">
  <div class="wrap cptab"><br>
    <h2><?php echo get_admin_page_title()?></h2>

    <table>
    <?php foreach($params as $param=>$data){?>
      <tr>
        <th scope="row"><label for="<?php echo $param?>"><?php echo $data?>:</label></th>
        <td>
          <select id="<?php echo $param?>" name="options[<?php echo $param?>]">
          <?php foreach($cpanel->_multi_options[$param] as $k=>$v){
            $sel = ($k==$cpanel->opt($param)) ? 'selected' : '';
            echo "<option $sel value='$k'>".$v[0]."</option>";
          }?>
          </select>
          <span class="description">Это название будет появляться по умолчанию в панели администратора, пунктах меню и системных уведомлениях.</span>
        </td>
      </tr>
    <?php }?>
    <tr>
      <th scope="row"><label for="case_responsible">Ответственный по умолчанию (если не указан):</label></th>
      <td>
        <select id="case_responsible" name="options[case_responsible]">
        <?php foreach(get_posts(array('post_type'=>'persons','numberposts'=>-1,'orderby'=>'title','order'=>'ASC')) as $person){
          $sel = ($person->ID==$cpanel->opt('case_responsible')) ? 'selected' : '';
          echo "<option $sel value='$person->ID'>$person->post_title</option>";
        }?>
        </select>
        <span class="description">Выберите персону, которая будет назначаться в случае, если у процесса не указан ответственный.</span>
      </td>
    </tr>
    </table>
  </div>

  <p><input type="submit" value="Сохранить" name="cp_save" class="dochanges button" /></p>
</form>

<br/><br/>

<form id="wpcases_menu_reset" class="reset form-table" action="" method="post">
  <input type="hidden" id="action" name="action" value="reset">
  <?php foreach($params as $param=>$data){?><input type="hidden" id="<?php echo $param?>" name="options[<?php echo $param?>]" value=""><?php }?>

  <p><input type="submit" value="Сбросить настройки" name="cp_reset" class="dochanges button" /></p>
</form>
