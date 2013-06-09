## Cases Loop dataTable
Impressive dataTable-based plugin and shortcodes for extended work with Wordpress Loop.


## How to use

### Posts table shortcode:
```console
  [datatables title='New datatable', type='post, page' fields='ID, post_title']
```

```console
  [datatables src='global' fields='ID:int, post_title:link' titles='ID:Post Id, post_title:Post Title' tax='category:1', view='nowrap, scroll']
```

#### Params
- **title**: h3 header title. HTML tags are allowed
- **type**: post type separated by comma: `post, page, cases, etc`
- **parent**: ID of parent post
- **limit**: max count of rows returned
---
- **fields**: field names separated by comma, maybe with type: `ID:int, post_title:link, post_date:date, etc`
  - field name can be name of:
    - post field (`post_title`)
    - post meta_key (`prioritet`)
    - linked taxonomy slug (`category`)
  - **field type** uses for filters and can be:
    - `text` (default)
    - `null` (no filter)
    - `int` (number range)
    - `link` (link to post)
    - `date` (date range)
    - `select`
    - `cbox`
  - **BE VERY CAREFUL** using `select` or `cbox` types! With wrong taxonomy slug, they can cause extremely slow callback or even site crash.
- **titles**: field titles in the same format: `post_title:Заголовок поста, post_date:Дата публикации, etc`
- **tax**: taxonomies with term index: `functions:123, state:5, etc`
  - used with AND operator, accordingly selects records only with ALL enumerated tax/term pairs
- **meta**: meta keys with its values: `prioritet:1, participant:5, etc`
  - used with AND operator, accordingly selects records only with ALL enumerated key/values pairs
---
- **view**: datatable grid presentation: `id:css_id_attr, paginate:two_button, rows:25, scroll, nowrap, sql:validate`
  - *paginate* can be `two_button` or `full_numbers`
  - *sql:validate* used for datatable_generator() function only (means that direct SQL query string will be processed with $wpdb->prepare())
- **filter**: field value for filtering with: `ID:123, post_title:New`. Text only, sorry.
- **sort**: field names for sorting: `post_date:desc, prioritet:asc, etc`. By default table sorts by the first column in ascending order.
  - sorting order can be `asc` or `desc`
- **group**: field names for grouping, separated by comma: `prioritet, post_date, etc`
- **tree**: 2 fields to create nested tree, separated by colon: `ID:post_parent`
  - will not work if **group** used
---
- **src**: additional params source: `request, global`
  - `request` merges params from **$_REQUEST['dt']**, replaces exists
  - `global` uses current global post and taxonomy data instead `parent` and `tax`

#### Filters
- **cases_datatable_fields** - for datatable fields data (array of fields with key, title, type, order, etc)
- **cases_datatable_view** - for datatable view data (array of view params)
- **cases_datatable_sql** - for sql query if datatable_generator($params, $sql) call used
- **cases_datatable_args** - for WPQuery args if datatable_generator($params) call used
- **cases_datatable_data** - for result data (array of objects)
- **cases_datatable_head** - for table head (array of fields with key, title, type, order, etc)
- **cases_datatable_foot** - same for table foot
- **cases_datatable_row** - same for each single table row
- **cases_datatable_value** - for current value
  - you should use `add_filter('cases_datatable_value', 'some_amazing_function', 10, 4)` call
  - and your function declaration should be `some_amazing_function($value, $k, $v, $p)`, where:
    - `value` - current value
    - `k` - current column name
    - `v` - current field data
    - `p` - current row (post object)




## Versions

#### 0.9.14
* allow search in any post type

#### 0.9.13
* added ability to use the table for search results

#### 0.9.12
* strikethrough is based on presence of the result value

#### 0.9.11
* strikethrough is only enabled when all cases are listed

#### 0.9.10
* modified strikethrough appearance

#### 0.9.9
* added strikethrough for closed cases
* disabled dt.columnFilter.js

#### 0.9.8
* removed built-in buttons from template
* updated dt.js, dt.colRR.js, dt.columnFilter.js, dt.tableTools.js, jquery.jqGrid.min.js
* disabled dt.colRR.js

#### 0.9.7
* removed "Without closing date" checkbox

#### 0.9.6
* added `cbox` datatable value
* attempt to disable column sorting for checkboxes using bSortable

#### 0.9.5
* dt_state added (based on `results` taxonomy)
* sql validation added
* bunch of small fixes

#### 0.9.4
* refactor nested tree, runtime reduced from 5000-6000ms to 50-80ms

#### 0.9.3
* nested tree added (column sorting not works yet)

#### 0.9.2
* multilevel grouping with sorting and drill-down rows

#### 0.9.1
* filters added

#### 0.9
* conditional sql query added

#### 0.8.2
* `sort` param added

#### 0.8.1
* some fixes

#### 0.8
* default fields and titles
* new table view params
* 2-level rows grouping
* russian localization
* filters types: `range` and `select`
* 'howto' help section

#### 0.7.4
* admin pane button for 'releases' page

#### 0.7.3
* moving rows between tables
* releases page template changed

#### 0.7.2
* filter added
* filter state saving added

#### 0.7.1
* css changed
* fixed errors while empty data

#### 0.7
* datatables added
* releases added

#### 0.6
* multitables logic added
* multiterms selection added

...

#### 0.1
* Initial commit


> Copyright (c) 2012 Alex G <http://alex.gl>