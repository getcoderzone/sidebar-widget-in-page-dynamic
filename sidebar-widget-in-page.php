<?php

add_action( 'add_meta_boxes', 'sidebar_widget_meta' );
function sidebar_widget_meta()
{
    add_meta_box( 'quote-meta', __( 'Sidebar Widget' ), 'sidebar_widget_func', 'page', 'side', 'default' );
}


function sidebar_widget_func( $post )
{
    $sidebar_all = $GLOBALS['wp_registered_sidebars'];


    // Get values for filling in the inputs if we have them.
    $quote = get_post_meta( $post->ID, 'sidebar-widget', true );
       
    // Nonce to verify intention later
    wp_nonce_field( 'save_quote_meta', 'quote_nonce' );
    ?>
    <select style="width:100%" name="sidebar-widget">
        <?php foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { ?>
            <?php $sidebar_id =  $sidebar['id'];?>
             <option  <?php selected( $quote, $sidebar_id );?> value="<?php echo $sidebar_id; ?>">
                    <?php echo ucwords( $sidebar['name'] ); ?>
             </option>
        <?php } ?>
    </select>
    <?php
     
}
add_action( 'save_post', 'sidebar_widget_meta_save' );
function sidebar_widget_meta_save( $id )
{
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    if( !isset( $_POST['quote_nonce'] ) || !wp_verify_nonce( $_POST['quote_nonce'], 'save_quote_meta' ) ) return;
     
    if( !current_user_can( 'edit_post' ) ) return;
     
    $allowed = array(
        'p' => array()
    );
     
    if( isset( $_POST['sidebar-widget'] ) )
        update_post_meta( $id, 'sidebar-widget', wp_kses( $_POST['sidebar-widget'], $allowed ) );
     
}

/* Add a page template*/
<?php 
    $quote = get_post_meta( $post->ID, 'sidebar-widget', true );
    dynamic_sidebar($quote);
?>


