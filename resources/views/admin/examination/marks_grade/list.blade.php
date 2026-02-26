@extends('layouts.app')

@section('content')
<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Marks Grades
                    
                    </h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/examination/marks_grade/add') }}" class="btn btn-primary">
                        + Add New Marks Grade
                    </a>
                </div>
            </div>

            <!-- Search Card -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search Exam</h3>
                        </div>

                  

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Exam List</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Grade Name</th>
                                <th>Percent From</th>
                                <th>Percent T0</th>
                                <th>Created By</th>
                                <th>Created Date</th>
                                <th width="180">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($getRecord as $key => $value)
                                <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->percent_from }}</td>
                                    <td>{{ $value->percent_to }}</td>  
                                    
                                    <td>{{ $value->created_name }} {{$value->created_last_name}}</td>

                                    <td>{{ date('d-m-Y H:i A',strtotime($value->created_at)) }}</td>
                                    <td>
                                        <a href="{{ url('admin/examination/marks_grade/edit/'. $value->id) }}" class="btn btn-sm btn-primary">
                                            Edit
                                        </a>
                                        <a href="{{ url('admin/examination/marks_grade/delete/'. $value->id) }}"
                                           class="btn btn-icon btn-sm btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to delete this student?')"
                                           title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">
                                        No admins found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

               
            </div>
        </div>
    </div>

</main>
@endsection
