(function(){
    
    var modal = {
        
        action: null,
        postid: null,
        
        objects: function() {
            this.$modal  = $("#modal");
            this.$button = $("[data-modal]");
            this.$result = this.$modal.find("#result");
        },
        
        events: function(){
            var thiz = this;
            this.$button
                .on("click" , function(){
                    thiz.action = $(this).data("modal")
                    thiz.postid = $(this).data("ide")
                    thiz.$modal.modal('show', { backdrop: 'static' });
                    
                    $.post( thiz.action , { id: thiz.postid } , function(data){
                        thiz.$result.html(data)
                    });
                })
        },
        
        initialize: function() {
            this.objects();
            this.events();    
        }
        
    };
    
    modal.initialize();
//    
//    
//    if($("[data-modal-rmc]").length)
//    {
//        
//        $("[data-modal-rmc]").click(function(){
//            
//            $("#modal-rmc").modal('show', {backdrop: 'static'});
//            $.post( '/admin/request/rmc' , { id : $(this).data("modal-rmc") }, function(data){
//                
//                $('#modal-rmc #result').html(data);
//                
//            });
//            
//        });
//    }
//    
//    if($("[data-modal-edc]").length)
//    {
//        
//        $("[data-modal-edc]").click(function(){
//            
//            $("#modal-edc").modal('show', {backdrop: 'static'});
//            $.post( '/admin/request/edc' , { id : $(this).data("modal-edc") }, function(data){
//                
//                $('#modal-edc #result').html(data);
//                
//            });
//            
//        });
//        
//    }
    
    
})();