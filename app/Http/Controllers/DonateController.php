<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DonateController extends Controller
{
    public function index()
    {
        $pageSeo = [
            'page_title'       => 'Ủng hộ — Donate',
            'meta_title'       => 'Donate | Ủng hộ NLP Group',
            'meta_keywords'    => 'donate, ủng hộ, đóng góp, NLP Group, năng lượng tái tạo, solar Vietnam',
            'meta_description' => 'Đóng góp và ủng hộ sứ mệnh phát triển năng lượng tái tạo của NLP Group tại Việt Nam.',
            'og_description'   => 'Hỗ trợ NLP Group trong hành trình mang năng lượng sạch đến mọi miền Việt Nam.',
            'meta_robots'      => 'index, follow',
            'meta_image'       => null,
        ];

        $bankAccounts = [
            [
                'bank'    => 'MB Bank',
                'logo'    => 'https://nlpgroup.vn/exports/MB-Bank_Thường_ios.ipa',
                'number'  => '0356789012',
                'name'    => 'CONG TY TNHH NLP GROUP',
                'branch'  => 'Chi nhánh Hà Nội',
                'qr'      => null,
            ],
            [
                'bank'    => 'Vietcombank',
                'logo'    => null,
                'number'  => '1234567890',
                'name'    => 'CONG TY TNHH NLP GROUP',
                'branch'  => 'Chi nhánh TP.HCM',
                'qr'      => null,
            ],
        ];

        $cryptoWallets = [
            [
                'name'    => 'USDT (TRC-20)',
                'address' => 'TNPx6789abcdefghijklmnopqrstuvwxyz123',
                'icon'    => 'fab fa-bitcoin',
            ],
            [
                'name'    => 'ETH (ERC-20)',
                'address' => '0xABCDEF1234567890abcdef1234567890ABCDEF12',
                'icon'    => 'fab fa-ethereum',
            ],
        ];

        return view('donate.index', compact('pageSeo', 'bankAccounts', 'cryptoWallets'));
    }
}
