<?php
return [
    'light' => [
        'hero' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'another_sub_title' => 'text',
                    'short_description' => 'textarea',
                    'button_name' => 'text',
                    'image' => 'file',
                    'image2' => 'file',
                    'image3' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_description.*' => 'required|max:2000',
                    'button_name.*' => 'required|max:2000',
                    'background_image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                    'image1.*' => 'nullable|max:10000|image|mimes:jpg,jpeg,png',
                    'image2.*' => 'nullable|max:10000|image|mimes:jpg,jpeg,png',
                    'image3.*' => 'nullable|max:10000|image|mimes:jpg,jpeg,png',
                ]
            ],
            'preview' => 'assets/themes/light/preview/hero.png'
        ],
        'feature' => [
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'short_details' => 'text',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_details.*' => 'required|max:500',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ],
                'size' => [
                    'image' => '64x64'
                ]
            ],
            'preview' => 'assets/themes/light/preview/feature.png'
        ],
        'about' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_title' => 'text',
                    'short_description' => 'textarea',
                    'image' => 'file',
                    'image2' => 'file'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                    'short_description.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:10000|image|mimes:jpg,jpeg,png',
                    'image2.*' => 'nullable|max:10000|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '1279x1279',
                    'image2' => '1279x1279',
                ]
            ],
            'preview' => 'assets/themes/light/preview/about.png'
        ],
        'property' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                ],
            ],
            'preview' => 'assets/themes/light/preview/property.png'
        ],
        'testimonial' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'image' => 'file'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'designation' => 'text',
                    'description' => 'textarea',
                ],
                'validation' => [
                    'name.*' => 'required|max:100',
                    'designation.*' => 'required|max:2000',
                    'description.*' => 'required|max:2000',
                ]
            ],
            'preview' => 'assets/themes/light/preview/testimonial.png'
        ],
        'latest_property' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                ],
            ],
            'preview' => 'assets/themes/light/preview/latest_property.png'
        ],
        'statistics' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required',
                ],
            ],
            'preview' => 'assets/themes/light/preview/statistics.png'
        ],
        'faq' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_details' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                    'short_details.*' => 'required|max:2000'
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:190',
                    'description.*' => 'required|max:3000'
                ]
            ],
            'preview' => 'assets/themes/light/preview/faq.png'
        ],
        'contact' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'sub_heading' => 'text',
                    'title' => 'text',
                    'address' => 'text',
                    'email' => 'text',
                    'phone' => 'text',
                    'footer_short_details' => 'textarea',
                    'subscriber_message' => 'textarea',
                    'image' => 'file'
                ],
                'validation' => [
                    'heading.*' => 'required|max:100',
                    'sub_heading.*' => 'required|max:100',
                    'title.*' => 'required|max:100',
                    'address.*' => 'required|max:2000',
                    'email.*' => 'required|max:2000',
                    'phone.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ]
            ],
            'preview' => 'assets/themes/light/preview/contact.png'
        ],
        'blog' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'sub_heading' => 'text',
                ],
                'validation' => [
                    'heading.*' => 'required|max:100',
                    'sub_heading.*' => 'required|max:100',
                ]
            ],
             'preview' => 'assets/themes/light/preview/blog.png'
        ],
        'social' => [
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'font_icon' => 'text',
                    'link' => 'text',
                ],
                'validation' => [
                    'name.*' => 'required|max:100',
                    'font_icon.*' => 'required|max:100',
                    'link.*' => 'required|max:100'
                ]
            ],
            'preview' => 'assets/themes/light/preview/footer.png'
        ],
        'privacy_policy' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'short_title' => 'text',
                    'description' => 'textarea',
                ],
                'validation' => [
                    'heading.*' => 'required|max:500',
                    'last_update_date.*' => 'required',
                    'short_title.*' => 'required',
                    'description.*' => 'required',
                ]
            ],
            'preview' => 'assets/themes/light/preview/privacy_policy.png'
        ],
        'terms_condition' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'short_title' => 'text',
                    'description' => 'textarea',
                ],
                'validation' => [
                    'heading.*' => 'required|max:500',
                    'last_update_date.*' => 'required',
                    'short_title.*' => 'required',
                    'description.*' => 'required',
                ]
            ],
            'preview' => 'assets/themes/light/preview/terms_condition.png'
        ],
        'maintenance-page' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_description' => 'textarea',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                    'short_description.*' => 'required|max:5000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ]
            ],
            'preview' => 'assets/themes/light/preview/maintenance-page.png'
        ],
        'app_onboard' => [
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'subtitle' => 'text',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'subtitle.*' => 'required|max:100',
                    'image.*' => 'nullable|max:10000|image|mimes:jpg,jpeg,png',
                ]
            ],
            'preview' => 'assets/themes/light/preview/app_onboard.png'
        ],
    ],

    'green' => [
        'hero' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'happy_client_title' => 'text',
                    'happy_client_sub_title' => 'text',
                    'review_title' => 'text',
                    'review_sub_title' => 'text',
                    'welcome_bonus_title' => 'text',
                    'welcome_bonus_sub_title' => 'text',
                    'button_name' => 'text',
                    'image' => 'file',
                    'image2' => 'file',
                    'image3' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:500',
                    'happy_client.*' => 'required|max:100',
                    'review.*' => 'required|max:100',
                    'welcome_bonus.*' => 'required|max:100',
                    'button_name.*' => 'required|max:2000',
                    'image1.*' => 'nullable|max:10000|image|mimes:jpg,jpeg,png',
                    'image2.*' => 'nullable|max:10000|image|mimes:jpg,jpeg,png',
                    'image3.*' => 'nullable|max:10000|image|mimes:jpg,jpeg,png',
                ]
            ],
            'preview' => 'assets/themes/green/preview/hero.png'
        ],
        'feature' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'short_details' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:500',
                    'short_details.*' => 'required|max:200',
                ]
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'short_details' => 'text',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'short_details.*' => 'required|max:500',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ],
                'size' => [
                    'image' => '64x64'
                ]
            ],
            'preview' => 'assets/themes/green/preview/feature.png'
        ],
        'about' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_title' => 'text',
                    'short_description' => 'textarea',
                    'background_title' => 'text',
                    'background_short_title' => 'text',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                    'short_description.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:10000|image|mimes:jpg,jpeg,png',
                    'image2.*' => 'nullable|max:10000|image|mimes:jpg,jpeg,png',
                ],
                'size' => [
                    'image' => '1279x1279',
                    'image2' => '1279x1279',
                ]
            ],
            'preview' => 'assets/themes/green/preview/about.png'
        ],
        'property' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                ],
            ],
            'preview' => 'assets/themes/green/preview/property.png'
        ],
        'testimonial' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_description' => 'textarea',
                    'background_title' => 'text',
                    'image' => 'file'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'designation' => 'text',
                    'review' => 'number',
                    'description' => 'textarea',
                    'image' => 'file',
                ],
                'validation' => [
                    'name.*' => 'required|max:100',
                    'designation.*' => 'required|max:2000',
                    'review.*' => 'required|max:5',
                    'description.*' => 'required|max:2000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
                ]
            ],
            'preview' => 'assets/themes/green/preview/testimonial.png'
        ],
        'latest_property' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                ],
            ],
            'preview' => 'assets/themes/green/preview/latest_property.png'
        ],
        'statistics' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_details' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'text',
                    'font_icon' => 'text',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'description.*' => 'required',
                    'font_icon.*' => 'required',
                ],
            ],
            'preview' => 'assets/themes/green/preview/statistics.png'
        ],
        'news_letter' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text'
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                ],
            ],
            'preview' => 'assets/themes/green/preview/news_letter.png'
        ],
        'faq' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_details' => 'textarea',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                    'short_details.*' => 'required|max:2000'
                ],
            ],
            'multiple' => [
                'field_name' => [
                    'title' => 'text',
                    'description' => 'textarea'
                ],
                'validation' => [
                    'title.*' => 'required|max:190',
                    'description.*' => 'required|max:3000'
                ]
            ],
            'preview' => 'assets/themes/green/preview/faq.png'
        ],
        'contact' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'sub_heading' => 'text',
                    'google_map_embed_url' => 'text',
                    'address' => 'text',
                    'email' => 'text',
                    'phone' => 'text',
                    'footer_short_details' => 'textarea',
                    'subscriber_message' => 'textarea',
                ],
                'validation' => [
                    'heading.*' => 'required|max:100',
                    'sub_heading.*' => 'required|max:100',
                    'title.*' => 'required|max:100',
                    'address.*' => 'required|max:2000',
                    'email.*' => 'required|max:2000',
                    'phone.*' => 'required|max:2000',
                ]
            ],
            'preview' => 'assets/themes/green/preview/contact.png'
        ],
        'blog' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'sub_heading' => 'text',
                    'short_details' => 'text',
                ],
                'validation' => [
                    'heading.*' => 'required|max:100',
                    'sub_heading.*' => 'required|max:100',
                    'short_details' => 'required',
                ]
            ],
            'preview' => 'assets/themes/green/preview/blog.png'
        ],
        'social' => [
            'multiple' => [
                'field_name' => [
                    'name' => 'text',
                    'font_icon' => 'text',
                    'link' => 'text',
                ],
                'validation' => [
                    'name.*' => 'required|max:100',
                    'font_icon.*' => 'required|max:100',
                    'link.*' => 'required|max:100'
                ]
            ],
            'preview' => 'assets/themes/green/preview/social.png'
        ],
        'privacy_policy' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'short_title' => 'text',
                    'description' => 'textarea',
                ],
                'validation' => [
                    'heading.*' => 'required|max:500',
                    'short_title.*' => 'required',
                    'description.*' => 'required',
                ]
            ],
            'preview' => 'assets/themes/green/preview/privacy_policy.png'
        ],
        'terms_condition' => [
            'single' => [
                'field_name' => [
                    'heading' => 'text',
                    'short_title' => 'text',
                    'description' => 'textarea',
                ],
                'validation' => [
                    'heading.*' => 'required|max:500',
                    'last_update_date.*' => 'required',
                    'short_title.*' => 'required',
                    'description.*' => 'required',
                ]
            ],
            'preview' => 'assets/themes/green/preview/terms_condition.png'
        ],
        'maintenance-page' => [
            'single' => [
                'field_name' => [
                    'title' => 'text',
                    'sub_title' => 'text',
                    'short_description' => 'textarea',
                    'image' => 'file',
                ],
                'validation' => [
                    'title.*' => 'required|max:100',
                    'sub_title.*' => 'required|max:100',
                    'short_description.*' => 'required|max:5000',
                    'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
                ]
            ],
            'preview' => 'assets/themes/green/preview/maintenance-page.png'
        ],
    ],

    'message' => [
        'required' => 'This field is required.',
        'min' => 'This field must be at least :min characters.',
        'max' => 'This field may not be greater than :max characters.',
        'image' => 'This field must be image.',
        'mimes' => 'This image must be a file of type: jpg, jpeg, png.',
        'integer' => 'This field must be an integer value',
    ],

    'content_media' => [
        'image' => 'file',
        'image2' => 'file',
        'image3' => 'file',
        'thumb_image' => 'file',
        'my_link' => 'url',
        'icon' => 'icon',
        'count_number' => 'number',
        'start_date' => 'date'
    ]
];

