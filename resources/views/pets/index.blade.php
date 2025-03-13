@extends('layouts.app')

@section('content')
    <div class="row justify-content-center mt-3">
        <div class="col-md-12">

            @if ($message = Session::get('success'))
                <div class="alert alert-success" role="alert">
                    {{ $message }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">Pet List</div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">id</th>
                                <th scope="col">name</th>
                                <th scope="col">
                                    <a href="{{ route('pets.create') }}" class="btn btn-primary">Create pet</a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pets as $pet)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $pet['id'] }}</td>
                                    <td>{{ $pet['name'] }}</td>
                                    <td>
                                        <a href="{{ route('pets.edit', $pet['id']) }}" class="btn btn-primary btn-sm"><i
                                                class="bi bi-pencil-square"></i> Edit</a>
                                        <form action="{{ route('pets.destroy', $pet['id']) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Do you want to delete this pet?');"><i
                                                    class="bi bi-trash"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <td colspan="7">
                                    <span class="text-danger">
                                        <strong>No pet found!</strong>
                                    </span>
                                </td>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $pets->links('pagination::bootstrap-4') }}

                </div>
            </div>
        </div>
    </div>
@endsection
