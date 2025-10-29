# Cara Menggunakan Koneksi Database Ganda (Absensi & TPP)

## Konfigurasi yang telah ditambahkan:

### 1. File .env
```env
# Database Utama (Absensi)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absensi
DB_USERNAME=root
DB_PASSWORD=root

# Database Kedua (TPP)
DB_TPP_CONNECTION=mysql_tpp
DB_TPP_HOST=127.0.0.1
DB_TPP_PORT=3306
DB_TPP_DATABASE=tpp
DB_TPP_USERNAME=root
DB_TPP_PASSWORD=root
```

### 2. File config/database.php
Telah ditambahkan koneksi `mysql_tpp` yang menggunakan variabel environment dari database TPP.

## Cara Penggunaan:

### 1. Menggunakan Query Builder

```php
// Query ke database absensi (default)
$users = DB::table('users')->get();

// Query ke database TPP
$tppData = DB::connection('mysql_tpp')->table('nama_tabel')->get();
```

### 2. Menggunakan Model

**Cara 1: Menggunakan $connection property di Model**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TppModel extends Model
{
    protected $connection = 'mysql_tpp'; // Koneksi ke database TPP
    protected $table = 'nama_tabel_tpp';
}
```

**Cara 2: Menggunakan connection() method saat runtime**

```php
// Menggunakan model dengan koneksi default
$pegawai = Pegawai::find(1);

// Menggunakan model dengan koneksi TPP
$tppData = new Pegawai();
$tppData->setConnection('mysql_tpp');
$results = $tppData->where('status', 'active')->get();
```

### 3. Menggunakan di Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Pegawai;

class TppController extends Controller
{
    public function index()
    {
        // Mengambil data dari database absensi
        $pegawai = Pegawai::all();
        
        // Mengambil data dari database TPP
        $tppData = DB::connection('mysql_tpp')
            ->table('tabel_tpp')
            ->join('tabel_lain', 'tabel_tpp.id', '=', 'tabel_lain.tpp_id')
            ->select('tabel_tpp.*', 'tabel_lain.nama')
            ->get();
            
        return view('tpp.index', compact('pegawai', 'tppData'));
    }
    
    public function store(Request $request)
    {
        // Menyimpan ke database absensi
        $pegawai = new Pegawai();
        $pegawai->nama = $request->nama;
        $pegawai->save();
        
        // Menyimpan ke database TPP
        DB::connection('mysql_tpp')->table('tabel_tpp')->insert([
            'pegawai_id' => $pegawai->id,
            'jumlah' => $request->jumlah,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->back();
    }
}
```

### 4. Menggunakan Migration

```bash
# Migration untuk database default (absensi)
php artisan make:migration create_table_absensi

# Migration untuk database TPP
php artisan make:migration create_table_tpp --database=mysql_tpp
```

### 5. Menggunakan Transaction dengan Multiple Database

```php
use Illuminate\Support\Facades\DB;

DB::transaction(function () {
    // Operasi pada database absensi
    DB::table('pegawai')->insert(['nama' => 'John Doe']);
    
    // Operasi pada database TPP
    DB::connection('mysql_tpp')->table('tpp_data')->insert([
        'pegawai_id' => 1,
        'jumlah' => 1000000
    ]);
});
```

## Tips Penting:

1. **Default Connection**: Database `absensi` tetap menjadi koneksi default
2. **Explicit Connection**: Selalu gunakan `->connection('mysql_tpp')` untuk mengakses database TPP
3. **Error Handling**: Pastikan database TPP ada dan dapat diakses
4. **Environment**: Sesuaikan konfigurasi di file `.env` untuk environment yang berbeda

## Testing Koneksi:

```php
// Test koneksi database absensi
try {
    DB::connection()->getPdo();
    echo "Koneksi database absensi berhasil!";
} catch (\Exception $e) {
    die("Tidak bisa terhubung ke database absensi: " . $e->getMessage());
}

// Test koneksi database TPP
try {
    DB::connection('mysql_tpp')->getPdo();
    echo "Koneksi database TPP berhasil!";
} catch (\Exception $e) {
    die("Tidak bisa terhubung ke database TPP: " . $e->getMessage());
}
