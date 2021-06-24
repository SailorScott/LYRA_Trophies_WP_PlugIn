<?php



function lyraAdmin_Landing(){
    $page_title = 'Mange Trophy Data';
    $menu_title = 'Trophy Data';
    $capability = 'edit_posts';
    $menu_slug  = 'lyra-admin';
    $function   = 'lyra_admin_landing_page';
    $icon_url   = 'dashicons-awards';
    $position   = 54;
                
    add_menu_page( $page_title,$menu_title,$capability,$menu_slug,$function,$icon_url,$position );

    // add_submenu_page(
    //     'lyra-admin',
    //     'Trophy Data',
    //     'Trophy Data',
    //     'edit_posts',
    //     'lyra-admin'
    // );

    add_submenu_page(
        'lyra-admin',
        'Manage Boats',
        'Manage Boats',
        'edit_posts',
        'lyra_admin_boats',
        'lyra_admin_boats'
    );

    // add_submenu_page(
    //     'lyra-admin',
    //     'Manage Regattas',
    //     'Manage Regattas',
    //     'edit_posts',
    //     'lyra_admin_regattas',
    //     'lyra_admin_manage_placeholder'
    // );

    add_submenu_page(
        'lyra-admin',
        'Manage Trophy Winners',
        'Manage Winners',
        'edit_posts',
        'lyra_admin_winners',
        'lyra_admin_winners'
    );


    // add_submenu_page(
    //     'lyra-admin',
    //     'Manage Non-Boat Trophy Winners',
    //     'Manage Non-Boat',
    //     'edit_posts',
    //     'lyra_admin_non-boat_winners',
    //     'lyra_admin_manage_placeholder'
    // );

    add_submenu_page(
        'lyra-admin',
        'Manage Trophies',
        'Manage Trophies',
        'edit_posts',
        'lyra_admin_trophies',
        'lyra_admin_trophies'
    );
}

  

function lyra_admin_landing_page(){

     $output = "<h1>Mange Trophy Data</h1>
     <p>Second release (6/23/2021) of LYRA Trophy management administration site. Any problems please let Scott Nichols (scott.nic@icloud.com) know.
      You are editing real data. Please be careful. We do have daily backups.</p>
    <p>This is an open source project and the source code is here <a href='https://github.com/SailorScott/LYRA_Trophies_WP_PlugIn'>https://github.com/SailorScott/LYRA_Trophies_WP_PlugIn</a> The project is still early in development.</p>";
     echo $output;
  } 



  function lyra_admin_manage_placeholder() {
    // check user capabilities
    if ( ! current_user_can( 'edit_posts' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            echo "<h2>Still working on it!</h2>";
            ?>
        </form>
    </div>
    <?php
}
    