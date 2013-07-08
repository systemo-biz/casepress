<?php

	/**
	 * Register multiple sidebars
	 */
	function cases_theme_register_sidebars() {

		// List of sidebars
		$sidebars = array(
			// Post type archives
			array( 'cases', 'Дела', 'Сайдбар для страницы с архивом типа записи Дела' ),
			array( 'report', 'Отчеты', 'Сайдбар для отчетов' ),
			array( 'objects', 'Объекты', 'Сайдбар для страницы с архивом типа записи Объекты' ),
			array( 'organizations', 'Организации', 'Сайдбар для страницы с архивом типа записи Организации' ),
			array( 'persons', 'Персоны', 'Сайдбар для страницы с архивом типа записи Персоны' ),
			// Front-page
			array( 'frontpage', 'Главная страница', 'Сайдбар для главной страницы' ),
		);

		// Register sidebars
		foreach ( $sidebars as $sidebar ) {

			register_sidebar( array(
				'id' => $sidebar[0],
				'name' => $sidebar[1],
				'description' => $sidebar[2],
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget' => '</div></section>',
				'before_title' => '<h3>',
				'after_title' => '</h3>'
			) );
		}
	}

	add_action( 'widgets_init', 'cases_theme_register_sidebars' );
?>