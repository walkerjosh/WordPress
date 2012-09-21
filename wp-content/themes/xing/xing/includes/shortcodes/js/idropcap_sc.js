// Dropcap Short Code

(function() {
     tinymce.create('tinymce.plugins.idropcap', {
        init : function(ed, url) {
             ed.addButton('idropcap', {
                title : 'Add an inverted DropCap (Select first letter and click this button)',
                image : url+'/images/idropcap.png',
                onclick : function() {
                      ed.selection.setContent('[dropcap style="inverted"]' + ed.selection.getContent() + '[/dropcap]');
                 }
             });
         },
         createControl : function(n, cm) {
             return null;
         },
     });
     tinymce.PluginManager.add('idropcap', tinymce.plugins.idropcap);
 })();