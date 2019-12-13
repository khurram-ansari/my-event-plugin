$(document).ready(function () {


    $('.hee').slick({
        dots: true,
        infinite: false,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });
    $('#allevents_table').DataTable();
    $("#addeventform").submit(function(){
        if ($("#thumburl").val()==""){
            $("#thumberror").html("Event thumb image is required");

        }
    });
			
    $('.btnclick').click(function(e){
        e.preventDefault();
        $.post(ajaxurl,{action:"event_plugin_library",name:"KSA"},function(response){
            console.log(response);
        });
       });
});
$(function (){
    // add event post request handler
    $("#addeventform").validate({
    submitHandler:function(){
    if ($("#thumburl").val()!=""){
        var post_data=$("#addeventform").serialize()+"&action=event_plugin_library&param=add_event_data";
        $.post(ajaxurl,post_data,function(response){
            var data=$.parseJSON(response);
            if(data.status==1){
                $("#addeventbtn").notify(data.message,{position:"right",className:"success"});
            }
            else if(data.status==2){
                $("#addeventbtn").notify(data.message,{position:"right",className:"error"});
            }
        });
    
    }
}
    });
    // edit event post request handler
    $("#editeventform").validate({
        submitHandler:function(){
            if ($("#thumburl").val()!=""){
                var post_data=$("#editeventform").serialize()+"&action=event_plugin_library&param=edit_event_data";
                $.post(ajaxurl,post_data,function(response){
                    var data=$.parseJSON(response);
                    if(data.status==2){
                        $("#editeventbtn").notify(data.message,{position:"right",className:"error"});
                    }
                    else if(data.status==1){
                        $("#editeventbtn").notify(data.message,{position:"right",className:"success"});
                        setTimeout(function () {
                            location.reload();
                        },1300);
                    }
                });

            }
        }
    });
    //delete event handler
    $(".deleteeventbtn").click(function () {
        var conf=confirm("Are you sure , you want to delete ?");
        if (conf) {
            var eventid = $(this).attr("data-id");
            var post_data = "action=event_plugin_library&param=delete_event_data&id=" + eventid;
            $.post(ajaxurl, post_data, function (response) {
                var data = $.parseJSON(response);
                console.log(data.status);
                if (data.status == 1) {
                    $.notify(data.message, {position: "bottom center", className: "success"});
                    setTimeout(function () {
                        location.reload();
                    }, 1300);
                } else if (data.status == 2) {
                    $.notify(data.message, {position: "bottom center", className: "error"});
                }
            });
        }

});
});

$(function (){
$("#txtthumb").on("click",function(){
var images=wp.media({
        title:"Upload Thumb",
        multiple:false,

    }).open().on("select",function(e){
var up_imges=images.state().get("selection").first();
var selectedimg=up_imges.toJSON();
$("#thumbimg").attr("src",selectedimg.url);
$("#thumbimg").css({height:"100px",width:"100px"});
$("#thumberror").html("");
$("#thumburl").val(selectedimg.url);
console.log(selectedimg.url);
});
    });
});