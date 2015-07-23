$("#nestable-list-1").on('nestable-stop', function(ev){

console.log( $(this).data('nestable').list() );

});

$("button[data-add]").click(function(){
    
    $('#nestable-list-1').find("li:first").clone().appendTo('#nestable-list-1').find("input[type='text']").val("");
    
});

$("#nestable-list-1").delegate( "[data-remove]", "click" , function(){
     if( $("[data-remove]").length > 1 ){
         this.closest("li").remove();
     }
});