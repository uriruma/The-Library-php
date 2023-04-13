<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();

        return view('books-index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'isbn' => 'required|unique:books',
            'title' => 'required',
            'author' => 'required',
            'published_date' => 'required|date',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
        ]);

        $book = Book::create([
            'isbn' => $request->input('isbn'),
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'published_date' => $request->input('published_date'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
        ]);

        $categories = $request->input('categories', []);

        $book->categories()->sync($categories);

        // return redirect()->route("books");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::findOrFail($id);

        return view('books-show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Book::findOrFail($id);

        return view('books-update', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'isbn' => 'required|unique:books,isbn,' . $id,
            'title' => 'required',
            'author' => 'required',
            'published_date' => 'required|date',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
        ]);

        $book = Book::findOrFail($id);

        $book->update([
            'isbn' => $request->input('isbn'),
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'published_date' => $request->input('published_date'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
        ]);

        $categories = $request->input('categories', []);

        $book->categories()->sync($categories);

        // return redirect()->route('books.show', $book->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);

        $book->delete();

        // return redirect()->route('books');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        // dd($search);
        $books = Book::where('title', 'like', '%'.$search.'%')
                    ->orWhere('author', 'like', '%'.$search.'%')
                    ->orWhere('isbn', 'like', '%'.$search.'%')
                    ->get();

        return view('books-index', ['books' => $books]);
    }

}
