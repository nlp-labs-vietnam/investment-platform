<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WikiController extends Controller
{
    public function nlpLabsVietnam()
    {
        $pageSeo = [
            'page_title'       => 'NLP Labs Vietnam — Wiki',
            'meta_title'       => 'NLP Labs Vietnam | Trung tâm Nghiên cứu & Phát triển Năng lượng Mặt trời',
            'meta_keywords'    => 'NLP Labs, NLP Group, nghiên cứu năng lượng mặt trời, R&D solar Vietnam, quang điện Việt Nam',
            'meta_description' => 'Tổng quan về NLP Labs Vietnam — bộ phận nghiên cứu và phát triển công nghệ quang điện của NLP Group, hoạt động tại Việt Nam.',
            'og_description'   => 'NLP Labs Vietnam là đơn vị R&D của NLP Group, chuyên nghiên cứu ứng dụng công nghệ điện mặt trời và năng lượng tái tạo tại Việt Nam.',
            'meta_robots'      => 'index, follow',
            'meta_image'       => null,
        ];

        return view('wiki.nlp-labs-vietnam', compact('pageSeo'));
    }
}
