<?php

namespace App\Console\Commands;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class deleteSaleAfterTwoMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $twoMonthsAgo = Carbon::now()->subMonths(2);
        $oldSales = Sale::where('created_at', '<', $twoMonthsAgo)->get();

        foreach ($oldSales as $sale) {
            $sale->saleItems()->forceDelete();
            $sale->forceDelete();
        }

        Log::info("Old Sales data deleted was runned with count :" . $oldSales->count);

        $this->info('Old sales and sale items deleted successfully.');
    }
}
