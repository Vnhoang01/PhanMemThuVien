@extends('layouts.master')

@section('content')

    <div class="container mt-4">

        <h3>📦 Trả sách</h3>

        <form method="POST" action="{{ route('loan_slips.return', $loan->id) }}">
            @csrf

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Sách</th>
                    <th>Lỗi</th>
                </tr>
                </thead>
                <tbody>

                @foreach($loan->details as $detail)
                    <tr>
                        <td>
                            {{ $detail->bookDetail->book->name }} <br>
                            <small>{{ $detail->bookDetail->barcode }}</small>
                        </td>

                        <td>
                            @foreach($errors as $error)
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="errors[{{ $detail->id }}][]"
                                           value="{{ $error->id }}">

                                    <label class="form-check-label">
                                        {{ $error->name }} ({{ number_format($error->fine_amount) }}đ)
                                    </label>
                                </div>
                            @endforeach
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>

            <button class="btn btn-success">
                Xác nhận trả sách
            </button>

        </form>

    </div>

@endsection
