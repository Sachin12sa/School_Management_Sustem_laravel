@extends('layouts.app')

@section('content')
<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                       Add Class TimeTable                       
                         {{-- <small class="text-muted">(Total : {{ $getRecord->total() }})</small> --}}
                    </h3>
                </div>
           
            </div>

            <!-- Search Card -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search Class Timetable </h3>
                        </div>

                        <form method="get" action="">
                            <div class="card-body">
                                <div class="row align-items-end">

                                    <div class="col-md-3">
                                        <label class="form-label"> Class Name</label>
                                        <input
                                            type="text"
                                            name="class_name"
                                            value="{{ request('name') }}"
                                            class="form-control"
                                            placeholder="Enter name"
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label"> Subject Name</label>
                                        <input
                                            type="text"
                                            name="Subject_name"
                                            value="{{ request('name') }}"
                                            class="form-control"
                                            placeholder="Enter name"
                                        />
                                    </div>
                
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">
                                            Search
                                        </button>
                                        <a href="{{ url('admin/class_timetable/list') }}" class="btn btn-success ms-1">
                                            Reset
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->

</main>
@endsection
