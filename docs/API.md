# Dokumentasi API — portalnews.newsmaker.id

Base URL: `https://portalnews.newsmaker.id/api`

Semua endpoint di bawah ini adalah `GET` dan **wajib** pakai Bearer Token di header:

```
Authorization: Bearer <token>
```

Ada 2 grup middleware dengan token berbeda:

| Middleware | Dipakai di | Header |
|---|---|---|
| `bearer` | Grup "5 PT" (`/v1/berita*`) | `Authorization: Bearer <token PT>` |
| `bearer-newsmaker` | Grup "Newsmaker 23" (`/v1/newsmaker/*`) | `Authorization: Bearer <token Newsmaker>` |

Semua response JSON pakai flag `JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES` (jadi karakter unicode/emoji dan slash `/` tidak di-escape).

---

## 1. Autentikasi

### 1.1 Grup "5 PT" — `bearer` (`app/Http/Middleware/BearerToken.php`)

Token valid (case-sensitive, hardcoded di middleware):

| Token | Client |
|---|---|
| `SGB-c7b0604664fd48d9` | PT. Solid Gold Berjangka |
| `RFB-115886a7f25067f3` | PT. Rifan Financindo Berjangka |
| `KPF-ae8aad2d2303b00f` | PT. Kontak Perkasa Futures |
| `EWF-06433b884f930161` | PT. Equity World Futures |
| `BPF-91e516ac4fe2e8ae` | PT. Best Profit Futures |

Gagal auth → `401 { "error": "Tidak ada akses ke API ini" }`

### 1.2 Grup "Newsmaker 23" — `bearer-newsmaker` (`app/Http/Middleware/BearerNewsmakerToken.php`)

Token diambil dari env `NEWSMAKER_API_TOKENS` (comma-separated, bisa multi-token). Default kalau env kosong: `NM23-8f0f24b4d56af1c3`.

Gagal auth → `401 { "error": "Tidak ada akses ke API Newsmaker ini" }`

---

## 2. Grup "5 PT" (`BeritaController`)

Prefix: `/v1` · Middleware: `bearer`

### GET `/v1/berita`
List semua berita. Sumber data: file cache `storage/app/cache/berita.json` (di-generate lewat command cache, bukan query langsung ke DB).

- Kalau cache belum ada → `503 { "status": "error", "message": "Cache API belum tersedia." }`
- Sukses → mengembalikan isi payload cache apa adanya (biasanya `{ "status": "success", "data": [...] }`)

### GET `/v1/berita/{slug}`
Detail 1 berita by slug, dicari dari array `data` di cache yang sama.

- Cache belum ada → `503`
- Slug tidak ketemu → `404 { "status": "error", "message": "Berita tidak ditemukan." }`
- Sukses → `200 { "status": "success", "data": {...} }`

---

## 3. Grup "Newsmaker 23"

Prefix: `/v1/newsmaker` · Middleware: `bearer-newsmaker`

### 3.1 Berita (`NewsmakerArticleController`)

Sumber data untuk `index` & `byCategory` & `show` = **query langsung ke DB** (tabel `newsmaker_articles`, bukan cache JSON). Hanya `categories()` yang baca cache.

#### GET `/v1/newsmaker/kategori`
List kategori berita. Sumber: cache `storage/app/cache/newsmaker.json` key `categories`.

- Cache belum ada → `503 { "status": "error", "message": "Cache Newsmaker belum tersedia." }`
- Sukses → `200 { "status": "success", "data": [...] }`

#### GET `/v1/newsmaker/berita`
List semua berita, paginated, urut terbaru (`latest()`).

Query params:
| Param | Tipe | Default | Keterangan |
|---|---|---|---|
| `page` | int | 1 | Halaman |

Per page tetap **20**.

Response `data[]` item (list view — tanpa konten penuh, biar payload ringan):
```json
{
  "id": 1,
  "slug": "judul-berita",
  "main_category_id": 2,
  "sub_category_id": null,
  "author_id": 3,
  "image": "path/gambar.jpg",
  "image_url": "https://portalnews.newsmaker.id/path/gambar.jpg",
  "title_id": "Judul Bahasa Indonesia",
  "title_en": "English Title",
  "notif": false,
  "author": "Nama Author",
  "author_initial": "NA",
  "author_user": { "id": 3, "name": "Nama Author" },
  "source": "sumber berita",
  "main_category": { "id": 2, "name": "Ekonomi", "slug": "ekonomi" },
  "created_at": "2026-08-20T10:00:00.000000Z",
  "updated_at": "2026-08-20T10:00:00.000000Z"
}
```

`meta.pagination`: `current_page`, `per_page`, `total`, `last_page`, `from`, `to`, `has_more_pages`, `prev_page_url`, `next_page_url`.

#### GET `/v1/newsmaker/berita/{slug}`
Ambigu secara nama — sebenarnya "list berita **per kategori**", bukan detail 1 berita:

- `{slug}` dicocokkan ke `slug` kategori (`newsmaker_main_categories`).
  - Kalau ketemu kategori → list berita di kategori itu, paginated (sama seperti `/berita` di atas, plus objek `category` di response).
  - Kalau **tidak** ketemu kategori **dan** `{slug}` berupa angka murni → dicoba sebagai `id` artikel, kalau ketemu balikin **detail 1 artikel** (termasuk `content_id`/`content_en`).
  - Kalau tetap tidak ketemu → `404 { "status": "error", "message": "Kategori Newsmaker 23 tidak ditemukan." }`

Response list per kategori:
```json
{
  "status": "success",
  "category": { "id": 2, "name": "Ekonomi", "slug": "ekonomi" },
  "data": [ /* item list sama seperti di atas */ ],
  "meta": { "pagination": { ... } }
}
```

#### GET `/v1/newsmaker/berita/show/{slug}`
Detail 1 berita by slug (regex `[A-Za-z0-9-]+`). Ini endpoint detail yang "benar" — pakai ini kalau butuh 1 artikel lengkap.

- Tidak ketemu → `404 { "status": "error", "message": "Berita Newsmaker 23 tidak ditemukan." }`
- Sukses → `200 { "status": "success", "data": { ...item list, "content_id": "...", "content_en": "..." } }`

---

### 3.2 Kalender Ekonomi (`KalenderController`)

Sumber data: `EconomicCalendarPayloadService` (cache-based).

#### GET `/v1/newsmaker/kalender-ekonomi`
List semua event kalender ekonomi, sudah di-sort by tanggal & jam, paginated (20/halaman).

Query params:
| Param | Tipe | Keterangan |
|---|---|---|
| `page` | int | Halaman (default 1) |

- Cache belum ada → `503 { "status": "error", "message": "Cache kalender belum tersedia." }`
- Sukses → `200 { "status": "success", "data": [...], "meta": { ...buildMeta, "pagination": {...} } }`

#### GET `/v1/newsmaker/kalender-ekonomi/periode`
List semua periode yang tersedia, plus data yang sudah dikelompokkan per periode.

- Sukses → `200 { "status": "success", "data": { "<periode>": [...event...], ... }, "meta": {...} }`

#### GET `/v1/newsmaker/kalender-ekonomi/{period}`
Sama seperti endpoint pertama tapi difilter ke 1 periode (regex `[A-Za-z_-]+`).

- Periode tidak valid → `404 { "status": "error", "message": "Period tidak valid.", "available_periods": [...] }`

---

### 3.3 Historical Data (`PivotController`)

#### GET `/v1/newsmaker/historical-data`
Data pivot historis, langsung dari cache `storage/app/cache/pivot.json`, dikembalikan apa adanya.

- Cache belum ada → `503 { "status": "error", "message": "Cache pivot belum tersedia." }`

---

### 3.4 TikTok (`TiktokController`)

#### GET `/v1/newsmaker/tiktok`
Data embed TikTok dari cache `storage/app/cache/tiktok.json`, dikembalikan apa adanya.

- Cache belum ada → `503 { "status": "error", "message": "Cache TikTok belum tersedia." }`

---

### 3.5 Video Briefing (`NewsmakerVideoBriefingController`)

#### GET `/v1/newsmaker/video-briefing`
Coba baca cache `storage/app/cache/video-briefing.json` dulu; kalau tidak ada, fallback query ke tabel `newsmaker_video_briefings` langsung (lalu hasil query ditulis ulang ke cache).

- Tabel belum ada (migrasi belum jalan) → `503 { "status": "error", "message": "Tabel Video Briefing belum tersedia. Jalankan migrasi terlebih dahulu." }`
- Sukses:
```json
{
  "status": "success",
  "message": "Data Video Briefing berhasil diambil",
  "data": [
    {
      "id": 1,
      "title": "Judul Video",
      "embed_code": "<iframe ...>",
      "backup_video_url": "https://...",
      "image": "path/thumb.jpg",
      "image_url": "https://portalnews.newsmaker.id/path/thumb.jpg",
      "created_at": "...",
      "updated_at": "..."
    }
  ],
  "generated_at": "2026-08-23T00:00:00.000000Z"
}
```

---

### 3.6 Popup Banner (`PopupBannerController`)

#### GET `/v1/newsmaker/popup-banner`
Cache `storage/app/cache/popup-banner.json`, **difilter otomatis** cuma yang aktif saat request (`is_active === true` dan waktu sekarang di antara `start_at`–`end_at`, timezone dari `config('app.timezone')`).

- Cache belum ada → `503 { "status": "error", "message": "Cache popup banner belum tersedia." }`
- Sukses → `200 { "status": "success", "data": [...banner aktif...], "meta": { "total": N, "generated_at": "..." } }`

---

### 3.7 Iklan (`IklanController`)

#### GET `/v1/newsmaker/iklan`
Sama persis pola-nya dengan popup banner: cache `storage/app/cache/iklan.json`, difilter yang `is_active` dan dalam rentang `start_at`–`end_at`.

- Cache belum ada → `503 { "status": "error", "message": "Cache iklan belum tersedia." }`
- Sukses → `200 { "status": "success", "data": [...iklan aktif...], "meta": { "total": N, "generated_at": "..." } }`

---

### 3.8 Pasar Indonesia — Berita & Analisis (`PasarIndonesiaArticleController`)

Prefix: `/v1/newsmaker/pasar-indonesia` · Sumber data: cache `storage/app/cache/pasar-indonesia.json`

#### GET `/v1/newsmaker/pasar-indonesia/kategori`
List kategori (key `categories` di cache).

- Response: `200 { "status": "success", "type": "berita", "data": [...] }`

#### GET `/v1/newsmaker/pasar-indonesia/berita`
List berita, paginated & filterable.

Query params:
| Param | Tipe | Default | Keterangan |
|---|---|---|---|
| `page` | int | 1 | Halaman |
| `per_page` | int | 20 | Maks 100 |
| `category` | string | – | Slug kategori utama, harus ada di `main_categories` |
| `subcategory` | string | – | Slug subkategori, harus ada di `categories` |

- `category`/`subcategory` invalid → `422 { "status": "error", "message": "...", "available_categories"/"available_subcategories": [...] }`
- Sukses → `200 { "status": "success", "type": "berita", "data": [...], "meta": { "filters": {...}, "available_categories": [...], "available_subcategories": [...], "pagination": {...} } }`

#### GET `/v1/newsmaker/pasar-indonesia/berita/{slug}`
Detail 1 berita (regex slug `[A-Za-z0-9-]+`).

- Tidak ketemu → `404 { "status": "error", "message": "Berita Pasar Indonesia tidak ditemukan." }`

#### GET `/v1/newsmaker/pasar-indonesia/analisis`
Sama seperti `/berita` di atas tapi tipe `analisis`. **Catatan:** filter `category`/`subcategory` hanya berlaku untuk tipe `berita` — di endpoint ini filter tsb diabaikan (selalu `null` di response, data tidak difilter).

Query params: `page`, `per_page` (sama seperti di atas).

#### GET `/v1/newsmaker/pasar-indonesia/analisis/{slug}`
Detail 1 analisis (regex slug `[A-Za-z0-9-]+`).

- Tidak ketemu → `404 { "status": "error", "message": "Analisis Pasar Indonesia tidak ditemukan." }`

Semua endpoint di atas: cache belum ada → `503 { "status": "error", "message": "Cache Pasar Indonesia belum tersedia." }`

---

### 3.9 Pasar Indonesia — Regulasi & Institusi (`PasarIndonesiaRegulasiInstitusiArticleController`)

Prefix: `/v1/newsmaker/pasar-indonesia` · Sumber data: cache `storage/app/cache/pasar-indonesia-regulasi-institusi.json`

#### GET `/v1/newsmaker/pasar-indonesia/regulasi-institusi`
List artikel, paginated & filterable by kategori.

Query params:
| Param | Tipe | Default | Keterangan |
|---|---|---|---|
| `page` | int | 1 | Halaman |
| `per_page` | int | 20 | Maks 100 |
| `category` | string | – | Slug kategori, harus ada di `categories` |

- `category` invalid → `422 { "status": "error", "message": "Kategori Regulasi & Institusi tidak valid.", "available_categories": [...] }`
- Sukses → `200 { "status": "success", "type": "regulasi-institusi", "data": [...], "meta": { "filters": {...}, "available_categories": [...], "pagination": {...} } }`

#### GET `/v1/newsmaker/pasar-indonesia/regulasi-institusi/{slug}`
Detail 1 artikel (regex slug `[A-Za-z0-9-]+`).

- Tidak ketemu → `404 { "status": "error", "message": "Artikel Regulasi & Institusi tidak ditemukan." }`

Cache belum ada (kedua endpoint) → `503 { "status": "error", "message": "Cache Regulasi & Institusi belum tersedia." }`

---

## 4. Ringkasan Semua Endpoint

| # | Method | Endpoint | Controller | Sumber Data |
|---|---|---|---|---|
| 1 | GET | `/v1/berita` | BeritaController | Cache `berita.json` |
| 2 | GET | `/v1/berita/{slug}` | BeritaController | Cache `berita.json` |
| 3 | GET | `/v1/newsmaker/kategori` | NewsmakerArticleController | Cache `newsmaker.json` |
| 4 | GET | `/v1/newsmaker/berita` | NewsmakerArticleController | DB (`newsmaker_articles`) |
| 5 | GET | `/v1/newsmaker/berita/{slug}` | NewsmakerArticleController | DB — list per kategori / detail by id |
| 6 | GET | `/v1/newsmaker/berita/show/{slug}` | NewsmakerArticleController | DB — detail by slug |
| 7 | GET | `/v1/newsmaker/kalender-ekonomi` | KalenderController | Cache (via `EconomicCalendarPayloadService`) |
| 8 | GET | `/v1/newsmaker/kalender-ekonomi/periode` | KalenderController | Cache (grouped) |
| 9 | GET | `/v1/newsmaker/kalender-ekonomi/{period}` | KalenderController | Cache (filtered) |
| 10 | GET | `/v1/newsmaker/historical-data` | PivotController | Cache `pivot.json` |
| 11 | GET | `/v1/newsmaker/tiktok` | TiktokController | Cache `tiktok.json` |
| 12 | GET | `/v1/newsmaker/video-briefing` | NewsmakerVideoBriefingController | Cache `video-briefing.json` → fallback DB |
| 13 | GET | `/v1/newsmaker/popup-banner` | PopupBannerController | Cache `popup-banner.json` (filtered aktif) |
| 14 | GET | `/v1/newsmaker/iklan` | IklanController | Cache `iklan.json` (filtered aktif) |
| 15 | GET | `/v1/newsmaker/pasar-indonesia/kategori` | PasarIndonesiaArticleController | Cache `pasar-indonesia.json` |
| 16 | GET | `/v1/newsmaker/pasar-indonesia/berita` | PasarIndonesiaArticleController | Cache `pasar-indonesia.json` |
| 17 | GET | `/v1/newsmaker/pasar-indonesia/berita/{slug}` | PasarIndonesiaArticleController | Cache `pasar-indonesia.json` |
| 18 | GET | `/v1/newsmaker/pasar-indonesia/analisis` | PasarIndonesiaArticleController | Cache `pasar-indonesia.json` |
| 19 | GET | `/v1/newsmaker/pasar-indonesia/analisis/{slug}` | PasarIndonesiaArticleController | Cache `pasar-indonesia.json` |
| 20 | GET | `/v1/newsmaker/pasar-indonesia/regulasi-institusi` | PasarIndonesiaRegulasiInstitusiArticleController | Cache `pasar-indonesia-regulasi-institusi.json` |
| 21 | GET | `/v1/newsmaker/pasar-indonesia/regulasi-institusi/{slug}` | PasarIndonesiaRegulasiInstitusiArticleController | Cache `pasar-indonesia-regulasi-institusi.json` |

**Total: 21 endpoint** (2 di grup "5 PT" + 19 di grup "Newsmaker 23").

---

## 5. Catatan Teknis

- Endpoint yang sumbernya **cache JSON** (`storage/app/cache/*.json`) butuh command Artisan cache-generator dijalankan dulu (biasanya via scheduler) — kalau belum pernah jalan atau file cache hilang, semua endpoint itu balikin `503`.
- Endpoint `/v1/newsmaker/berita*` (kecuali `/kategori`) sudah **migrasi ke query DB langsung** (lihat commit `c3bf82c`), jadi selalu real-time — beda dari endpoint cache-based lainnya.
- Ada typo kecil di `routes/api.php` baris 30: `ROute::get` (huruf besar semua) — tetap jalan karena PHP case-insensitive untuk nama class, tapi sebaiknya dirapikan ke `Route::get`.
- Semua pesan error pakai Bahasa Indonesia dan format konsisten `{ "status": "error", "message": "..." }` kecuali error auth yang formatnya `{ "error": "..." }` (beda struktur, dari middleware bukan controller).
