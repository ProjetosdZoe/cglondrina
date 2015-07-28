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
    
    var fileAdd = {
        
        objects: function() {
            this.$parent = $("[data-inputParent]");
            this.$result = $("[data-add-group]");
            this.$button = $("[data-add]");
        },
        
        events: function(){
            var thiz = this;
            this.$button
                .on("click" , function(){
                
                    var item = thiz.$parent.clone().appendTo( thiz.$result );
                    item.find("input").removeAttr("id").attr("name", Math.floor(Math.random() * 3370146 ) );
                    item.find("label").remove();
                    return false;
                
                });
        },
        
        initialize: function() {
            this.objects();
            this.events();    
        }
        
    };
    
    modal.initialize();
    fileAdd.initialize();
    
    
})();