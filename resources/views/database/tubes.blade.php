@extends('layouts.app')

@section('title', 'Database Tubes')
@section('page-title', 'Database Tubes')

@section('breadcrumb')
    <a href="{{ route('database', absolute: false) }}">Database</a>
    <span class="breadcrumb-separator">/</span>
    <span>Tubes</span>
@endsection

@section('content')
<style>
    .empty-page-card {
        background: #ffffff;
        border: 1px solid #dbe4f2;
        border-radius: 16px;
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.06);
        padding: 2rem;
        min-height: 320px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .empty-page-icon {
        width: 64px;
        height: 64px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        margin-bottom: 1rem;
    }

    .empty-page-icon svg {
        width: 32px;
        height: 32px;
    }

    .empty-page-title {
        color: #0f172a;
        font-size: 1.2rem;
        font-weight: 900;
        letter-spacing: -0.03em;
        margin-bottom: 0.35rem;
    }

    .empty-page-desc {
        color: #64748b;
        font-size: 0.88rem;
        font-weight: 600;
    }
</style>

<div class="empty-page-card">
    <div>
        <div class="empty-page-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M7 4h10" />
                <path d="M7 20h10" />
                <path d="M9 4v16" />
                <path d="M15 4v16" />
                <path d="M9 9h6" />
                <path d="M9 15h6" />
            </svg>
        </div>
        <div class="empty-page-title">Tubes</div>
        <div class="empty-page-desc">Halaman Tubes masih kosong dan akan dikembangkan nanti.</div>
    </div>
</div>
@endsection
