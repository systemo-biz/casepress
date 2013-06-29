/*
* File:        jquery.dataTables.tree.js
* Version:     1.0.
* Author:      Alex G
* 
* Copyright 2012 Alex G, all rights reserved.
*
* This source file is free software, under either the GPL v2 license or a
* BSD style license, as supplied with this software.
* 
* Parameters:
* @ccol                          Integer             Index of the column that contains row ID - default 0
* @pcol                          Integer             Index of the column that contains row parent ID - default 0
*/
(function($){
  $.fn.rowTree = function (params){
    var defaults = {
      ccol: 0,
      pcol: 0,
    };

    return this.each(function (index, elem){
      var options = $.extend(defaults, params);
      if(options.ccol==options.pcol || options.ccol<0 || options.pcol<0) return;

      var oTable = $(elem).dataTable();

      oTable.parents('.datatable_wrapper').find('.dataTable').each(function(){
        var trs = $(this).find('tr');
        trs.find('th:eq('+options.pcol+')').hide();
        trs.find('td:eq('+options.pcol+')').hide();
      });

      // main tree function
      var fnDrawCallBackWithTree = function(oSettings){
        if(oSettings.aiDisplay.length == 0) return;
        var nTrs = oTable.find('tbody tr');
        for(var i=0; i<nTrs.length; i++){
          var pval = oSettings.aoData[oSettings.aiDisplay[oSettings._iDisplayStart+i]]._aData[options.pcol];
          if(pval!=0 || pval.length>2){
            var cval = oSettings.aoData[oSettings.aiDisplay[oSettings._iDisplayStart+i]]._aData[options.ccol];
            _fnMoveRow(nTrs, cval);
          }
        }
      };

      function _fnMoveRow(nTrs, cval){
        var tr = null;
        for(var i=0; i<nTrs.length; i++){
          var txt = (nTrs[i].cells[options.ccol].textContent) ? nTrs[i].cells[options.ccol].textContent : nTrs[i].cells[options.ccol].innerText;
          if(txt==cval){tr = $(nTrs[i]); break;}
        } 
        if(tr==null) return null;

        var pval = tr.find('td:eq('+options.pcol+')').text();
        if(pval!=0 || pval.length>2){
          var res = _fnMoveRow(nTrs, pval);
          if(res != null){
            // tr.attr('data-level', res[1]).removeClass("even odd").addClass("level"+res[1]+" prn-"+pval);
            tr.removeClass("even odd").addClass("inner level"+res[1]);
            res[0].removeClass("even odd").addClass("group level"+(res[1]-1)).after(tr.detach());
            return [tr, res[1]+1];
          }
        }
        // if there are no parent ID, or parent case not found - return current case as root
        return [tr, 1];
      }

      oTable.fnSort([[options.ccol,'asc'], [options.pcol,'asc']]);
      oTable.fnSettings().aoDrawCallback.push({'fn': fnDrawCallBackWithTree, 'sName':'fnRowTree'});
      oTable.fnDraw();
    });
  };
})(jQuery);