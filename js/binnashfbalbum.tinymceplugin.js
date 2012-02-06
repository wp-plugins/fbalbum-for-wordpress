(function() {
    tinymce.create('tinymce.plugins.binnashfbalbum', {
         init : function(ed, url) {
            ed.addButton('binnashfbalbum', {
                title : 'FBAlbum for Wordpress',
                image : url+'/../images/binnashfbalbum.png',
                onclick : function() {
                    tb_show("FBAlbum for Wordpress",
                    "#TB_inline?height=150&amp;width=300&amp;inlineId=binnash-fbalbum-form");
                    $j= jQuery.noConflict();
                    $j("#TB_window").css({
                        height: 150,
                        width: 295
                    });                    
                }
            });
            
            $j= jQuery.noConflict();
            $j.post(ajaxurl,{'action':'fetch_fb_album_list'},function(data){
               $j(data).appendTo('body').hide();               
               $j('#wp-link-submit').live('click',function(){
                   var c = ed.selection.getContent({format:'html'});
                   var selected = $j('#binnash-fbalbum-shortcode-selector').val();
                   var link = '[binnashfbalbum4wp]';
//                   if(selected !=0){
                   if(selected != 0)
                       link = '[binnashfbalbum4wp album_id='+selected+']';                    
                   ed.selection.setContent(link);
                   tb_remove();
//                   }
//                   else{
//                       alert("Please Select An Album.");
//                   }
               });
               $j('#wp-link-cancel').live('click',function(){
                   tb_remove();
               });
           });             
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname : "FBAlbum for Wordpress",
                author : 'Nur Hasan',
                authorurl : 'http:binnash.blogspot.com/',
                infourl : 'http:binnash.blogspot.com/',
                version : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('binnashfbalbum', tinymce.plugins.binnashfbalbum);
})();

