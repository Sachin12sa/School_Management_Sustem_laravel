@extends('layouts.app')
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center py-1">
                <div class="col-sm-6">
                    <h4 class="mb-0 fw-semibold"><i class="bi bi-book-fill me-2 text-primary"></i>Edit Book</h4>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <a href="{{ url('admin/library/book/list') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ url('admin/library/book/edit/'.$getRecord->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">

                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-3 mb-3">
                            <div class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-info-circle me-1"></i> Book Information
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control"
                                               value="{{ old('title', $getRecord->title) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Author <span class="text-danger">*</span></label>
                                        <input type="text" name="author" class="form-control"
                                               value="{{ old('author', $getRecord->author) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">ISBN</label>
                                        <input type="text" name="isbn" class="form-control"
                                               value="{{ old('isbn', $getRecord->isbn) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Publisher</label>
                                        <input type="text" name="publisher" class="form-control"
                                               value="{{ old('publisher', $getRecord->publisher) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Edition</label>
                                        <input type="text" name="edition" class="form-control"
                                               value="{{ old('edition', $getRecord->edition) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Publish Year</label>
                                        <input type="number" name="publish_year" class="form-control"
                                               value="{{ old('publish_year', $getRecord->publish_year) }}"
                                               min="1900" max="{{ date('Y') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Category</label>
                                        <input type="text" name="category" class="form-control"
                                               value="{{ old('category', $getRecord->category) }}"
                                               list="categoryList">
                                        <datalist id="categoryList">
                                            @foreach($getCategories as $cat)
                                                <option value="{{ $cat }}">
                                            @endforeach
                                        </datalist>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Rack Number</label>
                                        <input type="text" name="rack_number" class="form-control"
                                               value="{{ old('rack_number', $getRecord->rack_number) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Total Copies <span class="text-danger">*</span></label>
                                        <input type="number" name="quantity" class="form-control"
                                               value="{{ old('quantity', $getRecord->quantity) }}" min="1" required>
                                        <div class="form-text">Currently {{ $getRecord->quantity - $getRecord->available }} copies issued.</div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Description</label>
                                        <textarea name="description" class="form-control" rows="3">{{ old('description', $getRecord->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-3 mb-3">
                            <div class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-image me-1"></i> Cover Image
                            </div>
                            <div class="card-body text-center">
                                <img id="coverPreview"
                                     src="{{ $getRecord->cover_image ? asset('storage/'.$getRecord->cover_image) : asset('dist/assets/img/default-book.png') }}"
                                     class="rounded mb-3 border"
                                     style="width:120px;height:160px;object-fit:cover;">
                                <input type="file" name="cover_image" class="form-control form-control-sm"
                                       accept="image/*" onchange="previewCover(this)">
                                <div class="form-text">Leave blank to keep current image.</div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-toggle-on me-1"></i> Status
                            </div>
                            <div class="card-body">
                                <select name="status" class="form-select" required>
                                    <option value="1" {{ old('status', $getRecord->status) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $getRecord->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ url('admin/library/book/list') }}" class="btn btn-outline-secondary flex-grow-1">Cancel</a>
                            <button type="submit" class="btn btn-primary flex-grow-1 fw-semibold">
                                <i class="bi bi-save me-1"></i> Update Book
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</main>

<script>
function previewCover(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { document.getElementById('coverPreview').src = e.target.result; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection