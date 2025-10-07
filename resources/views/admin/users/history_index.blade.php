@extends('layouts.app')
@section('title','Admin • Users History')

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush

@section('content')
<div class="admin-users-page">
  <div class="container py-4" style="max-width:1200px;">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h1 class="h4 mb-0">Users History</h1>
      <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">Back to Users</a>
    </div>

    <form method="get" class="row gy-2 gx-2 mb-3">
      <div class="col-lg-6">
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-search"></i></span>
          <input name="q" value="{{ $q }}" class="form-control" placeholder="ค้นหา: username / email / first/last name">
        </div>
      </div>
      <div class="col-lg-3 d-flex gap-2">
        <button class="btn btn-primary">ค้นหา</button>
        <a href="{{ route('admin.users.history.index') }}" class="btn btn-outline-secondary">ล้าง</a>
      </div>
    </form>

    <div class="table-responsive rounded-3 border border-1">
      <table class="table table-hover align-middle mb-0 bg-white">
        <thead class="table-light position-sticky top-0" style="z-index:1;">
          <tr>
            <th style="width:72px" class="text-end">ID</th>
            <th>Username / Email</th>
            <th>ชื่อ - สกุล</th>
            <th style="width:160px" class="text-end">Scans</th>
            <th style="width:110px"></th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $u)
            <tr>
              <td class="text-end">{{ $u->id }}</td>
              <td>
                <div class="fw-semibold">{{ $u->username }}</div>
                <div class="text-muted small">{{ $u->email }}</div>
              </td>
              <td>{{ $u->first_name }} {{ $u->last_name }}</td>
              <td class="text-end fw-bold">{{ $u->scan_histories_count }}</td>
              <td class="text-end">
                <a href="{{ route('admin.users.history.show',$u) }}" class="btn btn-sm btn-outline-secondary rounded-pill">ดูประวัติ</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-4">ไม่พบข้อมูล</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $users->links() }}
    </div>
  </div>
</div>
@endsection


