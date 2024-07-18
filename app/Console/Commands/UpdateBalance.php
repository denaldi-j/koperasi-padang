<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-balance {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $id = $this->argument('id');

        $updateBalance = new \App\Actions\Balance\UpdateBalance();
        $updateBalance->handle($id);
    }
}
