<?php
  wp_loop_jqgrid(array(
    "title"=>"Объекты IT", "posttype"=>"objects",
    "tax_slug"=>"objects_category", "tax_id"=>"35",
    "fields"=>"id,post_title,post_date", "fields_name"=>"ID,Название проекта,Дата создания",
    "table_id"=>"jqgrid_objects",
  ));
  wp_loop_jqgrid(array(
    "title"=>"Релизы", "posttype"=>"cases",
    "tax_slug"=>"functions", //"tax_id"=>"65", "id_object"=>"",
    "fields"=>"id,post_title,post_date", "fields_name"=>"ID,Заголовок,Дата создания",
    "table_id"=>"jqgrid_releases", "format"=>"noload"
  ));
  wp_loop_jqgrid(array(
    "title"=>"Изменения проекта", "posttype"=>"cases",
    "tax_slug"=>"functions", "tax_id"=>"24,157",
    "fields"=>"id,post_title,responsible,state,prioritet", "fields_name"=>"ID,Заголовок,Ответственный,Статус,Приоритет",
    "table_id"=>"jqgrid_object_changes", "format"=>"noload"
  ));
  // wp_loop_jqgrid(array(
  //   "title"=>"Свободные изменения", "posttype"=>"cases",
  //   "tax_slug"=>"functions", "tax_id"=>"24,157",
  //   "fields"=>"id,post_title,responsible,state,prioritet", "fields_name"=>"ID,Заголовок,Ответственный,Статус,Приоритет",
  //   "table_id"=>"jqgrid_free_changes"
  // ));
?>

<script type='text/javascript'>
  $(document).ready(function(){
    // $("#jqgrid_objects #list").unbind("reloadGrid.jqGrid");

    $("#jqgrid_objects #list tr a").live('click', function(e){
      e.preventDefault();
      var jqgrid = $("#jqgrid_releases");
      var url = jqgrid.attr("data-link") + "&id_object="+$(this).parents("tr").attr("id");
      jqgrid.find('#list').setGridParam({url:url}).trigger("reloadGrid");
      jqgrid.find(".ui-jqgrid-title").html("Релизы проекта «"+$(this).text()+"»");

      var jqgrid = $("#jqgrid_object_changes");
      var url = jqgrid.attr("data-link") + "&id_object="+$(this).parents("tr").attr("id");
      jqgrid.find('#list').setGridParam({url:url}).trigger("reloadGrid");
      jqgrid.find(".ui-jqgrid-title").html("Изменения проекта «"+$(this).text()+"»");
    });
  });
</script>
