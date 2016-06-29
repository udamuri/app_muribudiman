function admin()
{
	this.baseUrl = '';
	
	this.initialScript = function()
	{	

		AdminObj.dinamicButton();
		
		$('#add-new-user').on('click', function(){
			$('#user_form_label').text('New User');
			AdminObj.clearUserForm();
			$('#myModalUser').modal({
				backdrop: 'static',
				keyboard: false
			});
		});

	
		$('#xp-pdx-id-save-user').on('click', function(){
			if ($('#p_ud').val() == 0 ) 
			{
				AdminObj.setUser();
			}
			else
			{
				var p_ud = $('#p_ud').val();
				AdminObj.updateUser(p_ud);
			}
			
		});
	}
	
	this.dinamicButton = function()
	{
		$('.update-user').unbind('click');
		$('.update-user').on('click', function(){
			var id = this.id;
			var ids = id.replace('update-user-','');
			AdminObj.getUser(ids);
		});

		$('.delete-user').unbind('click');
		$('.delete-user').on('click', function(){
			var id = this.id;
			var ids = id.replace('delete-user-','');
			AdminObj.deleteUser(ids);
		});
	}

	this.clearUserForm = function()
	{
		IndexObj.yiiClearErrorForm();
		$('#p_ud').val(0);
		$('#username').val('');
		$('#firstname').val('');
		$('#lastname').val('');
		$('#email').val('');
		$('#level_user').val('');
		$('#password').val('user');
		$('#box-password').fadeIn(0);
	}

	this.clearUserUpdateForm = function()
	{
		IndexObj.yiiClearErrorForm();
		$('#p_ud').val(0);
		$('#username').val('');
		$('#firstname').val('');
		$('#lastname').val('');
		$('#email').val('');
		$('#level_user').val('');
		$('#password').val('user');
		$('#box-password').fadeOut(0);
	}

	//Set User
	this.setUser = function()
	{
		var arrForm = [
			['UserForm[username]',$('#username').val()],
			['UserForm[firstname]',$('#firstname').val()],
			['UserForm[lastname]',$('#lastname').val()],
			['UserForm[email]',$('#email').val()],
			['UserForm[password]',$('#password').val()],
			['UserForm[level_user]',$('#level_user').val()],
		];
		IndexObj.yiiAjaxForm(
			'admin/site/create-user', 
			arrForm, 
			'xp-pdx-id-save-user',  //btn id
			function(data){
				if(typeof data == 'string')
				{
					var arrData = IndexObj.jsonToArray(data);
					if(arrData['status'] == 'success') 
					{
						//IndexObj.alertBox('Success', 'success', 1000,'');
						IndexObj.yiiClearErrorForm();
						IndexObj.yiiAddSuccessForm();
						IndexObj.wlocation(IndexObj.baseUrl+'user');
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


	//Get User
	this.getUser = function(p_id)
	{
		var arrForm = [
			['p_id',p_id],
		];
		IndexObj.yiiAjaxForm(
			'admin/site/get-user', 
			arrForm, 
			'',  //btn id
			function(data){
				if(typeof data == 'string')
				{
					var arrData = IndexObj.jsonToArray(data);
					if(arrData['status'] == 'success') 
					{
						var aData = arrData['arr-data'];
						AdminObj.clearUserUpdateForm();
						$('#p_ud').val(aData['id']);
						$('#username').val(aData['username']);
						$('#firstname').val(aData['firstname']);
						$('#lastname').val(aData['lastname']);
						$('#email').val(aData['email']);
						$('#level_user').val(aData['level_user']);
						$('#user_form_label').text('Update User');
						$('#myModalUser').modal({
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

	//Update User
	this.updateUser = function(pid)
	{
		var arrForm = [
			['p_id',pid],
			['UserUpdateForm[firstname]',$('#firstname').val()],
			['UserUpdateForm[lastname]',$('#lastname').val()],
			['UserUpdateForm[level_user]',$('#level_user').val()],
		];
		IndexObj.yiiAjaxForm(
			'admin/site/update-user', 
			arrForm, 
			'xp-pdx-id-save-user',  //btn id
			function(data){
				if(typeof data == 'string')
				{
					var arrData = IndexObj.jsonToArray(data);
					if(arrData['status'] == 'success') 
					{
						IndexObj.alertBox('Success', 'success', 1000,'');
						IndexObj.yiiClearErrorForm();
						IndexObj.yiiAddSuccessForm();
						$('#user_name_'+pid).text(arrData['data']['firstname']+' '+arrData['data']['lastname']);
						$('#user_level_user_'+pid).text(arrData['data']['level_user']);
						$('#xp-pdx-id-close-user').click();
					}
					else if(arrData['status'] == 'error')
					{
						//IndexObj.alertBox('Ups .. Silahkan Ulangi', 'error', 1000,'');
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

	//Update User
	this.deleteUser = function(pid)
	{
		var arrForm = [
			['p_id',pid],
			['UserUpdateForm[firstname]',$('#firstname').val()],
			['UserUpdateForm[lastname]',$('#lastname').val()],
			['UserUpdateForm[level_user]',$('#level_user').val()],
		];
		IndexObj.yiiAjaxForm(
			'admin/site/delete-user', 
			arrForm, 
			'',  //btn id
			function(data){
				if(typeof data == 'string')
				{
					var arrData = IndexObj.jsonToArray(data);
					if(arrData['status'] == 'success') 
					{
						IndexObj.alertBox('Success', 'success', 1000,'');
						IndexObj.yiiClearErrorForm();
						IndexObj.yiiAddSuccessForm();
						$('#user_status_'+pid).html(arrData['data']);
					}
					else if(arrData['status'] == 'error')
					{
						//IndexObj.alertBox('Ups .. Silahkan Ulangi', 'error', 1000,'');
					}
				
				}
				
				return false;
			}
		);
	}

	
}

var AdminObj = new admin();