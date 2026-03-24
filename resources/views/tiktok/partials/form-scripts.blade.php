@php
    $previewSource = old('embed_code', data_get($tiktok ?? null, 'embed_code', ''));
@endphp

<script>
    function openModal(id) {
        const modal = document.getElementById(id);

        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);

        if (modal) {
            modal.classList.add('hidden');
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('[data-modal]').forEach(function(modal) {
                modal.classList.add('hidden');
            });
        }
    });

    document.querySelectorAll('[data-modal]').forEach(function(modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });

    const embedTextarea = document.getElementById('embed_code');
    const previewFrame = document.getElementById('embedPreviewFrame');
    const previewEmpty = document.getElementById('embedPreviewEmpty');

    function buildPreviewDocument(embedCode) {
        const safeEmbedCode = embedCode.replace(/<\/script>/gi, '<\\/script>');

        return `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            padding: 16px;
            font-family: Inter, system-ui, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
        }

        .preview-shell {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100%;
        }

        blockquote {
            max-width: 100% !important;
        }
    </style>
</head>
<body>
    <div class="preview-shell">${safeEmbedCode}</div>
    <script async src="https://www.tiktok.com/embed.js"><\/script>
</body>
</html>`;
    }

    function renderEmbedPreview(embedCode) {
        const trimmedEmbedCode = embedCode.trim();

        if (!previewFrame || !previewEmpty) {
            return;
        }

        if (trimmedEmbedCode === '') {
            previewFrame.classList.add('hidden');
            previewFrame.srcdoc = '';
            previewEmpty.classList.remove('hidden');
            return;
        }

        previewEmpty.classList.add('hidden');
        previewFrame.classList.remove('hidden');
        previewFrame.srcdoc = buildPreviewDocument(trimmedEmbedCode);
    }

    embedTextarea?.addEventListener('input', function(event) {
        renderEmbedPreview(event.target.value);
    });

    renderEmbedPreview(@json($previewSource));
</script>
