@extends('layouts.app')

@section('content')
    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            @if ($message = Session::get('error'))
                <div class="alert alert-danger" role="alert">
                    {{ $message }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <div class="float-start">
                        Add New Pet
                    </div>
                    <div class="float-end">
                        <a href="{{ route('pets.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('pets.store') }}" method="post">
                        @csrf

                        <div class="mb-3 row">
                            <label for="category" class="col-md-4 col-form-label text-md-end text-start">Category</label>
                            <select class="form-select" name='category'>
                                <option selected value=''>Select categories...</option>
                                @foreach ($categories as $c)
                                    <option {{ old('category') === $c['id'] ? 'selected' : '' }}
                                        value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 row">
                            <label for="name" class="col-md-4 col-form-label text-md-end text-start">Name</label>
                            <div class="col-md-6">
                                <input class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name') }}">
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="photoUrls (separate with comma)"
                                class="col-md-4 col-form-label text-md-end text-start">PhotoUrls</label>
                            <div class="col-md-6">
                                <input class="form-control @error('photoUrls') is-invalid @enderror" id="photoUrls"
                                    name="photoUrls" value="{{ old('photoUrls') }}">
                                @if ($errors->has('photoUrls'))
                                    <span class="text-danger">{{ $errors->first('photoUrls') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tags" class="col-md-4 col-form-label text-md-end text-start">Tag</label>
                            <select multiple class="form-select" name='tags[]' id='tags'>
                                <option value=''>Select tags...</option>
                                @foreach ($tags as $t)
                                    <option {{ old('tags') && (false !== array_search((string)$t['id'], old('tags'))) ? 'selected' : '' }} value="{{ $t['id'] }}">
                                        {{ $t['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 row">
                            <label for="status" class="col-md-4 col-form-label text-md-end text-start">Status</label>
                            <select class="form-select" name='status'>
                                <option selected value=''>Select statuses...</option>
                                @foreach ($statuses as $s)
                                    <option {{ old('status') === $s ? 'selected' : '' }} value="{{ $s }}">
                                        {{ $s }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 row">
                            <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Add pet">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
