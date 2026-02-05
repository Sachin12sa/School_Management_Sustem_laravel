@extends('layouts.app')

@section('content')
<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit Class</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">

                    <div class="card card-primary card-outline mb-4">

                        <form method="post" action="{{ url('admin/class/edit/' . $getRecord->id) }}">
                            @csrf

                            <div class="card-body">

                                <!-- Class Name -->
                                <div class="mb-3">
                                    <label class="form-label">Class Name</label>
                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        placeholder="Enter class name"
                                        value="{{ old('name', $getRecord->name) }}"
                                        required
                                    >
                                    <div class="text-danger">
                                        {{ $errors->first('name') }}
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select value="{{ old('name', $getRecord->status) }}" name="status" class="form-control" value>
                                        <option value="0" {{ $getRecord->status == 0 ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="1" {{ $getRecord->status == 1 ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>

                            </div>

                            <!-- Footer -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                                <a href="{{ url('admin/class/list') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>

</main>
@endsection
