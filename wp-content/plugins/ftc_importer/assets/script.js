jQuery(document).ready(function($){
	"use strict";
	
	/* Select Options */
	$('.ftc-importer-wrapper .option label').bind('click', function(){
		var checkbox = $(this).find('input[type="checkbox"]');
		if( checkbox.is(':checked') ){
			$(this).addClass('selected');
		}
		else{
			$(this).removeClass('selected');
		}
		
		ftc_import_button_state();
	});
	
	function ftc_import_button_state(){
		var disabled = true;
		$('.ftc-importer-wrapper .option input[type="checkbox"]').each(function(){
			if( $(this).is(':checked') ){
				disabled = false;
				return;
			}
		});
		$('#ftc-import-button').attr('disabled', disabled);
	}
	
	/* Import */
	var ftc_import_percent = 0, ftc_import_percent_increase = 0, ftc_import_index_file = 0;
	var ftc_arr_import_data = new Array();

	/* Full Import */
	$('#ftc-import-button-full').bind('click', function(){	
		$('.ftc-importer-wrapper .option label').unbind('click');
		$(this).attr('disabled', true);
		$(this).siblings('.importing-button').removeClass('hidden');
		$('.ftc-importer-wrapper label').addClass('hidden');
		$('#ftc-import-button').css('display','none');
		
		$('.ftc-importer-wrapper .import-result').removeClass('hidden');

			ftc_arr_import_data.push( {'action' : 'ftc_import_theme_options', 'message' : 'Theme Options Imported Successfully'} );

			ftc_arr_import_data.push( {'action' : 'ftc_mmm_options_backup', 'message' : 'Mega Main Menu Imported Successfully'} );		

			ftc_arr_import_data.push( {'action' : 'ftc_import_widget', 'message' : 'Widgets Imported Successfully'} );
	
			ftc_arr_import_data.push( {'action' : 'ftc_import_revslider', 'message' : 'Revolution Sliders Imported Successfully'} );
		
			ftc_arr_import_data.push( {'action' : 'ftc_import_content', 'message' : 'Demo Content Imported Successfully'});			
		
			ftc_arr_import_data.push( {'action' : 'ftc_import_config'} );
		
		var total_ajaxs = ftc_arr_import_data.length;
		
		if( total_ajaxs == 0 ){
			return;
		}
		
		ftc_import_percent_increase = 100 / total_ajaxs;
		
		ftc_import_ajax();
		
	});
	/* Custom Import */
	$('#ftc-import-button').bind('click', function(){
		$('.ftc-importer-wrapper .option label').unbind('click');
		
		$(this).attr('disabled', true);
		$(this).siblings('.importing-button').removeClass('hidden');
		$('#ftc-import-button-full').addClass('hidden');
		
		$('.ftc-importer-wrapper .import-result').removeClass('hidden');
		
		var import_theme_options = $('#ftc_import_theme_options').is(':checked');
		var mmm_options_backup= $('#ftc_mmm_options_backup').is(':checked');
		var import_widget = $('#ftc_import_widget').is(':checked');
		var import_revslider = $('#ftc_import_revslider').is(':checked');
		var import_demo_content = $('#ftc_import_demo_content').is(':checked');
		
		if( import_theme_options ){
			ftc_arr_import_data.push( {'action' : 'ftc_import_theme_options', 'message' : 'Theme Options Imported Successfully'} );
		}

		if( mmm_options_backup ){
			ftc_arr_import_data.push( {'action' : 'ftc_mmm_options_backup', 'message' : 'Mega Main Menu Imported Successfully'} );
		}
		
		if( import_widget ){
			ftc_arr_import_data.push( {'action' : 'ftc_import_widget', 'message' : 'Widgets Imported Successfully'} );
		}
		
		if( import_revslider ){
			ftc_arr_import_data.push( {'action' : 'ftc_import_revslider', 'message' : 'Revolution Sliders Imported Successfully'} );
		}

		if( import_demo_content ){			
			ftc_arr_import_data.push( {'action' : 'ftc_import_content', 'message' : 'Demo Content Imported Successfully'});			
		}
		
		if( import_demo_content ){
			ftc_arr_import_data.push( {'action' : 'ftc_import_config'} );
		}
		
		var total_ajaxs = ftc_arr_import_data.length;
		
		if( total_ajaxs == 0 ){
			return;
		}
		
		ftc_import_percent_increase = 100 / total_ajaxs;
		
		ftc_import_ajax();
		
	});
	
	function ftc_import_ajax(){
		if( ftc_import_index_file == ftc_arr_import_data.length ){
			ftc_add_result_message( 'Success!!!' );
			$('.ftc-importer-wrapper .fa.importing-button').hide();
			return;
		}
		$.ajax({
			type: 'POST'
			,url: ajaxurl
			,async: true
			,data: ftc_arr_import_data[ftc_import_index_file]
			,complete: function(jqXHR, textStatus){
				ftc_import_percent += ftc_import_percent_increase;
				ftc_progress_bar_handle();
				if( ftc_arr_import_data[ftc_import_index_file].message ){
					ftc_add_result_message( ftc_arr_import_data[ftc_import_index_file].message );
				}
				ftc_import_index_file++;
				setTimeout(function(){
					ftc_import_ajax();
				}, 6000);
			}
		});
	}
	
	function ftc_progress_bar_handle(){
		if( ftc_import_percent > 100 ){
			ftc_import_percent = 100;
		}
		var progress_bar = $('.ftc-importer-wrapper .import-result .progress-bar');
		progress_bar.css({'width': Math.ceil( ftc_import_percent ) + '%'});
		progress_bar.html( Math.ceil( ftc_import_percent ) + '% Complete');
	}
	
	function ftc_add_result_message( message ){
		var message_wrapper = $('.ftc-importer-wrapper .messages');
		message_wrapper.append('<p>' + message + '</p>');
	}
	
});
