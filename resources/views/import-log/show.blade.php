<x-default-layout>
    @section('title')
        Import Log Details
    @endsection

    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Import Log #{{ $importLog->id }}</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('import-logs.index') }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-arrow-left fs-6"></i>
                    Back to Logs
                </a>
            </div>
        </div>

        <div class="card-body pt-9 pb-0">
            <!-- Basic Log Information -->
            <div class="row mb-7">
                <div class="col-lg-3 fw-semibold text-muted">Status</div>
                <div class="col-lg-9">
                    @php
                        $statusBadgeClass = match($importLog->status) {
                            'success' => 'badge-success',
                            'failed' => 'badge-danger',
                            'pending' => 'badge-warning',
                            default => 'badge-dark',
                        };
                    @endphp
                    <span class="badge {{ $statusBadgeClass }}">{{ ucfirst($importLog->status) }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <div class="col-lg-3 fw-semibold text-muted">Action</div>
                <div class="col-lg-9">
                    @php
                        $badgeClass = match($importLog->action) {
                            'submit' => 'badge-light-primary',
                            'preview' => 'badge-light-info',
                            'confirm' => 'badge-light-success',
                            'cancel' => 'badge-light-warning',
                            default => 'badge-light-dark',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($importLog->action) }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <div class="col-lg-3 fw-semibold text-muted">User</div>
                <div class="col-lg-9">{{ $importLog->user ? $importLog->user->name : 'Unknown' }}</div>
            </div>

            <div class="row mb-7">
                <div class="col-lg-3 fw-semibold text-muted">Date & Time</div>
                <div class="col-lg-9">{{ $importLog->created_at->format('d M Y, H:i:s') }}</div>
            </div>

            <!-- File Information -->
            @if($importLog->file_name)
                <div class="separator my-10"></div>
                <h3 class="mb-7">File Information</h3>

                <div class="row mb-7">
                    <div class="col-lg-3 fw-semibold text-muted">File Name</div>
                    <div class="col-lg-9">{{ $importLog->file_name }}</div>
                </div>

                @if($importLog->file_type)
                    <div class="row mb-7">
                        <div class="col-lg-3 fw-semibold text-muted">File Type</div>
                        <div class="col-lg-9">{{ $importLog->file_type }}</div>
                    </div>
                @endif

                @if($importLog->file_size)
                    <div class="row mb-7">
                        <div class="col-lg-3 fw-semibold text-muted">File Size</div>
                        <div class="col-lg-9">{{ $importLog->file_size }}</div>
                    </div>
                @endif

                @if($importLog->file_path)
                    <div class="row mb-7">
                        <div class="col-lg-3 fw-semibold text-muted">File Path</div>
                        <div class="col-lg-9">
                            <code>{{ $importLog->file_path }}</code>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Related Course Information -->
            @if($importLog->mataKuliahSemester)
                <div class="separator my-10"></div>
                <h3 class="mb-7">Related Course Information</h3>

                <div class="row mb-7">
                    <div class="col-lg-3 fw-semibold text-muted">Course</div>
                    <div class="col-lg-9">
                        @if($importLog->mataKuliahSemester->mkk)
                            {{ $importLog->mataKuliahSemester->mkk->kode }} - {{ $importLog->mataKuliahSemester->mkk->nama }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>

                <div class="row mb-7">
                    <div class="col-lg-3 fw-semibold text-muted">Semester / Year</div>
                    <div class="col-lg-9">
                        {{ $importLog->mataKuliahSemester->semester }} / {{ $importLog->mataKuliahSemester->tahun }}
                    </div>
                </div>
            @endif

            <!-- Error Information -->
            @if($importLog->error_message)
                <div class="separator my-10"></div>
                <h3 class="mb-7">Error Information</h3>

                <div class="row mb-7">
                    <div class="col-lg-3 fw-semibold text-muted">Error Message</div>
                    <div class="col-lg-9">
                        <div class="alert alert-danger">
                            {{ $importLog->error_message }}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Additional Details -->
            @if($importLog->details && is_array($importLog->details) && count($importLog->details) > 0)
                <div class="separator my-10"></div>
                <h3 class="mb-7">Additional Details</h3>

                <div class="row mb-7">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800">
                                        <th>Key</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($importLog->details as $key => $value)
                                        <tr>
                                            <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                            <td>
                                                @if(is_array($value))
                                                    <pre>{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                @elseif(is_bool($value))
                                                    {{ $value ? 'True' : 'False' }}
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-default-layout>
