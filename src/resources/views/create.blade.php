@extends('iba::base')

@push('title')
Creating {{ title_case($type) }}
@endpush

@section('main')

	<article class="shadow" style="overflow: visible;">
	
		{{--<h2>New {{ title_case($type) }}</h2>--}}
	
		<form class="iba-main-form pure-form pure-form-aligned" action="/iba/analog/{{ $type }}/" method="post">
		
			{{ csrf_field() }}
			
			@include('iba::modules.form.create')
			
			<button type="submit" class="pure-button pure-input-1-2 pure-button-primary">Create</button>
			
		</form>
		
	</article>

@endsection