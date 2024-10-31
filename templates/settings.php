<?php
  if(! isset($reach_connector_plugin) ) {
    // instantiate the plugin class
    echo( "<!-- Instantiating REACH Connector Plugin Object... -->");
    $reach_connector_plugin = new Reach_Connector_Plugin();
  }
?>

<div class="wrap">
  <?php screen_icon(); ?>
  <h2>REACH&#174; Connector Settings</h2>
  <form action="options.php" method="POST" >

    <?php settings_fields('reach-connector'); ?>
    <?php do_settings_sections( 'reach-connector-options' ); ?>

    <?php submit_button(); ?>
  </form>
</div>
