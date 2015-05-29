<?php

/*
 * Load components for ACF integrations
 */

if(function_exists('acf_form_head')){
	include_once 'class-cp-add-acf-form-to-pages.php';
	include_once 'cp-acf-functions-select.php';
}