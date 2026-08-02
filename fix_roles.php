<?php
App\Models\User::where('role', 'admin')->orWhere('role', 'owner')->get()->each(function($u) {
    $u->assignRole('owner');
    $u->role = 'owner';
    $u->save();
});
echo "Fixed";
