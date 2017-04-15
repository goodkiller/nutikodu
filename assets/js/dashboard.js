var Dashboard = {
	
	container: null,
	isotope : null,

	loading: true,

	items: {
		dynamic: []
	},

	options: {
		gutter: 4,
		timers: {
			events: 5000
		}
	},

	events: {
		long_click: {
			enabled: true
		}
	},

	init: function( container ){

		var parent = this;
		
		// Set container
		parent.container = $( container );

		// Disable loader
		$( '.ui-loader' ).remove();

		// Load items
		parent.loadItems();

		// On window resize
		$(window).bind('debouncedresize', function(){
			parent.resizeItems();
		});

		// Clickable events
		$( document.body ).on('click', '[data-command]', function(){

			var $btn = $( this ),
				$btn_data = $btn.data();

			// Send command
			Command.send( $btn_data.id, $btn_data.command, $btn );
		});

		// Load timers
		this.loadTimers();
	},

	loadItems: function(){

		var parent = this;

		this.isotope = this.container.isotope({
			resizable: false,
			itemSelector: 'figure',
			masonry: {
				columnWidth: this.__calc_col_width(),
				gutterWidth: parent.options.gutter
			}
		});

		$.getJSON( 'ajax/dashboards/get_items/1', function(data){

			// Add items
			if( data.items !== undefined && data.items.length > 0 ){
				for( var i in data.items ){
					parent.addItem( data.items[ i ] );
				}
			}

			// Stop loading
			parent.setLoader( false );
		});
	},

	loadTimers: function(){

		var parent = this;

		setInterval( function(){
			parent.getItemEvents();
		}, parent.options.timers.events );
	},

	resizeItems: function(){

		var parent = this;

		this.container.find('figure').each(function(){

			// Calculate sizes
			var $item = $(this), 
				$sizes = parent.__calculateItemSizes( $item );

			// Add sizes
			$item.css({
				width: $sizes.w,
				height: $sizes.h
			});
		});

		this.isotope
			.isotope({
				masonry: {
					columnWidth: this.__calc_col_width()
				}
			});
	},

	getItemEvents: function(){

		var parent = this;

		if( !parent.loading && parent.items.dynamic.length > 0 )
		{
			$.ajax({
				type: 'POST',
				url: 'ajax/events/items',
				data: {
					items: parent.items.dynamic
				},
				success: function( response ){ 

					if( response.status == 'OK' )
					{
						for( var i in response.items )
						{
							// Update item
							parent.updateItem( response.items[ i ] );
						}
					}
				},
				dataType: 'json'
			});
		}
	},

	addItem: function( item_info ){

		// Add dynamic items
		if( item_info.display_type == 'dynamic' ){
			this.items.dynamic.push( item_info.id );
		}
		
		$item_container = $( '<figure />' )
			.addClass( this.__getItemClasses( item_info ) )
			.css( 'backgroundColor', item_info.bg_color )
			.data({
				'id': item_info.id,
				'did': item_info.did
			})
			.attr({
				'data-id': item_info.id,
				'data-did': item_info.did,
				'data-display-type': item_info.display_type
			})
			.bind( 'taphold', this.__on_long_click );

		// Title
		if( item_info.title !== undefined && item_info.title.length > 0 )
		{
			$( '<div />' )
				.addClass( 'title' )
				.text( item_info.title )
				.appendTo( $item_container );

			$item_container.attr( 'title', item_info.title );
		}

		// Icon
		if( item_info.body !== undefined && item_info.body.length > 0 )
		{
			$( '<div />' )
				.addClass( 'body' )
				.html( item_info.body )
				.appendTo( $item_container );
		}

		if( item_info.event_type !== null )
		{
			// Click
			if( item_info.event_type == 'click' ){
				$item_container.bind( 'click', this.__on_single_click );
			}

			// Toggle
			if( item_info.event_type == 'toggle' ){
				$item_container.bind( 'click', this.__on_toggle );
			}
		}

		// Calculate sizes
		var $sizes = this.__calculateItemSizes( $item_container );

		// Add sizes
		$item_container.css({
			width: $sizes.w,
			height: $sizes.h
		});

		// Run isotope
		this.isotope
			.isotope( 'insert', $item_container )
			.isotope({
				masonry: {
					columnWidth: this.__calc_col_width()
				}
			});

		// After item loaded
		this._afterItemLoaded( item_info );
	},

	updateItem: function( item_info ){

		// Update title
		if( item_info.title !== undefined && item_info.title.length > 0 ){
			$( 'figure[data-did="' + item_info.did + '"] > .title' ).text( item_info.title );
		}

		// Update body
		if( item_info.body !== undefined && item_info.body.length > 0 ){
			$( 'figure[data-did="' + item_info.did + '"] > .body' ).html( item_info.body );
		}

		// After item loaded
		this._afterItemLoaded( item_info );
	},

	setLoader: function( status ){

		this.loading = status;
	},

	_afterItemLoaded: function( item_info ){

		var parent = this,
			$figure = $( 'figure[data-did="' + item_info.did + '"]' ),
			$slider = $( 'input[data-provide="slider"]', $figure );

		// Slider
		$slider.bootstrapSlider();
		$slider
			.on("slideStart", function(){

				// Disable long click event
				parent.events.long_click.enabled = false;
			})
			.on("slideStop", function(slideEvt){

				var $item = $(this).closest('figure').data();

				$.get( 'ajax/zitems/exact/' + $item.id + '/' + slideEvt.value );

				// Enable long click event
				parent.events.long_click.enabled = true;
			});
	},

	__getItemClasses: function( item_info ){

		var classes = [];

		if( item_info.size != null ){
			classes.push( 'size-' + item_info.size );
		}

		if( item_info.classname != null && item_info.classname !== null ){
			classes.push( item_info.classname );
		}

		if( item_info.event_type !== null ){
			classes.push( 'ripple_fx' );
		}

		return classes.join( ' ' );
	},

	__calculateItemSizes: function( $item ){

		var columnWidth = this.__calc_col_width(),
			multiplier = $item.attr('class').match(/size-(\d)x(\d)/),
			width = multiplier[ 1 ] ? columnWidth * multiplier[ 1 ] - this.options.gutter : columnWidth - this.options.gutter,
			height = multiplier[ 2 ] ? columnWidth * multiplier[ 2 ] - this.options.gutter : columnWidth - this.options.gutter;

		return {
			'w': width,
			'h': height
		};
	},

	__calc_col_width: function(){

		var w = this.container.width(), 
			columnNum = Math.round( w / 100 ),
			columnWidth = Math.floor( w / columnNum );

		return columnWidth;
	},

	__on_long_click: function( e ){

		if( Dashboard.events.long_click.enabled )
		{
			var $item = $( this );

			Dialog.open(
				'#item_settings',
				$item.find( '.title' ).text(), 
				'ajax/settings/item/' + $item.data( 'id' ) 
			);
		}
	},

	__on_single_click: function( e ){

		var $item = $( this );

		Dialog.open(
			'#item_toggle', 
			$item.find( '.title' ).text(), 
			'ajax/zitems/click/' + $item.data( 'id' )
		);
	},

	__on_toggle: function( e ){

		var $item = $( this );

		$.get( 'ajax/zitems/toggle/' + $item.data( 'id' ) );
	}
};