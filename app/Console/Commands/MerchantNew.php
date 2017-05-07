<?php

namespace App\Console\Commands;

use App\Models\Merchant;
use Illuminate\Console\Command;

class MerchantNew extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merchant:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new merchant key and secret';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $description = $this->ask('Provide a description for the merchant');

        $data = Merchant::createNew($description);

        $this->info("Merchant created.");
        $this->info("merchant-key: {$data['key']}");
        $this->info("merchant-secret: {$data['secret']}");

        return 0;

    }

}