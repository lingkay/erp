/**
Core script to handle the entire theme and core functions
**/
var Notification = function () {
	var oldData = {};
	var updateMenu = function(res){
		
		//rewrite dom only when there's new notification
		if(JSON.stringify(oldData) == JSON.stringify(res)){
			return true;
		}
		oldData = res;
		$('#notification-ul').html('');
		if(res.unread > 0){
			$('#notification-count').html(res.unread);
		}
		$('#notification-count-inline').html(res.unread);

		if(res.notifications.length > 0){
			var ct = 0;
			for (i in res.notifications){
				//Limit notifications to 5
				if(ct >= 5) break;
				ct++;

				var ntf = res.notifications[i];
				switch(ntf.type){
					case "UPDATE" :
						icon = '<span class="label label-sm label-icon label-success"><i class="fa fa-plus"></i></span>';
						break;
					case "ALERT" :
						icon = '<span class="label label-sm label-icon label-warning "><i class="fa fa-bell-o"></i></span>';
						break;
					case "CALENDAR" :
						icon = '<span class="label label-sm label-icon label-info "><i class="fa fa-calendar"></i></span>';
						break;
				}
				var readclass = ntf.read==false? "unread-notification":"";
				var row = '<li class="notification-link '+ readclass +'"  data-id="'+ntf.id+'" data-link="'+ntf.link+'" >'+
				            '<a href="javascript:void(0)">'+
				            icon+
				            ntf.message+
				            ' <span class="time">'+ntf.time_passed+'</span></a>'+
				        '</li>';

		        $('#notification-ul').append($(row));

			}

			//$('#notification-ul').html(rows);
		}


	}

	var redirectToLink = function(link){
		window.location.href = link;
	}

	var setAsRead = function(ntf){
		$.ajax({
                type: "GET",
                cache: false,
                url: '/notifications/read/'+ntf.id,
                dataType: "json",
                success: function (res) {
                	redirectToLink(ntf.link);
                },
                error: function (xhr, ajaxOptions, thrownError) {

                }
         });
	}
	var getNotifications = function(){
		$.ajax({
                type: "GET",
                cache: false,
                url: '/notifications/all',
                dataType: "json",
                success: function (res) {
                	updateMenu(res);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                  
                }
         });
		$("#header_notification_bar").on('click','.notification-link',function(){
			var ntf = $(this).data();
			setAsRead(ntf);
		});
	};

	return {
        //main function to initiate the theme
        init: function () {
         	setInterval( getNotifications,3000);
        }

    };


}();