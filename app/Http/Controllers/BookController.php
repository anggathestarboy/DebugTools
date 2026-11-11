<?php

namespace App\Http\Controllers;

use App\Jobs\FlushCacheJob;
use App\Models\Book;
use App\Models\User;
use App\Notifications\BookCreated;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $page = request('page', 1);
    $key = 'books_page_' . $page;

    if (Cache::has($key)) {
        Log::info('Cache hit untuk daftar buku', ['key' => $key]);
    }

    $books = Cache::remember($key, 3600, function () use ($key, $page) {
        Log::warning('Cache miss, mengambil buku dari database', [
            'key' => $key,
            'page' => $page,
        ]);

        return Book::latest()->paginate(10)->toArray();
    });

    Log::debug('Daftar buku ditampilkan', [
        'page' => $page,
        'total_item' => count($books['data']),
    ]);

    return view("book", compact("books"));
}

    public function percobaan() {
        $posts = [];
        $error = null;

        try {
            $response = Http::timeout(10)
                ->retry(2, 200)
                ->get('https://jsonplaceholder.typicode.com/posts', [
                    '_limit' => 10,
                ]);

            if ($response->successful()) {
                $posts = $response->json();
            } else {
                $error = 'API membalas dengan status ' . $response->status();
            }
        } catch (ConnectionException $e) {
            $error = 'Tidak bisa terhubung ke API';
        }

        return view('eksternal', compact('posts', 'error'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $data = $request->validate([
            "name" => "required",
            "desc" => "required",
            "publised_at" => "required"
        ]);

        $book = Book::create($data);

        FlushCacheJob::dispatch();

        User::first()?->notify(new BookCreated($book));

        return redirect("/")->with('success', "Book succsesfully created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }
}
