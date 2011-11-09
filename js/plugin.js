jQuery(document).ready(function($)
{
    $.fn.orderlyRefreshPositions = function(){
        var $sortable = $(this);
        $(this).find('li span.orderly-index').each(function(index){
            $(this).text((index + 1) + ". ");
        });
    }
    var $sortables = $('.orderly-sortable');
    $sortables.sortable({
        axis: 'y',
        containment: 'parent',
        cursor: 'move',
        opacity: 0.8,
        placeholder: 'ui-state-highlight',
        revert: false,
        zIndex: 9999
    })
    $sortables.bind('sortupdate', function(event, ui){
        $(this).orderlyRefreshPositions();
    });
});
