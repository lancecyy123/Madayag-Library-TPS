@extends('layouts.app')

@section('title', 'Books - Library TPS')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><i class="fas fa-book me-2"></i>Books Management</h2>
        <p class="text-muted">Manage your library's book collection</p>
    </div>
    <a href="{{ route('books.create') }}" class="btn btn-gradient">
        <i class=></i>Add New Book
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($books->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">Title</th>
                            <th width="5%">Author</th>
                            <th width="5%">ISBN</th>
                            <th width="5%">Category</th>
                            <th width="5%">Available/Total</th>
                            <th width="5%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                        <tr>
                            <td>
                                <strong>{{ $book->title }}</strong>
                            </td>
                            <td>{{ $book->author }}</td>
                            <td><code>{{ $book->isbn }}</code></td>
                            <td>
                                <span class=>{{ $book->category }}</span>
                            </td>
                            <td>
                                <span class=>
                                    {{ $book->available_copies }}/{{ $book->total_copies }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-info">
                                        <i class=>Show</i>
                                    </a>
                                    <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-outline-warning">
                                        <i class=>Edit</i>
                                    </a>
                                    <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                            <i class=>Remove</i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $books->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-book fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Books Found</h4>
                <p class="text-muted">Start by adding your first book to the library.</p>
                <a href="{{ route('books.create') }}" class="btn btn-gradient">
                    <i class="fas fa-plus me-2"></i>Add First Book
                </a>
            </div>
        @endif
    </div>
</div>
@endsection