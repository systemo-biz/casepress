<?php
class CasesControlPanel{
  var $title = "CasePress";
  var $name = "casepress_options";

  var $_options;
  var $_multi_options = array(
    'case_title' => array(
      0 => array('Дело', 'Дела', 'Дел'),
      1 => array('Задача', 'Задачи', 'Задач'),
      2 => array('Операция', 'Операции', 'Операций'),
      3 => array('Кейс', 'Кейсы', 'Кейсов'),
      4 => array('Тикет', 'Тикеты', 'Тикетов'),
    ),
    'case_tax_title' => array(
      0 => array('Категория', 'Категории', 'Категорию'),
      1 => array('Номенклатура', 'Номенклатуры', 'Номенклатуру'),
      2 => array('Функция', 'Функции', 'Функцию'),
    ),
  );
  var $_default_options = array(
    'case_title' => 0,
    'case_tax_title' => 0,
    'case_responsible' => 0,
  );

  function __construct(){
    $this->_options = get_option($this->name);
    if(!is_array($this->_options)) add_option($this->name, $this->_default_options);
    $this->update_options($_REQUEST["options"], $_REQUEST["action"]);
    add_action('admin_menu', array(&$this, 'add_menu'));
  }



  function add_menu(){
    add_action("admin_enqueue_scripts", array(&$this, "add_admin_scripts"));
    add_menu_page($this->title, $this->title, 8, "wpcases_menu", array(&$this, "wpcases_menu_common"), null, 300);
    add_submenu_page("wpcases_menu", "$this->title - Общие опции", "Общие опции", 8, "wpcases_menu_common", array(&$this, "wpcases_menu_common"));
  }
  function add_admin_scripts(){wp_enqueue_style("wpcases_admin_css", (plugin_dir_url(__FILE__)."/admin.css"));}
  function wpcases_menu_common(){include(__FUNCTION__.".php");}



  function opt($k){
    return isset($this->_options[$k]) ? $this->_options[$k] : 0;
  }
  function optm($k, $i=null){
    switch($k){
      case 'case_title':
      case 'case_tax_title':
        return isset($i) ? $this->_multi_options[$k][$this->opt($k)][$i] : $this->_multi_options[$k][$this->opt($k)];
      default:
        return $this->opt($k);
    }
  }
  function update_options($data, $action=''){
    switch($action){
      case 'reset':
        foreach((array)$data as $k=>$v) $this->_options[$k] = $this->_default_options[$k];
        update_option($this->name, $this->_options);
        $notice = "Изменения сброшены.";
        break;
      case 'save':
        if(is_array($data)){
          foreach((array)$data as $k=>$v) $this->_options[$k] = $v;
          update_option($this->name, $this->_options);
          $notice = "Изменения сохранены.";
        }else
          $notice = "Ничего не изменено.";
        break;
    }
    if(isset($notice)) echo "<div class='updated' id='message'><p>$notice</p></div>";
  }
}

global $cpanel;
$cpanel = new CasesControlPanel();
