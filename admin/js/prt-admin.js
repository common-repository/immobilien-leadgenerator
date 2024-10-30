(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	function escapeHtml(text) {
		var map = {
			'&': '&amp;',
			'<': '&lt;',
			'>': '&gt;',
			'"': '&quot;',
			"'": '&#039;'
		};
		
		return text.replace(/[&<>"']/g, function(m) { return map[m]; });
	}

	function parseAdditionalPagesJson() {
		var value = $('#prt_apj_hidden_json').val();
		
		if (value.length <= 0) {
			value = '[]';
		}

		var jsonValue;
		
		try {
			jsonValue = JSON.parse(value);
		} catch (error) {
			console.log('non valid json found for: prt_apj_hidden_json', error);
			jsonValue = [];
			saveAdditionalPagesToJson(jsonValue);
		}

		return jsonValue;
	}

	function renderAdditionalPagesTables(json) {
		var jsonValue = json || parseAdditionalPagesJson();

		$('.prt_apj_data_table tbody').empty();

		for (var i = 0; i < jsonValue.length; i++) {
			var additionalPage = jsonValue[i];
			var ap_append  = additionalPage['append'] == 'before' ? 'Vor' : 'Nach';
			var ap_page    = additionalPage['page'];
			var ap_title   = additionalPage['title'];
			var ap_content = additionalPage['content'];
			var ap_fullpage = additionalPage['fullpage'];

			var thickboxTrigger = '<a data-id="'+i+'" href="#TB_inline?width=650&height=550&inlineId=prt_apj_modal_edit" title="Edit additional page" class="thickbox prt_apj_modal_trigger"><button class="button button-primary" type="button"><span class="dashicons dashicons-edit prt-lineheight-inherit"></span></button></a>';
			var removeButton = '<button type="button" class="button button-secondary prt_apj_data_remove_row"><span class="dashicons dashicons-trash prt-lineheight-inherit"></span></button>';

			$('.prt_apj_data_table tbody').append(
				'<tr id="prt_apj_data_row_'+i+'">' +
				'<td>'+ thickboxTrigger+'&nbsp;'+removeButton +'</td>' + 
				'<td>'+ap_append+'</td> <td>'+ap_page+'</td> <td>'+ap_title+'</td> <td><code>'+ escapeHtml(ap_content) +'</code></td> ' +
				'<td> '+ (ap_fullpage == true ? 'Ja' : 'Nein') +' </td>' +
				'</tr>'
			);
		}

		saveAdditionalPagesToJson(jsonValue);
	}

	function addAdditionalPage(page) {
		var jsonValue = parseAdditionalPagesJson();
		jsonValue.push(page);

		renderAdditionalPagesTables(jsonValue);
	}

	function removeRowFromDataTable(id) {
		var row = $('.prt_apj_data_table tr#'+id);
		var index = row.index();
		
		var jsonValue = parseAdditionalPagesJson();
		jsonValue.splice( index, 1 ); // delete index
		saveAdditionalPagesToJson(jsonValue);
		
		row.remove();
	}

	function saveAdditionalPagesToJson(jsonValue) {
		$('#prt_apj_hidden_json').val( JSON.stringify(jsonValue) );
	}

	$(document).ready(function() {

		// are we at the PRT APJ page?
		if( $('#prt_apj_hidden_json').length ) {
	
			renderAdditionalPagesTables();

			$('.prt_additional_pages_json').on('click', '#prt_apj_add_button', function(e) {
				e.preventDefault();

				console.log('add btn clicked');

				var new_append  = $('.prt_apj_new_table #prt_apj_new_append');
				var new_page    = $('.prt_apj_new_table #prt_apj_new_page');
				var new_title   = $('.prt_apj_new_table #prt_apj_new_title');
				var new_content = $('.prt_apj_new_table #prt_apj_new_content');
				var new_fullpage = $('.prt_apj_new_table #prt_apj_new_fullpage');

				addAdditionalPage({
					append:   new_append.val(),
					page:     new_page.val(),
					title:    new_title.val(),
					content:  new_content.val(),
					fullpage: new_fullpage.is(':checked')
				});

				// reset
				new_append.val('before');
				new_page.val('1');
				new_title.val('');
				new_content.val('');
				new_fullpage.prop('checked', false);
			});

			$('.prt_additional_pages_json').on('click', '.prt_apj_data_remove_row',function(e) {
				e.preventDefault();

				var rowId = $(this).closest('tr').attr('id');

				if (rowId) {
					removeRowFromDataTable(rowId);
				} else {
					console.log('rowId not found')
				}
			});

			$('.prt_additional_pages_json').on('click', '.prt_apj_modal_trigger', function(e) {
				var clickedId = $(this).attr('data-id');
				if (clickedId) {

					//parse
					var parsedClickedId = parseInt(clickedId);
					$('table.prt_apj_edit_table').attr('data-id', parsedClickedId);
					
					var jsonValue = parseAdditionalPagesJson();

					$('table.prt_apj_edit_table #prt_apj_edit_append').val(jsonValue[parsedClickedId].append);
					$('table.prt_apj_edit_table #prt_apj_edit_page').val(jsonValue[parsedClickedId].page);
					$('table.prt_apj_edit_table #prt_apj_edit_title').val(jsonValue[parsedClickedId].title);
					$('table.prt_apj_edit_table #prt_apj_edit_content').val(jsonValue[parsedClickedId].content);
					$('table.prt_apj_edit_table #prt_apj_edit_fullpage').prop('checked', jsonValue[parsedClickedId].fullpage);

				} else {
					e.preventDefault();
					return false;
				}
			});

			$('body').on('click', '.prt_apj_edit_submit', function(e) {
				var editId = $('table.prt_apj_edit_table').attr('data-id');
				if (editId) {
					var parsedEditId = parseInt(editId);

					var edit_append  = $('table.prt_apj_edit_table #prt_apj_edit_append').val();
					var edit_page    = $('table.prt_apj_edit_table #prt_apj_edit_page').val();
					var edit_title   = $('table.prt_apj_edit_table #prt_apj_edit_title').val();
					var edit_content = $('table.prt_apj_edit_table #prt_apj_edit_content').val();
					var edit_fullpage     = $('table.prt_apj_edit_table #prt_apj_edit_fullpage').is(':checked');

					var jsonValue = parseAdditionalPagesJson();
					jsonValue[parsedEditId].append   = edit_append;
					jsonValue[parsedEditId].page     = edit_page;
					jsonValue[parsedEditId].title    = edit_title;
					jsonValue[parsedEditId].content  = edit_content;
					jsonValue[parsedEditId].fullpage = edit_fullpage;

					renderAdditionalPagesTables(jsonValue); //its saves autom.

					tb_remove(); // close modal
				}

			});

			$('.prt_additional_pages_json').on('change', '#prt_apj_new_fullpage', function(e) {
				if(this.checked) {
					$('#prt_apj_alert_box').html(
						"<p>Wenn Sie diese Option aktivieren, müssen Sie auch nur ein einziges <code>img</code> Element im Inhalt haben. Andere Elemente werden ignoriert.<br><br>"
						+ "Das Bild sollte mindestens 595 x 842 px groß sein (Breite x Höhe).<br><b>Empfohlen</b> ist 2480 x 3508 px (Breite x Höhe).</p>"
					);
					tb_show("Info", "#TB_inline?inlineId=prt_apj_alert_box");
				}
			});

		}


	});

})( jQuery );
