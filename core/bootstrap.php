<?php

add_action( 'wp_head', 'olint_initialize_links_in_new_tab' );
add_action( 'admin_menu', 'olint_admin_menu' );

/**
 * Initialize Links In New Tab
 *
 * @since 1.0.0
 *
 * @return void
 */
function olint_initialize_links_in_new_tab() {

    //get current website domain
    $current_domain = parse_url( get_option( 'home' ) );
    ?>
    <script type="text/javascript">
        //<![CDATA[
        function olint_prepare_all_external_links() {

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

        function olint_load_external_links_in_new_tab( function_name ){
            var olint_on_load = window.onload;

            if (typeof window.onload != 'function'){
                window.onload = function_name;
            } else {
                window.onload = function(){
                    olint_on_load();
                    function_name();
                }
            }
        }

        olint_load_external_links_in_new_tab( olint_prepare_all_external_links );

    //]]>
    </script>
    <?php
}

function olint_admin_menu() {
    add_options_page( esc_html__( 'Open links in new tab', "open-links-in-new-tab" ),
        esc_html__( 'Open Links', "open-links-in-new-tab" ),
        'manage_options',
        'open_links_in_new_tab',
        'olint_options_page' );
}

function olint_options_page() {
    ?>
    <div class="olint-wrap">
        <h2><?php echo esc_html__( "Open Links in New Tab", "open-links-in-new-tab" );?></h2>
        <p>
            <form method="post" action="options.php">
                <?php wp_nonce_field( 'update-options' );?>
                <?php echo esc_html__( "By default, all external links (i.e. links that point outside the current host name) will open in a new tab.", "open-links-in-new-tab" );?><br />
                <?php echo esc_html__( "You can change this feature by below options. You can also open internal links in new tab.", "open-links-in-new-tab" );?><br /><br />
                
                <input class="olint-input input-text" name="olint_open_external_link_in_new_tab" type="checkbox" id="olint_open_external_link_in_new_tab" value="yes" <?php echo ('yes' === get_option( 'olint_open_external_link_in_new_tab', '' )) ? 'checked' : ''; ?> /> 
                <label for="olint_open_external_link_in_new_tab"><?php echo esc_html__('Open external links in new tab','open-links-in-new-tab');?></label><br>
                <input class="olint-input input-text" name="olint_open_internal_link_in_new_tab" type="checkbox" id="olint_open_internal_link_in_new_tab" value="yes" <?php echo ('yes' === get_option( 'olint_open_internal_link_in_new_tab', '' )) ? 'checked' : ''; ?> />
                <label for="olint_open_internal_link_in_new_tab"><?php echo esc_html__('Open internal links in new tab','open-links-in-new-tab');?></label><br>

                <input type="hidden" name="action" value="update" />
                <input type="hidden" name="page_options" value="olint_open_external_link_in_new_tab,olint_open_internal_link_in_new_tab" />
                <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
            </form>
        </p>
    </div>
    <?php
}
