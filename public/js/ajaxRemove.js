$(function(){
    $('.ajax-remove-trigger').click(function(e){
        let path = $(this).attr('data-path');
        e.preventDefault();
        $.ajax({
            url: path,
            type: "GET",
            dataType: "json",
            success: function(data)
            {
                if(data.success)
                {
                    $(this).fadeOut('slow' , function(){
                        $(this).parents(".removable-element").remove();
                    });
                }
                else if(data.message){
                    alert(data.message);
                }
            }.bind(this)
        });

    });
});