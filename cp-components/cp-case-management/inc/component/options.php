<?php

	$states = array( '0' => __( 'Not selected', $this->textdomain ) );
	foreach ( get_terms( 'state', array( 'hide_empty' => false ) ) as $state )
		$states[$state->term_id] = $state->name;

	$results = array( '0' => __( 'Not selected', $this->textdomain ) );
	foreach ( get_terms( 'results', array( 'hide_empty' => false ) ) as $result )
		$results[$result->term_id] = $result->name;

	/** Plugin options */
	$options = array(
		array(
			'name' => __( 'States', $this->textdomain ),
			'type' => 'opentab'
		),
		array(
			'name' => __( 'Designation', $this->textdomain ),
			'desc' => __( 'Select term for designation', $this->textdomain ),
			'std' => '0',
			'options' => $states,
			'id' => 'designation',
			'type' => 'select'
		),
		array(
			'name' => __( 'Registration', $this->textdomain ),
			'desc' => __( 'Select term for registration', $this->textdomain ),
			'std' => '0',
			'options' => $states,
			'id' => 'registration',
			'type' => 'select'
		),
		array(
			'name' => __( 'Preparation', $this->textdomain ),
			'desc' => __( 'Select term for preparation', $this->textdomain ),
			'std' => '0',
			'options' => $states,
			'id' => 'preparation',
			'type' => 'select'
		),
		array(
			'name' => __( 'Execution', $this->textdomain ),
			'desc' => __( 'Select term for execution', $this->textdomain ),
			'std' => '0',
			'options' => $states,
			'id' => 'execution',
			'type' => 'select'
		),
		array(
			'name' => __( 'Completion', $this->textdomain ),
			'desc' => __( 'Select term for completion', $this->textdomain ),
			'std' => '0',
			'options' => $states,
			'id' => 'completion',
			'type' => 'select'
		),
		array(
			'name' => __( 'Archive', $this->textdomain ),
			'desc' => __( 'Select term for archive', $this->textdomain ),
			'std' => '0',
			'options' => $states,
			'id' => 'archive',
			'type' => 'select'
		),
		array(
			'type' => 'closetab'
		),
		array(
			'name' => __( 'Results', $this->textdomain ),
			'type' => 'opentab'
		),
		array(
			'name' => __( 'Delayed', $this->textdomain ),
			'desc' => __( 'Select term for delayed', $this->textdomain ),
			'std' => '0',
			'options' => $results,
			'id' => 'delayed',
			'type' => 'select'
		),
		array(
			'name' => __( 'Canceled', $this->textdomain ),
			'desc' => __( 'Select term for canceled', $this->textdomain ),
			'std' => '0',
			'options' => $results,
			'id' => 'canceled',
			'type' => 'select'
		),
		array(
			'name' => __( 'Failure', $this->textdomain ),
			'desc' => __( 'Select term for failure', $this->textdomain ),
			'std' => '0',
			'options' => $results,
			'id' => 'failure',
			'type' => 'select'
		),
		array(
			'name' => __( 'Success', $this->textdomain ),
			'desc' => __( 'Select term for success', $this->textdomain ),
			'std' => '0',
			'options' => $results,
			'id' => 'success',
			'type' => 'select'
		),
		array(
			'type' => 'closetab'
		),
	);
?>