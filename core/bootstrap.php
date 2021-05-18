<?php

add_action( 'wp_head', 'elint_initialize_external_links_in_new_tab' );

/**
 * Initialize External Links In New Tab
 *
 * @since 1.0.0
 *
 * @return void
 */
function elint_initialize_external_links_in_new_tab() {

    //get current website domain
    $current_domain = parse_url( get_option( 'home' ) );
    ?>
    <script type="text/javascript">
        //<![CDATA[
        function elint_prepare_all_external_links() {

            if( !document.links ) {
                document.links = document.getElementsByTagName('a');
            }
            var all_links       = document.links;
            var open_in_new_tab = false;

            // loop through all the links of current page
            for( var current = 0; current < all_links.length; current++ ) {
                var current_link = all_links[current];
                open_in_new_tab  = false;

                //only work if current link does not have any onClick attribute
                if( all_links[current].hasAttribute('onClick') == false ) {
                    // open link in new tab if the web address starts with http or https, but does not refer to current domain
                    if( (current_link.href.search(/^http/) != -1) && (current_link.href.search('<?php echo esc_html( $current_domain['host'] ); ?>') == -1)  && (current_link.href.search(/^#/) == -1) ){
                        open_in_new_tab = true;
                    }

                    //if open_in_new_tab is true, update onClick attribute of current link
                    if( open_in_new_tab == true ){
                        all_links[current].setAttribute( 'onClick', 'javascript:window.open(\''+current_link.href+'\'); return false;' );
                        all_links[current].removeAttribute('target');
                    }
                }
            }
        }

        function elint_load_external_links_in_new_tab( function_name ){
            var elint_on_load = window.onload;

            if (typeof window.onload != 'function'){
                window.onload = function_name;
            } else {
                window.onload = function(){
                    elint_on_load();
                    function_name();
                }
            }
        }

        elint_load_external_links_in_new_tab( elint_prepare_all_external_links );

    //]]>
    </script>
    <?php
}