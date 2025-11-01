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
                    
                    // ✅ FIX 1: Re-initialize WooCommerce variation forms for newly loaded products
                    if(typeof wc_add_to_cart_variation_params !== 'undefined'){
                        button.closest('.load-more-container').find('.variations_form').each(function(){
                            $(this).wc_variation_form();
                        });
                    }
                    
                    if(page >= max){
                        button.remove();
                    }
                }
            },
            error: function(xhr, status, error){
                console.error('Load More Error:', error);
                button.find('.btn-text').show();
                button.find('.btn-loader').hide();
            }
        });
    });
});