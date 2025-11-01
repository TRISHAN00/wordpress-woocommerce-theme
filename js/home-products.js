jQuery(document).ready(function($){

    $(document).on('click', '.load-more-btn', function(e){
        e.preventDefault();

        var button = $(this);
        var page = parseInt(button.attr('data-page')) + 1;
        var max = parseInt(button.attr('data-max'));
        var category = button.attr('data-category');

        button.find('.btn-text').hide();
        button.find('.btn-loader').show();

        $.ajax({
            url: ajax_loadmore.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_more_products',
                category: category,
                page: page
            },
            success: function(response){
                button.find('.btn-text').show();
                button.find('.btn-loader').hide();

                if(response){
                    button.closest('.load-more-container').find('#post-container').append(response);
                    button.attr('data-page', page);

                    if(page >= max){
                        button.remove(); // hide button if last page
                    }
                }
            }
        });
    });

});
