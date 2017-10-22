jQuery(document).ready(function($) {
    $('#wp-content-editor-container').droppable({
        over: function(event, ui) {
            $(this).addClass('overed');
        },
        out: function(event, ui) {
            $(this).removeClass('overed');
        },
        drop: function(event, ui) {
            var $drag_element = $( ui.draggable[0] );
            var field = '<input type="text">';

            var type = $drag_element.attr('id');
            switch ( type ) {
                case "text":
                case "email":
                case "number":
                case "checkbox":
                    field = '<input type="'+ type +'" value="" />';
                break;

                case "textarea":
                    field = '<textarea></textarea>';
                break;

                case "submit":
                    field = '<input type="submit" value="submit" />';
                break;
          }
          tinyMCE.activeEditor.execCommand('mceInsertContent', false, field);
          $(this).removeClass('overed');
          return false;
        }
    });
});