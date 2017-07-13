(function(){
		'use strict';
		var $ = jQuery;
		$.fn.extend({
			filterTable: function(){
				return this.each(function(){
					$(this).on('keyup', function(e){
						$('.filterTable_no_results').remove();
						var $this = $(this), 
							search = $this.val().toLowerCase(), 
							target = $this.attr('data-filters'), 
							$target = $(target), 
							$rows = $target.find('tbody tr');
							
						if(search == '') {
							$rows.show(); 
						} else {
							$rows.each(function(){
								var $this = $(this);
								$this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();
							})
							if($target.find('tbody tr:visible').size() === 0) {
								var col_count = $target.find('tr').first().find('td').size();
								var no_results = $('<tr class="filterTable_no_results"><td colspan="'+col_count+'">No results found</td></tr>')
								$target.find('tbody').append(no_results);
							}
						}
					});
				});
			}
		});
		$('[data-action="filter"]').filterTable();
	})(jQuery); 
	$(function(){
	    // attach table filter plugin to inputs
		// $('[data-action="filter"]').filterTable();
		
		$('#main-content').on('click', '.panel-heading span.filter', function(e){
			var $this = $(this), 
				$panel = $this.parents('.panel');
			
			$panel.find('.panel-body').slideToggle();
			if($this.css('display') != 'none') {
				$panel.find('.panel-body input').focus();
			}
		});
		$('[data-toggle="tooltip"]').tooltip();
	})
	$(document).ready(function () {
		$('#main-content').on('change', '.form', function (e) {
			var $this = $(this);
			// console.log($this.find(':selected').text());
			var table = $this.parents('.panel').find('table').data('tabel');  
			var $target = $this.find(':selected').text();  
			alert($target);
			if ($target != 'all') {
			   $('.table[id="'+table+'"] tbody tr').hide();	 	
				$('.table[id="'+table+'"] tbody tr[data-status="' + $target + '"]').fadeIn('slow'); 
			} else { 
				$('.table[id="'+table+'"] tbody tr').css('display', 'none').fadeIn('slow'); 
			}
		});
	});	