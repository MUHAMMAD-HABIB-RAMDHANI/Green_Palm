<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BeritaController extends Controller
{
    protected function articles()
    {
        return [
            'petani-muda-tingkatkan-kualitas' => [
                'title'   => 'GreenPalm Dukung Petani Muda Tingkatkan Kualitas Sawit',
                'image'   => 'images/petani-muda.png',
                'date'    => '2025-08-20',
                'excerpt' => 'Semangat baru tumbuh di kalangan generasi muda untuk mengelola perkebunan sawit dengan teknologi.',
                'content' => "Semangat baru tumbuh di kalangan generasi muda untuk mengelola perkebunan sawit dengan teknologi modern. GreenPalm hadir sebagai mitra digital yang membantu petani muda mencatat aktivitas kebun, memantau pertumbuhan tanaman, dan mengakses informasi harga secara real-time.

Melalui pelatihan dan pendampingan yang rutin dilakukan, banyak petani muda mulai beralih dari metode pencatatan manual ke sistem digital yang lebih efisien. Hal ini tidak hanya mempercepat proses administrasi kebun, tetapi juga membantu mereka mengambil keputusan yang lebih tepat terkait waktu panen dan penjualan.

GreenPalm berkomitmen untuk terus mengembangkan fitur-fitur yang relevan dengan kebutuhan generasi petani baru, sekaligus menjembatani kesenjangan teknologi antara petani senior dan junior di sektor perkebunan sawit.",
            ],
            'harga-sawit-stabil-bengkalis' => [
                'title'   => 'Harga Sawit Stabil, Petani Bengkalis Sumringah',
                'image'   => 'images/perkebunan.png',
                'date'    => '2025-08-15',
                'excerpt' => 'Dinas Perkebunan Bengkalis menegaskan bahwa harga jual tandan buah segar (TBS) kelapa sawit tetap stabil.',
                'content' => "Dinas Perkebunan Bengkalis menegaskan bahwa harga jual tandan buah segar (TBS) kelapa sawit tetap stabil dalam beberapa bulan terakhir, memberikan dampak positif bagi kesejahteraan petani lokal.

Stabilitas harga ini didukung oleh permintaan pasar yang konsisten serta kebijakan pemerintah daerah yang berupaya menjaga keseimbangan antara harga di tingkat petani dan pabrik pengolahan kelapa sawit (PKS).

Para petani berharap tren positif ini dapat terus berlanjut, sehingga mereka bisa merencanakan pengeluaran dan investasi kebun dengan lebih baik ke depannya.",
            ],
            'potensi-pasar-eropa' => [
                'title'   => 'Potensi Pasar Sawit Indonesia di Eropa',
                'image'   => 'images/pasar-eropa.png',
                'date'    => '2025-08-10',
                'excerpt' => 'Pemerintah menilai potensi pasar sawit Indonesia di Eropa semakin terbuka lebar dengan standar baru.',
                'content' => "Pemerintah menilai potensi pasar sawit Indonesia di Eropa semakin terbuka lebar seiring dengan penerapan standar keberlanjutan baru yang mulai diadopsi oleh para eksportir dalam negeri.

Sejumlah perusahaan sawit nasional kini mulai menyesuaikan proses produksi mereka agar memenuhi sertifikasi keberlanjutan internasional, yang menjadi salah satu syarat utama untuk menembus pasar Eropa.

Langkah ini diharapkan dapat meningkatkan daya saing produk sawit Indonesia sekaligus membuka peluang ekonomi baru bagi petani dan pelaku industri di seluruh rantai pasok.",
            ],
            'tantangan-regulasi-baru' => [
                'title'   => 'Tantangan Regulasi Baru Industri Sawit',
                'image'   => 'images/industri-sawit.png',
                'date'    => '2025-08-05',
                'excerpt' => 'Pelaku industri sawit mulai mendiskusikan dampak dari regulasi denda terbaru yang diterapkan.',
                'content' => "Pelaku industri sawit mulai mendiskusikan dampak dari regulasi denda terbaru yang diterapkan pemerintah terkait pengelolaan lingkungan dan tata kelola perkebunan.

Beberapa asosiasi petani dan pengusaha sawit menilai regulasi ini penting untuk menjaga keberlanjutan industri, namun juga meminta adanya masa transisi yang cukup agar pelaku usaha kecil dapat menyesuaikan diri tanpa terbebani secara finansial.

Diskusi lanjutan antara pemerintah dan pemangku kepentingan industri terus dilakukan untuk mencari titik keseimbangan antara kepatuhan regulasi dan keberlangsungan usaha petani sawit.",
            ],
        ];
    }

    public function show($slug)
    {
        $articles = $this->articles();

        abort_unless(isset($articles[$slug]), 404);

        $article = $articles[$slug];
        $article['slug'] = $slug;

        $related = collect($articles)
            ->except($slug)
            ->take(3);

        return view('berita.show', compact('article', 'related'));
    }
}