@extends('iba::window.master')

@push('title')
	{{--- <span class="h2">Editing {{ title_case($type) }}</span> --}}
	<span>{{ title_case($type) }}</span> \ <span>id: {{ $book->id }}</span>
@endpush

@section('main')
	<div>
		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
	
		<form class="iba-main-form pure-form pure-form-aligned" action="/iba/analog/{{ $type }}/{{ $book->id }}/" method="POST">
			{{ method_field('PUT') }}
			{{ csrf_field() }}
		
			
		
			@include('iba::modules.form.edit')
			
			<button type="submit" class="pure-button pure-button-primary">Update</button>
			
		</form>
	</div>
@endsection
