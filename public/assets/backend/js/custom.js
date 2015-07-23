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
                });
        },
        
        initialize: function() {
            this.objects();
            this.events();    
        }
        
    };
    
    modal.initialize();
    
    
})();