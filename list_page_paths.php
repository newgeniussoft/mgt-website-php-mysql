<?php
require_once __DIR__ . '/app/models/Page.php';
$p = new Page();
$paths = array_map(function($row){ return $row['path']; }, $p->all());
echo json_encode($paths);
