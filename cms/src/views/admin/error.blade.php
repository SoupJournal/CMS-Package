@extends('cms::layouts.page')

@section('title') Error @stop

@section('content')

<?php

	//ensure title is set
	if (!isset($errorTitle)) {
		$errorTitle = 'Error';
	}
	
	//ensure description is set
	if (!isset($errorMessage)) {
		$errorMessage = 'Oops, an error occurred.';
	}
	
?>


<div class='col-lg-8 col-lg-offset-2'>


	<div class="container-fluid">
			
		<div class="text-center">
			<h1>{{ $errorTitle }}</h1>
		</div>
		
		<div class="text-center">	
			<h3>{{ $errorMessage }}</h3>
		</div>
			
	</div>


</div>

@stop