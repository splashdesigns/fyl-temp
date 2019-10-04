<?php
/**
 * Register the module and its form settings with new typography, border, align param settings provided in beaver builder version 2.2.
 * Applicable for BB version greater than 2.2 and UABB version 1.14.0 or later.
 *
 * Converted font, align, border settings to respective param setting.
 *
 * @package UABB Video Module
 */

$branding_name       = BB_Ultimate_Addon_Helper::get_builder_uabb_branding( 'uabb-plugin-name' );
$branding_short_name = BB_Ultimate_Addon_Helper::get_builder_uabb_branding( 'uabb-plugin-short-name' );
$branding            = '';
if ( empty( $branding_name ) && empty( $branding_short_name ) ) {
	$branding = 'no';
} else {
	$branding = 'yes';
}
FLBuilder::register_module(
	'UABBVideo',
	array(
		'general'          => array(
			'title'    => __( 'General', 'uabb' ), // Tab title.
			'sections' => array( // Tab Sections.
				'general'      => array( // Section.
					'title'  => __( 'Video', 'uabb' ), // Section Title.
					'fields' => array( // Section Fields.
						'video_type'   => array(
							'type'    => 'select',
							'label'   => __( 'Video Type', 'uabb' ),
							'default' => 'youtube',
							'options' => array(
								'youtube' => __( 'YouTube', 'uabb' ),
								'vimeo'   => __( 'Vimeo', 'uabb' ),
							),
							'toggle'  => array(
								'youtube' => array(
									'fields'   => array( 'youtube_link', 'end', 'start' ),
									'sections' => array( 'video_option' ),
									'tabs'     => array( 'yt_subscribe_bar' ),
								),
								'vimeo'   => array(
									'fields'   => array( 'vimeo_link', 'start' ),
									'sections' => array( 'vimeo_option' ),
								),
							),
						),
						'youtube_link' => array(
							'type'        => 'text',
							'label'       => __( 'Link', 'uabb' ),
							'default'     => 'https://www.youtube.com/watch?v=HJRzUQMhJMQ',
							'description' => UABBVideo::get_description( 'youtube_link' ),
							'connections' => array( 'url' ),
						),
						'vimeo_link'   => array(
							'type'        => 'text',
							'label'       => __( 'Link', 'uabb' ),
							'default'     => 'https://vimeo.com/274860274',
							'description' => UABBVideo::get_description( 'vimeo_link' ),
							'connections' => array( 'url' ),
						),
						'start'        => array(
							'type'    => 'unit',
							'label'   => __( 'Start Time', 'uabb' ),
							'default' => '',
							'help'    => __( 'Specify a start time (in seconds).', 'uabb' ),
							'units'   => array( 'Sec' ),
							'slider'  => true,
						),
						'end'          => array(
							'type'    => 'unit',
							'label'   => __( 'End Time', 'uabb' ),
							'default' => '',
							'help'    => __( 'Specify a End time (in seconds).', 'uabb' ),
							'units'   => array( 'Sec' ),
							'slider'  => true,
						),
						'aspect_ratio' => array(
							'type'    => 'select',
							'label'   => __( 'Aspect Ratio', 'uabb' ),
							'default' => '16_9',
							'options' => array(
								'16_9' => __( '16:9', 'uabb' ),
								'4_3'  => __( '4:3', 'uabb' ),
								'3_2'  => __( '3:2', 'uabb' ),
								'1_1'  => __( '1:1', 'uabb' ),
							),
						),
					),
				),
				'video_option' => array(
					'title'  => __( 'Video Options', 'uabb' ),
					'fields' => array(
						'yt_autoplay'       => array(
							'type'    => 'select',
							'label'   => __( 'AutoPlay', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'no' => array(
									'tabs' => array( 'thumbnail' ),
								),
							),
							'help'    => __( 'Thumbnail will not display if AutoPlay mode is enabled. ', 'uabb' ),
						),
						'yt_suggested'      => array(
							'type'    => 'select',
							'label'   => __( 'Suggested Videos', 'uabb' ),
							'default' => 'hide',
							'options' => array(
								'no'  => __( 'Current Video Channel', 'uabb' ),
								'yes' => __( 'Any Random Video', 'uabb' ),
							),
						),
						'yt_controls'       => array(
							'type'    => 'select',
							'label'   => __( 'Player Control', 'uabb' ),
							'default' => 'show',
							'options' => array(
								'yes' => __( 'Show', 'uabb' ),
								'no'  => __( 'Hide', 'uabb' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'yt_modestbranding' ),
								),
							),
						),
						'yt_mute'           => array(
							'type'    => 'select',
							'label'   => __( 'Mute', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
						),
						'yt_modestbranding' => array(
							'type'    => 'select',
							'label'   => __( 'Modest Branding', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'help'    => __( 'This option lets you use a YouTube player that does not show a YouTube logo.', 'uabb' ),
						),
						'yt_privacy'        => array(
							'type'    => 'select',
							'label'   => __( 'Privacy Mode', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'help'    => __( "When you turn on privacy mode, YouTube won't store information about visitors on your website unless they play the video.", 'uabb' ),
						),
					),
				),
				'vimeo_option' => array(
					'title'  => __( 'Video option', 'uabb' ),
					'fields' => array(
						'vimeo_autoplay' => array(
							'type'    => 'select',
							'label'   => __( 'Autoplay', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'no' => array(
									'tabs' => array( 'thumbnail' ),
								),
							),
							'help'    => __( 'Thumbnail will not display if AutoPlay mode is enabled.', 'uabb' ),
						),
						'vimeo_loop'     => array(
							'type'    => 'select',
							'label'   => __( 'Loop', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'help'    => __( 'Choose a video to play continuously in a loop. The video will automatically start again after reaching the end.', 'uabb' ),
						),
						'vimeo_title'    => array(
							'type'    => 'select',
							'label'   => __( 'Intro Title', 'uabb' ),
							'default' => 'show',
							'options' => array(
								'yes' => __( 'Show', 'uabb' ),
								'no'  => __( 'Hide', 'uabb' ),
							),
							'help'    => __( 'Displays title of the video.', 'uabb' ),
						),
						'vimeo_portrait' => array(
							'type'    => 'select',
							'label'   => __( 'Intro Portrait', 'uabb' ),
							'default' => 'show',
							'options' => array(
								'yes' => __( 'Show', 'uabb' ),
								'no'  => __( 'Hide', 'uabb' ),
							),
							'help'    => __( 'Displays the author’s profile image.', 'uabb' ),
						),
						'vimeo_byline'   => array(
							'type'    => 'select',
							'label'   => __( 'Intro Byline', 'uabb' ),
							'default' => 'show',
							'options' => array(
								'yes' => __( 'Show', 'uabb' ),
								'no'  => __( 'Hide', 'uabb' ),
							),
							'help'    => __( 'Displays the author’s name of the video.', 'uabb' ),
						),
						'vimeo_color'    => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Controls Color', 'uabb' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
						),
					),
				),
			),
		),
		'thumbnail'        => array(
			'title'    => __( 'Thumbnail', 'uabb' ),
			'sections' => array(
				'section_image_overlay' => array(
					'title'  => __( 'Thumbnail & Overlay', 'uabb' ),
					'fields' => array(
						'show_image_overlay'  => array(
							'type'    => 'select',
							'label'   => __( 'Thumbnail', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Custom Thumbnail', 'uabb' ),
								'no'  => __( 'Default Thumbnail', 'uabb' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'image_overlay' ),
								),
								'no'  => array(
									'fields' => array( 'yt_thumbnail_size' ),
								),
							),
						),
						'yt_thumbnail_size'   => array(
							'type'    => 'select',
							'label'   => __( 'Default Thumbnail Size', 'uabb' ),
							'default' => 'maxresdefault',
							'options' => array(
								'maxresdefault' => __( 'Maximum Resolution', 'uabb' ),
								'hqdefault'     => __( 'High Quality', 'uabb' ),
								'mqdefault'     => __( 'Medium Quality', 'uabb' ),
								'sddefault'     => __( 'Standard Quality', 'uabb' ),
							),
						),
						'image_overlay'       => array(
							'type'        => 'photo',
							'label'       => __( 'Select Custom Thumbnail', 'uabb' ),
							'show_remove' => true,
							'connections' => array( 'photo' ),
						),
						'image_overlay_color' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Overlay color', 'uabb' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-video__outer-wrap:before',
								'property'  => 'background',
								'important' => true,
							),
						),
						'video_double_click'  => array(
							'type'    => 'select',
							'label'   => __( 'Enable Double Click on Mobile', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'help'    => __( 'Enable this option if you are not able to see custom thumbnail or overlay color on Mobile.', 'uabb' ),
						),
					),
				),
				'section_play_icon'     => array(
					'title'  => __( 'Play Button', 'uabb' ),
					'fields' => array(
						'play_source'                => array(
							'type'    => 'select',
							'label'   => __( 'Image/Icon', 'uabb' ),
							'default' => 'default',
							'options' => array(
								'image'   => __( 'Image', 'uabb' ),
								'icon'    => __( 'Icon', 'uabb' ),
								'default' => __( 'Default', 'uabb' ),
							),
							'toggle'  => array(
								'image'   => array(
									'fields' => array(
										'play_img',
										'play_img_size',
									),
								),
								'icon'    => array(
									'fields' => array( 'play_icon', 'play_icon', 'play_icon_color', 'play_icon_hover_color' ),
								),
								'default' => array(
									'fields' => array(
										'play_default_icon_bg',
										'play_default_icon_bg_hover',
									),
								),
							),
						),
						'play_img'                   => array(
							'type'        => 'photo',
							'label'       => __( 'Select Image', 'uabb' ),
							'show_remove' => true,
							'connections' => array( 'photo' ),
						),
						'play_icon'                  => array(
							'type'        => 'icon',
							'label'       => __( 'Select Icon', 'uabb' ),
							'default'     => 'far fa-play-circle',
							'show_remove' => true,
						),
						'play_icon_size'             => array(
							'type'    => 'unit',
							'label'   => __( 'Size', 'uabb' ),
							'default' => '75',
							'slider'  => true,
							'units'   => array( 'px' ),
						),
						'play_default_icon_bg'       => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Background Color', 'uabb' ),
							'default'     => '',
							'show_reset'  => 'true',
							'show_alpha'  => 'true',
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-youtube-icon-bg,.uabb-vimeo-icon-bg',
								'property'  => 'fill',
								'important' => true,
							),
						),
						'play_default_icon_bg_hover' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Hover Background Color', 'uabb' ),
							'default'     => '',
							'show_reset'  => 'true',
							'show_alpha'  => 'true',
							'preview'     => array(
								'type' => 'none',
							),
						),
						'play_icon_color'            => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Color', 'uabb' ),
							'default'     => '',
							'show_reset'  => 'true',
							'show_alpha'  => 'true',
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-video__play-icon',
								'property'  => 'color',
								'important' => true,
							),
						),
						'play_icon_hover_color'      => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Hover Color', 'uabb' ),
							'default'     => '',
							'show_reset'  => 'true',
							'show_alpha'  => 'true',
							'preview'     => array(
								'type' => 'none',
							),
						),
						'hover_animation'            => array(
							'type'    => 'select',
							'label'   => __( 'Hover Animation', 'uabb' ),
							'default' => '',
							'options' => array(
								''                => __( 'None', 'uabb' ),
								'float'           => __( 'Float', 'uabb' ),
								'sink'            => __( 'Sink', 'uabb' ),
								'wobble-vertical' => __( 'Wobble Vertical', 'uabb' ),
							),
						),
					),
				),
			),
		),

		'sticky_video'     => array(
			'title'    => __( 'Sticky Video', 'uabb' ),
			'sections' => array(
				'section_sticky_enable'       => array(
					'title'  => __( 'Sticky Video Settings ', 'uabb' ),
					'fields' => array(
						'enable_sticky'      => array(
							'type'    => 'select',
							'label'   => __( 'Enable Sticky Video', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes ', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields'   => array( 'sticky_alignment', 'sticky_video_width', 'sticky_hide_on' ),
									'sections' => array( 'section_background_sticky', 'section_sticky_close_button', 'heading_sticky_info_bar' ),
								),
							),
						),
						'sticky_alignment'   => array(
							'type'    => 'select',
							'label'   => __( 'Sticky Alignment', 'uabb' ),
							'options' => array(
								'top_left'     => __( 'Top Left', 'uabb' ),
								'top_right'    => __( 'Top Right ', 'uabb' ),
								'center_left'  => __( 'Center Left', 'uabb' ),
								'center_right' => __( 'Center Right', 'uabb' ),
								'bottom_left'  => __( 'Bottom Left', 'uabb' ),
								'bottom_right' => __( 'Bottom Right', 'uabb' ),
							),
						),
						'sticky_video_width' => array(
							'type'        => 'unit',
							'label'       => __( 'Video Width', 'uabb' ),
							'default'     => '360',
							'placeholder' => 'auto',
							'maxlength'   => '6',
							'size'        => '8',
							'units'       => array( 'px' ),
							'slider'      => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '360',
									'medium'     => '',
									'responsive' => '250',
								),
							),
						),
						'sticky_hide_on'     => array(
							'type'    => 'select',
							'label'   => __( 'Hide Sticky Video on', 'uabb' ),
							'default' => 'none',
							'options' => array(
								'none'    => __( 'None', 'uabb' ),
								'desktop' => __( 'Desktop', 'uabb' ),
								'tablet'  => __( 'Tablet', 'uabb' ),
								'mobile'  => __( 'Mobile', 'uabb' ),
								'both'    => __( 'Tablet + Mobile', 'uabb' ),
							),
						),
					),
				),
				'section_background_sticky'   => array(
					'title'  => __( 'Background', 'uabb' ),
					'fields' => array(
						'sticky_video_margin'  => array(
							'type'       => 'dimension',
							'label'      => __( 'Spacing from Edges', 'uabb' ),
							'slider'     => true,
							'responsive' => true,
							'units'      => array( 'px' ),
							'help'       => __( 'Note: This is spacing around the sticky video with respect to the Alignment chosen.', 'uabb' ),
						),


						'sticky_video_padding' => array(
							'type'       => 'dimension',
							'label'      => __( 'Background Size', 'uabb' ),
							'slider'     => true,
							'responsive' => true,
							'units'      => array( 'px' ),
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.uabb-sticky-apply iframe,.uabb-sticky-apply .uabb-video__thumb',
								'property'  => 'padding',
								'unit'      => 'px',
								'important' => true,
							),
						),

						'sticky_video_color'   => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'uabb' ),
							'default'    => 'ffffff',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-video__outer-wrap.uabb-sticky-apply .uabb-video-inner-wrap',
								'property' => 'background',
							),
						),
					),
				),
				'section_sticky_close_button' => array( // Section.
					'title'  => __( 'Close Button', 'uabb' ), // Section Title.
					'fields' => array( // Section Fields.
						'enable_sticky_close_button' => array(
							'type'    => 'select',
							'label'   => __( 'Icon', 'uabb' ),
							'default' => 'icon',
							'options' => array(
								'icon' => __( 'Icon', 'uabb' ),
								'none' => __( 'None', 'uabb' ),
							),
							'toggle'  => array(
								'icon' => array(
									'fields' => array( 'sticky_close_icon', 'sticky_close_icon_color', 'sticky_close_icon_bgcolor' ),
								),
							),
						),

						'sticky_close_icon'          => array(
							'type'        => 'icon',
							'label'       => __( 'Select Icon', 'uabb' ),
							'default'     => 'fas fa-times',
							'show_remove' => true,
						),

						'sticky_close_icon_color'    => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Icon Color', 'uabb' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-video-sticky-close',
								'property'  => 'color',
								'important' => true,
							),
						),
						'sticky_close_icon_bgcolor'  => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Background Color', 'uabb' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-sticky-apply .uabb-video-sticky-close',
								'property'  => 'background',
								'important' => true,
							),
						),
					),
				),
				'heading_sticky_info_bar'     => array(
					'title'  => __( 'Info Bar', 'uabb' ),
					'fields' => array(
						'sticky_info_bar_enable'  => array(
							'type'    => 'select',
							'label'   => __( 'Enable', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes ', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'help'    => __( 'Enable this option to display the informative text under Sticky video.', 'uabb' ),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'sticky_info_bar_text', 'sticky_info_bar_color', 'sticky_info_bar_bgcolor', 'sticky_info_bar_padding', 'sticky_field_options' ),
								),
							),
						),

						'sticky_info_bar_text'    => array(
							'type'        => 'text',
							'label'       => __( 'Text', 'uabb' ),
							'placeholder' => __( 'This is info bar', 'uabb' ),
							'connections' => array( 'string', 'html' ),
						),
						'sticky_field_options'    => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'uabb' ),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.uabb-video-sticky-infobar',
								'important' => true,
							),
						),
						'sticky_info_bar_color'   => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Color', 'uabb' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-video-sticky-infobar',
								'property'  => 'color',
								'important' => true,
							),
						),
						'sticky_info_bar_bgcolor' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Background Color', 'uabb' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-video-sticky-infobar',
								'property'  => 'background',
								'important' => true,
							),
						),
						'sticky_info_bar_padding' => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'uabb' ),
							'slider'     => true,
							'responsive' => true,
							'units'      => array( 'px' ),
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.uabb-sticky-apply .uabb-video-sticky-infobar',
								'property'  => 'padding',
								'unit'      => 'px',
								'important' => true,
							),
						),
					),
				),
			),
		),

		'yt_subscribe_bar' => array(
			'title'    => __( 'YouTube Subscribe Bar', 'uabb' ),
			'sections' => array(
				'enable_subscribe_bar'    => array(
					'title'  => __( 'YouTube Subscribe Bar', 'uabb' ),
					'fields' => array(
						'yt_subscribe_enable' => array(
							'type'    => 'select',
							'label'   => __( 'Enable Subscribe Bar', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields'   => array( 'select_options', 'yt_subscribe_text' ),
									'sections' => array( 'subscribe_field_options' ),
								),
							),
						),
						'select_options'      => array(
							'type'    => 'select',
							'label'   => __( 'Select Channel ID/Channel Name', 'uabb' ),
							'default' => 'channel_id',
							'options' => array(
								'channel_id'   => __( 'Channel ID', 'uabb' ),
								'channel_name' => __( 'Channel Name', 'uabb' ),
							),
							'toggle'  => array(
								'channel_name' => array(
									'fields' => array( 'yt_channel_name' ),
								),
								'channel_id'   => array(
									'fields' => array( 'yt_channel_id' ),
								),
							),
						),
						'yt_channel_name'     => array(
							'type'        => 'text',
							'label'       => __( 'YouTube Channel Name', 'uabb' ),
							'default'     => 'TheBrainstormForce',
							'description' => UABBVideo::get_description( 'yt_channel_name' ),
						),
						'yt_channel_id'       => array(
							'type'        => 'text',
							'label'       => __( 'YouTube Channel ID', 'uabb' ),
							'default'     => 'UCtFCcrvupjyaq2lax_7OQQg',
							'description' => UABBVideo::get_description( 'yt_channel_id' ),
						),
						'yt_subscribe_text'   => array(
							'type'        => 'text',
							'label'       => __( 'Subscribe to channel text', 'uabb' ),
							'default'     => 'Subscribe to our YouTube Channel',
							'connections' => array( 'string', 'html' ),
						),
					),
				),
				'subscribe_field_options' => array(
					'title'  => __( 'Settings', 'uabb' ),
					'fields' => array(
						'subscribe_font_typo'      => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'uabb' ),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.uabb-subscribe-bar-prefix',
								'important' => true,
							),
						),
						'show_count'               => array(
							'type'    => 'select',
							'label'   => __( 'Show Subscribers Count', 'uabb' ),
							'default' => 'yes',
							'options' => array(
								'no'  => __( 'No', 'uabb' ),
								'yes' => __( 'Yes', 'uabb' ),
							),
						),
						'subscribe_text_color'     => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Text Color', 'uabb' ),
							'default'     => 'ffffff',
							'show_reset'  => 'true',
							'show_alpha'  => 'true',
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '
								.uabb-subscribe-bar-prefix',
								'property'  => 'color',
								'important' => true,
							),
						),
						'subscribe_text_bg_color'  => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Background Color', 'uabb' ),
							'default'     => '1b1b1b',
							'show_reset'  => 'true',
							'show_alpha'  => 'true',
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-subscribe-bar',
								'property'  => 'background-color',
								'important' => true,
							),
						),
						'subscribe_padding'        => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'uabb' ),
							'slider'     => true,
							'responsive' => true,
							'units'      => array( 'px' ),
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.uabb-subscribe-bar',
								'property'  => 'padding',
								'unit'      => 'px',
								'important' => true,
							),
						),
						'subscribe_bar_responsive' => array(
							'type'    => 'select',
							'label'   => __( 'Stack on', 'uabb' ),
							'default' => 'none',
							'options' => array(
								'none'    => __( 'None', 'uabb' ),
								'desktop' => __( 'Desktop', 'uabb' ),
								'tablet'  => __( 'Tablet', 'uabb' ),
								'mobile'  => __( 'Mobile', 'uabb' ),
							),
							'toggle'  => array(
								'desktop' => array(
									'fields' => array( 'subscribe_bar_spacing' ),
								),
								'tablet'  => array(
									'fields' => array( 'subscribe_bar_spacing' ),
								),
								'mobile'  => array(
									'fields' => array( 'subscribe_bar_spacing' ),
								),
							),
						),
						'subscribe_bar_spacing'    => array(
							'type'    => 'unit',
							'label'   => __( 'Spacing', 'uabb' ),
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'      => 'css',
								'selector'  => '.uabb-subscribe-responsive-desktop .uabb-subscribe-bar-prefix',
								'property'  => 'margin-bottom',
								'unit'      => 'px',
								'important' => true,
							),
						),
					),
				),
			),
		),
		'uabb_docs'        => array(
			'title'    => __( 'Docs', 'uabb' ),
			'sections' => array(
				'knowledge_base' => array(
					'title'  => __( 'Helpful Information', 'uabb' ),
					'fields' => array(
						'uabb_helpful_information' => array(
							'type'    => 'raw',
							'content' => '<ul class="uabb-docs-list" data-branding=' . $branding . '>

								<li class="uabb-docs-list-item"> <i class="ua-icon ua-icon-chevron-right2"> </i> <a href="https://www.ultimatebeaver.com/docs/video-module/?utm_source=uabb-pro-backend&utm_medium=module-editor-screen&utm_campaign=video-module" target="_blank" rel="noopener"> Getting started article </a> </li>

								<li class="uabb-docs-list-item"> <i class="ua-icon ua-icon-chevron-right2"> </i> <a href="https://www.ultimatebeaver.com/docs/how-to-set-thumbnail-image-for-video/?utm_source=uabb-pro-backend&utm_medium=module-editor-screen&utm_campaign=video-module" target="_blank" rel="noopener"> How to Set Thumbnail Image for Video? </a> </li>

								<li class="uabb-docs-list-item"> <i class="ua-icon ua-icon-chevron-right2"> </i> <a href="https://www.ultimatebeaver.com/docs/how-to-style-play-button/?utm_source=uabb-pro-backend&utm_medium=module-editor-screen&utm_campaign=video-module" target="_blank" rel="noopener"> How to Style Play Button? </a> </li>

								<li class="uabb-docs-list-item"> <i class="ua-icon ua-icon-chevron-right2"> </i> <a href="https://www.ultimatebeaver.com/docs/display-youtube-subscribe-bar-for-video/?utm_source=uabb-pro-backend&utm_medium=module-editor-screen&utm_campaign=video-module" target="_blank" rel="noopener"> How to Display YouTube Subscribe Bar for Video? </a> </li>

								<li class="uabb-docs-list-item"> <i class="ua-icon ua-icon-chevron-right2"> </i> <a href="https://www.ultimatebeaver.com/docs/how-to-find-youtube-channel-name-and-channel-id/?utm_source=uabb-pro-backend&utm_medium=module-editor-screen&utm_campaign=video-module" target="_blank" rel="noopener"> How to Find YouTube Channel Name and Channel ID? </a> </li>
								
								<li class="uabb-docs-list-item"> <i class="ua-icon ua-icon-chevron-right2"> </i> <a href="https://www.ultimatebeaver.com/docs/default-thumbnail-broken-in-video-module//?utm_source=uabb-pro-backend&utm_medium=module-editor-screen&utm_campaign=video-module" target="_blank" rel="noopener"> Default Thumbnail Broken in Video Module </a> </li>
								<li class="uabb-docs-list-item"> <i class="ua-icon ua-icon-chevron-right2"> </i> <a href="https://www.ultimatebeaver.com/docs/sticky-video/?utm_source=uabb-pro-backend&utm_medium=module-editor-screen&utm_campaign=video-module" target="_blank" rel="noopener"> Sticky Video </a> </li>
							 </ul>',
						),
					),
				),
			),
		),
	)
);
