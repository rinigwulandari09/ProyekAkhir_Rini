<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Lahan Baru - Notasawit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light p-4">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow-sm p-4">
            <h4 class="mb-4 text-success">Tambah Peta Lahan Baru</h4>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('lahan.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Pilih Petani (Pemilik)</label>
                    <select name="petani_id" class="form-select" required>
                        <option value="">-- Pilih Petani --</option>
                        @foreach($data_petani as $petani)
                            <option value="{{ $petani->id }}">{{ $petani->nama_petani }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lahan / Nama Blok</label>
                    <input type="text" name="nama_lahan" class="form-control" placeholder="Contoh: Blok B Sawit Makmur" required value="{{ old('nama_lahan') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Luas Lahan (Hektar)</label>
                    <input type="number" step="0.01" name="luas_hektar" class="form-control" placeholder="Contoh: 4.5" required value="{{ old('luas_hektar') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kumpulan Koordinat Polygon (Longitude,Latitude)</label>
                    <div class="form-text text-muted mb-2">
                        Masukkan koordinat sudut lahan per baris. Format: <b>Longitude,Latitude</b> (Tanpa spasi).<br>
                        <span class="text-danger">*Penting: Baris pertama dan baris terakhir HARUS SAMA agar areanya mengunci.</span>
                    </div>
                    <textarea name="koordinat_raw" rows="6" class="form-control" placeholder="101.20,-0.50&#10;101.25,-0.50&#10;101.25,-0.55&#10;101.20,-0.55&#10;101.20,-0.50" required>{{ old('koordinat_raw') }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('lahan.index') }}" class="btn btn-secondary">Kembali ke Peta</a>
                    <button type="submit" class="btn btn-success">Simpan Koordinat Lahan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>