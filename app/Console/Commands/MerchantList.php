<?php

namespace App\Console\Commands;

use App\Models\Merchant;
use Illuminate\Console\Command;

class MerchantList extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merchant:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all registered merchants';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $headers = ['Description', 'Created at', 'Key'];

        $merchants = Merchant::all(['description', 'created_at', 'key'])->toArray();

        if (empty($merchants)) {

            $this->info('There are no merchants registered. Create a new one with php artisan merchant:new MyMerchantName');
            return 0;

        }

        $this->table($headers, $merchants);

        return 0;

    }

}