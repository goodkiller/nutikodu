var Dialog = {

	modal: null,

	open: function( selector, title, url ){

		this.closeAll();

		this.modal = $( selector ).modal('show');

		$( '.modal-title', this.modal ).text( title );
		$( '.modal-body', this.modal ).load( url );
	},

	close: function(){

		this.modal.modal('hide');
	},

	closeAll: function(){

		$( '.modal' ).modal('hide');
	}
};