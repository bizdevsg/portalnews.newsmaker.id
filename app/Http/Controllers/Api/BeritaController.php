<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::select([
            'id',
            'title',
            'title_sg',
            'title_rfb',
            'title_kpf',
            'title_ewf',
            'title_bpf',
            'slug',
            'content',
            'image1',
            'image2',
            'image3',
            'image4',
            'image5',
            'image6',
            'category_id',
            'created_at',
            'updated_at'
        ])
            ->with(['category:id,name,slug'])
            ->get()
            ->transform(function ($berita) {
                return [
                    'id'         => $berita->id,
                    'title'      => $berita->title,
                    'titles'     => [
                        'default' => $berita->title,
                        'sg'      => $berita->title_sg ?? $berita->title,
                        'rfb'     => $berita->title_rfb ?? $berita->title,
                        'kpf'     => $berita->title_kpf ?? $berita->title,
                        'ewf'     => $berita->title_ewf ?? $berita->title,
                        'bpf'     => $berita->title_bpf ?? $berita->title,
                    ],
                    'slug'       => $berita->slug,
                    'content'    => $berita->content, // HTML asli
                    'category_id' => $berita->category_id,
                    'kategori'   => $berita->category,
                    'images'     => $berita->images, // accessor
                    'created_at' => $berita->created_at,
                    'updated_at' => $berita->updated_at,
                ];
            });

        return response()->json(
            [
                'status' => 'success',
                'data'   => $beritas
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES // <- tambahan penting
        );
    }

    public function show($slug)
    {
        $berita = Berita::select([
            'id',
            'title',
            'title_sg',
            'title_rfb',
            'title_kpf',
            'title_ewf',
            'title_bpf',
            'slug',
            'content',
            'image1',
            'image2',
            'image3',
            'image4',
            'image5',
            'image6',
            'category_id',
            'created_at',
            'updated_at'
        ])
            ->with(['category:id,name'])
            ->where('slug', $slug)
            ->first();

        if (!$berita) {
            return response()->json(
                [
                    'status'  => 'error',
                    'message' => 'Berita tidak ditemukan.'
                ],
                404,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        $data = [
            'id'         => $berita->id,
            'title'      => $berita->title,
            'titles'     => [
                'default' => $berita->title,
                'sg'      => $berita->title_sg ?? $berita->title,
                'rfb'     => $berita->title_rfb ?? $berita->title,
                'kpf'     => $berita->title_kpf ?? $berita->title,
                'ewf'     => $berita->title_ewf ?? $berita->title,
                'bpf'     => $berita->title_bpf ?? $berita->title,
                'backup'  => $berita->title_backup ?? $berita->title,
            ],
            'slug'       => $berita->slug,
            'content'    => $berita->content, // HTML asli
            'category_id' => $berita->category_id,
            'kategori'   => $berita->category,
            'images'     => $berita->images,
            'created_at' => $berita->created_at,
            'updated_at' => $berita->updated_at,
        ];

        return response()->json(
            [
                'status' => 'success',
                'data'   => $data
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }
}
