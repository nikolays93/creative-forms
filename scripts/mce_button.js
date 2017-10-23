/* global tinymce */
// ( function() {
//     tinymce.PluginManager.add( 'query_shortcode', function( editor ) {
//         editor.addButton( 'query_shortcode', {
//             // text: '{Query}',
//             // type: 'menubutton',
//             // icon: false,
//             // menu: [{
//             //  text: 'Вставить шорткод [query]',
//             //  onclick: function() {
//             //      wp.mce.query_shortcode.popupwindow(editor);
//             //  }
//             // }]
//             text: '{Query}',
//             onclick: function() {
//                 wp.mce.query_shortcode.popupwindow(editor);
//             }
//         });
//     });
// })();

(function() {
    tinymce.PluginManager.add( 'cforms_mce', function( editor, url ) {

        function updateEventListeners() {
            var inputs = editor.getDoc().getElementsByTagName( 'input' );
            var textareas = editor.getDoc().getElementsByTagName( 'textarea' );
            var selects = editor.getDoc().getElementsByTagName( 'select' );

            var forms = [inputs, textareas, selects];
            forms.forEach(function(val) {
                if( val.length ) {
                    for (var i = val.length - 1; i >= 0; i--) {
                        val[i].addEventListener("dblclick", inputDBLClick);
                    }
                }
            });
        }

        function inputDBLClick() {
            var input = this;
            var fields = [
                {
                    type   : 'textbox',
                    name   : 'name',
                    label  : 'Name',
                    value  : input.name
                },
                {
                    type   : 'textbox',
                    name   : 'value',
                    label  : 'Value',
                    value  : input.value
                },
                {
                    type   : 'textbox',
                    name   : 'class',
                    label  : 'Class',
                    value  : input.className
                },
                {
                    type   : 'textbox',
                    name   : 'placeholder',
                    label  : 'Placeholder',
                    value  : input.placeholder
                },
            ];

            switch ( input.type ) {
                case "text":
                case "email":
                case "number":
                    editor.windowManager.open( {
                        title: 'Edit text field',
                        body: fields,
                        onsubmit: function( values ) {
                            input.name = values.data.name;
                            input.value = values.data.value;
                            input.className = values.data.class;
                            input.placeholder = values.data.placeholder;
                        }
                    } );
                    break;

                case "checkbox":
                case "radio":
                    break;

                default:
                    break;
            }
        }

        editor.on('init', function(args){
            updateEventListeners();
        });

        editor.on('change', function(e) {
            updateEventListeners();
        });
        // Add Button to Visual Editor Toolbar
        // editor.addButton('cforms_mce', {
        //     text: 'BUTTON',
        //     title: 'Insert CSS Class',
        //     onclick: function() {
        //         console.log(editor.getDoc().getElementsByTagName( 'input' ));
        //     }
        // });

        // Add Command when Button Clicked
        // editor.addCommand('custom_class', function() {
        //     .forEach(function(a,b,c){
        //         console.log(a,b,c);
        //     });
        // });
    });
})();