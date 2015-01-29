/*add class last div block in row*/
$(function() {
    var modules_items='.modules_content.page_modules>div.items',
        module_item='div.item',
        count_items = $(modules_items).children($(module_item)).length,
        start_element = 2,
        count_plus = 3;
    for (var i = start_element; i<=count_items; i+=count_plus){
        $(modules_items).children($(module_item)).eq(i).addClass('last');
    }
});

