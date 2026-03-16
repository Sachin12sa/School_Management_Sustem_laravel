@extends('layouts.app')
@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold"><i class="bi bi-book-fill me-2 text-primary"></i>Add New Book</h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('librarian/library/book/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ url('librarian/library/book/add') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">

                        {{-- Main Info --}}
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm rounded-3 mb-3">
                                <div
                                    class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                    <i class="bi bi-info-circle me-1"></i> Book Information
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Title <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ old('title') }}" placeholder="Book title" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Author <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="author" class="form-control"
                                                value="{{ old('author') }}" placeholder="Author name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">ISBN</label>
                                            <input type="text" required name="isbn" class="form-control"
                                                value="{{ old('isbn') }}" placeholder="978-...">
                                            @error('isbn')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror

                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Publisher</label>
                                            <input type="text" name="publisher" class="form-control"
                                                value="{{ old('publisher') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Edition</label>
                                            <input type="text" name="edition" class="form-control"
                                                value="{{ old('edition') }}" placeholder="e.g. 3rd">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Publish Year</label>
                                            <input type="number" name="publish_year" class="form-control"
                                                value="{{ old('publish_year') }}" placeholder="{{ date('Y') }}"
                                                min="1900" max="{{ date('Y') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Category</label>
                                            <input type="text" name="category" class="form-control"
                                                value="{{ old('category') }}" placeholder="e.g. Science, Math, Fiction"
                                                list="categoryList">
                                            <datalist id="categoryList">
                                                @foreach ($getCategories as $cat)
                                                    <option value="{{ $cat }}">
                                                @endforeach
                                            </datalist>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Rack Number</label>
                                            <input type="text" name="rack_number" class="form-control"
                                                value="{{ old('rack_number') }}" placeholder="e.g. A-12">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Total Copies <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="quantity" class="form-control"
                                                value="{{ old('quantity', 1) }}" min="1" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Description</label>
                                            <textarea name="description" class="form-control" rows="3" placeholder="Brief description of the book">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Cover + Status --}}
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm rounded-3 mb-3">
                                <div
                                    class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                    <i class="bi bi-image me-1"></i> Cover Image
                                </div>
                                <div class="card-body text-center">
                                    <img id="coverPreview" src="{{ asset('dist/assets/img/default-book.png') }}"
                                        class="rounded mb-3 border" style="width:120px;height:160px;object-fit:cover;">
                                    <input type="file" name="cover_image" class="form-control form-control-sm"
                                        accept="image/*" onchange="previewCover(this)">
                                    <div class="form-text">JPG, PNG, WEBP — max 2MB</div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm rounded-3">
                                <div
                                    class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                    <i class="bi bi-toggle-on me-1"></i> Status
                                </div>
                                <div class="card-body">
                                    <select name="status" class="form-select" required>
                                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <a href="{{ url('admin/library/book/list') }}"
                                    class="btn btn-outline-secondary flex-grow-1">Cancel</a>
                                <button type="submit" class="btn btn-primary flex-grow-1 fw-semibold">
                                    <i class="bi bi-save me-1"></i> Save Book
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
                reader.onload = function(e) {
                    document.getElementById('coverPreview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
