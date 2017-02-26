var Command = {

	waiting: false,

	/**
	 * Send command
	 * @method  send
	 * @author  Marko Praakli
	 * @date    2017-01-06
	 */
	send: function( item_id, command, $btn ){

		var parent = this,
			url = 'ajax/command/send',
			data = {
				item_id: item_id,
				command: command
			};

		// One command on time
		if( !parent.waiting )
		{
			parent.waiting = true;

			// Do ajax request
			$.post( url, data, function( response ){
				parent._on_success( response, $btn );
			} ).fail( function( e ){
				parent._on_fail( e, $btn );
			} ).always( function( e ){
				parent.waiting = false;
			} );
		}
	},

	/**
	 * On command success
	 * @method  _on_success
	 * @author  Marko Praakli
	 * @date    2017-01-06
	 */
	_on_success: function( response, $btn ){

		console.log( "success" );
		console.log( response );

		this.waiting = false;

		$btn.addClass( 'fshake_fx' );

		setTimeout(function(){
			$btn.removeClass( 'fshake_fx' );
		}, 1000);
	},

	/**
	 * On command fail
	 * @method  _on_fail
	 * @author  Marko Praakli
	 * @date    2017-01-06
	 */
	_on_fail: function( e, $btn ){

		this.waiting = false;

		// Shake it
		$btn.addClass( 'fshake_fx' );

		setTimeout(function(){
			$btn.removeClass( 'fshake_fx' );
		}, 1000);
	}
};