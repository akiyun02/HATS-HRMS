<?php

use App\Models\EmployeeProfile;
use App\Models\LeaveLedgerEntry;
use App\Models\LeavePolicy;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$output = [];
$output['policies'] = LeavePolicy::with('leaveTypes')->get()->toArray();
$output['latest_user'] = User::latest()->first()?->toArray();
$output['latest_profile'] = EmployeeProfile::latest()->first()?->toArray();
$output['latest_ledgers'] = LeaveLedgerEntry::latest()->take(5)->get()->toArray();

file_put_contents(__DIR__.'/debug.json', json_encode($output, JSON_PRETTY_PRINT));
echo "Done\n";
