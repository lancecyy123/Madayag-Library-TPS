# 📚 Library Management System

## 📝 Description / Overview  
This project is a **Library Management System** built using the **Laravel framework**.  
It allows administrators to manage books, members, and transactions efficiently through a web interface.  
Users can add, edit, delete, and view records for books, members, and borrowed transactions.  
The system provides a simple dashboard and well-structured pages using Blade templates.

---

## 🎯 Objectives  
- To develop a CRUD-based system for managing library resources.  
- To apply MVC architecture using Laravel framework.  
- To practice database integration and RESTful routing.  
- To demonstrate Blade templating and Laravel controllers.  
- To gain hands-on experience in building scalable web applications.

---

## ⚙️ Features / Functionality  
✅ **Books Management** – Add, edit, delete, and view book records.  
✅ **Members Management** – Maintain a list of members with full CRUD functionality.  
✅ **Transactions Management** – Record and monitor borrowed, overdue, and returned books.  
✅ **Dashboard** – Quick overview of the system data.  
✅ **Blade Templates** – User-friendly and responsive design with reusable layouts.  
✅ **MySQL Integration** – All data is stored and retrieved from a relational database.  

---

## 💻 Installation Instructions

Follow these steps to set up and run the project locally:

```bash
# 1️⃣ Clone the repository
git clone https://github.com/lancecyy123/Madayag-Library-TPS.git

# 2️⃣ Navigate into the project directory
cd Madayag-Library-TPS

# 3️⃣ Install dependencies
composer install

# 4️⃣ Copy the example environment file
cp .env.example .env

# 5️⃣ Configure your database credentials in the .env file

# 6️⃣ Generate an application key
php artisan key:generate

# 7️⃣ Run database migrations
php artisan migrate

# 8️⃣ Start the development server
php artisan serve

---

## 🚀 Usage

Open the system in your browser: http://127.0.0.1:8000

Use the Dashboard to view quick stats.

Go to Books, Members, or Transactions to manage records.

Click “Add” to create a new entry.

Use “Edit” to modify existing entries.

Click “Delete” to remove items from the database.

Data updates automatically reflect in MySQL.

---

## Screenshots / Code Snippets

- **Controller Example (BookController.php)**
```php
namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index() {
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    public function create() {
        return view('books.create');
    }

    public function store(Request $request) {
        Book::create($request->all());
        return redirect()->route('books.index');
    }

    public function edit(Book $book) {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book) {
        $book->update($request->all());
        return redirect()->route('books.index');
    }

    public function destroy(Book $book) {
        $book->delete();
        return redirect()->route('books.index');
    }
}


- **Blade Example (index.blade.php)**
```php
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Book List</h1>
    <a href="{{ route('books.create') }}" class="btn btn-primary mb-3">Add Book</a>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th><th>Author</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($books as $book)
            <tr>
                <td>{{ $book->title }}</td>
                <td>{{ $book->author }}</td>
                <td>
                    <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

---

## 👥 Contributors
| Name | Role |
|------|------|
| **Lance Madayag** | Developer |
| **Lance Napeñas** | Collaborator |

---

## License
This project was created as part of our ITPC 115 coursework and is intended solely for academic and educational purposes.