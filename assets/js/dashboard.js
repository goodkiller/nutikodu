var Dashboard = {
	
	container: null,
	isotope : null,

	options: {
		gutter: 4
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

			// Slider
			$( 'input[data-provide="slider"]' ).bootstrapSlider();
			$( 'input[data-provide="slider"]' ).on("slideStop", function(slideEvt) {

				var $item = $(this).closest('figure').data();

				$.get( 'ajax/zitems/exact/' + $item.id + '/' + slideEvt.value );
			});
		});
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

	addItem: function( item_info ){

		$item_container = $( '<figure />' )
			.addClass( this.__getItemClasses( item_info ) )
			.css( 'backgroundColor', item_info.bg_color )
			.data({
				'id': item_info.item_id
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

		var $item = $( this );

		Dialog.open(
			'#item_settings',
			$item.find( '.title' ).text(), 
			'ajax/settings/item/' + $item.data( 'id' ) 
		);
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