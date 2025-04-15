<x-default-layout>

    @section('title')
        Rapor CPL Mahasiswa
    @endsection

    {{-- @section('breadcrumbs')
        {{ Breadcrumbs::render('report.index') }}
    @endsection --}}

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1 me-2">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search" id="mySearchInput"/>
                </div>
                <!--end::Search-->

                <!--begin::Angkatan filter-->
                <div class="d-flex align-items-center position-relative my-1">
                    <select class="form-select form-select-solid w-200px" id="angkatanFilter">
                        <option value="">Semua Angkatan</option>
                        @foreach($angkatanList as $angkatan)
                            <option value="{{ $angkatan }}">Angkatan {{ $angkatan }}</option>
                        @endforeach
                    </select>
                </div>
                <!--end::Angkatan filter-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <!--begin::Add user-->
                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        Add User
                    </button> --}}
                    <!--end::Add user-->
                </div>
                <!--end::Toolbar-->

                <!--begin::Modal-->
                <livewire:user.add-user-modal></livewire:user.add-user-modal>
                <!--end::Modal-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['mahasiswa-table'].search(this.value).draw();
            });

            document.getElementById('angkatanFilter').addEventListener('change', function () {
                window.LaravelDataTables['mahasiswa-table'].column('angkatan:name').search(this.value).draw();
            });

            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_user').modal('hide');
                    window.LaravelDataTables['mahasiswa-table'].ajax.reload();
                });
            });
            $('#kt_modal_add_user').on('hidden.bs.modal', function () {
                Livewire.dispatch('new_user');
            });
        </script>
    @endpush

</x-default-layout>
