@extends('layouts.app')
@section('title','User Scan History')

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Match user scan history card styling */
    .scan-card { transition: box-shadow .2s ease, transform .2s ease; }
    .scan-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.06); }
    .scan-card-header { display:flex; align-items:center; justify-content:space-between; gap:8px; margin-bottom:.25rem; }
    .skin-badge { background:#f3f4f6; color:#111; padding:.25rem .5rem; border-radius:999px; font-weight:600; font-size:.85rem; white-space:nowrap; }
    .scan-image-wrap { margin:.5rem 0 .75rem; aspect-ratio: 4 / 3; overflow:hidden; border-radius:12px; background:#f8fafc; }
    .scan-image-wrap img { width:100%; height:100%; object-fit:cover; display:block; }
    .scan-tags { display:flex; flex-wrap:wrap; gap:.4rem; margin:.25rem 0 .5rem; }
    .chip { padding:.25rem .6rem; border-radius:999px; background:#eef2ff; color:#3730a3; font-weight:600; font-size:.8rem; }
    .chip-more { background:#e5e7eb; color:#111827; }
    .scan-meta { display:flex; justify-content:space-between; color:#6b7280; font-size:.85rem; margin-top:.25rem; }
  </style>
@endpush
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush

@section('content')
<div class="container py-4" style="max-width:1100px;">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h5 mb-0">ประวัติการสแกน: {{ $user->first_name }} {{ $user->last_name }} ({{ $user->username }})</h1>
    <a href="{{ route('admin.users.history.index') }}" class="btn btn-outline-secondary btn-sm">ย้อนกลับ</a>
  </div>

  @if($scans->count()===0)
    <div class="alert alert-info mb-0">ยังไม่มีประวัติการสแกน</div>
  @else
    <div class="row g-3">
      @foreach($scans as $scan)
        <div class="col-md-6">
          <div class="card h-100 scan-card">
            <div class="card-body">
              <?php $skinName = optional($scan->skinType)->skin_type_name ?? '—'; ?>
              <div class="scan-card-header">
                <div class="fw-semibold">สแกนเมื่อ {{ \Carbon\Carbon::parse($scan->scan_timestamp)->format('d M Y H:i') }}</div>
                <span class="skin-badge">{{ $skinName }}</span>
              </div>

              @php
                $rawPath = trim((string) $scan->result_image_path);
                $url = null;
                if ($rawPath !== '' && (str_starts_with($rawPath,'http://')||str_starts_with($rawPath,'https://'))) {
                  $url = $rawPath;
                }
                if (!$url && (str_starts_with($rawPath,'storage/')||str_starts_with($rawPath,'/storage/'))){
                  $clean = ltrim($rawPath,'/');
                  if (file_exists(public_path($clean))) $url = asset($clean);
                }
                if (!$url){
                  try{ if (\Illuminate\Support\Facades\Storage::disk('public')->exists($rawPath)) $url = \Illuminate\Support\Facades\Storage::url($rawPath); }catch(\Throwable $e){}
                }
                if (!$url){
                  $under = 'storage/'.ltrim($rawPath,'/');
                  if (file_exists(public_path($under))) $url = asset($under);
                }
                if (!$url && file_exists(public_path($rawPath))) $url = asset($rawPath);
              @endphp

              @if($url)
                <div class="scan-image-wrap">
                  <img src="{{ $url }}" alt="Scan result">
                </div>
              @endif

              @if($scan->results && $scan->results->count())
                <?php
                  $total = $scan->results->count();
                  $shown = $scan->results->take(3);
                  $more = $total - $shown->count();
                ?>
                <div class="scan-tags">
                  @foreach($shown as $res)
                    <span class="chip">{{ $res->acneType->acne_type_name ?? $res->class_name ?? 'ไม่ระบุ' }}</span>
                  @endforeach
                  @if($more > 0)
                    <span class="chip chip-more">+{{ $more }}</span>
                  @endif
                </div>
                <div class="scan-meta">
                  <span>ประเภทสิวทั้งหมด: <strong>{{ $total }}</strong></span>
                  <span>ประเภทผิว: <strong>{{ $skinName }}</strong></span>
                </div>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <div class="mt-3">{{ $scans->links() }}</div>
  @endif
</div>
@endsection


