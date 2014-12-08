<?php
/*
Plugin Name: Pivot Table by CasePress
*/
/*
* [Description] Create pivot table from array
* @param 
$array - исходный массив
$head - поле которое пойдет в шапку таблицы
$a - имя поля, значения которого пойдут в первую колонку (like A column in Excel)
$dim_func - функия измерения сводных данных. как правило количество, но может быть и сумма или среднее
$dim_field - поле по которому пойдет функция измерения, если это не count


@return $pivot_table - array with pivot table
*/
function cp_array_to_pivot_table($data, $a, $head, $dim_func = 'count', $dim_field = null) {
    
//Получаем массив по полю
$data_a = array();
foreach($data as $value) {
    $data_a[] = $value[$a];
}
$data_a = array_unique($data_a);
sort($data_a);

//Получаем массив значений (уникализированные и отсортированные) по полю для шапки
$data_head = array();
foreach($data as $value) {
    $data_head[] = $value[$head];
}
$data_head = array_unique($data_head);
sort($data_head);


//Определяем таблицу свода
$pivot_table = array();
    
//Заполняем первую строку таблицы - шапку
$thead = array();
//первая ячейка это заголовок первого столбца
$thead[] = $a;
foreach($data_head as $h_item){
     $thead[] = $h_item;
    }
//Добавляем шапку в таблицу
$pivot_table[] = $thead;
    
    
foreach($data_a as $a_item) {
     $row = array();

     //Указываем значение в первом столбце
     $row[] = $a_item;

     //Запускаем цикл, чтобы пройтись по значениям шапки
     foreach($data_head as $h_item){
          $izm = 0;
          foreach($data as $row_izm){
              if($row_izm[$a] == $a_item and $row_izm[$head] == $h_item) $izm++;
              //die('$row_izm[$a] = ' . $row_izm[$a] . ', ' . '$a_item = ' . $a_item . ', $row_izm[$head] = ' . $row_izm[$head] . ', $h_item = ' . $h_item);
          }
          //добавляем в ячейку сумму элементов пересечения
          $row[] = $izm;
     }

     $pivot_table[] = $row;
}

return $pivot_table;
}