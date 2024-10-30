// JavaScript Document
(function() {
	tinymce.create('tinymce.plugins.lineateButtons', {
		init : function(ed, url) {
			ed.addButton('lineate', {  
				title : 'Define a line of poetry',  
				image : url+'/line-break.png',  
				onclick : function() {  
					ed.selection.setContent('[lineate]' + ed.selection.getContent() + '[/lineate]');  
				}  
			});
		}
	});
	tinymce.PluginManager.add('lineate', tinymce.plugins.lineateButtons);
})();