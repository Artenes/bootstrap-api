<?php

namespace App\Console\Commands;

use App\Models\Merchant;
use Illuminate\Console\Command;

class MerchantDelete extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merchant:delete {key : the key to be deleted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes a merchant';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $key = $this->argument('key');

        $merchant = Merchant::find($key);

        if (!$merchant) {

            $this->error("No merchant found with the key {$key}.");
            return 0;

        }

        $merchant->delete();

        $this->info('Merchant deleted.');

        return 0;

    }

}