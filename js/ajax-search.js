jQuery(document).ready(function($) {

    $(document).on('keyup', '#search-input', function() {
        var searchQuery = $(this).val().trim();

        // Avoid querying empty input
        if (searchQuery.length === 0) {
            $('#search-results').fadeOut().html('');
            return;
        }

        // Perform AJAX instantly
        $.ajax({
            url: ajax_search_params.ajax_url,
            type: 'POST',
            data: {
                action: 'ajax_product_search',
                nonce: ajax_search_params.nonce,
                query: searchQuery
            },
            success: function(response) {
                $('#search-results').html(response).fadeIn();
            }
        });
    });

    // Click outside to close results
    $(document).click(function(e) {
        if (!$(e.target).closest('.header-search').length) {
            $('#search-results').fadeOut();
        }
    });
});
