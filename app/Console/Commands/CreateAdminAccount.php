<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateAdminAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Super Admin account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? $this->ask('Admin Email');
        $password = $this->argument('password') ?? $this->secret('Admin Password');
        
        \App\Models\Admin::create([
            'name' => 'Super Admin',
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($password)
        ]);
        
        $this->info("Admin account ($email) created successfully!");
    }
}
