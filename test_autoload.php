<?php
require 'vendor/autoload.php';
echo "Checking class existence...\n";
if (class_exists('App\Http\Controllers\AdminController')) {
    echo "SUCCESS: AdminController exists.\n";
} else {
    echo "FAILURE: AdminController NOT found.\n";
}
