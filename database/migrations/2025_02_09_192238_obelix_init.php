<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Prodi;
use App\Models\Kaprodi;
use App\Models\Dosen;
use App\Models\Kurikulum;
use App\Models\MataKuliahKurikulum;
use App\Models\MataKuliahSemester;
use App\Models\Mahasiswa;
use App\Models\Cpl;
use App\Models\Pi;
use App\Models\Cpmk;
use App\Models\CpmkCpl;
use App\Models\CpmkPi;
use App\Models\Nilai;
use App\Models\NilaiCpmk;

return new class extends Migration {
    public function up(): void
    {
        // Tabel Program Studi
        Schema::create((new Prodi)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('nama', 100)->unique();
            $table->integer('cpmk')->default(10);
            $table->integer('cpl')->default(10);
            $table->timestamps();
        });

        // Tabel Kaprodi
        Schema::create((new Kaprodi)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->constrained((new Prodi)->getTable())->onDelete('cascade');
            $table->string('nip', 20)->unique();
            $table->string('nama', 100);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });

        // Tabel Dosen
        Schema::create((new Dosen)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('nip', 20)->unique();
            $table->string('nidn', 20)->unique()->nullable();
            $table->string('nama', 100);
            $table->timestamps();
        });

        // Tabel Kurikulum
        Schema::create((new Kurikulum)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            // $table->year('tahun');
            // $table->integer('semester')->default(1);
            $table->foreignId('prodi_id')->constrained((new Prodi)->getTable())->onDelete('cascade');
            $table->timestamps();

            $table->unique(['prodi_id', 'nama']);
        });

        // Tabel Mata Kuliah Kurikulum
        Schema::create((new MataKuliahKurikulum)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20);
            $table->string('nama', 150);
            $table->string('kategori');
            $table->string('semester');
            $table->foreignId('kurikulum_id')->constrained((new Kurikulum)->getTable())->onDelete('cascade');
            $table->integer('sks');
            $table->timestamps();

            $table->unique(['kurikulum_id', 'kode']);
        });

        // Tabel Mata Kuliah Semester
        Schema::create((new MataKuliahSemester)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('mkk_id')->constrained((new MataKuliahKurikulum)->getTable())->onDelete('cascade');
            $table->year('tahun');
            $table->integer('semester');
            $table->foreignId('pengampu_id')->constrained((new Dosen)->getTable())->onDelete('cascade');
            $table->foreignId('koord_pengampu_id')->constrained((new Dosen)->getTable())->onDelete('cascade');
            $table->foreignId('gpm_id')->constrained((new Dosen)->getTable())->onDelete('cascade');
            $table->timestamps();

            $table->unique(['mkk_id', 'tahun', 'semester']);
        });

        // Tabel Mahasiswa
        Schema::create((new Mahasiswa)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('nim', 15)->unique();
            $table->string('nama', 100);
            $table->foreignId('prodi_id')->constrained((new Prodi)->getTable())->onDelete('cascade');
            $table->timestamps();
        });

        // Tabel CPL
        Schema::create((new Cpl)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->integer('nomor');
            $table->string('nama');
            $table->text('deskripsi');
            $table->foreignId('kurikulum_id')->constrained((new Kurikulum)->getTable())->onDelete('cascade');
            $table->timestamps();

            $table->unique(['kurikulum_id', 'nomor']);
        });

        // Tabel PI / SUB CPL
        Schema::create((new Pi)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->integer('nomor');
            $table->text('deskripsi');
            $table->foreignId('cpl_id')->constrained((new Cpl)->getTable())->onDelete('cascade');
            $table->timestamps();

            $table->unique(['cpl_id', 'nomor']);
        });

        // Tabel CPMK
        Schema::create((new Cpmk)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('mks_id')->constrained((new MataKuliahSemester)->getTable())->onDelete('cascade');
            $table->integer('nomor');
            $table->string('deskripsi');
            $table->string('level_taksonomi')->nullable();
            $table->timestamps();

            $table->unique(['mks_id', 'nomor']);
        });


        // Tabel Hubungan CPMK - CPL
        Schema::create((new CpmkCpl)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('cpmk_id')->constrained((new Cpmk)->getTable())->onDelete('cascade');
            $table->foreignId('cpl_id')->constrained((new Cpl)->getTable())->onDelete('cascade');
            $table->float('bobot')->check('bobot >= 0 AND bobot <= 1');
            $table->timestamps();

            $table->unique(['cpmk_id', 'cpl_id']);
        });

        // Tabel Hubungan CPMK - PI
        Schema::create((new CpmkPi)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('cpmk_id')->constrained((new Cpmk)->getTable())->onDelete('cascade');
            $table->foreignId('pi_id')->constrained((new Pi)->getTable())->onDelete('cascade');
            $table->float('bobot')->check('bobot >= 0 AND bobot <= 1');
            $table->timestamps();

            $table->unique(['cpmk_id', 'pi_id']);
        });

        // Tabel Nilai Mahasiswa
        Schema::create((new Nilai)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained((new Mahasiswa)->getTable())->onDelete('cascade');
            $table->foreignId('mks_id')->constrained((new MataKuliahSemester)->getTable())->onDelete('cascade');
            $table->string('kelas')->nullable();
            $table->integer('semester');
            // $table->enum('status', ['baru', 'ulang']);
            $table->string('status');
            $table->float('nilai_akhir_angka')->check('nilai_akhir_angka >= 0 AND nilai_akhir_angka <= 100');
            $table->enum('nilai_akhir_huruf', ['A', 'AB', 'B', 'BC', 'C', 'D', 'E']);
            $table->float('nilai_bobot')->check('nilai_angka >= 0 AND nilai_angka <= 4');
            // $table->enum('outcome', ['lulus', 'remidi_cpmk', 'tidak_lulus']);
            $table->string('outcome');
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'mks_id']);
        });

        // Tabel Nilai - CPMK
        Schema::create((new NilaiCpmk)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('nilai_id')->constrained((new Nilai)->getTable())->onDelete('cascade');
            $table->foreignId('cpmk_id')->constrained((new Cpmk)->getTable())->onDelete('cascade');
            $table->float('nilai_angka')->check('nilai >= 0 AND nilai <= 100')->nullable();
            $table->float('nilai_bobot')->check('nilai_angka >= 0 AND nilai_angka <= 4')->nullable();
            $table->timestamps();

            $table->unique(['nilai_id', 'cpmk_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists((new NilaiCpmk)->getTable());
        Schema::dropIfExists((new Nilai)->getTable());
        Schema::dropIfExists((new CpmkPi)->getTable());
        Schema::dropIfExists((new CpmkCpl)->getTable());
        Schema::dropIfExists((new Cpmk)->getTable());
        Schema::dropIfExists((new Pi)->getTable());
        Schema::dropIfExists((new Cpl)->getTable());
        Schema::dropIfExists((new MataKuliahSemester)->getTable());
        Schema::dropIfExists((new MataKuliahKurikulum)->getTable());
        Schema::dropIfExists((new Mahasiswa)->getTable());
        Schema::dropIfExists((new Kurikulum)->getTable());
        Schema::dropIfExists((new Prodi)->getTable());
        Schema::dropIfExists((new Dosen)->getTable());
        Schema::dropIfExists((new Kaprodi)->getTable());
    }
};
