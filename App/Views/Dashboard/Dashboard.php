<?php
require_once __DIR__ . '/../Layout.php';
include __DIR__ . '/../Layout/Sidebar.php';
echo '<div class="py-3 px-2 d-flex flex-column w-100">';
if (!empty($page))
    include __DIR__ . '/../' . $page;
else
include __DIR__ .'/../Student/List.php';
echo '</div>';

include __DIR__ . '/../Layout/Footer.php';
