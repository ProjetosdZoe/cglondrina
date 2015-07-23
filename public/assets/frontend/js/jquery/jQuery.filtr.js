(function ( $ ) {
 
    $.fn.filtr = function( options ) {
        
        var $this = this;
        
        var settings = $.extend({
            target: null,
            targetChild: null,
            caseSensitive : false,
            invert : false
        }, options );
        
        var obj = ( settings.targetChild == null ) ? $(settings.target) : $(settings.target).find(settings.targetChild);
        
        $this.keyup(function(){
            
            var data = this.value.split();

            if (this.value == ""){
                obj.show();
                return;
            }

            if( settings.invert === true ){
                obj.show();
                obj.filter(function (i, v) {
                    var $t = $(this);
                    for (var d = 0; d < data.length; ++d) {
                        if ($t.is(":contains('" + data[d] + "')")) {
                            return true;
                        }
                    }
                    return false;
                }).hide();
            }

            else{
                obj.hide();
                obj.filter(function (i, v) {
                    var $t = $(this);
                    for (var d = 0; d < data.length; ++d) {
                        if ($t.is(":contains('" + data[d] + "')")) {
                            return true;
                        }
                    }
                    return false;
                }).show();
            }
            
        });
        
        if( settings.caseSensitive === false ){
            jQuery.expr[':'].contains = function(a, i, m) {  return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0; };
        }
 
    };
 
}( jQuery ));
