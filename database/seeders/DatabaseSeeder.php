<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\CampaignUpdate;
use App\Models\FundReport;
use App\Models\FundReportPhoto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Default Users
        $admin = User::create([
            'name' => 'Admin Peduli',
            'email' => 'admin@peduli.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $campaigner = User::create([
            'name' => 'Yayasan Peduli Sesama',
            'email' => 'campaigner@peduli.com',
            'password' => Hash::make('password'),
            'role' => 'campaigner',
        ]);

        $donator = User::create([
            'name' => 'Budi Santoso',
            'email' => 'donator@peduli.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // 2. Seed Default Categories
        $categoriesData = [
            ['name' => 'Bencana Alam', 'slug' => 'bencana-alam', 'description' => 'Bantuan untuk korban bencana alam seperti banjir, gempa bumi, tsunami, dll.'],
            ['name' => 'Kesehatan', 'slug' => 'kesehatan', 'description' => 'Bantuan biaya pengobatan medis, operasi, dan alat kesehatan.'],
            ['name' => 'Pendidikan', 'slug' => 'pendidikan', 'description' => 'Bantuan beasiswa, renovasi sekolah, buku, dan alat tulis anak sekolah.'],
            ['name' => 'Panti Asuhan', 'slug' => 'panti-asuhan', 'description' => 'Santunan dan kebutuhan pokok panti asuhan anak, lansia, dan disabilitas.'],
            ['name' => 'Kemanusiaan', 'slug' => 'kemanusiaan', 'description' => 'Program sosial kemasyarakatan, pembangunan sanitasi, air bersih, dll.'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::create($cat);
        }

        // 3. Seed Campaigns
        // Campaign 1: Kesehatan (Active)
        $campaign1 = Campaign::create([
            'user_id' => $campaigner->id,
            'category_id' => $categories['kesehatan']->id,
            'title' => 'Bantuan Medis Untuk Pengobatan Kanker Adik Sinta',
            'slug' => 'bantuan-medis-untuk-pengobatan-kanker-adik-sinta',
            'description' => 'Adik Sinta (6 tahun) saat ini sedang berjuang melawan kanker darah (leukemia) stadium lanjut. Biaya kemoterapi dan perawatan intensif sangat besar dan di luar kemampuan keluarganya. Mari kita bantu ringankan beban pengobatan Adik Sinta agar ia bisa kembali ceria dan menatap masa depan.',
            'target_amount' => 50000000.00,
            'current_amount' => 12500000.00,
            'end_date' => now()->addDays(30),
            'status' => 'active',
            'image_path' => 'campaigns/sinta_cancer_campaign.jpg',
        ]);

        // Campaign 2: Bencana Alam (Active)
        $campaign2 = Campaign::create([
            'user_id' => $campaigner->id,
            'category_id' => $categories['bencana-alam']->id,
            'title' => 'Tanggap Darurat Banjir Bandang Luwu',
            'slug' => 'tanggap-darurat-banjir-bandang-luwu',
            'description' => 'Curah hujan yang tinggi menyebabkan banjir bandang di wilayah Luwu. Banyak warga kehilangan rumah, pakaian, dan akses air bersih. Kami menggalang dana darurat untuk menyediakan makanan siap saji, selimut, pakaian layak pakai, obat-obatan, serta air bersih bagi para pengungsi.',
            'target_amount' => 100000000.00,
            'current_amount' => 45000000.00,
            'end_date' => now()->addDays(15),
            'status' => 'active',
            'image_path' => 'campaigns/luwu_flood_campaign.jpg',
        ]);

        // Campaign 3: Pendidikan (Completed)
        $campaign3 = Campaign::create([
            'user_id' => $admin->id,
            'category_id' => $categories['pendidikan']->id,
            'title' => 'Beasiswa Pendidikan Anak Pelosok Papua',
            'slug' => 'beasiswa-pendidikan-anak-pelosok-papua',
            'description' => 'Program beasiswa ini ditujukan untuk membiayai sekolah dasar dan menengah anak-anak kurang mampu di pedalaman Papua. Bantuan meliputi seragam, buku, alat tulis, dan SPP bulanan sekolah mereka. Terima kasih atas kepedulian Anda yang luar biasa.',
            'target_amount' => 30000000.00,
            'current_amount' => 30000000.00,
            'end_date' => now()->subDays(2),
            'status' => 'completed',
            'image_path' => 'campaigns/papua_education_campaign.jpg',
        ]);

        // 4. Seed Donations
        // Donations for Campaign 1 (Sinta)
        Donation::create([
            'campaign_id' => $campaign1->id,
            'user_id' => $donator->id,
            'donor_name' => $donator->name,
            'amount' => 1000000.00,
            'status' => 'success',
            'payment_method' => 'transfer',
            'payment_proof' => 'proofs/donation1.png',
            'notes' => 'Lekas sembuh ya adik Sinta! Kami sekeluarga mendoakanmu.',
            'is_anonymous' => false,
        ]);

        Donation::create([
            'campaign_id' => $campaign1->id,
            'user_id' => null,
            'donor_name' => null,
            'amount' => 500000.00,
            'status' => 'success',
            'payment_method' => 'qris',
            'payment_proof' => null,
            'notes' => 'Semoga lekas sembuh, amin.',
            'is_anonymous' => true,
        ]);

        Donation::create([
            'campaign_id' => $campaign1->id,
            'user_id' => null,
            'donor_name' => 'Susi Susanti',
            'amount' => 11000000.00,
            'status' => 'success',
            'payment_method' => 'transfer',
            'payment_proof' => 'proofs/donation2.png',
            'notes' => 'Semoga membantu meringankan biaya kemoterapi.',
            'is_anonymous' => false,
        ]);

        Donation::create([
            'campaign_id' => $campaign1->id,
            'user_id' => null,
            'donor_name' => null,
            'amount' => 100000.00,
            'status' => 'pending',
            'payment_method' => 'qris',
            'payment_proof' => null,
            'notes' => null,
            'is_anonymous' => true,
        ]);

        // Donations for Campaign 2 (Luwu Flood)
        Donation::create([
            'campaign_id' => $campaign2->id,
            'user_id' => $donator->id,
            'donor_name' => $donator->name,
            'amount' => 5000000.00,
            'status' => 'success',
            'payment_method' => 'transfer',
            'payment_proof' => 'proofs/donation3.png',
            'notes' => 'Semoga bantuan ini meringankan duka saudara-saudara kita di Luwu.',
            'is_anonymous' => false,
        ]);

        Donation::create([
            'campaign_id' => $campaign2->id,
            'user_id' => null,
            'donor_name' => 'PT Berkah Sejahtera',
            'amount' => 40000000.00,
            'status' => 'success',
            'payment_method' => 'transfer',
            'payment_proof' => 'proofs/donation4.png',
            'notes' => 'Donasi atas nama perusahaan untuk korban bencana banjir bandang.',
            'is_anonymous' => false,
        ]);

        // Donations for Campaign 3 (Papua)
        Donation::create([
            'campaign_id' => $campaign3->id,
            'user_id' => null,
            'donor_name' => null,
            'amount' => 30000000.00,
            'status' => 'success',
            'payment_method' => 'transfer',
            'payment_proof' => 'proofs/donation5.png',
            'notes' => 'Semoga anak-anak Papua dapat meraih cita-cita mereka.',
            'is_anonymous' => true,
        ]);

        // 5. Seed Campaign Updates
        CampaignUpdate::create([
            'campaign_id' => $campaign1->id,
            'title' => 'Pencairan Tahap Pertama untuk Biaya Kemoterapi',
            'content' => 'Halo para donatur, hari ini kami telah melakukan pencairan tahap pertama sebesar Rp 5.000.000. Dana ini langsung diserahkan kepada pihak rumah sakit untuk menutupi biaya kemoterapi sesi kedua Adik Sinta. Terima kasih banyak atas kedermawanan Anda.',
        ]);

        CampaignUpdate::create([
            'campaign_id' => $campaign1->id,
            'title' => 'Sinta Selesai Menjalani Kemoterapi Sesi Kedua',
            'content' => 'Sinta telah selesai menjalani rangkaian kemoterapi sesi kedua. Kondisinya saat ini masih lemas, namun menunjukkan tanda-tanda perkembangan positif. Dokter menyarankan untuk istirahat total selama seminggu sebelum memulai sesi berikutnya.',
        ]);

        // 6. Seed Fund Reports & Photos
        $report1 = FundReport::create([
            'campaign_id' => $campaign1->id,
            'amount_spent' => 5000000.00,
            'purpose' => 'Biaya Kemoterapi Sesi Ke-2 Sinta',
            'description' => 'Pembayaran lunas untuk tagihan obat-obatan kemoterapi dan sewa kamar perawatan intensif Adik Sinta di RS Harapan Bangsa.',
            'report_date' => now()->subDays(5),
        ]);

        FundReportPhoto::create([
            'fund_report_id' => $report1->id,
            'photo_path' => 'receipts/kemoterapi_sinta_receipt.jpg',
            'caption' => 'Kuitansi pembayaran rumah sakit sebesar Rp 5.000.000',
        ]);
    }
}
