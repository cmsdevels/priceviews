(function () {

    $(document).on('click', 'a.toggle-settings', function (e) {
        e.preventDefault();
        $(this).closest('li').toggleClass('expanded');
    });

    $(document).click('click', function (e) {
        var targetElement = $(e.target),
            expandedItem = targetElement.closest('.expanded');
        if (!expandedItem.length)
            $('a.toggle-settings').closest('li').removeClass('expanded');

    });

})();