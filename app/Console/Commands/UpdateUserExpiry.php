<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class UpdateUserExpiry extends Command
{
    protected $signature = 'users:update-expiry';
    protected $description = 'Update user expiry dates';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();

        User::where('expiry_date', '<', $now)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $this->info('User expiry dates updated successfully.');
    }
}
