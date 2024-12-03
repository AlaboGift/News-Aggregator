<?php

namespace App\Services;

use App\Enums\Categories;
use App\Enums\Sources;
use App\Models\Article;
use App\Models\Category;
use App\Models\Source;
use App\Utils\Utils;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NewsService
{
    public function fetchNews(): bool
    {
        $sources = Source::all();

        foreach($sources as $source){
            defer(function() use($source){
                $this->fetchNewsViaAPI($source->name);
            });
        }

        return true;
    }

    public function fetchNewsViaAPI($source): bool
    {
        return match($source){
            Sources::NewsAPI => $this->newsAPI(),
            Sources::NewYorkTimes => $this->newYorkTimes(),
            default => $this->theGuardian()
        };
    }

    public function newsAPI(): bool
    {
        $articles = [];
        $apiKey = env('NEWSAPI_KEY');
        $response = Http::get("https://newsapi.org/v2/everything?sources=espn&sortBy=publishedAt&apiKey={$apiKey}");
        
        if ($response->ok()) {
            foreach ($response->json('articles') as $article) {
                
                if($article['author']){
                    $articles[] = [ 
                        'title' => $article['title'],
                        'content' => $article['description'],
                        'source' => Sources::NewsAPI,
                        'author' => $article['author'],
                        'category' => Categories::Sports,
                        'url' => $article['url'],
                        'image_url' => $article['urlToImage'],
                        'published_at' => Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
    
            Article::upsert(
                $articles,
                ['url'],
                ['title', 'content', 'author', 'category', 'image_url', 'published_at', 'updated_at']
            );
    
            return true;
        }
    
        return false;
    }
    
    public function newYorkTimes(): bool
    {
        $articles = [];
        $apiKey = env('NYT_API_KEY');
        $response = Http::get("https://api.nytimes.com/svc/news/v3/content/all/all.json?api-key={$apiKey}");
    
        if ($response->ok()) {
            foreach ($response->json('results') as $article) {

                $author = str_replace("By", "", $article['byline']);

                if($author){
                    $articles[] = [ 
                        'title' => $article['title'],
                        'content' => $article['abstract'],
                        'source' => Sources::NewYorkTimes,
                        'author' => $author,
                        'category' => $article['section'] ?? Categories::General,
                        'url' => $article['url'],
                        'image_url' => $article['multimedia']['url'],
                        'published_at' => Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            Article::upsert(
                $articles,
                ['url'],
                ['title', 'content', 'author', 'category', 'image_url', 'published_at', 'updated_at']
            );

            return true;
        }

        return false;
    }

    public function theGuardian(): bool
    {
        $articles = [];
        $apiKey = env('GUARDIAN_API_KEY');
        $response = Http::get("https://content.guardianapis.com/search?api-key={$apiKey}");

    
        if ($response->ok()) {
            foreach ($response->json('response.results') as $article) {

                $articles[] = [ 
                    'title' => $article['webTitle'],
                    'content' => $article['webTitle'],
                    'source' => Sources::Guardian,
                    'author' => $article['author'] ?? 'Unknown',
                    'category' => $article['pillarName'] ?? Categories::General,
                    'url' => $article['webUrl'],
                    'image_url' => 'https://ui-avatars.com/api/?name='.urlencode((string) $article['webTitle']).'&color=7F9CF5&background=EBF4FF',
                    'published_at' => Carbon::parse($article['webPublicationDate'])->format('Y-m-d H:i:s'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Article::upsert(
                $articles,
                ['url'],
                ['title', 'content', 'author', 'category', 'image_url', 'published_at', 'updated_at']
            );

            return true;
        }

        return false;
    }

    public function articles($request)
    {
        $query = Article::query();

        //Use Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            Utils::updateUserPreference($search);
            $query->whereRaw("LOWER(CONCAT_WS(' ', title,content)) LIKE ?", "%".strtolower($search)."%");
        }else{
            //Use User Preference
            $keywords = Utils::userPreferences();
            $query->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->whereRaw("LOWER(CONCAT_WS(' ', title,content)) LIKE ?", "%".strtolower($keyword)."%");
                }
            });
        }
    
        //Use Category Filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }
    
        //Use Source Filter
        if ($request->filled('source')) {
            $query->where('source', $request->input('source'));
        }
    
        //Use Date Filter
        if ($request->filled('date')) {
            $query->whereDate('published_at', $request->input('date'));
        }
    
        return $query->latest();
    }

    public function sources()
    {
        return Source::all();
    }

    public function categories()
    {
        return Category::all();
    }
}