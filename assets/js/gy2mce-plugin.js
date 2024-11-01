/**
 * Created by admin on 2022-04-28.
 */
/* Giphy plugin for tinymce */

( function () {

    // skip if sliders list is not available
    //if( ! __MS_EDITOR || ! __MS_EDITOR.sliders )
      //  return;


    tinymce.PluginManager.add( 'gy2mce_shortcodes_button', function( editor, url ) {



        /*for ( slider_alias in __MS_EDITOR.sliders ) {
            item_label = __MS_EDITOR.sliders[ slider_alias ] + " [#" + slider_alias + "]";
            menu_items.push( { text: item_label, value: slider_alias } );
        };*/


        var ed = tinymce.activeEditor;
        editor.addButton( 'gy2mce_shortcodes_button', {
            text: 'GIPHY',
            icon: false,
            title:"GIPHY",
            onclick: function(e) {
                tb_show("", "#TB_inline?height=750&width=750&inlineId=my-content-id");
            }
        });
    });

})();