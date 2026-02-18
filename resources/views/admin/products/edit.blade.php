@extends('layouts.admin')

@section('title', 'Edit Produk - Admin')
@section('page-title', 'Edit Produk')

@section('styles')
<style>
    .image-preview {
        width: 200px;
        height: 200px;
        border: 2px dashed var(--medium-gray);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 1rem;
        overflow: hidden;
        background: var(--light-gray);
    }

    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .spec-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .spec-row input {
        flex: 1;
    }
</style>
@endsection

@section('content')
<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <div class="card-custom">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Produk</h5>
                </div>
                <div class="card-body">
                    <!-- Nama Produk -->
                    <div class="mb-3">
                        <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="5" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Spesifikasi -->
                    <div class="mb-3">
                        <label class="form-label">Spesifikasi Produk</label>

                        <div id="specifications-container">
                            @if($product->specifications)
                                @foreach($product->specifications as $key => $value)
                                <div class="spec-row">
                                    <input type="text" class="form-control" name="spec_keys[]" placeholder="Nama Spec" value="{{ $key }}">
                                    <input type="text" class="form-control" name="spec_values[]" placeholder="Nilai" value="{{ $value }}">
                                    <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                @endforeach
                            @endif
                        </div>

                        <button type="button" class="btn btn-sm btn-secondary" onclick="addSpecRow()">
                            <i class="fas fa-plus"></i> Tambah Spesifikasi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Harga Sewa -->
            <div class="card-custom mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Harga Sewa</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga 6 Jam <span class="text-danger">*</span></label>
                            <input type="number" name="price_6_hours" class="form-control @error('price_6_hours') is-invalid @enderror"
                                   value="{{ old('price_6_hours', $product->price_6_hours) }}" required min="0">
                            @error('price_6_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga 12 Jam <span class="text-danger">*</span></label>
                            <input type="number" name="price_12_hours" class="form-control @error('price_12_hours') is-invalid @enderror"
                                   value="{{ old('price_12_hours', $product->price_12_hours) }}" required min="0">
                            @error('price_12_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga 24 Jam <span class="text-danger">*</span></label>
                            <input type="number" name="price_24_hours" class="form-control @error('price_24_hours') is-invalid @enderror"
                                   value="{{ old('price_24_hours', $product->price_24_hours) }}" required min="0">
                            @error('price_24_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga 1.5 Hari <span class="text-danger">*</span></label>
                            <input type="number" name="price_1_5_days" class="form-control @error('price_1_5_days') is-invalid @enderror"
                                   value="{{ old('price_1_5_days', $product->price_1_5_days) }}" required min="0">
                            @error('price_1_5_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Gambar -->
            <div class="card-custom">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-image"></i> Gambar Produk</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Upload Gambar Baru</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                               accept="image/*" onchange="previewImage(event)">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                    </div>

                    <div class="image-preview" id="imagePreview">
                        @if($product->image)
                            <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
                        @else
                            <i class="fas fa-camera" style="font-size: 3rem; color: var(--medium-gray);"></i>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stok & Status -->
            <div class="card-custom mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-warehouse"></i> Stok & Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                               value="{{ old('stock', $product->stock) }}" required min="0">
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Ketersediaan <span class="text-danger">*</span></label>
                        <select name="is_available" class="form-select @error('is_available') is-invalid @enderror" required>
                            <option value="1" {{ old('is_available', $product->is_available) == 1 ? 'selected' : '' }}>Tersedia</option>
                            <option value="0" {{ old('is_available', $product->is_available) == 0 ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                        @error('is_available')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="card-custom mt-3">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary-custom w-100 mb-2">
                        <i class="fas fa-save"></i> Update Produk
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
// Preview image
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        }
        reader.readAsDataURL(file);
    }
}

// Add specification row
function addSpecRow() {
    const container = document.getElementById('specifications-container');
    const newRow = document.createElement('div');
    newRow.className = 'spec-row';
    newRow.innerHTML = `
        <input type="text" class="form-control" name="spec_keys[]" placeholder="Nama Spesifikasi">
        <input type="text" class="form-control" name="spec_values[]" placeholder="Nilai">
        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(newRow);
}
</script>
@endsection
