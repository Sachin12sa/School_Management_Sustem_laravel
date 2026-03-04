@extends('layouts.app')

@section('content')
<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Notice Board
                        <small class="text-muted">(Total : {{ $getRecord->total() }})</small>
                    </h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/communicate/notice_board/add') }}" class="btn btn-primary">
                        + Add New Notice Board
                    </a>
                </div>
            </div>

            <!-- Search Card -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search Notice Board</h3>
                        </div>
                         <form method="get" action="">
                            <div class="card-body">
                                <div class="row align-items-end">

                                    <div class="col-md-3">
                                        <label class="form-label">Title</label>
                                        <input
                                            type="text"
                                            name="title"
                                            value="{{ request('title') }}"
                                            class="form-control"
                                            placeholder="Enter Title"
                                        />
                                    </div>

                 
                                     <div class="col-md-2">
                                        <label class="form-label">Notice Date</label>
                                        <div class="input-group">
                                        <input type="date" name="notice_date" id="notice_date" class="form-control" 
                                                value="{{ request('notice_date') ? date('Y-m-d', strtotime(request('notice_date'))) : '' }}">
                                        <span class="input-group-text" onclick="document.getElementById('notice_date').showPicker()">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Publish Date</label>
                                        <div class="input-group">
                                        <input type="date" name="publish_date" id="publish_date" class="form-control" 
                                                value="{{ request('publish_date') ? date('Y-m-d', strtotime(request('publish_date'))) : '' }}">
                                        <span class="input-group-text" onclick="document.getElementById('publish_date').showPicker()">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Message</label>
                                        <input
                                            type="text"
                                            name="message"
                                            value="{{ request('message') }}"
                                            class="form-control"
                                            placeholder="Enter Message"
                                        />
                                    </div>
                                    
                                    

                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary">
                                            Search
                                        </button>
                                        <a href="{{ url('admin/communicate/notice_board') }}" class="btn btn-success ms-1">
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
    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Admin List</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Title</th>
                                <th>Notice Date</th>
                                <th>Publish Date</th>
                                <th>Message To</th>
                                <th>Created By</th>
                                <th>Created Date</th>
                                <th width="180">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <thead>
                                @foreach ($getRecord as $value )
                                <tr>
                                    
                                    <td>{{$value->id}}</td>
                                    <td>{{$value->title}}</td>
                                    <td>{{date('d-m-Y',strtotime($value->notice_date))}}</td>
                                    <td>{{date('d-m-Y',strtotime($value->publish_date))}}</td>
                                    <td>
                                        @foreach ($value->getMessage as $message )
                                            @if ($message->message_to == 2)
                                            <div>Teacher</div>
                                            @elseif ($message->message_to == 3)
                                            <div>Student</div>
                                            @elseif ($message->message_to == 4)
                                            <div>Parent</div>
                                            @endif
                                            
                                        @endforeach
                                    </td>
                                    <td>{{$value->created_by_name}}</td>
                                    <td>{{$value->created_at}}</td>
                                    <td class="d-flex justify-content-center gap-3">
                       
                                    <a href="{{ url('admin/communicate/notice_board/edit/'.$value->id) }}"
                                class="btn btn-sm btn-primary">
                                    Edit
                                </a>                               
                                <a href="{{ url('admin/communicate/notice_board/delete/'.$value->id) }}"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure you want to delete this subject?')">
                                    Delete
                                </a>
                            </td>
                                        
                                   
                                </tr>
                                {{-- @empty
                                <tr>
                                    <td colspan = "100%">Record Not Found.</td>
                                </tr> --}}
                                 @endforeach
                            </thead>
                           
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer text-end">
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

</main>
@endsection
