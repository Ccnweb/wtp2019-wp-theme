<nav id="menu_content">
    <header>
        <img src="<?php echo get_template_directory_uri() ?>/img/logo_wtp_gold.png"/>
        <a href="<?php echo get_home_url() ?>"><img src="<?php echo get_template_directory_uri() ?>/img/logo_next_step.png" style="margin-right: calc(50vw - 31px);"/></a>
    </header>

    <div class="menu_inside">
        <button class="black open_choix">JE M'INSCRIS</button>

        <!-- THE MAIN MENU IS LOADED HERE -->
        <?php wp_nav_menu( array( 'theme_location' => 'menu-principal', 'container_class' => 'menu_entries' ) ); ?>

        <img class="menu_logo_ccn" src="<?php echo get_template_directory_uri() ?>/img/logo_ccn_white.png" alt="">
    </div>

    <footer>
        <!-- THE LANGUAGES MENU IS LOADED HERE -->
        <div class="choose_lang">Choose your language</div>
        <?php wp_nav_menu( array( 'theme_location' => 'menu-langues', 'container_class' => 'menu_langues' ) ); ?>
    </footer>
</nav>