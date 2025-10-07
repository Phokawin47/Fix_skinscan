@extends('layouts.app')

@section('title', 'Scan History • SkinScan')

@push('styles')
  <style>
    /* Scoped styles for Scan History, following existing theme */
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

@section('content')
    <section class="features">
        <div class="container">
            <div class="section-header">
                <h2>ประวัติการสแกนของคุณ</h2>
                <p>ดูผลการวิเคราะห์ผิวหน้าที่คุณเคยสแกนไว้</p>
            </div>

            @if($scans->count() === 0)
                <div class="feature-card" style="text-align:center;">
                    <h3>ยังไม่มีประวัติการสแกน</h3>
                    <p>เริ่มสแกนครั้งแรกของคุณได้เลย</p>
                    <a href="{{ route('facescan.idx') }}" class="btn-primary">
                        <i class="fas fa-camera"></i>
                        <span>Start Your Scan</span>
                    </a>
                </div>
            @else
                <div class="features-grid">
                    @foreach($scans as $scan)
                        <?php $skinName = optional($scan->skinType)->skin_type_name ?? '—'; ?>
                        <div class="feature-card scan-card">
                            <div class="scan-card-header">
                                <h3 style="margin:0;">สแกนเมื่อ {{ \Carbon\Carbon::parse($scan->scan_timestamp)->format('d M Y H:i') }}</h3>
                                <span class="skin-badge">{{ $skinName }}</span>
                            </div>
                            @if($scan->result_image_path)
                                <?php
                                    $rawPath = trim((string) $scan->result_image_path);
                                    $resolvedUrl = null;

                                    // 1) Absolute URL
                                    if ($rawPath !== '' && (str_starts_with($rawPath, 'http://') || str_starts_with($rawPath, 'https://'))) {
                                        $resolvedUrl = $rawPath;
                                    }

                                    // 2) If begins with storage/ already (e.g., storage/scan_results/foo.jpg)
                                    if (!$resolvedUrl && (str_starts_with($rawPath, 'storage/') || str_starts_with($rawPath, '/storage/'))) {
                                        $clean = ltrim($rawPath, '/');
                                        $full = public_path($clean);
                                        if (file_exists($full)) {
                                            $resolvedUrl = asset($clean);
                                        }
                                    }

                                    // 3) Try Laravel public disk (expects paths like: scan_results/foo.jpg)
                                    if (!$resolvedUrl) {
                                        try {
                                            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($rawPath)) {
                                                $resolvedUrl = \Illuminate\Support\Facades\Storage::url($rawPath); // -> /storage/{path}
                                            }
                                        } catch (\Throwable $e) {
                                            // ignore and try next fallback
                                        }
                                    }

                                    // 4) If filename-only or relative (e.g., foo.jpg or scan_results/foo.jpg) placed under public/storage/...
                                    if (!$resolvedUrl) {
                                        $underStorage = 'storage/' . ltrim($rawPath, '/');
                                        $full = public_path($underStorage);
                                        if (file_exists($full)) {
                                            $resolvedUrl = asset($underStorage);
                                        }
                                    }

                                    // 5) Final fallback: directly under public root
                                    if (!$resolvedUrl) {
                                        $full = public_path($rawPath);
                                        if (file_exists($full)) {
                                            $resolvedUrl = asset($rawPath);
                                        }
                                    }
                                ?>
                                <div class="scan-image-wrap">
                                    @if($resolvedUrl)
                                        <img src="{{ $resolvedUrl }}" alt="Scan result" />
                                    @endif
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
                    @endforeach
                </div>

                <div style="margin-top:16px;">
                    {{ $scans->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection


