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

            <div class="card shadow-sm mb-4">
                <div class="card-body">

                    <form method="GET" action="{{ route('student.borrow') }}">
                        <div class="row g-2">

                            <div class="col-md-10 position-relative">

                                <input type="text"
                                       name="keyword"
                                       value="{{ request('keyword') }}"
                                       class="form-control pe-5"
                                       placeholder="🔍 Tìm theo tên sách, tác giả hoặc thể loại">

                                @if(request('keyword'))
                                    <a href="{{ route('student.borrow') }}"
                                       class="search-clear">
                                        &times;
                                    </a>
                                @endif

                            </div>

                            <div class="col-md-2 d-grid">
                                <button class="btn btn-primary">
                                    🔍 Tìm
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>

        <form id="borrowForm" method="POST" action="{{ route('student.borrow.submit') }}">
            @csrf

            <h2>Sách Có Sẵn</h2>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">

                @foreach($books as $book)

                    <div class="col">

                        <div class="card h-100 shadow-sm border-0">

                            @if($book->image)
                                <img src="{{ asset('storage/' . $book->image) }}"
                                     class="card-img-top bg-light"
                                     style="height:250px; object-fit:contain;"
                                     alt="{{ $book->name }}">
                            @else
                                <img src="{{ asset('storage/' . $book->image) }}"
                                     class="card-img-top bg-light"
                                     style="height:250px; object-fit:contain;"
                                     alt="No image">
                            @endif

                            <div class="card-body d-flex flex-column">

                                <h5 class="card-title">
                                    {{ $book->name }}
                                </h5>

                                <p class="mb-1">
                                    <strong>Tác giả:</strong>
                                    {{ $book->author->name ?? 'Không rõ' }}
                                </p>

                                <p class="mb-1">
                                    <strong>Thể loại:</strong>
                                    {{ $book->category->name ?? 'Không rõ' }}
                                </p>

                                <p class="mb-3">
                                    <strong>Còn lại:</strong>
                                    {{ $book->available_quantity ?? 0 }}
                                    quyển
                                </p>

                                <div class="mt-auto">
                                    <a href="{{ route('login_student.detail', $book->id) }}"
                                       class="btn btn-primary w-100">
                                        📖 Xem Chi Tiết
                                    </a>
                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $books->links() }}
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
