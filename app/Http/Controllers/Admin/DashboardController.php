<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tenantCount = \App\Models\Tenant::count();
        return view('admin.dashboard', compact('tenantCount'));
    }
}
