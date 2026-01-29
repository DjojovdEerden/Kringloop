<?php
$type = $_GET['type'] ?? 'magazijn';
$voorraadType = $type === 'winkel' ? 'winkel' : 'magazijn';

require __DIR__ . '/includes/magazijn.php';
return;
