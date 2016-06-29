function ticket()
{
	this.baseUrl = '';
	this.typex = '';
	
	this.initialScript = function()
	{	

		
		$('[data-toggle="tooltip"]').tooltip(); 
		
		if(TicketObj.typex == '0')
		{
			TicketObj.dinamicButton();

			$('#add-new-ticket').on('click', function(){
				$('#ticket_form_label').text('New Ticket');
				TicketObj.clearUserForm();
				$('#myModalTicket').modal({
					backdrop: 'static',
					keyboard: false
				});
			});

		
			$('#xp-pdx-id-save-ticket').on('click', function(){
				if ($('#p_ud').val() == 0 ) 
				{
					TicketObj.setTicket();
				}
				else
				{
					var p_ud = $('#p_ud').val();
					TicketObj.updateTicket(p_ud);
				}
				
			});
		}

		if(TicketObj.typex == '1')
		{
			TicketObj.dinamicAllButton();
		}
	}
	
	this.dinamicButton = function()
	{
		$('.update-ticket').unbind('click');
		$('.update-ticket').on('click', function(){
			var id = this.id;
			var ids = id.replace('update-ticket-','');
			TicketObj.getTicket(ids);
		});

	}

	this.dinamicAllButton = function()
	{
		$('.all-update-ticket').unbind('click');
		$('.all-update-ticket').on('click', function(){
			var id = this.id;
			var ids = id.replace('all-update-ticket-','');
			$('#process_ticket_form_label').text('Set Ticket Status');
			$('#p_ud').val(ids);
			$('#myModalTicketStatus').modal({
				backdrop: 'static',
				keyboard: false
			});
		});

		$('.all-assigned-ticket').unbind('click');
		$('.all-assigned-ticket').on('click', function(){
			var id = this.id;
			var ids = id.replace('all-assigned-ticket-','');
			TicketObj.getItSupport(ids);
		});

		$('.assigned-to-user').unbind('click');
		$('.assigned-to-user').on('click', function(){
			var id = this.id;
			var ids = id.replace('assigned-to-user-','');
			var t_id = $('#t_idx').val();
			TicketObj.setAssignedTo(t_id, ids);
		});

		$('.ticket-process-data').unbind('click');
		$('.ticket-process-data').on('click', function(){
			var id = this.id;
			var ids = id.replace('ticket-process-data-','');
			var p_ud = $('#p_ud').val();
			TicketObj.ticketStatus(p_ud, ids);
		});
	}

	this.clearUserForm = function()
	{
		IndexObj.yiiClearErrorForm();
		$('#p_ud').val(0);
		$('#ticket_name').val('');
		$('#ticket_desc').val('');
	}

	//Set Ticket
	this.setTicket = function()
	{
		var arrForm = [
			['TicketForm[ticket_name]',$('#ticket_name').val()],
			['TicketForm[ticket_desc]',$('#ticket_desc').val()],
		];

		IndexObj.yiiAjaxForm(
			'ticket/site/create-ticket', 
			arrForm, 
			'xp-pdx-id-save-ticket',  //btn id
			function(data){
				if(typeof data == 'string')
				{
					var arrData = IndexObj.jsonToArray(data);
					if(arrData['status'] == 'success') 
					{
						//IndexObj.alertBox('Success', 'success', 1000,'');
						IndexObj.yiiClearErrorForm();
						IndexObj.yiiAddSuccessForm();
						IndexObj.wlocation(IndexObj.baseUrl+'user-ticket');
					}
					else if(arrData['status'] == 'error')
					{
						IndexObj.alertBox('Ups .. Please try again', 'error', 1000,'');
					}
					else if(arrData['status'] == 'form-error')
					{
						IndexObj.alertBox('Ups .. Please try again', 'warning', 1000,'');
						IndexObj.yiiClearErrorForm();
						if((typeof arrData['error-form']) == 'object')
						{
							$.each(arrData['error-form'], function( index, value ) {
								if(arrData['error-form'][index])
								{
									var box = index.split('-');
									$('#box-'+box[1]).addClass('has-error');
									$('#text-'+box[1]).text(value);
								}
							});
						}
					}	
				}
				
				return false;
			}
		);
	}


	//Get Ticket
	this.getTicket = function(p_id)
	{

		var arrForm = [
			['p_id',p_id],
		];
		IndexObj.yiiAjaxForm(
			'ticket/site/get-ticket', 
			arrForm, 
			'',  //btn id
			function(data){
				if(typeof data == 'string')
				{
					var arrData = IndexObj.jsonToArray(data);
					if(arrData['status'] == 'success') 
					{
						var aData = arrData['arr-data'];
						TicketObj.clearUserForm();
						$('#p_ud').val(aData['ticket_id']);
						$('#ticket_name').val(aData['ticket_name']);
						$('#ticket_desc').val(aData['ticket_desc']);
						$('#ticket_form_label').text('Update Ticket');
						$('#myModalTicket').modal({
							backdrop: 'static',
							keyboard: false
						});
					}
					else
					{
						IndexObj.alertBox('Ups .. Please try again', 'error', 1000,'');
					}
				}
				
				return false;
			}
		);
	}


	//Update Ticket
	this.updateTicket = function(pid)
	{
		var arrForm = [
			['p_id',pid],
			['TicketForm[ticket_name]',$('#ticket_name').val()],
			['TicketForm[ticket_desc]',$('#ticket_desc').val()],
		];
		IndexObj.yiiAjaxForm(
			'ticket/site/update-ticket', 
			arrForm, 
			'xp-pdx-id-save-ticket',  //btn id
			function(data){
				if(typeof data == 'string')
				{
					var arrData = IndexObj.jsonToArray(data);
					if(arrData['status'] == 'success') 
					{
						IndexObj.alertBox('Success', 'success', 1000,'');
						IndexObj.yiiClearErrorForm();
						IndexObj.yiiAddSuccessForm();
						$('#ticket_name_'+pid).text(arrData['data']['ticket_name']);
						$('#ticket_desc_'+pid).text(arrData['data']['ticket_desc']);
						$('#xp-pdx-id-close-ticket').click();
					}
					else if(arrData['status'] == 'error-role-update')
					{
						IndexObj.alertBox('Ups .. Sorry cannot edit', 'danger', 1000,'');
					}
					else if(arrData['status'] == 'form-error')
					{
						IndexObj.alertBox('Ups .. Please try again', 'warning', 1000,'');
						IndexObj.yiiClearErrorForm();
						if((typeof arrData['error-form']) == 'object')
						{
							$.each(arrData['error-form'], function( index, value ) {
								if(arrData['error-form'][index])
								{
									var box = index.split('-');
									$('#box-'+box[1]).addClass('has-error');
									$('#text-'+box[1]).text(value);
								}
							});
						}
					}	
				}
				
				return false;
			}
		);
	}

	//get It Support
	this.getItSupport = function(t_id)
	{
		var arrForm = [
			['t_id', t_id],
		];
		IndexObj.yiiAjaxForm(
			'ticket/site/get-it-support', 
			arrForm, 
			'',  //btn id
			function(data){
				if(typeof data == 'string')
				{
					var arrData = IndexObj.jsonToArray(data);
					if(arrData['status'] == 'success') 
					{
						var dataArr = arrData['arr-data'];
						var html = '';
						$('#assigned_form_label').text('Assigned To');
						$('#it-support-container').empty();
						$('#t_idx').val(t_id);
						$('#myModalItSupport').modal({
							backdrop: 'static',
							keyboard: false
						});
						if(dataArr.length)
						{
							for(var i=0;i<dataArr.length;i++)
							{
								html += '<div class="assigned-to">'+
											'<div class="col-lg-11 col-md-11 col-lg-9">'+dataArr[i]['username']+' ('+dataArr[i]['firstname']+' '+dataArr[i]['lastname']+')'+'</div>'+
											'<div class="col-lg-1 col-md-1 col-lg-3"><button id="assigned-to-user-'+dataArr[i]['u_id']+'" class="btn btn-primary btn-xs assigned-to-user">Assigned</button></div>'+
											'<div class="clearfix"></div>'+
										'</div>';
							}
						}
						else
						{
							html = '<div class="assigned-to"><div class="col-md-12">Empty It Support</div><div class="clearfix"></div></div>';
						}
						

						$('#it-support-container').html(html);
						TicketObj.dinamicAllButton();
					}
					else if(arrData['status'] == 'error')
					{
						IndexObj.alertBox('Ups .. Sorry cannot get It Support Data', 'danger', 1000,'');
					}	
				}
				
				return false;
			}
		);
	}

	//get It Support
	this.setAssignedTo = function(t_id, u_id)
	{
		var arrForm = [
			['t_id', t_id],
			['u_id', u_id],
		];
		IndexObj.yiiAjaxForm(
			'ticket/site/set-assigned', 
			arrForm, 
			'',  //btn id
			function(data){
				if(typeof data == 'string')
				{
					var arrData = IndexObj.jsonToArray(data);
					if(arrData['status'] == 'success') 
					{
						var label = arrData['label-data'];
						$('#assigned-data-ticket-'+t_id).html(label);
						IndexObj.alertBox('Success Assigned', 'success', 1000,'');
						$('#asasad-id-close-assignet-add-top').click();
					}
					else if(arrData['status'] == 'error')
					{
						IndexObj.alertBox('Ups .. Sorry cannot get It Support Data', 'danger', 1000,'');
					}	
				}
				
				return false;
			}
		);
	}

	this.ticketStatus = function(t_id, t_status)
	{
		var arrForm = [
			['t_id', t_id],
			['t_status', t_status],
		];
		IndexObj.yiiAjaxForm(
			'ticket/site/set-ticket-status', 
			arrForm, 
			'',  //btn id
			function(data){
				if(typeof data == 'string')
				{
					var arrData = IndexObj.jsonToArray(data);
					if(arrData['status'] == 'success') 
					{
						var label = arrData['label-status'];
						$('#all_ticket_status_'+t_id).html(label);
						IndexObj.alertBox('Success Change Status', 'success', 1000,'');
						$('#asasad-id-close-status-ticket-add-top').click();
					}
					else if(arrData['status'] == 'error')
					{
						IndexObj.alertBox('Ups .. Sorry cannot get It Support Data', 'danger', 1000,'');
					}	
				}
				
				return false;
			}
		);
	}
	
}

var TicketObj = new ticket();