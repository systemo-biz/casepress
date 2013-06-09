jQuery(document).ready(function(){
  $('.dataTable').find("tbody tr").live('click', function(e){
    var tr = $(this);
    if(tr.is('.group') || tr.find('.dataTables_empty').length>0) return;
    if(!tr.hasClass('row_selected')) $(this).parents('.dataTable').dataTable().find('tr.row_selected').removeClass('row_selected');
    tr.toggleClass('row_selected');
  });

/*
  $('.datatable_wrapper .actions #cb_opened').change(function(e){
    var cb = $(this);
    var id = parseInt(cb.attr('data-target'));
    var dt = cb.parents('.datatable_wrapper').find('.dataTables_scrollBody .dataTable').first().dataTable();
    if(cb.is(':checked')) dt.fnFilter('---', id);
    else dt.fnFilter('', id);
  });
*/

  $('.datatable_wrapper .filters .filter-button').click(function(e){
    var cb = $(this);
    cb.parents('.filters').find('.filter-button').removeClass('ui-state-hover');
    var foot = cb.parents('.datatable_wrapper').find('.dataTables_scrollFoot .dataTable tfoot').first();
    var filter = cb.data('filter');
    foot.find('th').each(function(i){
      var input = $(this).find('input').first();
      if(i in filter) input.attr('value', filter[i]).removeClass('search_init');
      else input.attr('value', '').addClass('search_init');
      input.keyup().blur();
    });
    cb.addClass('ui-state-hover');
  });
});