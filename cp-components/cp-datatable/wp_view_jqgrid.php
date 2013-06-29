<?php
include 'wp_fields_jqgrid.php';

function wp_view_jqgrid($params){
  global $default_atts, $posttype, $tax_slug, $tax_id, $title, $status, $fields, $fields_name, $id_object, $group;

  $jqgrid_id = isset($params['table_id']) ? $params['table_id'] : preg_replace('/[^a-zA-Z0-9\-]/', '', "jqgrid-$posttype-$tax_slug-$tax_id");
  if(is_tax($tax_slug) && !$tax_id){
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    $tax_id = $term->term_id;
  }
  $status = isset($_GET['status_form']) ? $_GET['status_form'] : "open";
  $status_sel=array($status=>"selected='selected'");
  $status_options = ""; foreach(array("open"=>"Открытые", "all"=>"Все") as $v=>$n) $status_options .= "<option value='$v' ".$status_sel[$v].">$n</option>";

  $ajaxurl = "/wp-admin/admin-ajax.php?action=wp_data_jqgrid&posttype=$posttype&tax_slug=$tax_slug&tax_id=$tax_id&fields=$fields&status=$status";
  if($id_object) $ajaxurl.="&id_object=$id_object";

  $col_model = ""; foreach(explode(',', $fields) as $frow) $col_model.=$frow();
  $col_names = "'".implode("','",explode(",", $fields_name))."'";
  $caption = (strlen($title)>0) ? "caption:'$title'," : "";
  $grouping = (strlen($group)>0) ? "grouping:true, groupingView:{groupField:['$group'],groupColumnShow:[false]}," : "";

  switch($params['format']){
    case "noload":
      echo "
      <div id='$jqgrid_id' data-link='$ajaxurl'>
        <table id='list'><tr><td/></tr></table>
        <div id='pager'></div>

        <script type='text/javascript'>
          jQuery(document).ready(function(){
            jQuery('#$jqgrid_id #list').jqGrid({datatype:'xml', mtype:'POST', rowNum:15, rowList:[15,30,50,100], height:'auto', autowidth:true, sortname:'post_date', viewrecords:true, sortorder:'desc',
              url:'#',
              colModel:[$col_model], colNames:[$col_names],
              pager:jQuery('#$jqgrid_id #pager'),
              $caption
            });
            jQuery('#$jqgrid_id #list').jqGrid('filterToolbar');
          });
        </script>
      </div>
      ";
      break;
    default:
      echo "
      <div id='$jqgrid_id' data-link='$ajaxurl'>
        <form method='GET' action=''>
          <select name='status_form' onchange='this.form.submit();'>
            $status_options
          </select>
        </form>

        <table id='list'><tr><td/></tr></table>
        <div id='pager'></div>

        <script type='text/javascript'>
          jQuery(document).ready(function(){
            jQuery('#$jqgrid_id #list').jqGrid({datatype:'xml', mtype:'POST', rowNum:15, rowList:[15,30,50,100], height:'auto', autowidth:true, sortname:'post_date', viewrecords:true, sortorder:'desc',
              url:'$ajaxurl',
              colModel:[$col_model], colNames:[$col_names],
              pager:jQuery('#$jqgrid_id #pager'),
              $caption
              $grouping
            });
            jQuery('#$jqgrid_id #list').jqGrid('filterToolbar');
          });
        </script>
      </div>
      ";
  }
}
