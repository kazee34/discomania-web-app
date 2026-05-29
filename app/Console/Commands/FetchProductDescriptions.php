<?php

namespace App\Console\Commands;

use App\Models\ProductModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FetchProductDescriptions extends Command
{
    protected $signature = 'products:fetch-descriptions {--force : Overwrite existing descriptions}';
    protected $description = 'Fetch album descriptions from Wikipedia for all products';

    public function handle(): int
    {
        $query = ProductModel::query();

        if (! $this->option('force')) {
            $query->whereNull('description');
        }

        $products = $query->get();

        if ($products->isEmpty()) {
            $this->info('All products already have descriptions. Use --force to overwrite.');
            return self::SUCCESS;
        }

        $this->info("Fetching descriptions for {$products->count()} products...");
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        $found = 0;
        $notFound = 0;

        foreach ($products as $product) {
            $description = $this->fetchDescription($product->artist, $product->album_title);

            if ($description) {
                $product->update(['description' => $description]);
                $found++;
            } else {
                $notFound++;
            }

            $bar->advance();
            sleep(1);
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done. Found: {$found} | Not found: {$notFound}");

        return self::SUCCESS;
    }

    private function fetchDescription(string $artist, string $albumTitle): ?string
    {
        // Try Spanish Wikipedia first
        $candidates = [
            "{$albumTitle} ({$artist} álbum)",
            "{$albumTitle} (álbum)",
            "{$albumTitle}",
        ];

        foreach ($candidates as $title) {
            $description = $this->getWikipediaSummary($title, 'es');
            if ($description) {
                return $description;
            }
        }

        $description = $this->searchWikipedia($artist, $albumTitle, 'es');
        if ($description) {
            return $description;
        }

        // Fall back to English Wikipedia
        $candidates = [
            "{$albumTitle} ({$artist} album)",
            "{$albumTitle} (album)",
            "{$albumTitle}",
        ];

        foreach ($candidates as $title) {
            $description = $this->getWikipediaSummary($title, 'en');
            if ($description) {
                return $description;
            }
        }

        return $this->searchWikipedia($artist, $albumTitle, 'en');
    }

    private function getWikipediaSummary(string $title, string $lang = 'en'): ?string
    {
        $slug = Str::of($title)->replace(' ', '_')->toString();

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Discomania/1.0 (music store; contact@discomania.com)',
            ])->get("https://{$lang}.wikipedia.org/api/rest_v1/page/summary/{$slug}");

            if ($response->successful()) {
                $data = $response->json();
                $extract = $data['extract'] ?? null;

                if ($extract && strlen($extract) > 50) {
                    $firstParagraph = explode("\n", $extract)[0];
                    return strlen($firstParagraph) > 50 ? $firstParagraph : $extract;
                }
            }
        } catch (\Throwable) {
            // continue
        }

        return null;
    }

    private function searchWikipedia(string $artist, string $albumTitle, string $lang = 'en'): ?string
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Discomania/1.0 (music store; contact@discomania.com)',
            ])->get("https://{$lang}.wikipedia.org/w/api.php", [
                'action' => 'query',
                'list' => 'search',
                'srsearch' => "{$artist} {$albumTitle}",
                'format' => 'json',
                'srlimit' => 1,
            ]);

            if ($response->successful()) {
                $results = $response->json('query.search', []);
                if (! empty($results)) {
                    return $this->getWikipediaSummary($results[0]['title'], $lang);
                }
            }
        } catch (\Throwable) {
            // continue
        }

        return null;
    }
}
