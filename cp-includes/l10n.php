<?php

define('TEXT_DOMAIN', 'casepress');		// Домен для текстов CasePress
define('PRINT_L10N_EXAMPLE', true);		// Если 'true', выводит на экран пример локализации текста

// Загрузка домена текстов CasePress
function load_casepress_textdomain() {
    load_plugin_textdomain(TEXT_DOMAIN, false, dirname(plugin_basename(__DIR__)) . '/cp-content/languages');

	// Выводим пример, если попросили (и домен загружен)
	if (PRINT_L10N_EXAMPLE && is_textdomain_loaded(TEXT_DOMAIN))
		print_l10n_example();
}

add_action('plugins_loaded', 'load_casepress_textdomain');

// Пример локализации текста
function print_l10n_example() {
	echo '<pre>';
	echo "Printing localization example.\n";
	echo "Text Domain: '" . TEXT_DOMAIN . "'.\n\n";

	$count = rand(0, 100);
	$examples = [];

	// 1. Простой перевод текста
	$examples[] = __(
		"Example: First example! It's just string to translate",		// текст, для которого нужно получить перевод
		TEXT_DOMAIN														// текстовый домен
	);

	// 2. Перевод текста с указанием контекста перевода
	$examples[] = _x(
		'Example: Second example! Click here to add new!',		// текст для перевода
		'Example',												// контекст перевода
		TEXT_DOMAIN
	);

	$examples[] = _x('Example: Third example! Click here to add new!', 'Picture', TEXT_DOMAIN);

	// 3. Перевод строки текста с последующим выводом его на экран
	_e(
		"Example: Hello, dear user!\n",		// текст для перевода
		TEXT_DOMAIN
	);

	_e("Example: This text will be printed on screen\n", TEXT_DOMAIN);

	// 4. Перевод текста с указанием контекста перевода и вывод на экран
	_ex(
		"Example: Translating text with 'picture' context: landscape\n",		// текст
		'Picture',																// контекст перевода
		TEXT_DOMAIN
	);

	_ex("Example: Translating text with 'terrain' context: landscape\n", 'Terrain', TEXT_DOMAIN);

	// 5. Перевод текста с указанием форм единственного и множественного числа
	$examples[] = _n(
		'Example: We deleted %d spam message.',			// вариант текста для единственного числа
		'Example: We deleted %d spam messages.',		// вариант текста для множественного числа
		$count,											// число элементов
		TEXT_DOMAIN
	);

	$examples[] = _n('Example: I found %d dollar.', 'Example: I found %d dollars.', $count, TEXT_DOMAIN);

	// 6. Перевод текста с указанием единственной и множественной форм и контекста перевода
	$examples[] = _nx(
		'Example: %d star',			// форма единственного числа
		'Example: %d stars',		// форма множественного числа
		$count,						// число элементов (звёзд)
		'Commentary rating', 		// контекст перевода, здесь контекстом является рейтинг комментария
		TEXT_DOMAIN
	);

	// 7. Указание примечания для переводчиков
	// Примечание располагается над строкой с переводимым текстом и задается как обычный комментарий в коде,
	// но должен начинаться с фразы "translators:" в верхнем регистре, например, так:
	/* TRANSLATORS: PHP time format, see @ http://php.net/date */
	$examples[] = __('g:i:s a', TEXT_DOMAIN);

	// 8. Перевод текста для использования в JavaScript
	/*
		// --- PHP:
		// Где-то у нас подключён скрипт
		wp_enqueue_script('my-script', …);

		// Переводим строки для использования в этом скрипте
		wp_localize_script(
			'my-script',		// название скрипта
			'objectL10n',		// название объекта, который будет хранить переведённые строки
			array(				// массив строк
				'speed'  => __('Speed' , TEXT_DOMAIN),
				'submit' => __('Submit', TEXT_DOMAIN)
			)
		);

		// --- JavaScript:
		// Теперь в JavaScript используем локализованные версии строк
		$('#submit').val(objectL10n.submit);
		$('#speed').val('150 {speed}'.replace('{speed}', objectL10n.speed));
	*/

	foreach ($examples as $example)
		printf("$example\n", $count);

	echo '</pre>';
}