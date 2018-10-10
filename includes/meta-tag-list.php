<?php
/**
 * List of meta tags which can be edited with this plugin.
 */

defined('ABSPATH') || die();

$dpmt_meta_tag_list = [

    __( 'General tags', 'dp-meta-tags' ) => [
        'var' => 'general',
        'info' => __( 'Basic HTML meta tags.', 'dp-meta-tags' ),
        'attr' => 'name',
        'fields' => [
            'description' => [
                'info' => __( 'This text will appear below your title in Google search results. Describe this page/post in 155 maximum characters. Note: Google will not consider this in its search ranking algorithm.', 'dp-meta-tags' ),                
                'variable' => 'dpmt_general_description'
            ],

            'keywords' => [
                'info' => __( 'Improper or spammy use most likely will hurt you with some search engines. Google will not consider this in its search ranking algorithm, so it\'s not really recommended.', 'dp-meta-tags' ),   
                'variable' => 'dpmt_general_keywords'
            ]
        ]
    ],



    __( 'Open Graph', 'dp-meta-tags' ) => [
        'var' => 'og',
        'info' => __( 'Open Graph has become very popular, so most social networks default to Open Graph if no other meta tags are present.', 'dp-meta-tags' ),
        'attr' => 'property',
        'fields' => [
            'og:title' => [
                'info' => __( 'The headline.', 'dp-meta-tags' ),                
                'variable' => 'dpmt_og_title'
            ],
            'og:description' => [
                'info' => __( 'A short summary about the content.', 'dp-meta-tags' ),                
                'variable' => 'dpmt_og_description'
            ],
            'og:type' => [
                'info' => __( 'Article, website or other. Here is a list of all available types: <a href="http://ogp.me/#types" target="_blank">http://ogp.me/#types</a>', 'dp-meta-tags' ), 
                'variable' => 'dpmt_og_type'
            ],
            'og:audio' => [
                'info' => __( 'URL to your content\'s audio.', 'dp-meta-tags' ),                 
                'variable' => 'dpmt_og_audio'
            ],
            'og:image' => [
                'info' => __( 'URL to your content\'s image. It should be at least 600x315 pixels, but 1200x630 or larger is preferred (up to 5MB). Stay close to a 1.91:1 aspect ratio to avoid cropping.', 'dp-meta-tags' ), 
                'variable' => 'dpmt_og_image'
           ],
            'og:image:alt' => [
                'info' => __( 'A text description of the image for visually impaired users.', 'dp-meta-tags' ), 
                'variable' => 'dpmt_og_image_alt'
           ],
            'og:video' => [
                'info' => __( 'URL to your content\'s video. Videos need an og:image tag to be displayed in News Feed.', 'dp-meta-tags' ), 
                'variable' => 'dpmt_og_video'
            ],
            'og:url' => [
                'info' => __( 'The URL of your page. Use the canonical URL for this tag (the search engine friendly URL that you want the search engines to treat as authoritative).', 'dp-meta-tags' ), 
                'variable' => 'dpmt_og_url'
            ]
        ]
    ],


    
    __( 'Twitter Cards', 'dp-meta-tags' ) => [
        'var' => 'twitter',
        'info' => __( 'Simply add a few lines of markup to your webpage, and users who Tweet links to your content will have a "Card" added to the Tweet that’s visible to their followers. Once you filled the meta tags, you can validate your cards <a href="https://cards-dev.twitter.com/validator" target="_blank">here</a>.', 'dp-meta-tags' ),
        'attr' => 'name',
        'fields' => [
            'twitter:card' => [
                'info' => __( 'The card type.', 'dp-meta-tags' ),
                'variable' => 'dpmt_twitter_card',
                'values' => ['summary', 'summary_large_image', 'player']
            ],
            'twitter:site' => [
                'info' => __( 'The Twitter username of your website. E.g.: @divpusherthemes', 'dp-meta-tags' ),
                'variable' => 'dpmt_twitter_site'
            ],
            'twitter:title' => [
                'info' => __( 'Title of content (max 70 characters)', 'dp-meta-tags' ),
                'variable' => 'dpmt_twitter_title'
            ],
            'twitter:description' => [
                'info' => __( 'Description of content (maximum 200 characters)', 'dp-meta-tags' ),
                'variable' => 'dpmt_twitter_description'
            ],
            'twitter:image' => [
                'info' => __( 'URL of image to use in the card. Images must be less than 5MB in size. JPG, PNG, WEBP and GIF formats are supported.', 'dp-meta-tags' ),
                'variable' => 'dpmt_twitter_image'
            ],
            'twitter:image:alt' => [
                'info' => __( 'A text description of the image for visually impaired users.', 'dp-meta-tags' ),
                'variable' => 'dpmt_twitter_image_alt'
            ],
            'twitter:player' => [
                'info' => __( 'HTTPS URL to iFrame player. This must be a HTTPS URL which does not generate active mixed content warnings in a web browser. The audio or video player must not require plugins such as Adobe Flash.', 'dp-meta-tags' ),
                'variable' => 'dpmt_twitter_player'
            ],
            'twitter:player:width' => [
                'info' => __( 'Width of iframe in pixels.', 'dp-meta-tags' ),
                'variable' => 'dpmt_twitter_player_width'
            ],
            'twitter:player:height' => [
                'info' => __( 'Height of iframe in pixels.', 'dp-meta-tags' ),
                'variable' => 'dpmt_twitter_player_height'
            ],
            'twitter:player:stream' => [
                'info' => __( 'URL to raw video or audio stream.', 'dp-meta-tags' ),
                'variable' => 'dpmt_twitter_player_stream'
            ],
            'twitter:player:stream:content_type' => [
                'info' => __( 'The MIME type of video or audio stream, e.g.: <b>video/mp4</b> for *.mp4, <b>audio/mpeg</b> for *.mp3', 'dp-meta-tags' ),
                'variable' => 'dpmt_twitter_player_stream_content_type'
            ]
        ]
    ],



    'Custom' => [
        'var' => 'custom',
        'info' => __( 'Insert your custom meta tags here.', 'dp-meta-tags' ),
        'fields' => [
            __( 'Custom meta tags', 'dp-meta-tags' ) => [
                'info' => '',                
                'variable' => 'dpmt_custom'
            ],
        ]
    ]

];
