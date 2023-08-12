@extends('layouts.app')

@section('content')
    <h1>Book List</h1>
    <div class="my-4">
        <input type="text" id="searchBox" placeholder="Search books..." class="form-control">
    </div>
    <select id="sortSelect" class="mb-4">
        <option value="asc">Ascending</option>
        <option value="desc">Descending</option>
    </select>
    <ul id="bookList" class="list-group"></ul>
    <nav aria-label="Page navigation">
        <ul id="pagination" class="pagination mt-4"></ul>
    </nav>

    <script src="books.js"></script>
@endsection