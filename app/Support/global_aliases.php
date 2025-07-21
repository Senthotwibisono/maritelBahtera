<?php

// Alias Carbon
if (!class_exists('Carbon')) {
    class_alias(Carbon\Carbon::class, 'Carbon');
}

// Alias DataTables
if (!class_exists('DataTables')) {
    class_alias(Yajra\DataTables\Facades\DataTables::class, 'DataTables');
}
