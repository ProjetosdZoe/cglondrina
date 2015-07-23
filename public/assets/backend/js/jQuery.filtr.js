(function ( $ ) {
 
    $.fn.filtr = function( options ) {
        
        var $this = this;
        
        var settings = $.extend({
            target: null,
            targetChild: null,
            caseSensitive : false,
            invert : false
        }, options );
        
        if( settings.targetChild == null ) { 
            var jo = $(settings.target) }
        else {
            var jo = $(settings.target).find(settings.targetChild);
        }
        
        $this.keyup(function () {
            
                var data = this.value.split();
            
                if (this.value == "") {
                    jo.show();
                    return;
                }
            
                if( settings.invert === true ){
                    jo.show();
                    jo.filter(function (i, v) {
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
                    jo.hide();
                    jo.filter(function (i, v) {
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
