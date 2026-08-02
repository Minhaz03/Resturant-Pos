<?php
$users = App\Models\User::whereNotNull('tenant_id')->get();
foreach ($users as $u) {
    if ($u->roles->count() === 0) {
        $u->assignRole('owner');
        echo "Assigned owner to {$u->email}\n";
    }
}
