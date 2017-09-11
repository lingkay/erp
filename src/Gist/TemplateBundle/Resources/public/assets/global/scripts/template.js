var Template = function () {
	var initSelect = function(){
		 $('.select_2').select2(); // styling selects
	}

	var initColorpicker = function(){
		  $('.colorpicker-group').colorpicker({
	            format: 'hex'
	        });
	}

	var initSummernote = function(){

        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
              ]
        });
	}

	var initToastr = function(){
            // Flash messages
        toastr.options = {
            closeButton: true,
            positionClass: 'toast-top-right',
            onclick: null,
            showDuration: 1000,
            hideDuration: 1000,
            timeOut: 10000,
            extendedTimeOut: 5000,
            preventDuplicates: true
        };


        $("input[name='error[]']").each(function(){
            if ($(this).val() != '' || $(this).val() != null) {
                toastr['error']($(this).val(), 'Error');
            };
        });

        $("input[name='success[]']").each(function(){
            if ($(this).val() != '' || $(this).val() != null) {
                toastr['success']($(this).val(), 'Success');
            };
        }); 

	}

	var initTimepicker = function(){
		 $('.timepicker-no-seconds').timepicker({
            autoclose: true,
            minuteStep: 5
        });
	}

	var initTypeahead = function(){
       $('.typeahead').each(function(){
        var auto = $(this);
        auto.typeahead({
            ajax: auto.data('path'),
            onSelect : function(item){
                auto.prev('input').val(item.value)
                auto.prop('readonly', true)
            }
        });
       });       

	}

	var initDatepicker = function(){
        $('.date-picker').datepicker({
        todayHighlight: true,
        autoclose: true
       });

	}

  return {
        //main function to initiate the module
        init: function () {
            initSelect();
            initTimepicker();
            initDatepicker();
            initToastr();
            initSummernote();
            initColorpicker();
            initTypeahead();
        }

    };
}();
