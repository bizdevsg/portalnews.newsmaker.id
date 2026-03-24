<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Source+Serif+4:wght@400;600&display=swap" rel="stylesheet">
<style>
    .nm23 {
        --nm-ink: #0f172a;
        --nm-ink-soft: #475569;
        --nm-ink-muted: #94a3b8;
        --nm-surface: #ffffff;
        --nm-surface-soft: #f8fafc;
        --nm-border: #e2e8f0;
        --nm-accent: #2563eb;
        --nm-accent-2: #14b8a6;
        --nm-accent-3: #f59e0b;
        --nm-danger: #ef4444;
        --nm-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        font-family: "Space Grotesk", system-ui, -apple-system, sans-serif;
        color: var(--nm-ink);
    }

    .dark .nm23 {
        --nm-ink: #e2e8f0;
        --nm-ink-soft: #cbd5f5;
        --nm-ink-muted: #94a3b8;
        --nm-surface: #0f172a;
        --nm-surface-soft: #111827;
        --nm-border: #1e293b;
        --nm-shadow: 0 20px 40px rgba(15, 23, 42, 0.45);
    }

    .nm-title {
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .nm-hero {
        background: radial-gradient(circle at top left, rgba(37, 99, 235, 0.18), transparent 55%),
            radial-gradient(circle at bottom right, rgba(20, 184, 166, 0.2), transparent 45%),
            linear-gradient(135deg, #0f172a, #1e293b 60%, #1e40af);
        color: #f8fafc;
        border-radius: 28px;
        padding: 28px;
        box-shadow: var(--nm-shadow);
    }

    .nm-card {
        background: var(--nm-surface);
        border: 1px solid var(--nm-border);
        border-radius: 20px;
        box-shadow: 0 16px 30px rgba(15, 23, 42, 0.06);
    }

    .nm-panel {
        background: var(--nm-surface-soft);
        border: 1px solid var(--nm-border);
        border-radius: 18px;
    }

    .nm-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .nm-btn-primary {
        background: var(--nm-accent);
        color: #fff;
    }

    .nm-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 20px rgba(37, 99, 235, 0.25);
    }

    .nm-btn-ghost {
        background: transparent;
        border-color: var(--nm-border);
        color: var(--nm-ink);
    }

    .nm-btn-ghost:hover {
        background: rgba(148, 163, 184, 0.12);
    }

    .nm-btn-success {
        background: #16a34a;
        color: #fff;
    }

    .nm-btn-danger {
        background: var(--nm-danger);
        color: #fff;
    }

    .nm-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        background: rgba(255, 255, 255, 0.14);
        color: #f8fafc;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .nm-field label {
        font-weight: 600;
        color: var(--nm-ink-soft);
    }

    .nm-input,
    .nm-textarea,
    .nm-select {
        width: 100%;
        border-radius: 12px;
        border: 1px solid var(--nm-border);
        padding: 12px 14px;
        background: var(--nm-surface);
        color: var(--nm-ink);
        transition: border 0.2s ease, box-shadow 0.2s ease;
    }

    .nm-input:focus,
    .nm-textarea:focus,
    .nm-select:focus {
        outline: none;
        border-color: rgba(37, 99, 235, 0.7);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .nm-muted {
        color: var(--nm-ink-muted);
        font-size: 13px;
    }

    .nm-grid {
        display: grid;
        gap: 20px;
    }

    .nm-kicker {
        text-transform: uppercase;
        letter-spacing: 0.3em;
        font-size: 11px;
        color: rgba(248, 250, 252, 0.7);
    }

    .nm-serifs {
        font-family: "Source Serif 4", serif;
    }
</style>