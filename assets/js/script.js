$(document).ready(function () {
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
    $("#addeventform").validate({
    submitHandler:function(){
    if ($("#thumburl").val()!=""){
        var post_data=$("#addeventform").serialize()+"&action=event_plugin_library&param=add_event_data";
        $.post(ajaxurl,post_data,function(response){
            var data=$.parseJSON(response);
            if(data.status==1){
                $("#addeventbtn").notify(data.message,{position:"right",className:"success"});
            }
        });
    
    }
}
    })
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
})    