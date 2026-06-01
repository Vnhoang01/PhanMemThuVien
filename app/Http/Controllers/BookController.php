<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookDetail;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // =========================
    // LIST
    // =========================
    public function index(Request $request)
    {
        $query = Book::with([
            'author',
            'category',
            'publisher',
            'details'
        ])->withCount([

            'details',

            'details as available_count' => function ($q) {
                $q->where('status', 'available');
            },

            'details as borrowed_count' => function ($q) {
                $q->where('status', 'borrowed');
            },

            'details as damaged_count' => function ($q) {
                $q->where('status', 'damaged');
            },

            'details as lost_count' => function ($q) {
                $q->where('status', 'lost');
            },
        ]);

        // Tìm kiếm
        if ($request->keyword) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {

                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('book_code', 'like', "%{$keyword}%")
                    ->orWhere('isbn', 'like', "%{$keyword}%")

                    ->orWhereHas('author', function ($sub) use ($keyword) {
                        $sub->where('name', 'like', "%{$keyword}%");
                    })

                    ->orWhereHas('category', function ($sub) use ($keyword) {
                        $sub->where('name', 'like', "%{$keyword}%");
                    })

                    ->orWhereHas('publisher', function ($sub) use ($keyword) {
                        $sub->where('name', 'like', "%{$keyword}%");
                    });
            });
        }

        $books = $query
            ->latest()
            ->paginate(3)
            ->withQueryString();

        return view('books.index', compact('books'));
    }

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        $authors = Author::all();
        $categories = Category::all();
        $publishers = Publisher::all();

        return view('books.create', compact(
            'authors',
            'categories',
            'publishers'
        ));
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required|string|max:255',

            'isbn' => [
                'nullable',
                'string',
                'max:20',
                'unique:books,isbn',
                'regex:/^[0-9\-Xx]+$/'
            ],

            'year_of_publication' =>
                'nullable|integer|min:1900|max:' . date('Y'),

            'author_id' => 'required|exists:authors,id',

            'category_id' => 'required|exists:categories,id',

            'publisher_id' => 'required|exists:publishers,id',

            'description' => 'nullable',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        DB::transaction(function () use ($request) {

            $imagePath = null;

            // Upload ảnh
            if ($request->hasFile('image')) {

                $imagePath = $request->file('image')
                    ->store('books', 'public');
            }

            $book = Book::create([

                'book_code' => $this->generateBookCode(),

                'isbn' => $this->normalizeIsbn($request->isbn),

                'name' => $request->name,

                'year_of_publication' =>
                    $request->year_of_publication,

                'author_id' => $request->author_id,

                'category_id' => $request->category_id,

                'publisher_id' => $request->publisher_id,

                'status' => 'available',

                'description' => $request->description,

                'image' => $imagePath,
            ]);

            // Tạo từng cuốn sách vật lý
            for ($i = 1; $i <= $request->total_quantity; $i++) {

                $barcode =
                    $book->book_code .
                    '-' .
                    str_pad($i, 4, '0', STR_PAD_LEFT);

                BookDetail::create([

                    'book_id' => $book->id,

                    'barcode' => $barcode,

                    'status' => 'available'
                ]);
            }
        });

        return redirect()
            ->route('books.index')
            ->with('success', 'Thêm sách thành công');
    }

    // =========================
    // EDIT
    // =========================
    public function edit(Book $book)
    {
        $authors = Author::all();
        $categories = Category::all();
        $publishers = Publisher::all();

        return view('books.edit', compact(
            'book',
            'authors',
            'categories',
            'publishers'
        ));
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, Book $book)
    {
        $request->validate([

            'name' => 'required|string|max:255',

            'isbn' => [
                'nullable',
                'string',
                'max:20',
                'unique:books,isbn,' . $book->id,
                'regex:/^[0-9\-Xx]+$/'
            ],

            'year_of_publication' =>
                'nullable|integer|min:1900|max:' . date('Y'),

            'author_id' => 'required|exists:authors,id',

            'category_id' => 'required|exists:categories,id',

            'publisher_id' => 'required|exists:publishers,id',

            'description' => 'nullable',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = $book->image;

        // Upload ảnh mới
        if ($request->hasFile('image')) {

            // Xóa ảnh cũ
            if ($book->image) {

                Storage::disk('public')
                    ->delete($book->image);
            }

            $imagePath = $request->file('image')
                ->store('books', 'public');
        }

        $book->update([

            'isbn' => $this->normalizeIsbn($request->isbn),

            'name' => $request->name,

            'year_of_publication' =>
                $request->year_of_publication,

            'author_id' => $request->author_id,

            'category_id' => $request->category_id,

            'publisher_id' => $request->publisher_id,

            'description' => $request->description,

            'image' => $imagePath,
        ]);

        return redirect()
            ->route('books.index')
            ->with('success', 'Cập nhật thành công');
    }

    // =========================
    // DELETE
    // =========================
    public function destroy(Book $book)
    {
        DB::transaction(function () use ($book) {

            // Không cho xóa nếu đang mượn
            $isBorrowing = $book->details()
                ->where('status', 'borrowed')
                ->exists();

            if ($isBorrowing) {

                throw new \Exception(
                    'Không thể xóa sách đang được mượn'
                );
            }

            // Xóa ảnh
            if ($book->image) {

                Storage::disk('public')
                    ->delete($book->image);
            }

            // Xóa bản vật lý
            $book->details()->delete();

            // Xóa sách
            $book->delete();
        });

        return redirect()
            ->route('books.index')
            ->with('success', 'Xóa thành công');
    }

    // =========================
    // GET AVAILABLE DETAILS
    // =========================
    public function details($id)
    {
        $details = BookDetail::with('book')

            ->where('book_id', $id)

            ->where('status', 'available')

            ->get();

        return response()->json($details);
    }

    // =========================
    // GENERATE BOOK CODE
    // =========================
    private function generateBookCode()
    {
        do {

            $code =
                'BOOK-' .
                str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        } while (
            Book::where('book_code', $code)->exists()
        );

        return $code;
    }

    // =========================
    // NORMALIZE ISBN
    // =========================
    private function normalizeIsbn($isbn)
    {
        return $isbn
            ? str_replace(['-', ' '], '', $isbn)
            : null;
    }
}
