@extends('layouts.layout_student')

@section('title', 'Mượn sách')

@section('content')

    <div class="student-container">

        @if(session('success'))
            <p style="color: green; text-align: center;">{{ session('success') }}</p>
        @endif

        @if($errors->any())
            <div style="color: red; text-align: center;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="borrowForm" method="POST" action="{{ route('student.borrow.submit') }}">
            @csrf

            <h2>Sách Có Sẵn</h2>
            <div class="book-list" id="bookList">
                @foreach($books as $book)
                    <div class="book-item" data-id="{{ $book->id }}">
                        <h3>{{ $book->name }}</h3>
                        <p><strong>Tác Giả:</strong> {{ $book->author->name ?? 'Không rõ' }}</p>
                        <p><strong>Thể Loại:</strong> {{ $book->category->name ?? 'Không rõ' }}</p>
                        <p><strong>Số quyển sách có sẵn:</strong> {{ $book->available_quantity ?? 0 }}</p>
                        <a href="{{ route('login_student.detail', $book->id) }}"
                           class="borrow-btn text-decoration-none text-center d-block">

                            📖 Xem Chi Tiết

                        </a>
                    </div>
                @endforeach
            </div>
        </form>
    </div>

    <script>
        let selectedBooks = [];

        function selectBook(bookId, button) {
            const bookItem = button.parentElement;
            const index = selectedBooks.indexOf(bookId);

            if (index > -1) {
                selectedBooks.splice(index, 1);
                bookItem.classList.remove('selected');
                button.textContent = 'Chọn Để Mượn';
            } else {
                selectedBooks.push(bookId);
                bookItem.classList.add('selected');
                button.textContent = 'Bỏ Chọn';
            }

            updateSelectedList();
            updateSubmitButton();
        }

        function updateSelectedList() {
            const list = document.getElementById('selectedBooks');
            list.innerHTML = '';
            selectedBooks.forEach(id => {
                const li = document.createElement('li');
                li.textContent = `ID Sách: ${id}`;
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'book_ids[]';
                hiddenInput.value = id;
                li.appendChild(hiddenInput);
                list.appendChild(li);
            });
        }

        function updateSubmitButton() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = selectedBooks.length === 0;
        }
    </script>
@endsection
