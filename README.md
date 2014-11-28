CasePress - adaptive case managment system powered by WordPress
=========
# Описание

## Назначение

Это платформа, на базе которой можно построить гибкую ERP/CRM систему. Концепция адаптивного кейс-менеджмента, заложенная в основу системы, позволяет гибко и быстро настраивать бизнес-процессы любой сложности.

Система проверена в организациях от 10 до 2000 сотрудников.

На ее базе можно автоматизировать любые бизнес-процессы, от управления проектами и производства, с продажами и маркетингом, до кадровых процедур и финансового планирования.

Единая платформа, единая система. Без затрат на интеграцию.

Может быть установлена как удаленное облако, или на внутренний сервер.

Доступ с любых устройств, от стационарных компьютеров, до смартфонов.

Любые операционные системы от Windows XP и MacOS, до Linux и Android.

## Технические особенности
- OpenSource и без ограничений в распространении
- Поддержка GitHub Updater https://github.com/afragen/github-updater/
- Использует Bootstrap http://getbootstrap.com/css/
- php/mysql - как базовый стек технологий

## Ссылки

- Сайт: [casepress.org](http://casepress.org/)
- Лицензия [MIT](http://ru.wikipedia.org/wiki/%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F_MIT)
- Это бета-версия. Все вопросы, предложения и идеи пишем тут https://github.com/systemo-biz/casepress/issues
- План развития https://github.com/systemo-biz/casepress/wiki/Todo

# Установка
1. Ставим тему https://github.com/systemo-biz/alienship-cp
2. Лучше сразу создать дочернюю тему ([инструкция](http://codex.wordpress.org/%D0%94%D0%BE%D1%87%D0%B5%D1%80%D0%BD%D0%B8%D0%B5_%D1%82%D0%B5%D0%BC%D1%8B))
3. Выполняем инструкцию http://casepress.org/kb/shpargalka/


# Changelog

## 20141001
- Стилизовать ACF под alienship. Наработки можно взять от у https://github.com/fotonstep
- URL перезаписи http://casepress.org/kb/web/kak-pomenyat-format-url-dlya-cpt-wordpress/
- Вырезан DataTable и убраны шаблоны из плагина. Чтобы применялись типовые шаблоны темы Alienship


## 20140830
- Перетащить систему на Alienship
- Изменить вывод постов на блог. С возможностью через хук добавлять различные поля в вывод.
- Перетащить все секции на хук the_content
