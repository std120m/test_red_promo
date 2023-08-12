// пример массива книг
let books = Array.from({length: 100}, (_, i) => ({title: `Book ${i + 1}`, author: `Author ${i + 1}`}));

let currentPage = 1;
let booksPerPage = 10;

// Функция отображения книг
function displayBooks(books, page, booksPerPage) {
  let startIndex = (page - 1) * booksPerPage;
  let endIndex = startIndex + booksPerPage;
  let booksToDisplay = books.slice(startIndex, endIndex);

  document.getElementById('bookList').innerHTML = '';
  booksToDisplay.forEach(function (book) {
    document.getElementById('bookList').innerHTML += `<li class='list-group-item'>${book.title} by ${book.author}</li>`;
  });

  displayPagination(books, booksPerPage);
}

// Функция отображения пагинации
function displayPagination(books, booksPerPage) {
  let totalPages = Math.ceil(books.length / booksPerPage);

  document.getElementById('pagination').innerHTML = '';

  for (let i = 1; i <= totalPages; i++) {
    let isActive = currentPage == i ? 'active' : '';
    document.getElementById('pagination').innerHTML += `<li class="page-item ${isActive}"><a class="page-link" href="#">${i}</a></li>`;
  }
}

displayBooks(books, currentPage, booksPerPage);

// Событие клика на элемент пагинации
document.getElementById('pagination').addEventListener('click', function (e) {
  if(e.target.className === 'page-link') {
    currentPage = Number(e.target.innerHTML);
    displayBooks(books, currentPage, booksPerPage);
  }
});

// Сортировка
$('#sortSelect').on('change', function() {
    var option = $(this).val();
    if (option === 'asc') {
        books.sort(function(a, b) { return a.title > b.title ? 1 : -1; });
    } else if (option === 'desc') {
        books.sort(function(a, b) { return a.title < b.title ? 1 : -1; });
    }
    displayBooks(books);
});

// Поиск
$('#searchBox').on('keyup', function() {
    var value = $(this).val().toLowerCase();
    var filteredBooks = books.filter(function(book) {
        return book.title.toLowerCase().includes(value) || book.author.toLowerCase().includes(value);
    });
    displayBooks(filteredBooks);
});