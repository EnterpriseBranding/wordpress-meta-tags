<?php

/**
 * List of meta tags which can be edited in the admin
 */

defined('ABSPATH') || die();

$dpmt_meta_tag_list = [

    'General tags' => [
        'info' => 'Basic HTML meta tags.',
        'attr' => 'name',
        'fields' => [
            'description' => [
                'info' => 'This text will appear below your title in Google search results. Describe this page/post in 155 maximum characters. Note: Google will not consider this in its search ranking algorithm.',                
                'variable' => 'dpmt_general_description'
            ],

            'keywords' => [
                'info' => 'Improper or spammy use most likely will hurt you with some search engines. Google will not consider this in its search ranking algorithm, so it\'s not really recommended.',
                'variable' => 'dpmt_general_keywords'
            ]
        ]
    ],



    'Open Graph' => [
        'info' => 'Open Graph has become very popular, so most social networks default to Open Graph if no other meta tags are present.',
        'attr' => 'property',
        'fields' => [
            'og:title' => [
                'info' => 'The headline.',                
                'variable' => 'dpmt_og_title'
            ],
            'og:description' => [
                'info' => 'A short summary about the content.',                
                'variable' => 'dpmt_og_description'
            ],
            'og:type' => [
                'info' => 'Article, website or other. Here is a list of all available types: <a href="http://ogp.me/#types" target="_blank">http://ogp.me/#types</a>',                
                'variable' => 'dpmt_og_type'
            ],
            'og:audio' => [
                'info' => 'URL to your content\'s audio.',                
                'variable' => 'dpmt_og_audio'
            ],
            'og:image' => [
                'info' => 'URL to your content\'s image. It should be at least 600x315 pixels, but 1200x630 or larger is preferred (up to 5MB). Stay close to a 1.91:1 aspect ratio to avoid cropping.',                
                'variable' => 'dpmt_og_image'
           ],
            'og:image:alt' => [
                'info' => 'A text description of the image for visually impaired users.',                
                'variable' => 'dpmt_og_image_alt'
           ],
            'og:video' => [
                'info' => 'URL to your content\'s video. Videos need an og:image tag to be displayed in News Feed.',                
                'variable' => 'dpmt_og_video'
            ],
            'og:url' => [
                'info' => 'The URL of your page. Use the canonical URL for this tag (the search engine friendly URL that you want the search engines to treat as authoritative).',                
                'variable' => 'dpmt_og_url'
            ]
        ]
    ],


    
    'Twitter Cards' => [
        'info' => 'Simply add a few lines of markup to your webpage, and users who Tweet links to your content will have a "Card" added to the Tweet that’s visible to their followers.',
        'attr' => 'name',
        'fields' => [
            'twitter:card' => [
                'info' => 'The card type.',                
                'variable' => 'dpmt_twitter_card',
                'values' => ['summary', 'summary_large_image', 'player']
            ],
            'twitter:site' => [
                'info' => 'The Twitter username of your website.',                
                'variable' => 'dpmt_twitter_site'
            ],
            'twitter:title' => [
                'info' => 'Title of content (max 70 characters)',                
                'variable' => 'dpmt_twitter_title'
            ],
            'twitter:description' => [
                'info' => 'Description of content (maximum 200 characters)',                
                'variable' => 'dpmt_twitter_description'
            ],
            'twitter:image' => [
                'info' => 'URL of image to use in the card. Images must be less than 5MB in size. JPG, PNG, WEBP and GIF formats are supported.',                
                'variable' => 'dpmt_twitter_image'
            ],
            'twitter:image:alt' => [
                'info' => 'A text description of the image for visually impaired users.',                
                'variable' => 'dpmt_twitter_image_alt'
            ],
            'twitter:player' => [
                'info' => 'HTTPS URL of player iframe.',                
                'variable' => 'dpmt_twitter_player'
            ],
            'twitter:player:width' => [
                'info' => 'Width of iframe in pixels.',                
                'variable' => 'dpmt_twitter_player_width'
            ],
            'twitter:player:height' => [
                'info' => 'Height of iframe in pixels.',                
                'variable' => 'dpmt_twitter_player_height'
            ],
            'twitter:player:stream' => [
                'info' => 'URL to raw video or audio stream.',                
                'variable' => 'dpmt_twitter_player_stream'
            ]
        ]
    ]

];
