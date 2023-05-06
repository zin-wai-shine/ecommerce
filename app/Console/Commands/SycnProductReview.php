<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class SycnProductReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'product reviews';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Syncing Products Reviews...");

        $products = Product::with('reviews')->get();

        foreach($products as $product)
        {
            $avgRating = $product->reviews()->avg('rate');
            $avg = $product->oneRound($avgRating);
            if($avgRating == null)
            {
                $avg = 0;
            }
            // return $avg;
            $product->avg_rating = $avg;

            $product->save();
        }

        $this->info('Synced  successfully!');

        return Command::SUCCESS;
    }
}
