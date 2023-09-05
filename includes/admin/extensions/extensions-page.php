<div class="wrap">
  <h1><?php esc_html_e('Extensions Page', 'ism-ideasci-modules'); ?></h1>

  <!-- Output tabs -->
  <?php
  $current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'publications';
  ?>
  <h2 class="nav-tab-wrapper">
    <a href="?page=ideasci-extensions&tab=publications" data-tab="publications" class="nav-tab <?php echo ($current_tab == 'publications' ? 'nav-tab-active' : ''); ?>"> <?php esc_html_e('Publications', 'ism-ideasci-modules'); ?></a>
    <a href="?page=ideasci-extensions&tab=events" data-tab="events" class="nav-tab <?php echo ($current_tab == 'events' ? 'nav-tab-active' : ''); ?>"> <?php esc_html_e('Events', 'ism-ideasci-modules'); ?></a>
  </h2>

  <!-- Output tab content -->
  <div class="tab-content">
    <div id="publications-tab-content" <?php echo ($current_tab == 'publications' ? 'style="display:block;"' : 'style="display:none;"'); ?>>
      <?php require_once 'publications-tab.php'; ?>
    </div>
    <div id="events-tab-content" <?php echo ($current_tab == 'events' ? 'style="display:block;"' : 'style="display:none;"'); ?>>
      <?php require_once 'events-tab.php'; ?>
    </div>
  </div>
</div>


<script>
  // Get all tab links
  var tabLinks = document.querySelectorAll('.nav-tab');

  // Add click event listeners to the tab links
  tabLinks.forEach(function(tabLink) {
    tabLink.addEventListener('click', function(event) {
      event.preventDefault(); // Prevent default link behavior
      var tab = this.getAttribute('data-tab');

      // Hide all tab content
      var tabContents = document.querySelectorAll('.tab-content > div');
      tabContents.forEach(function(content) {
        content.style.display = 'none';
      });

      // Show the selected tab content
      document.getElementById(tab + '-tab-content').style.display = 'block';

      // Remove the active class from all tabs and add it to the clicked tab
      tabLinks.forEach(function(link) {
        link.classList.remove('nav-tab-active');
      });
      this.classList.add('nav-tab-active');

      // Update the URL to reflect the selected tab (optional)
      history.pushState(null, null, "?page=ideasci-extensions&tab=" + tab);
    });
  });
</script>