<?php
/**
 * Register the module and its form settings with new typography, border, align param settings provided in beaver builder version 2.2.
 * Applicable for BB version greater than 2.2 and UABB version 1.14.0 or later.
 *
 * Converted font, align, border settings to respective param setting.
 *
 * @package UABB Contact Form Module
 */

FLBuilder::register_module(
	'UABBContactFormModule', array(
		'general'    => array(
			'title'    => __( 'General', 'uabb' ),
			'sections' => array(
				'name_section'    => array(
					'title'  => __( 'Name Field', 'uabb' ),
					'fields' => array(
						'name_toggle'      => array(
							'type'    => 'select',
							'label'   => __( 'Name', 'uabb' ),
							'default' => 'show',
							'options' => array(
								'show' => __( 'Show', 'uabb' ),
								'hide' => __( 'Hide', 'uabb' ),
							),
							'toggle'  => array(
								'show' => array(
									'fields' => array( 'name_width', 'name_label', 'name_placeholder', 'name_required' ),
								),
							),
						),
						'name_width'       => array(
							'type'    => 'select',
							'label'   => __( 'Width', 'uabb' ),
							'default' => '100',
							'options' => array(
								'100' => __( '100%', 'uabb' ),
								'50'  => __( '50%', 'uabb' ),
							),
						),
						'name_label'       => array(
							'type'    => 'text',
							'label'   => __( 'Label', 'uabb' ),
							'preview' => array(
								'type'      => 'text',
								'selector'  => '.uabb-name label',
								'important' => true,
							),
						),
						'name_placeholder' => array(
							'type'    => 'text',
							'label'   => __( 'Placeholder', 'uabb' ),
							'default' => __( 'Your Name', 'uabb' ),
							'preview' => array(
								'type'      => 'none',
								'important' => true,
							),
						),
						'name_required'    => array(
							'type'    => 'select',
							'label'   => __( 'Required', 'uabb' ),
							'help'    => __( 'Enable to make name field compulsary.', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => 'Yes',
								'no'  => 'No',
							),
						),
					),
				),
				'email_section'   => array(
					'title'  => __( 'Email Field', 'uabb' ),
					'fields' => array(
						'email_toggle'      => array(
							'type'    => 'select',
							'label'   => __( 'Email', 'uabb' ),
							'default' => 'show',
							'options' => array(
								'show' => __( 'Show', 'uabb' ),
								'hide' => __( 'Hide', 'uabb' ),
							),
							'toggle'  => array(
								'show' => array(
									'fields' => array( 'email_width', 'email_label', 'email_placeholder', 'email_required' ),
								),
							),
						),
						'email_width'       => array(
							'type'    => 'select',
							'label'   => __( 'Width', 'uabb' ),
							'default' => '100',
							'options' => array(
								'100' => __( '100%', 'uabb' ),
								'50'  => __( '50%', 'uabb' ),
							),
						),
						'email_label'       => array(
							'type'    => 'text',
							'label'   => __( 'Label', 'uabb' ),
							'preview' => array(
								'type'      => 'text',
								'selector'  => '.uabb-email label',
								'important' => true,
							),
						),
						'email_placeholder' => array(
							'type'    => 'text',
							'label'   => __( 'Placeholder', 'uabb' ),
							'default' => __( 'Your Email', 'uabb' ),
							'preview' => array(
								'type' => 'none',
							),
						),
						'email_required'    => array(
							'type'    => 'select',
							'label'   => __( 'Required', 'uabb' ),
							'help'    => __( 'Enable to make email field compulsary.', 'uabb' ),
							'default' => 'yes',
							'options' => array(
								'yes' => 'Yes',
								'no'  => 'No',
							),
						),
					),
				),
				'subject_section' => array(
					'title'  => __( 'Subject Field', 'uabb' ),
					'fields' => array(
						'subject_toggle'      => array(
							'type'    => 'select',
							'label'   => __( 'Subject', 'uabb' ),
							'default' => 'show',
							'options' => array(
								'show' => __( 'Show', 'uabb' ),
								'hide' => __( 'Hide', 'uabb' ),
							),
							'toggle'  => array(
								'show' => array(
									'fields' => array( 'subject_width', 'subject_label', 'subject_placeholder', 'subject_required' ),
								),
							),
						),
						'subject_width'       => array(
							'type'    => 'select',
							'label'   => __( 'Width', 'uabb' ),
							'default' => '100',
							'options' => array(
								'100' => __( '100%', 'uabb' ),
								'50'  => __( '50%', 'uabb' ),
							),
						),
						'subject_label'       => array(
							'type'    => 'text',
							'label'   => __( 'Label', 'uabb' ),
							'default' => __( 'Subject', 'uabb' ),
							'preview' => array(
								'type'      => 'text',
								'selector'  => '.uabb-subject label',
								'important' => true,
							),
						),
						'subject_placeholder' => array(
							'type'    => 'text',
							'label'   => __( 'Placeholder', 'uabb' ),
							'default' => __( 'Subject', 'uabb' ),
							'preview' => array(
								'type' => 'none',
							),
						),
						'subject_required'    => array(
							'type'    => 'select',
							'label'   => __( 'Required', 'uabb' ),
							'help'    => __( 'Enable to make subject field compulsary.', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => 'Yes',
								'no'  => 'No',
							),
						),
					),
				),
				'phone_section'   => array(
					'title'  => __( 'Phone Field', 'uabb' ),
					'fields' => array(
						'phone_toggle'      => array(
							'type'    => 'select',
							'label'   => __( 'Phone', 'uabb' ),
							'default' => 'hide',
							'options' => array(
								'show' => __( 'Show', 'uabb' ),
								'hide' => __( 'Hide', 'uabb' ),
							),
							'toggle'  => array(
								'show' => array(
									'fields' => array( 'phone_width', 'phone_label', 'phone_placeholder', 'phone_required' ),
								),
							),
						),
						'phone_width'       => array(
							'type'    => 'select',
							'label'   => __( 'Width', 'uabb' ),
							'default' => '100',
							'options' => array(
								'100' => __( '100%', 'uabb' ),
								'50'  => __( '50%', 'uabb' ),
							),
						),
						'phone_label'       => array(
							'type'    => 'text',
							'label'   => __( 'Label', 'uabb' ),
							'default' => __( 'Phone', 'uabb' ),
							'preview' => array(
								'type'      => 'text',
								'selector'  => '.uabb-phone label',
								'important' => true,
							),
						),
						'phone_placeholder' => array(
							'type'    => 'text',
							'label'   => __( 'Placeholder', 'uabb' ),
							'default' => __( 'Phone', 'uabb' ),
							'preview' => array(
								'type' => 'none',
							),
						),
						'phone_required'    => array(
							'type'    => 'select',
							'label'   => __( 'Required', 'uabb' ),
							'help'    => __( 'Enable to make phone field compulsary.', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => 'Yes',
								'no'  => 'No',
							),
						),
					),
				),
				'msg_section'     => array(
					'title'  => __( 'Message Field', 'uabb' ),
					'fields' => array(
						'msg_toggle'      => array(
							'type'    => 'select',
							'label'   => __( 'Message', 'uabb' ),
							'default' => 'show',
							'options' => array(
								'show' => __( 'Show', 'uabb' ),
								'hide' => __( 'Hide', 'uabb' ),
							),
							'toggle'  => array(
								'show' => array(
									'fields' => array( 'msg_width', 'msg_height', 'msg_label', 'msg_placeholder', 'msg_required', 'textarea_top_margin', 'textarea_bottom_margin' ),
								),
							),
						),
						'msg_width'       => array(
							'type'    => 'select',
							'label'   => __( 'Width', 'uabb' ),
							'default' => '100',
							'options' => array(
								'100' => __( '100%', 'uabb' ),
								'50'  => __( '50%', 'uabb' ),
							),
						),
						'msg_label'       => array(
							'type'    => 'text',
							'label'   => __( 'Label', 'uabb' ),
							'preview' => array(
								'type'      => 'text',
								'selector'  => '.uabb-message label',
								'important' => true,
							),
						),
						'msg_placeholder' => array(
							'type'    => 'text',
							'label'   => __( 'Placeholder', 'uabb' ),
							'default' => __( 'Your Message', 'uabb' ),
							'preview' => array(
								'type' => 'none',
							),
						),
						'msg_required'    => array(
							'type'    => 'select',
							'label'   => __( 'Required', 'uabb' ),
							'help'    => __( 'Enable to make message field compulsary.', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => 'Yes',
								'no'  => 'No',
							),
						),
					),
				),
				'terms_section'   => array(
					'title'  => __( 'Terms and Conditions Field', 'uabb' ),
					'fields' => array(
						'terms_checkbox'      => array(
							'type'    => 'select',
							'label'   => __( 'Terms and Conditions Checkbox', 'uabb' ),
							'default' => 'hide',
							'options' => array(
								'show' => __( 'Show', 'uabb' ),
								'hide' => __( 'Hide', 'uabb' ),
							),
							'toggle'  => array(
								'show' => array(
									'fields'   => array( 'terms_checkbox_text', 'terms_text' ),
									'sections' => array( 'checkbox_typography', 'terms_typography', 'terms-checkbox-style' ),
								),
							),
						),
						'terms_checkbox_text' => array(
							'type'    => 'text',
							'label'   => __( 'Checkbox Text', 'uabb' ),
							'default' => __( 'I Accept the Terms and Conditions', 'uabb' ),
						),
						'terms_text'          => array(
							'type'          => 'editor',
							'label'         => 'Terms and Conditions',
							'default'       => __( 'Please go through the following terms and conditions carefully.', 'uabb' ),
							'media_buttons' => false,
							'rows'          => 8,
							'preview'       => array(
								'type'      => 'text',
								'selector'  => '.uabb-terms-text',
								'important' => true,
							),
							'connections'   => array( 'string' ),
						),
					),
				),
				'success'         => array(
					'title'  => __( 'Success', 'uabb' ),
					'fields' => array(
						'success_action'  => array(
							'type'    => 'select',
							'label'   => __( 'Success Action', 'uabb' ),
							'options' => array(
								'none'         => __( 'None', 'uabb' ),
								'show_message' => __( 'Show Message', 'uabb' ),
								'redirect'     => __( 'Redirect', 'uabb' ),
							),
							'toggle'  => array(
								'show_message' => array(
									'fields' => array( 'success_message' ),
								),
								'redirect'     => array(
									'fields' => array( 'success_url' ),
								),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'success_message' => array(
							'type'          => 'editor',
							'label'         => '',
							'media_buttons' => false,
							'rows'          => 8,
							'default'       => __( 'Thanks for your message! We’ll be in touch soon.', 'uabb' ),
							'preview'       => array(
								'type' => 'none',
							),
							'connections'   => array( 'string', 'html' ),
						),
						'success_url'     => array(
							'type'        => 'link',
							'connections' => array( 'url' ),
							'label'       => __( 'Success URL', 'uabb' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
					),
				),
			),
		),
		'style'      => array(
			'title'    => __( 'Style', 'uabb' ),
			'sections' => array(
				'form-general'         => array(
					'title'  => '',
					'fields' => array(
						'form_style'         => array(
							'type'    => 'select',
							'label'   => __( 'Form Style', 'uabb' ),
							'default' => 'style1',
							'options' => array(
								'style1' => __( 'Style 1', 'uabb' ),
								'style2' => __( 'Style 2', 'uabb' ),
							),
							'toggle'  => array(
								'style1' => array(
									'fields' => array( 'enable_label' ),
								),
							),
							'help'    => __( 'Input fleld Apperance', 'uabb' ),
						),
						'enable_label'       => array(
							'type'    => 'select',
							'label'   => __( 'Enable Label', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
						),
						'enable_placeholder' => array(
							'type'    => 'select',
							'label'   => __( 'Enable Placeholder', 'uabb' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
						),
					),
				),
				'input-colors'         => array(
					'title'  => __( 'Input Color', 'uabb' ),
					'fields' => array(
						'input_text_color'       => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Text Color', 'uabb' ),
							'default'     => '333333',
							'show_alpha'  => true,
							'show_reset'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form .uabb-input-group-wrap input, .uabb-contact-form .uabb-input-group-wrap input[type=text]::placeholder, .uabb-contact-form .uabb-input-group-wrap input[type=email]::placeholder, .uabb-contact-form .uabb-input-group-wrap input[type=tel]::placeholder, .uabb-contact-form .uabb-input-group-wrap textarea::placeholder',
								'property'  => 'color',
								'important' => true,
							),
						),
						'input_background_color' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Background Color', 'uabb' ),
							'default'     => '',
							'show_alpha'  => true,
							'show_reset'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form .uabb-input-group-wrap input, .uabb-contact-form .uabb-input-group-wrap textarea, .uabb-contact-form .uabb-terms-checkbox span:before',
								'property'  => 'background',
								'important' => true,
							),
						),
					),
				),
				'input-border-style'   => array(
					'title'  => __( 'Input Border Style', 'uabb' ),
					'fields' => array(
						'input_border'              => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'uabb' ),
							'responsive' => true,
							'default'    => array(
								'style' => 'solid',
								'color' => 'cccccc',
								'width' => array(
									'top'    => '1',
									'right'  => '1',
									'bottom' => '1',
									'left'   => '1',
								),
							),
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form .uabb-input-group-wrap input, .uabb-contact-form .uabb-input-group-wrap textarea, .uabb-contact-form .uabb-terms-checkbox span:before',
								'important' => true,
							),
						),
						'input_border_active_color' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Border Active Color', 'uabb' ),
							'default'     => 'bbbbbb',
							'show_alpha'  => true,
							'show_reset'  => true,
							'preview'     => array(
								'type' => 'none',
							),
						),
					),
				),
				'input-fields'         => array(
					'title'  => __( 'Input Size and Alignment', 'uabb' ),
					'fields' => array(
						'input_text_align'         => array(
							'type'    => 'align',
							'label'   => __( 'Text Alignment', 'uabb' ),
							'default' => 'left',
							'preview' => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form .uabb-input-group-wrap input, .uabb-contact-form textarea',
								'property'  => 'text-align',
								'important' => true,
							),
						),
						'msg_height'               => array(
							'type'        => 'unit',
							'label'       => __( 'Textarea Height', 'uabb' ),
							'placeholder' => '130',
							'slider'      => true,
							'units'       => array( 'px' ),
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form textarea',
								'property'  => 'min-height',
								'unit'      => 'px',
								'important' => true,
							),
						),
						'input_vertical_padding'   => array(
							'type'    => 'unit',
							'label'   => __( 'Vertical Padding', 'uabb' ),
							'default' => '',
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector'  => '.uabb-contact-form .uabb-input-group-wrap input',
										'property'  => 'padding-top',
										'unit'      => 'px',
										'important' => true,
									),
									array(
										'selector'  => '.uabb-contact-form .uabb-input-group-wrap input',
										'property'  => 'padding-bottom',
										'unit'      => 'px',
										'important' => true,
									),
								),
							),
						),
						'input_horizontal_padding' => array(
							'type'    => 'unit',
							'label'   => __( 'Horizontal Padding', 'uabb' ),
							'default' => '',
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector'  => '.uabb-contact-form .uabb-input-group-wrap input',
										'property'  => 'padding-left',
										'unit'      => 'px',
										'important' => true,
									),
									array(
										'selector'  => '.uabb-contact-form .uabb-input-group-wrap input',
										'property'  => 'padding-right',
										'unit'      => 'px',
										'important' => true,
									),
								),
							),
						),
					),
				),
				'form-style'           => array(
					'title'  => 'Form Style',
					'fields' => array(
						'form_bg_type'           => array(
							'type'    => 'select',
							'label'   => __( 'Background Type', 'uabb' ),
							'default' => 'none',
							'options' => array(
								'none'     => __( 'None', 'uabb' ),
								'color'    => __( 'Color', 'uabb' ),
								'gradient' => __( 'Gradient', 'uabb' ),
								'image'    => __( 'Image', 'uabb' ),
							),
							'toggle'  => array(
								'color'    => array(
									'fields' => array( 'form_bg_color', 'form_bg_color_opc' ),
								),
								'image'    => array(
									'fields' => array( 'form_bg_img', 'form_bg_img_pos', 'form_bg_img_size', 'form_bg_img_repeat' ),
								),
								'gradient' => array(
									'fields' => array( 'form_bg_gradient' ),
								),
							),
						),
						'form_bg_gradient'       => array(
							'type'    => 'uabb-gradient',
							'label'   => __( 'Gradient', 'uabb' ),
							'default' => array(
								'color_one' => '',
								'color_two' => '',
								'direction' => 'left_right',
								'angle'     => '0',
							),
						),
						'form_bg_img'            => array(
							'type'        => 'photo',
							'label'       => __( 'Photo', 'uabb' ),
							'show_remove' => true,
						),
						'form_bg_img_pos'        => array(
							'type'    => 'select',
							'label'   => __( 'Background Position', 'uabb' ),
							'default' => 'center center',
							'options' => array(
								'left top'      => __( 'Left Top', 'uabb' ),
								'left center'   => __( 'Left Center', 'uabb' ),
								'left bottom'   => __( 'Left Bottom', 'uabb' ),
								'center top'    => __( 'Center Top', 'uabb' ),
								'center center' => __( 'Center Center', 'uabb' ),
								'center bottom' => __( 'Center Bottom', 'uabb' ),
								'right top'     => __( 'Right Top', 'uabb' ),
								'right center'  => __( 'Right Center', 'uabb' ),
								'right bottom'  => __( 'Right Bottom', 'uabb' ),
							),
						),
						'form_bg_img_repeat'     => array(
							'type'    => 'select',
							'label'   => __( 'Background Repeat', 'uabb' ),
							'default' => 'repeat',
							'options' => array(
								'no-repeat' => __( 'No Repeat', 'uabb' ),
								'repeat'    => __( 'Repeat All', 'uabb' ),
								'repeat-x'  => __( 'Repeat Horizontally', 'uabb' ),
								'repeat-y'  => __( 'Repeat Vertically', 'uabb' ),
							),
						),
						'form_bg_img_size'       => array(
							'type'    => 'select',
							'label'   => __( 'Background Size', 'uabb' ),
							'default' => 'cover',
							'options' => array(
								'contain' => __( 'Contain', 'uabb' ),
								'cover'   => __( 'Cover', 'uabb' ),
								'initial' => __( 'Initial', 'uabb' ),
								'inherit' => __( 'Inherit', 'uabb' ),
							),
						),
						'form_bg_color'          => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Background Color', 'uabb' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form',
								'property'  => 'background-color',
								'important' => true,
							),
						),
						'form_spacing_dimension' => array(
							'type'       => 'dimension',
							'label'      => __( 'Form Padding', 'uabb' ),
							'slider'     => true,
							'units'      => array( 'px' ),
							'responsive' => true,
						),
						'form_radius'            => array(
							'type'    => 'unit',
							'label'   => __( 'Round Corner', 'uabb' ),
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form',
								'property'  => 'border-radius',
								'unit'      => 'px',
								'important' => true,
							),
						),
					),
				),
				'error-style'          => array(
					'title'  => __( 'Validation Style', 'uabb' ),
					'fields' => array(
						'invalid_msg_color'    => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Input Message Color', 'uabb' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'help'        => __( 'This color would be applied to validation message and error icon in input field', 'uabb' ),
							'preview'     => 'none',
						),
						'invalid_border_color' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Input border color', 'uabb' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'help'        => __( 'If the validation is not right then this color would be applied to input border', 'uabb' ),
							'preview'     => 'none',
						),
						'success_msg_color'    => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Success Message Color', 'uabb' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => 'none',
						),
						'error_msg_color'      => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Error Message color', 'uabb' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => 'none',
						),
						'error_msg_alignment'  => array(
							'type'    => 'align',
							'label'   => __( 'Message Alignment', 'uabb' ),
							'default' => 'left',
							'preview' => 'none',
						),
					),
				),
				'terms-checkbox-style' => array(
					'title'  => __( 'Terms Checkbox Style', 'uabb' ),
					'fields' => array(
						'checkbox_size'           => array(
							'type'    => 'unit',
							'label'   => __( 'Size', 'uabb' ),
							'default' => '24',
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector'  => '.uabb-contact-form .uabb-input-group-wrap input[type="checkbox"] + span:before',
										'property'  => 'width',
										'unit'      => 'px',
										'important' => true,
									),
									array(
										'selector'  => '.uabb-contact-form .uabb-input-group-wrap input[type="checkbox"] + span:before',
										'property'  => 'height',
										'unit'      => 'px',
										'important' => true,
									),
								),
							),
						),
						'checkbox_bgcolor'        => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Background Color', 'uabb' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form .uabb-input-group-wrap input[type="checkbox"] + span:before',
								'property'  => 'background',
								'important' => true,
							),
						),
						'checkbox_selected_color' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Checked Color', 'uabb' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'refresh',
							),
						),
						'checkbox_border_width'   => array(
							'type'    => 'unit',
							'label'   => __( 'Border Width', 'uabb' ),
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form .uabb-input-group-wrap input[type="checkbox"] + span:before',
								'property'  => 'border-width',
								'unit'      => 'px',
								'important' => true,
							),
						),
						'checkbox_border_color'   => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Border Color', 'uabb' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form .uabb-input-group-wrap input[type="checkbox"] + span:before',
								'property'  => 'border-color',
								'important' => true,
							),
						),
						'checkbox_border_radius'  => array(
							'type'    => 'unit',
							'label'   => __( 'Checkbox Round Corners', 'uabb' ),
							'default' => '0',
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form .uabb-input-group-wrap input[type="checkbox"] + span:before',
								'property'  => 'border-radius',
								'unit'      => 'px',
								'important' => true,
							),
						),
					),
				),
			),
		),
		'button'     => array(
			'title'    => __( 'Button', 'uabb' ),
			'sections' => array(
				'button-style'  => array(
					'title'  => __( 'Submit Button', 'uabb' ),
					'fields' => array(
						'btn_text'            => array(
							'type'    => 'text',
							'label'   => __( 'Text', 'uabb' ),
							'default' => 'SEND YOUR MESSAGE',
							'preview' => array(
								'type'      => 'text',
								'selector'  => '.uabb-contact-form-submit span',
								'important' => true,
							),
						),
						'btn_icon'            => array(
							'type'        => 'icon',
							'label'       => __( 'Icon', 'uabb' ),
							'show_remove' => true,
						),
						'btn_icon_position'   => array(
							'type'    => 'select',
							'label'   => __( 'Icon Position', 'uabb' ),
							'default' => 'before',
							'options' => array(
								'before' => __( 'Before Text', 'uabb' ),
								'after'  => __( 'After Text', 'uabb' ),
							),
						),
						'btn_processing_text' => array(
							'type'    => 'text',
							'label'   => __( 'Processing Text', 'uabb' ),
							'default' => 'Please Wait...',
							'preview' => array(
								'type' => 'none',
							),
						),
					),
				),
				'btn-style'     => array(
					'title'  => __( 'Button Style', 'uabb' ),
					'fields' => array(
						'btn_style'        => array(
							'type'    => 'select',
							'label'   => __( 'Style', 'uabb' ),
							'default' => 'flat',
							'options' => array(
								'flat'        => __( 'Flat', 'uabb' ),
								'transparent' => __( 'Transparent', 'uabb' ),
								'gradient'    => __( 'Gradient', 'uabb' ),
								'3d'          => __( '3D', 'uabb' ),
							),
							'toggle'  => array(
								'transparent' => array(
									'fields' => array( 'btn_border_width', 'hover_attribute' ),
								),
							),
						),
						'btn_border_width' => array(
							'type'   => 'unit',
							'label'  => __( 'Border Width', 'uabb' ),
							'slider' => true,
							'units'  => array( 'px' ),
						),
					),
				),
				'btn-colors'    => array(
					'title'  => __( 'Button Colors', 'uabb' ),
					'fields' => array(
						'btn_text_color'             => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Text Color', 'uabb' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form .uabb-contact-form-submit .uabb-contact-form-button-text',
								'property'  => 'color',
								'important' => true,
							),
						),
						'btn_text_hover_color'       => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Text Hover Color', 'uabb' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'none',
							),
						),
						'btn_background_color'       => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Background Color', 'uabb' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form .uabb-contact-form-submit',
								'property'  => 'background',
								'important' => true,
							),
						),
						'btn_background_hover_color' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Background Hover Color', 'uabb' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'none',
							),
						),
						'hover_attribute'            => array(
							'type'    => 'select',
							'label'   => __( 'Apply Hover Color To', 'uabb' ),
							'default' => 'bg',
							'options' => array(
								'border' => __( 'Border', 'uabb' ),
								'bg'     => __( 'Background', 'uabb' ),
							),
							'width'   => '75px',
						),
					),
				),
				'btn-structure' => array(
					'title'  => __( 'Button Structure', 'uabb' ),
					'fields' => array(
						'btn_align'              => array(
							'type'    => 'select',
							'label'   => __( 'Button Width/Alignment', 'uabb' ),
							'default' => 'left',
							'options' => array(
								'full'   => __( 'Full', 'uabb' ),
								'left'   => __( 'Left', 'uabb' ),
								'center' => __( 'Center', 'uabb' ),
								'right'  => __( 'Right', 'uabb' ),
							),
						),
						'btn_radius'             => array(
							'type'    => 'unit',
							'label'   => __( 'Border Radius', 'uabb' ),
							'default' => '',
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form .uabb-contact-form-submit',
								'property'  => 'border-radius',
								'unit'      => 'px',
								'important' => true,
							),
						),
						'btn_vertical_padding'   => array(
							'type'    => 'unit',
							'label'   => __( 'Vertical Padding', 'uabb' ),
							'default' => '',
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector'  => '.uabb-contact-form .uabb-contact-form-submit',
										'property'  => 'padding-top',
										'unit'      => 'px',
										'important' => true,
									),
									array(
										'selector'  => '.uabb-contact-form .uabb-contact-form-submit',
										'property'  => 'padding-bottom',
										'unit'      => 'px',
										'important' => true,
									),
								),
							),
						),
						'btn_horizontal_padding' => array(
							'type'    => 'unit',
							'label'   => __( 'Horizontal Padding', 'uabb' ),
							'default' => '',
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector'  => '.uabb-contact-form .uabb-contact-form-submit',
										'property'  => 'padding-left',
										'unit'      => 'px',
										'important' => true,
									),
									array(
										'selector'  => '.uabb-contact-form .uabb-contact-form-submit',
										'property'  => 'padding-right',
										'unit'      => 'px',
										'important' => true,
									),
								),
							),
						),
					),
				),
			),
		),
		'template'   => array(
			'title'    => __( 'Email', 'uabb' ),
			'sections' => array(
				'email-subject'  => array(
					'title'  => __( 'Email & Subject', 'uabb' ),
					'fields' => array(
						'email_template_info' => array(
							'type'     => 'uabb-msgbox',
							'label'    => '',
							'msg_type' => 'info',
							'content'  => __( 'In the following subject & email template fields, you can use these mail-tags:<br/><br/><span class="uabb_cf_mail_tags"></span>', 'uabb' ),
						),
						'mailto_email'        => array(
							'type'        => 'text',
							'label'       => __( 'Send To Email', 'uabb' ),
							'default'     => '',
							'placeholder' => 'example@mail.com',
							'help'        => __( 'The contact form will send to this e-mail. Defaults to the admin email.', 'uabb' ),
							'preview'     => array(
								'type' => 'none',
							),
							'connections' => array( 'html' ),
						),
						'email_subject'       => array(
							'type'    => 'text',
							'label'   => __( 'Email Subject', 'uabb' ),
							'default' => '[SUBJECT]',
							'help'    => __( 'The subject of email received, by default if you have enabled subject it would be shown by shortcode or you can manually add yourself', 'uabb' ),
						),
					),
				),
				'email-template' => array(
					'title'  => __( 'Email Template', 'uabb' ),
					'fields' => array(
						'email_template' => array(
							'type'        => 'textarea',
							'label'       => '',
							'rows'        => '10',
							'default'     => $default_template,
							'description' => __( 'Here you can design the email you receive', 'uabb' ),
						),
						'email_sccess'   => array(
							'type'    => 'text',
							'label'   => __( 'Success Message', 'uabb' ),
							'default' => __( 'Message Sent!', 'uabb' ),
						),
						'email_error'    => array(
							'type'    => 'text',
							'label'   => __( 'Error Message', 'uabb' ),
							'default' => __( 'Message failed. Please try again.', 'uabb' ),
						),
					),
				),
			),
		),
		'typography' => array(
			'title'    => __( 'Typography', 'uabb' ),
			'sections' => array(
				'input_typography'    => array(
					'title'  => __( 'Input Text', 'uabb' ),
					'fields' => array(
						'input_typo'             => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'uabb' ),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => 'input, textarea',
								'important' => true,
							),
						),
						'input_top_margin'       => array(
							'type'    => 'unit',
							'label'   => __( 'Input Top Margin', 'uabb' ),
							'default' => '',
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'      => 'css',
								'type'      => 'refresh',
								'selector'  => 'input, .checkbox-label',
								'property'  => 'margin-top',
								'unit'      => 'px',
								'important' => true,
							),
						),
						'input_bottom_margin'    => array(
							'type'    => 'unit',
							'label'   => __( 'Input Bottom Margin', 'uabb' ),
							'default' => '',
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'      => 'css',
								'type'      => 'refresh',
								'selector'  => 'input, .checkbox-label',
								'property'  => 'margin-bottom',
								'unit'      => 'px',
								'important' => true,
							),
						),
						'textarea_top_margin'    => array(
							'type'    => 'unit',
							'label'   => __( 'Textarea Top Margin', 'uabb' ),
							'default' => '',
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'      => 'css',
								'type'      => 'refresh',
								'selector'  => 'textarea',
								'property'  => 'margin-top',
								'unit'      => 'px',
								'important' => true,
							),
						),
						'textarea_bottom_margin' => array(
							'type'    => 'unit',
							'label'   => __( 'Textarea Bottom Margin', 'uabb' ),
							'default' => '',
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'      => 'css',
								'type'      => 'refresh',
								'selector'  => 'textarea',
								'property'  => 'margin-bottom',
								'unit'      => 'px',
								'important' => true,
							),
						),
					),
				),
				'button_typography'   => array(
					'title'  => __( 'Button Text', 'uabb' ),
					'fields' => array(
						'button_typo'       => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'uabb' ),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form-submit',
								'important' => true,
							),
						),
						'btn_top_margin'    => array(
							'type'        => 'unit',
							'label'       => __( 'Top Margin', 'uabb' ),
							'placeholder' => '',
							'slider'      => true,
							'units'       => array( 'px' ),
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-submit-btn',
								'property'  => 'margin-top',
								'unit'      => 'px',
								'important' => true,
							),
						),
						'btn_bottom_margin' => array(
							'type'    => 'unit',
							'label'   => __( 'Bottom Margin', 'uabb' ),
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'      => 'css',
								'property'  => 'margin-bottom',
								'selector'  => '.uabb-submit-btn',
								'unit'      => 'px',
								'important' => true,
							),
						),
					),
				),
				'label_typography'    => array(
					'title'  => __( 'Label Text', 'uabb' ),
					'fields' => array(
						'label_typo'          => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'uabb' ),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form label',
								'important' => true,
							),
						),
						'label_color'         => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Color', 'uabb' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form label',
								'property'  => 'color',
								'important' => true,
							),
						),
						'label_top_margin'    => array(
							'type'    => 'unit',
							'label'   => __( 'Label Top Margin', 'uabb' ),
							'default' => '',
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form label',
								'property'  => 'margin-top',
								'unit'      => 'px',
								'important' => true,
							),
						),
						'label_bottom_margin' => array(
							'type'    => 'unit',
							'label'   => __( 'Label Bottom Margin', 'uabb' ),
							'default' => '',
							'slider'  => true,
							'units'   => array( 'px' ),
							'preview' => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form label',
								'property'  => 'margin-bottom',
								'unit'      => 'px',
								'important' => true,
							),
						),
					),
				),
				'checkbox_typography' => array(
					'title'  => __( 'Checkbox Text', 'uabb' ),
					'fields' => array(
						'checkbox_typo'  => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'uabb' ),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form .uabb-terms-label',
								'important' => true,
							),
						),
						'checkbox_color' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Color', 'uabb' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form .uabb-terms-label',
								'property'  => 'color',
								'important' => true,
							),
						),
					),
				),
				'terms_typography'    => array(
					'title'  => __( 'Terms and Conditions Text', 'uabb' ),
					'fields' => array(
						'terms_typo'  => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'uabb' ),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form .uabb-terms-text',
								'important' => true,
							),
						),
						'terms_color' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Color', 'uabb' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.uabb-contact-form .uabb-terms-text',
								'property'  => 'color',
								'important' => true,
							),
						),
					),
				),
			),
		),
		'reCAPTCHA'  => array(
			'title'       => __( 'reCAPTCHA', 'uabb' ),
			'sections'    => array(
				'recaptcha_general' => array(
					'title'  => '',
					'fields' => array(
						'uabb_recaptcha_toggle'     => array(
							'type'    => 'select',
							'label'   => 'reCAPTCHA Field',
							'default' => 'hide',
							'options' => array(
								'show' => __( 'Show', 'uabb' ),
								'hide' => __( 'Hide', 'uabb' ),
							),
							'help'    => __( 'If you want to show this field, please provide valid Site and Secret Keys.', 'uabb' ),
							'preview' => array(
								'type' => 'none',
							),
							'toggle'  => array(
								'show' => array(
									'fields' => array( 'uabb_recaptcha_theme', 'uabb_recaptcha_site_key', 'uabb_recaptcha_secret_key' ),
								),
							),
						),
						'uabb_recaptcha_site_key'   => array(
							'type'    => 'text',
							'label'   => __( 'Site Key', 'uabb' ),
							'default' => '',
							'preview' => array(
								'type' => 'none',
							),
						),
						'uabb_recaptcha_secret_key' => array(
							'type'    => 'text',
							'label'   => __( 'Secret Key', 'uabb' ),
							'default' => '',
							'preview' => array(
								'type' => 'none',
							),
						),
						'uabb_recaptcha_theme'      => array(
							'type'    => 'select',
							'label'   => __( 'Theme', 'uabb' ),
							'default' => 'light',
							'options' => array(
								'light' => __( 'Light', 'uabb' ),
								'dark'  => __( 'Dark', 'uabb' ),
							),
						),
					),
				),
			),
			'description' => sprintf( /* translators: a%s: search term */ __( 'Please register keys for your website at <a%s>Google Admin Console</a>.', 'uabb' ), ' href="https://www.google.com/recaptcha/admin" target="_blank"' ),
		),
		'uabb_docs'  => array(
			'title'    => __( 'Docs', 'uabb' ),
			'sections' => array(
				'knowledge_base' => array(
					'title'  => __( 'Helpful Information', 'uabb' ),
					'fields' => array(
						'uabb_helpful_information' => array(
							'type'    => 'raw',
							'content' => '<ul class="uabb-docs-list" data-branding=' . $branding . '>

								<li class="uabb-docs-list-item"> <i class="ua-icon ua-icon-chevron-right2"> </i> <a href="https://www.ultimatebeaver.com/docs/design-email-template-to-receive-user-information-within-mail/?utm_source=uabb-pro-backend&utm_medium=module-editor-screen&utm_campaign=contact-form-module" target="_blank" rel="noopener"> How to Design the Email Template to Receive User Information within the email? </a> </li>

							 </ul>',
						),
					),
				),
			),
		),
	)
);
