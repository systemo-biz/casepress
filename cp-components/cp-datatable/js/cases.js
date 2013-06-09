jQuery().ready(function(){
	var post_type = jQuery('#posttype').attr('class');
	var functions = jQuery('#functions').attr('class');
	
	switch(post_type){
		case 'functions':
		case 'cases':
			jQuery("#list").jqGrid({
				url:jQGajax.ajaxurl+'?action=jqga&tp='+post_type+'&func='+functions,
				datatype:"xml",
				colNames:['ИД','Заголовок','Автор','Инициатор','Ответственный','Срок/Дедлайн','Дата завершения','Статус','Объект','Категория дел','Тэг','Приоритет','Дата'],
				colModel:[
					{name:'id', index:'id', width:30, search:true, searchoptions:{sopt:["eq","ne","lt","le","gt","ge"]}},
					{name:'post_title', index:'post_title', width:200, /*formatter: 'link',*/ search:true, searchoptions:{sopt:["eq","ne","cn","nc"]}},
					{name:'author_post', index:'author_post', hidden: true},
					{name:'initiator', index:'initiator', width:50, search:true, searchoptions:{sopt:["eq","ne","cn","nc"]}},
					{name:'responsible', index:'responsible', width:50, search:true, searchoptions:{sopt:["eq","ne","cn","nc"]}},
					{name:'date_deadline', index:'date_deadline', width:50, align:'center', search:true, searchoptions:{sopt:["eq","ne","cn","nc"]}},
					{name:'date_end', index:'date_end', width:50, align:'center', search:true, searchoptions:{sopt:["eq","ne","cn","nc"]}},
					{name:'state', index:'state', width:50, search:true, searchoptions:{sopt:["eq","ne","cn","nc"]}},
					{name:'object', index:'object', width:50, search:true, searchoptions:{sopt:["eq","ne","cn","nc"]}},
					{name:'function', index:'function', width:50, search:true, searchoptions:{sopt:["eq","ne","cn","nc"]}},
					{name:'tag', index:'tag', width:50, hidden: true, search:false},
					{name:'prioritet', index:'prioritet', width:30, search:true, searchoptions:{sopt:["eq","ne","lt","le","gt","ge"]}},
					{name:'post_date', index:'post_date', width:50, align:'center', search:true, searchoptions:{sopt:["eq","ne","cn","nc"]}},
				],
				pager:jQuery('#pager'),
				mtype:'POST',
				rowNum:30,
				autowidth:true,
				rowList:[15,30,50],
				height: 'auto',
				sortname:'post_date',
				viewrecords:true,
				sortorder: "desc",
				grouping:false, 
					groupingView : { 
						groupField : ["prioritet"], 
						groupColumnShow : [false]
					},
				caption:jQGajax.caption,
		    		footerrow: false,
		    		userDataOnFooter: false
			}).navGrid('#pager',{edit:false,add:false,del:false});
		break;
		case 'objects':
		case 'organizations':
		case 'persons':
			jQuery("#list").jqGrid({
				url:jQGajax.ajaxurl+'?action=jqga&tp='+post_type,
				datatype:"xml",
				colNames:['ИД','ФИО','Ответственный','Категория','Дата'],
				colModel:[
					{name:'id', index:'id', width:30, search:true, searchoptions:{sopt:["eq","ne","lt","le","gt","ge"]}},
					{name:'post_title', index:'post_title', width:200, formatter: 'link', search:true, searchoptions:{sopt:["eq","ne","cn","nc"]}},
					{name:'responsible', index:'responsible', width:50, search:true, searchoptions:{sopt:["eq","ne","cn","nc"]}},
					{name:'category', index:'category', width:50, search:true, searchoptions:{sopt:["eq","ne","cn","nc"]}},
					{name:'post_date', index:'post_date', width:50, align:'center', search:true, searchoptions:{sopt:["eq","ne","cn","nc"]}},
				],
				pager:jQuery('#pager'),
				mtype:'POST',
				rowNum:30,
				autowidth:true,
				rowList:[15,30,50],
				height: 'auto',
				sortname:'post_date',	//sidx
				viewrecords:true,
				sortorder: "desc",
				grouping:false, 
					groupingView : { 
						groupField : ["id"], 
						groupColumnShow : [false]
					},
				caption:jQGajax.caption,
		    		footerrow: false,
		    		userDataOnFooter: false
			}).navGrid('#pager',{edit:false,add:false,del:false});
		break;
	}

	jQuery("#list").jqGrid('filterToolbar');
	
	jQuery("#chngroup").change(function(){
	var vl = jQuery("#chngroup").val();
	if(vl) {
		if(vl == "clear") {
			jQuery("#list").jqGrid('groupingRemove',true);
		} else {
			jQuery("#list").jqGrid('groupingGroupBy',vl);
		}
	}
	$('#list').trigger('reloadGrid');
	});
});
//url:jQGajax.ajaxurl+'?action=jqga&tp='+jQuery('#posttype').attr('class'),