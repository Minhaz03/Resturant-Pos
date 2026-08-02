<?php
$users = App\Models\User::all();
foreach ($users as $u) {
    echo "ID: {$u->id}, Email: {$u->email}, Role col: {$u->role}\n";
    echo "Roles via Spatie: " . json_encode($u->getRoleNames()) . "\n";
}
