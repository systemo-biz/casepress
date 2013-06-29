/*
* File:        jquery.dataTables.grouping.js
* Version:     1.0.
* Author:      Alex G
* 
* Copyright 2012 Alex G, all rights reserved.
*
* This source file is free software, under either the GPL v2 license or a
* BSD style license, as supplied with this software.
* 
* Parameters:
* @column                        Integer             Index of the column that will be used for grouping - default 0
* @order                         Enumeration         Sort direction of the group
* @txt                           String              Prefix that will be added to each group cell
* @expanded                      Any                 Check that group is expanded by default
* @fnOnGrouped                   Function            Function that is called when grouping is finished. Function has no parameters.
*/
(function($){
  $.fn.rowGrouping = function(params){
    var defaults = {
      column: [0],
      order: [""],
      txt: [""],
      expanded: null,
      fnOnGrouped: function(){},
    };
    String.prototype.hashCode = function(){
      var hash = 0;
      if(this.length==0) return hash;
      for(i=0; i<this.length; i++){
        char = this.charCodeAt(i);
        hash = ((hash<<5)-hash)+char;
        hash = hash & hash; // Convert to 32bit integer
      }
      return hash;
    }

    return this.each(function (index, elem){
      function _isExpanded(id){return ($.inArray(id, asExpandedGroups) != -1)}
      function _stripGroupName(name){return name.hashCode()}

      var oTable = $(elem).dataTable();
      var options = $.extend(defaults, params);
      var asExpandedGroups = [];

      // prepare options
      for(var i=0; i<options.column.length; i++){
        if(!options.order[i]) options.order[i]="asc";
      }
      if(options.expanded == null){
        // nothing
      }else if(options.expanded.prototype == Array){
        for(id in options.expanded) asExpandedGroups.push(id);
      }

      // main grouping function
      var _fnDrawCallBackWithGrouping = function(oSettings){
        if(oSettings.aiDisplay.length == 0) return;
      
        var nTrs = oTable.find('tbody tr');
        var iColspan = nTrs[0].getElementsByTagName('td').length;
        var bInitialGrouping = true;
        var last_gids = [];
      
        // for all table rows
        for(var i=0; i<nTrs.length; i++){
          var gids = [];
          // foreach group column
          for(var j=0; j<options.column.length; j++){
            var text = oSettings.aoData[oSettings.aiDisplay[oSettings._iDisplayStart+i]]._aData[options.column[j]];
            var gid = _stripGroupName(text); for(var z=j-1; z>=0; z--) gid = gids[z]+String(gid);
            gids[j] = gid;

            // create title if column data changed
            if(gid!=last_gids[j]){
              if(j>0 || bInitialGrouping) asExpandedGroups.push(gid);

              var td = document.createElement('td');
              td.colSpan = iColspan;
              td.innerHTML = options.txt[j]+text;

              var tr = document.createElement('tr');
              tr.id = "group-"+gid;
              tr.className = "group level"+j+" "+((_isExpanded(gid)) ?"expanded" :"collapsed");
              $(tr).attr('data-id', gid).attr('data-level', j);
              tr.appendChild(td);
              for(var z=0; z<j; z++) $(tr).addClass("prn-"+gids[z]);

              // set title click action
              $(tr).click(function (e){
                e.preventDefault();
                var id = $(this).attr("data-id");
                // var level = Number($(this).attr("data-level"))+1;
                if(_isExpanded(id)){
                  asExpandedGroups.splice($.inArray(id, asExpandedGroups), 1);
                  $(this).removeClass("expanded").addClass("collapsed");
                  oTable.find(".prn-"+id).hide();
                }else{
                  asExpandedGroups.push(id);
                  $(this).removeClass("collapsed").addClass("expanded");
                  // oTable.find(".level"+level+".prn-"+id).show().click();
                  oTable.find(".prn-"+id).show();
                }
              });

              nTrs[i].parentNode.insertBefore(tr, nTrs[i]);
              last_gids[j] = gids[j];

              // if title changes, all subtitles should change too
              for(var z=j+1; z<options.column.length; z++) last_gids[z]=null;
              // if any ansector hidden, this row should hide too
              for(var z=0; z<j; z++) if(!_isExpanded(gids[z])){$(tr).hide(); break;}
            }

            // add all tree ansectors
            $(nTrs[i]).addClass("prn-"+gid);
          }

          $(nTrs[i]).addClass("level"+options.column.length);
          for(var z=0; z<options.column.length; z++) if(!_isExpanded(gids[z])){$(nTrs[i]).hide(); break;}
          bInitialGrouping = false;
        }

        // grouped!
        options.fnOnGrouped();
      };

      // fix sorting order and hide ordering columns
      var aaSortingFixed = [];
      for(var i=0; i<options.column.length; i++){
        aaSortingFixed.push([options.column[i], options.order[i]]);
        oTable.fnSetColumnVis(options.column[i], false);
      }
      oTable.fnSettings().aaSortingFixed = aaSortingFixed;

      // set grouping titles and draw table
      oTable.fnSettings().aoDrawCallback.push({'sName':'fnRowGrouping', 'fn': _fnDrawCallBackWithGrouping});
      oTable.fnDraw();
    });
  };
})(jQuery);