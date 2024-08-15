<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isbn' => 'required|string',
            'title' => 'required|string',
            'subtitle' => 'nullable|string',
            'author' => 'nullable|string',
            'published' => 'nullable|string',
            'publisher' => 'nullable|string',
            'pages' => 'nullable|integer',
            'description' => 'nullable|string',
            'website' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $book = Book::create([
            'user_id' => Auth::id(),
            'isbn' => $request->isbn,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'author' => $request->author,
            'published' => $request->published,
            'publisher' => $request->publisher,
            'pages' => $request->pages,
            'description' => $request->description,
            'website' => $request->website,
        ]);

        return response()->json([
            'message' => 'Book created',
            'book' => $book
        ], 201);
    }


    public function index(Request $request)
    {
        // $books = Book::all();
        $books = Book::paginate(10);

        return response()->json($books, 200);
    }

    public function byId($id_books)
    {
        $book = Book::find($id_books);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        if ($book->user_id !== Auth::id()) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        return response()->json($book, 200);
    }

    public function update(Request $request, $id_books)
    {
        $book = Book::find($id_books);

        if (!$book) {
            return response()->json([
                'message' => "No query results for model [App\\Models\\Book] $id_books"
            ], 404);
        }

        if ($book->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'This action is unauthorized.'
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'isbn' => 'required|string',
            'title' => 'required|string',
            'subtitle' => 'nullable|string',
            'author' => 'nullable|string',
            'published' => 'nullable|string',
            'publisher' => 'nullable|string',
            'pages' => 'nullable|integer',
            'description' => 'nullable|string',
            'website' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $book->update($request->all());

        return response()->json([
            'message' => 'Book updated',
            'book' => $book
        ], 200);
    }

    public function destroy($id_books)
    {
        $book = Book::find($id_books);

        if (!$book) {
            return response()->json([
                'message' => "No query results for model [App\\Models\\Book] $id_books"
            ], 404);
        }

        if ($book->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'This action is unauthorized.'
            ], 403);
        }
        $book->delete();
        return response()->json([
            'message' => 'Book deleted',
            'book' => $book
        ], 200);
    }
}