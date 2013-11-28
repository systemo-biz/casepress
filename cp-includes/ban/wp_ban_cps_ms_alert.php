<?php
	
	
	if ( ! is_user_logged_in() || is_network_admin() )
		return;

	$blogs = get_blogs_of_user( get_current_user_id() );
	
	if(isset($_GET['action']) && $_GET['action']== 'logout' ){
		wp_clear_auth_cookie();
		do_action('wp_logout');
		wp_redirect(home_url());
		exit;
	}
/**/
	//if ( wp_list_filter( $blogs, array( 'userblog_id' => get_current_blog_id() ) ) )
		//return;

	$blog_name = get_bloginfo( 'name' );

	if ( empty( $blogs ) ){
		$output = sprintf( 'Вы попытались войти на сайт "%1$s" но у вас на текущий момент нет туда доступа. Если вы считаете, что должны иметь доступ к сайту "%1$s", свяжитесь с администратором сети.' , $blog_name );
		$output .= '<table>';
		$output .= "<tr>";
		$output .= "<td valign='top'>";
		$output .= "<a href='" . wp_logout_url() . "'>" . 'Выйти' . "</a>" ;
		$output .= "</td>";
		$output .= "</tr>";
		$output .= '</table>';
		wp_die($output );
	}

	$output = '<p>' . sprintf( 'Вы попытались войти на сайт "%1$s" но у вас на текущий момент нет туда доступа. Если вы считаете, что должны иметь доступ к сайту "%1$s", свяжитесь с администратором сети.', $blog_name ) . '</p>';
	$output .= '<p>' . 'Если вы попали на этот экран по ошибке и хотите открыть один из своих собственных сайтов, можно воспользоваться приведёнными ниже ссылками.' . '</p>';

	$output .= '<h3>' . 'Ваши сайты' . '</h3>';
	$output .= '<table>';

	foreach ( $blogs as $blog ) {
		$output .= "<tr>";
		$output .= "<td valign='top'>";
		$output .= "{$blog->blogname}";
		$output .= "</td>";
		$output .= "<td valign='top'>";
		$output .= "<a href='" . esc_url( get_admin_url( $blog->userblog_id ) ) . "'>" . 'Перейти в консоль' . "</a> | <a href='" . esc_url( get_home_url( $blog->userblog_id ) ). "'>" . 'Посмотреть сайт'  . "</a>" ;
		$output .= "</td>";
		$output .= "</tr>";
	}
	$output .= '</table>';

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru-RU">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo __('WordPress &rsaquo; Error'); ?></title>
	<style type="text/css">
		html {
			background: #f9f9f9;
		}
		body {
			background: #fff;
			color: #333;
			font-family: sans-serif;
			margin: 2em auto;
			padding: 1em 2em;
			-webkit-border-radius: 3px;
			border-radius: 3px;
			border: 1px solid #dfdfdf;
			max-width: 700px;
		}
		h1 {
			border-bottom: 1px solid #dadada;
			clear: both;
			color: #666;
			font: 24px Georgia, "Times New Roman", Times, serif;
			margin: 30px 0 0 0;
			padding: 0;
			padding-bottom: 7px;
		}
		#error-page {
			margin-top: 50px;
		}
		#error-page p {
			font-size: 14px;
			line-height: 1.5;
			margin: 25px 0 20px;
		}
		#error-page code {
			font-family: Consolas, Monaco, monospace;
		}
		ul li {
			margin-bottom: 10px;
			font-size: 14px ;
		}
		a {
			color: #21759B;
			text-decoration: none;
		}
		a:hover {
			color: #D54E21;
		}
		.button {
			display: inline-block;
			text-decoration: none;
			font-size: 14px;
			line-height: 23px;
			height: 24px;
			margin: 0;
			padding: 0 10px 1px;
			cursor: pointer;
			border-width: 1px;
			border-style: solid;
			-webkit-border-radius: 3px;
			border-radius: 3px;
			white-space: nowrap;
			-webkit-box-sizing: border-box;
			-moz-box-sizing:    border-box;
			box-sizing:         border-box;
			background: #f3f3f3;
			background-image: -webkit-gradient(linear, left top, left bottom, from(#fefefe), to(#f4f4f4));
			background-image: -webkit-linear-gradient(top, #fefefe, #f4f4f4);
			background-image:    -moz-linear-gradient(top, #fefefe, #f4f4f4);
			background-image:      -o-linear-gradient(top, #fefefe, #f4f4f4);
			background-image:   linear-gradient(to bottom, #fefefe, #f4f4f4);
			border-color: #bbb;
		 	color: #333;
			text-shadow: 0 1px 0 #fff;
		}

		.button.button-large {
			height: 29px;
			line-height: 28px;
			padding: 0 12px;
		}

		.button:hover,
		.button:focus {
			background: #f3f3f3;
			background-image: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#f3f3f3));
			background-image: -webkit-linear-gradient(top, #fff, #f3f3f3);
			background-image:    -moz-linear-gradient(top, #fff, #f3f3f3);
			background-image:     -ms-linear-gradient(top, #fff, #f3f3f3);
			background-image:      -o-linear-gradient(top, #fff, #f3f3f3);
			background-image:   linear-gradient(to bottom, #fff, #f3f3f3);
			border-color: #999;
			color: #222;
		}

		.button:focus  {
			-webkit-box-shadow: 1px 1px 1px rgba(0,0,0,.2);
			box-shadow: 1px 1px 1px rgba(0,0,0,.2);
		}

		.button:active {
			outline: none;
			background: #eee;
			background-image: -webkit-gradient(linear, left top, left bottom, from(#f4f4f4), to(#fefefe));
			background-image: -webkit-linear-gradient(top, #f4f4f4, #fefefe);
			background-image:    -moz-linear-gradient(top, #f4f4f4, #fefefe);
			background-image:     -ms-linear-gradient(top, #f4f4f4, #fefefe);
			background-image:      -o-linear-gradient(top, #f4f4f4, #fefefe);
			background-image:   linear-gradient(to bottom, #f4f4f4, #fefefe);
			border-color: #999;
			color: #333;
			text-shadow: 0 -1px 0 #fff;
			-webkit-box-shadow: inset 0 2px 5px -3px rgba( 0, 0, 0, 0.5 );
		 	box-shadow: inset 0 2px 5px -3px rgba( 0, 0, 0, 0.5 );
		}
	</style>
</head>
<body id="error-page">
	<?php echo $output; ?>
</body>
</html>
<?php die(); ?>