var Dialog = {

	modal: null,

	/**
	 * Open dialog modal
	 * @method  open
	 * @author  Marko Praakli
	 * @date    2017-01-06
	 */
	open: function( selector, title, url ){

		this.closeAll();

		this.modal = $( selector ).modal('show');

		$( '.modal-title', this.modal ).text( title );
		$( '.modal-body', this.modal ).load( url );
	},

	/**
	 * Close dialog modal
	 * @method  close
	 * @author  Marko Praakli
	 * @date    2017-01-06
	 */
	close: function(){

		this.modal.modal('hide');
	},

	/**
	 * Close all dialog modals
	 * @method  closeAll
	 * @author  Marko Praakli
	 * @date    2017-01-06
	 */
	closeAll: function(){

		$( '.modal' ).modal('hide');
	}
};