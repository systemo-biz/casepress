<?php
  $dt_id = $datatable['id'];
  

?>




<div class='datatable_wrapper'>
  <?php if(isset($datatable['title'])) echo "<h3>".$datatable['title']."</h3>"?>
  <div class='actions'>
    <?php if(isset($fields['date_end'])){?>
      <!-- <label><input id='cb_opened' data-target='<?php echo array_search('date_end', array_keys($fields))?>' type='checkbox' checked='checked'> Без даты закрытия</label> -->
    <?php }?>
    <?php
/*
      $ccstate='open';
      if(strpos($params['tax'], 'results')===false) $ccstate='all';
      elseif(strpos($params['tax'], 'results:ALL')!==false || strpos($params['tax'], 'results:ANY')!==false) $ccstate='close';

      $dtget = $_GET;
      $clean_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
      foreach(array('open'=>'Открытые', 'close'=>'Закрытые', 'all'=>'Все') as $sid=>$stitle){
        $dtget['dt_state']=$sid;
        $state_link = $clean_uri.(count($dtget) ? '?'.http_build_query($dtget) : '');
        echo "<div class='action'><a href='$state_link' class='DTTT_button ui-state-".(($ccstate==$sid) ?'active' :'default')."'><span>$stitle</span></a></div>";
      }
*/
/*
20130609, a@casepress.org: Нужно удалить этот блок. Пару месяцев потестить и если все ок, то удалить.
*/
/*       $class = ( isset( $params['class'] ) ) ? $params['class'] : '';
      if ( isset( $_GET['dt_state'] ) )
        $class .= ' tax-' . $_GET['dt_state'];
      elseif ( isset( $_REQUEST['tax'] ) )
        $class .= ' tax-' . $_REQUEST['tax'];
      else
        $class .= ' tax-' . sanitize_title( $params['tax'] ); */
    ?>
  </div>
  <table id='<?php echo $dt_id?>' class='dataTable <?php echo esc_attr( $class ); ?>'>
    <thead>
      <?php echo_datatable_head($fields)?>
    </thead>
    <tfoot>
      <?php //echo_datatable_foot($fields)?>
    </tfoot>
    <tbody>
      <?php foreach($posts as $p) echo_datatable_row($fields, $p)?>
    </tbody>
  </table>
  
  <?php if(count($datatable['filters'])){?>
    <div class='filters'>
      <label>Быстрые фильтры:</label>
      <?php foreach($datatable['filters'] as $k=>$f){?>
        <button class='DTTT_button ui-button filter-button ui-state-default' data-filter='<?php echo json_encode($f[1])?>'><span>
        <?php echo $f[0]?>
        </span></button>
      <?php }?>
    </div>
  <?php }?>
</div>




<script type='text/javascript'>
jQuery(document).ready(function(){
  var params = {
    <?php if ( $params['server-side'] && strlen( $sql ) <= 10 ) : ?>
	'bProcessing':true,
	'bServerSide':true,
	'sAjaxSource':'<?php echo admin_url( 'admin-ajax.php?action=cases_datatable_server_processing' ); ?>',
	'sServerMethod': 'POST',
	'fnServerParams': function( aoData ) {
		aoData.push( { 'name': 'dt_params', 'value': '<?php echo json_encode( $raw_params ); ?>' } );
	},
    <?php endif; ?>
    'bJQueryUI':true, 'bFilter':true,
    'bAutoWidth':false, 'sScrollX':"100%",
    'bStateSave':false, // 'bStateSave':true, 'iCookieDuration':31536000,
    "oLanguage":{
      "sProcessing":"Подождите...",
      "sLengthMenu":"_MENU_ записей",
      "sZeroRecords":"Записей не найдено.",
      "sInfo":"с _START_ до _END_ из _TOTAL_",
      "sInfoEmpty":"0 записей",
      "sInfoFiltered":"(отфильтровано из _MAX_ записей)",
      "sInfoPostFix":"",
      "sSearch":"Поиск:",
      "oPaginate":{
        "sFirst":"Первая",
        "sPrevious":"Предыдущая",
        "sNext":"Следующая",
        "sLast":"Последняя"
      }
    },
    "sDom": '<"H"RTfr><lip>t',
    "oTableTools":{"sSwfPath": '<?php echo plugin_dir_url(__FILE__)?>assets/export.swf'},
    "aaSorting":[
      <?php $i=0; foreach($fields as $k=>$d){
        if(isset($d['sort'])) echo "[$i, '".$d['sort']."'],"; $i++;
      }?>
    ],
    <?php if(isset($datatable['paginate']) && $datatable['paginate']=='no'){?>
      'bPaginate':false,
    <?php }else{?>
      'bPaginate':true,
      'sPaginationType':'<?php echo (isset($datatable["paginate"])) ? $datatable["paginate"] : "two_button"?>',
      <?php if(isset($datatable['rows'])){?>
        'iDisplayLength':<?php echo $datatable['rows']?>,
      <?php }?>
    <?php }?>
    <?php if(array_key_exists('scroll', $datatable)){?>
      'bPaginate':false,
      'sScrollY':'250px',
      'bScrollCollapse':true,
    <?php }?>
  };

  var filters = {aoColumns:[
    <?php foreach($fields as $k=>$d) switch($d['type']){
      case 'null':
        echo "null,";
        break;
      case 'int':
        echo "{type:'number'},";
        break;
      // case 'date':
      //   echo "{type:'date-range'},";
      //   break;
      case 'select':
      case 'cbox':
      // case 'tax':
        $values = "'".implode("','", (array)$d['values'])."'";
        echo "{type:'select', values:[$values], bSortable:false},";
        break;
      default:
        echo "{type:'text', bRegex:true, bSmart:true},";
        break;
    }?>
  ]};

  <?php if(count($datatable['group'])){?>
    params['bStateSave'] = false;
    params['bPaginate'] = false;
    params['bLengthChange'] = false;
    var groups = {
      <?php $cols=array(); $ords=array(); $txts=array();
        foreach($datatable['group'] as $g) if(isset($fields[$g])){
          $cols[] = array_search($g, array_keys($fields));
          $ords[] = $fields[$g]['sort'];
          $txts[] = $fields[$g]['title'].": ";
        }
        echo "
        column:[".implode(",", $cols)."],
        order:['".implode("','", $ords)."'],
        txt:['".implode("','", $txts)."'],
        ";
      ?>
    };
  <?php }else if(count($datatable['tree'])){?>
    params['bStateSave'] = false;
    params['bPaginate'] = false;
    params['bLengthChange'] = false;
    var tree = {
      <?php foreach($datatable['tree'] as $c=>$p){
        echo "
        ccol:".array_search($c, array_keys($fields)).",
        pcol:".array_search($p, array_keys($fields)).",
        ";
        break;
      }?>
    };
  <?php }?>

  var dt = jQuery("#<?php echo $dt_id?>").dataTable(params);
  // dt.columnFilter(filters);
  if(typeof(groups)!='undefined') dt.rowGrouping(groups);
  else if(typeof(tree)!='undefined') dt.rowTree(tree);

  // $("#<?php echo $dt_id?>").parents('.datatable_wrapper').find('.actions #cb_opened').change();

  <?php $i=0; foreach($fields as $k=>$d){
    if(isset($d['filter'])) echo "dt.fnFilter('".$d['filter']."', $i);"; $i++;
  }?>
});
</script>




<style type='text/css'>
  <?php if(array_key_exists('nowrap', $datatable)){?>
    table#<?php echo $dt_id?> tbody td{
      white-space: nowrap;
      max-width: 250px;
      overflow: hidden;
    }
  <?php }?>
</style>