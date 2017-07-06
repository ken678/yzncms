//新的inline_edit调用方法
//javacript
//$('span[nc_type="class_sort"]').inline_edit({act: 'microshop',op: 'update_class_sort'});
//html
//<span nc_type="class_sort" column_id="<?php echo $val['class_id'];?>" title="<?php echo $lang['nc_editable'];?>" class="editable tooltip"><?php echo $val['class_sort'];?></span>
//php
//$result = array();
//$result['result'] = FALSE;/TURE
//$result['message'] = '错误';
//echo json_encode($result);

(function($) {
 $.fn.inline_edit= function(options) {
     var settings = $.extend({}, {open: false}, options);
     return this.each(function() {
         $(this).click(onClick);
     });

     function onClick() {
         var span = $(this);
         var old_value = $(this).html();
         var column_id = $(this).attr("column_id");
         var s_name   = $(this).attr('fieldname');
         $('<input type="text">')
         .insertAfter($(this))
         .focus()
         .select()
         .val(old_value)
         .blur(function(){
             var new_value = $(this).attr("value");
             if(new_value != '') {
                 $.post(settings.act,{branch:s_name,id:column_id,value:new_value},function(data){
                     data = $.parseJSON(data);
                     if(data.result) {
                         span.show().text(new_value);
                     } else {
                         span.show().text(old_value);
                         if (typeof(data.message) != 'undefined') alert(data.message);
                     }
                 });
             } else {
                 span.show().text(old_value);
             }
             $(this).remove();
         })
         $(this).hide();
     }
}
})(jQuery);