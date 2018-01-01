<div class="pure-g">
	<div class="pure-u-1-1 pure-u-md-5-8">
		
		<div style="margin: 0.4rem;">
	
		@if (in_array('title', $book->dimensions))
			<div class="pure-control-group">
				<label class="label">Title</label>
				<input name="title" type="text"  placeholder="A title">
			</div>
		@endif
		
		@if (in_array('subtitle', $book->dimensions))
			<div class="pure-control-group">
				<label class="label">Subtitle</label>
				<input name="subtitle" type="text"  placeholder="A title">
			</div>
		@endif
		
		@if (in_array('slug', $book->dimensions))
			<div class="pure-control-group">
				<label class="label">Slug</label>
				<input name="slug" type="text"  placeholder="A title">
			</div>
		@endif
		
		@if (in_array('description', $book->dimensions))
			<div class="field is-horizontal">
				<label class="label">Description</label>
				<div class="field-body">
					<textarea name="description"  type="text" placeholder="Description"></textarea>
				</div>
			</div>
		@endif
			
			<textarea  placeholder="Textareas work too" name="content"></textarea>
	
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
					<input id="{{ $role }}" name="people[{{ $role }}]" type="text" style="display: none;">
					<div id="{{ $role }}-field"></div>
				</div>
				<script>
					$(function() {
						var selectedOptions = '';
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
				<input placeholder="location of file"  type="text" name="thumbnail[path]">
				<input placeholder="File name"  type="text" name="thumbnail[name]">
			</div>
			<div class="c-card__item">
				<input placeholder="Photographer"  type="text" name="thumbnail[photographer]">
				<input placeholder="Link to photographer's profile"  type="text" name="thumbnail[link]">
			</div>
		</div>
		@endif
		
		<div class="c-card">
			<div class="c-card__item c-card__item--divider">Keywords</div>
			<div class="c-card__item">
				<input id="keywords" name="keywords" style="display: none;">
				<div id="keywords-field" name="keywords" type="text" style="width: 100%; display: block;"></div>
			</div>
			<script>
				$(function() {
					var availableOptions = <?php echo $keywords->toJson(); ?>;
					var selectedOptions = ''
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
						<div class="pure-control-group">
							<label class="label">{{ $key }}</label>
							<select name="bundles[]" >
								<option value=''></option>
							@foreach ( $values as $value )
								<option value="{{ $value->toJson() }}"
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
				<div class="pure-control-group">
					<label class="label">Draft</label>
					<input class="c-field flatpickr" name="timestamp[draft]" type="text" >
				</div>
			</div>
			<div class="c-card__item">
				<div class="pure-control-group">
					<label class="label">Published</label>
					<input class="c-field flatpickr" name="timestamp[publish]" type="text" >
				</div>
			</div>
			<div class="c-card__item">
				<div class="pure-control-group">
					<label class="label">Amended</label>
					<input class="c-field flatpickr" name="timestamp[amend]" type="text" >
				</div>
			</div>
		</div>
	</div>
</div>