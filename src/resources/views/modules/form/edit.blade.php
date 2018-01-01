<div class="pure-g">
	<div class="pure-u-1-1 pure-u-md-5-8">
		
		<div style="margin: 0.4rem;">
	
		@if (in_array('title', $book->dimensions))
			<div class="control field is-horizontal">
				<div class="field-label is-small"><label class="label">Title</label></div>
				<input name="title" type="text" class="c-field" placeholder="A title" value="{{ $book->title->value or ''}}">
			</div>
		@endif
		
		@if (in_array('subtitle', $book->dimensions))
			<div class="control field is-horizontal">
				<div class="field-label is-small"><label class="label">Subtitle</label></div>
				<input name="subtitle" type="text" class="c-field" placeholder="A title" value="{{ $book->subtitle->value or ''}}">
			</div>
		@endif
		
		@if (in_array('slug', $book->dimensions))
			<div class="control field is-horizontal">
				<div class="field-label is-small"><label class="label">Slug</label></div>
				<input name="slug" type="text" class="c-field" placeholder="A title" value="{{ $book->slug->value or ''}}">
			</div>
		@endif
		
		@if (in_array('description', $book->dimensions))
			<div class="field is-horizontal">
				<div class="field-label is-small"><label class="label">Description</label></div>
				<div class="field-body">
					<textarea name="description" class="c-field" type="text" placeholder="Description">{{ $book->description->value or '' }}</textarea>
				</div>
			</div>
		@endif
			
			<textarea class="c-field" placeholder="Textareas work too" name="content" {{ $textarea or '' }}></textarea>
	
		</div>
		
		<div class="c-card">
			<div class="c-card__item c-card__item--divider">People related data</div>
			<script>
				var availablePeople = <?php echo $peoples->toJson(); ?>;
			</script>
			
			@foreach ( $roles as $role )
			
			<div class="c-card__item">
				<div class="control field">
					<label class="label is-small">{{ title_case($role) }}</label>
					<input id="{{ $role }}" name="people[{{ $role }}]" type="text" value='{!! $book->peoples($role)->get()->toJson() !!}' style="display: none;">
					<div id="{{ $role }}-field"></div>
				</div>
				<script>
					$(function() {
						var selectedOptions = '';
						@isset($book)
							selectedOptions = {{ $book->peoples($role)->get()->pluck('id')->toJson() }}
						@endisset
						console.log($('#{{ $role }}-field').selectize({
							persist: false,
							createOnBlur: true,
							create: function(input) {
									return {
										id: 'new' + input,
										name: input,
									};
								},
								render: {
							item: function(data, escape) {
							return "<div class='selected-{{ $role }}' data-data='"+JSON.stringify(data)+"'>"
							+ data.name
							+ '</div>';
							}
							},
							valueField: 'id',
							labelField: 'name',
							searchField: 'name',
							options: availablePeople,
							items: selectedOptions,
							onChange: function(value) {
									let people = $('.selected-{{ $role }}').map(function(){return $(this).attr('data-data');}).get().join();
									$('#{{ $role }}').val('['+people+']');
								},
							}));
						});
				</script>
			</div>
			@endforeach
			
		</div>
		
	</div>
	
	<div class="pure-u-1-1 pure-u-md-3-8">
		
		@if ( !isset($thumbnail))
		<div class="c-card">
			<div class="c-card__item c-card__item--divider">Thumbnail</div>
			<div class="c-card__item">
				<input placeholder="location of file" class="c-field" type="text" name="thumbnail[path]" value="{{ $book->thumbnail->path or '' }}">
				<input placeholder="File name" class="c-field" type="text" name="thumbnail[name]" value="{{ $book->thumbnail->name or '' }}">
			</div>
			<div class="c-card__item">
				<input placeholder="Photographer" class="c-field" type="text" name="thumbnail[photographer]" value="{{ $book->thumbnail->photographer or '' }}">
				<input placeholder="Link to photographer's profile" class="c-field" type="text" name="thumbnail[link]" value="{{ $book->thumbnail->link or '' }}">
			</div>
		</div>
		@endif
		
		<div class="c-card">
			<div class="c-card__item c-card__item--divider">Keywords</div>
			<div class="c-card__item">
				<input id="keywords" name="keywords" style="display: none;" value='{!! $book->keywords()->get()->toJson() !!}'>
				<div id="keywords-field" name="keywords" type="text" style="width: 100%; display: block;"></div>
			</div>
			<script>
				$(function() {
					var availableOptions = <?php echo $keywords->toJson(); ?>;
					var selectedOptions = ''
					@isset($book)
						selectedOptions = <?php echo $book->keywords()->pluck('keywords.id')->toJson(); ?>;
					@endisset
				$('#keywords-field').selectize({
					persist: false,
						createOnBlur: true,
						create: function(input) {
								return {
									id: 'new' + input,
									word: input,
								};
							},
						valueField: 'id', 
						labelField: 'word',
						searchField: 'word',
						render: {
					item: function(data, escape) {
					return "<div class='selected-keyword' data-data='"+JSON.stringify(data)+"'>"
					+ data.word
					+ '</div>';
					}
					},
						options: availableOptions,
						items: selectedOptions,
						onChange: function(value) {
							var keywords = $('.selected-keyword').map(function(){return $(this).attr('data-data');}).get().join();
							$('#keywords').val('['+keywords+']');
						}
					});
				});
			</script>
		</div>
	
		<div class="c-card">
			<div class="c-card__item c-card__item--divider">Bundles</div>
			@if ( isset($bundles) )
	   			@foreach ( $bundles as $key => $values )
					<div class="c-card__item">
						<div class="control field is-horizontal">
							<div class="field-label is-small"><label class="label">{{ $key }}</label></div>
							<select name="bundles[]" class="c-field">
								<option value=''></option>
							@foreach ( $values as $value )
								<option value="{{ $value->toJson() }}"
									@if ($book->bundleSingle($key))
										@if ( $book->bundleSingle($key)->title->value == $value->title->value )
										selected
										@endif
									@endif
								>
									{{ $value->title->value }}
								</option>
							@endforeach
							</select>
						</div>
					</div>
				@endforeach
			@endif
		</div>	

		<div class="c-card">
			<div class="c-card__item c-card__item--divider">Dates</div>
			<div class="c-card__item">
				<div class="control field is-horizontal">
					<div class="field-label is-small"><label class="label">Draft</label></div>
					<input class="c-field flatpickr" name="timestamp[draft]" type="text" @if ( isset($book->timestamp->draft) )
							value="{{ $book->timestamp->draft->toDateString() }}"
						@endif
						>
				</div>
			</div>
			<div class="c-card__item">
				<div class="control field is-horizontal">
					<div class="field-label is-small"><label class="label">Published</label></div>
					<input class="c-field flatpickr" name="timestamp[publish]" type="text" @if (isset($book->timestamp->publish) )
						value="{{ $book->timestamp->publish->toDateString() }}"
					@endif
					>
				</div>
			</div>
			<div class="c-card__item">
				<div class="control field is-horizontal">
					<div class="field-label is-small"><label class="label">Amended</label></div>
					<input class="c-field flatpickr" name="timestamp[amend]" type="text" @if ( isset($book->timestamp->amend) )
							value="{{ $book->timestamp->amend->toDateString() }}"
						@endif
						>
				</div>
			</div>
		</div>
	</div>
</div>