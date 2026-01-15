<?php
if (!defined('APP_BOOTSTRAPPED')) { http_response_code(404); exit; }

// Simple site footer
$year = date('Y');
?>
<footer class="py-4 border-top mt-5">
  <div class="container text-center text-muted small">
    © <?= $year ?> Book Library • All rights reserved
  </div>
</footer>