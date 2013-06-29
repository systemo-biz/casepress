<?php



add_action('add_meta_boxes','cp_persfio');
function cp_persfio() {
	    add_meta_box( 'personaFIO', 'Подробные данные','cp_fioform', 'persons', 'normal', 'high' );  
}


function cp_fioform() {  
	global $post;
	$thePostID = $post->ID;
	$FullName = get_the_title($post->ID); 
	if ($FullName != 'Черновик') $cp_name = $FullName;
	$cp_surname = strtok($FullName,' ');
	$cp_name = strtok(' ');
	$cp_patronymic = strtok(' ');
?>
	
	<form action="POST" style="padding-bottom: 20px;">
		<div style="width: 33%; float: left;"> Имя: </span> <input type="text" value="<?php echo $cp_name; ?>" id="cp_name" class="cp_fio_input"></input></div>
		<div style="width: 33%; float: left;"> Отчество: </span> <input type="text" value="<?php echo $cp_patronymic; ?>" id="cp_patronymic" class="cp_fio_input"></input></div>
		<div style="width: 33%; float: left;"> Фамилия:  <input type="text" value="<?php echo  $cp_surname;?>" id="cp_surname" class="cp_fio_input"></input></div>
	</form>	
	
	<div style="height: 28px;"></div>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			var cp_name,cp_surname,cp_patronymic,current_input,final_string;			
			//$('#postdivrich').hide();
			$('#personaFIO').hide();
			cp_name = $('#cp_name').val();
			cp_surname = $('#cp_surname').val();
			cp_patronymic = $('#cp_patronymic').val();
			$('#titlewrap').children('#title').css('width','80%');
			$('#titlewrap').append('<input id="my_fio_button" type="submit" onClick="return false;" class="button" value="...">');
			$('#my_fio_button').toggle(
			function(){
				$('#personaFIO').show();
			},
			function(){
				$('#personaFIO').hide();
			});	
			
			$('.cp_fio_input').blur(function(){
				$('#titlewrap').children('label').hide();
				current_input = $(this).attr('id');
				switch (current_input){
					case 'cp_name':	cp_name = jQuery.trim($(this).val())+' '; break;
					case 'cp_surname':	cp_surname = jQuery.trim($(this).val())+' '; break;
					case 'cp_patronymic': cp_patronymic = jQuery.trim($(this).val()); break;
				}
				final_string = cp_surname+cp_name+cp_patronymic;
					$('#titlewrap').children('#title').val(final_string);
			});
			
		}); 
	</script>
	
<?php
} 


?>