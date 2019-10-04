<?php

/**
 * Handles logic for page data post properties.
 *
 * @since 1.0
 */
final class FLPageDataPost {

	/**
	 * @since 1.0
	 * @param object $settings
	 * @return string
	 */
	static public function get_excerpt( $settings ) {

		global $post;
		$filter = false;
		if ( has_filter( 'the_content', 'FLBuilder::render_content' ) ) {
			remove_filter( 'the_content', 'FLBuilder::render_content' );
			$filter = true;
		}

		if ( is_single() ) {
			$content = ! empty( $post->post_excerpt ) ? apply_filters( 'the_excerpt', get_the_excerpt() ) : null;
		} else {
			$content = apply_filters( 'the_excerpt', get_the_excerpt() );
		}

		$args = apply_filters( 'fl_theme_builder_get_excerpt', array(
			'content' => $content,
			'length'  => is_numeric( $settings->length ) ? $settings->length : 55,
			'more'    => ! empty( $settings->more ) ? $settings->more : '...',
			'trim'    => true,
		), $settings );

		if ( $filter ) {
			add_filter( 'the_content', 'FLBuilder::render_content' );
		}
		if ( isset( $args['trim'] ) && $args['trim'] ) {
			return self::wp_trim_words( $args['content'], $args['length'], $args['more'] );
		}
		return $args['content'];
	}

	static public function wp_trim_words( $text, $num_words = 55, $more = null ) {
		if ( null === $more ) {
			$more = __( '&hellip;' );
		}

		$original_text = $text;

		/*
		* translators: If your word count is based on single characters (e.g. East Asian characters),
		* enter 'characters_excluding_spaces' or 'characters_including_spaces'. Otherwise, enter 'words'.
		* Do not translate into your own language.
		*/
		if ( strpos( _x( 'words', 'Word count type. Do not translate!' ), 'characters' ) === 0 && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
			$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
			preg_match_all( '/./u', $text, $words_array );
			$words_array = array_slice( $words_array[0], 0, $num_words + 1 );
			$sep         = '';
		} else {
			$words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
			$sep         = ' ';
		}

		if ( count( $words_array ) > $num_words ) {
			array_pop( $words_array );
			$text = implode( $sep, $words_array );
			$text = $text . $more;
		} else {
			$text = implode( $sep, $words_array );
		}

		/**
		 * Filters the text content after words have been trimmed.
		 *
		 * @since 3.3.0
		 *
		 * @param string $text          The trimmed text.
		 * @param int    $num_words     The number of words to trim the text to. Default 55.
		 * @param string $more          An optional string to append to the end of the trimmed text, e.g. &hellip;.
		 * @param string $original_text The text before it was trimmed.
		 */
		return apply_filters( 'wp_trim_words', $text, $num_words, $more, $original_text );
	}

	/**
	 * @since 1.0
	 * @deprecated 1.1.3
	 * @param string $length
	 * @return string
	 */
	static public function excerpt_length_filter( $length ) {
		_deprecated_function( __METHOD__, '1.1.3' );
		$settings = FLPageData::get_current_settings();
		return $settings && is_numeric( $settings->length ) ? $settings->length : 55;
	}

	/**
	 * @since 1.0
	 * @deprecated 1.1.3
	 * @param string $more
	 * @return string
	 */
	static public function excerpt_more_filter( $more ) {
		_deprecated_function( __METHOD__, '1.1.3' );
		$settings = FLPageData::get_current_settings();
		return $settings && ! empty( $settings->more ) ? $settings->more : '...';
	}

	/**
	 * @since 1.0
	 * @return string
	 */
	static public function get_content() {
		remove_filter( 'the_content', 'FLBuilder::render_content' );

		$content = apply_filters( 'the_content', get_the_content() );

		$content .= wp_link_pages( array(
			'before'      => '<div class="page-links">' . __( 'Pages:', 'fl-theme-builder' ),
			'after'       => '</div>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>',
			'echo'        => false,
		) );

		add_filter( 'the_content', 'FLBuilder::render_content' );

		return $content;
	}

	/**
	 * @since 1.0
	 * @param object $settings
	 * @return string
	 */
	static public function get_link( $settings ) {
		$href = get_permalink();

		if ( 'title' == $settings->text ) {
			$title = the_title_attribute( array(
				'echo' => false,
			) );
			$text  = get_the_title();
		} else {
			$title = esc_attr( $settings->custom_text );
			$text  = $settings->custom_text;
		}

		return "<a href='{$href}' title='{$title}'>{$text}</a>";
	}

	/**
	 * @since 1.0
	 * @param object $settings
	 * @return string
	 */
	static public function get_date( $settings ) {
		return get_the_date( $settings->format );
	}

	/**
	 * @since 1.0.3
	 * @param object $settings
	 * @return string
	 */
	static public function get_modified_date( $settings ) {

		if ( 'human' == $settings->format ) {
			return human_time_diff( get_the_time( 'U' ) ) . ' ago';
		}
		return get_the_modified_date( $settings->format );
	}

	/**
	 * @since 1.0
	 * @param object $settings
	 * @return string
	 */
	static public function get_featured_image( $settings ) {
		global $post;

		if ( 'tag' == $settings->display ) {

			$class = 'default' == $settings->align ? '' : 'align' . $settings->align;
			$image = get_the_post_thumbnail( $post, $settings->size, array(
				'itemprop' => 'image',
				'class'    => $class,
			) );

			if ( $image && 'yes' == $settings->linked ) {

				$href  = get_the_permalink();
				$title = the_title_attribute( array(
					'echo' => false,
				) );

				return "<a href='{$href}' title='{$title}'>{$image}</a>";
			} else {
				return $image;
			}
		} elseif ( 'url' == $settings->display ) {
			return get_the_post_thumbnail_url( $post, $settings->size );
		} elseif ( 'alt' == $settings->display ) {
			return get_post_meta( get_post_thumbnail_id( $post->ID ), '_wp_attachment_image_alt', true );
		} else {

			$image = get_post( get_post_thumbnail_id( $post->ID ) );

			if ( 'title' == $settings->display ) {
				return $image->post_title;
			} elseif ( 'caption' == $settings->display ) {
				return $image->post_excerpt;
			} elseif ( 'description' == $settings->display ) {
				return $image->post_content;
			}
		}
	}

	/**
	 * @since 1.0
	 * @param object $settings
	 * @return array
	 */
	static public function get_featured_image_url( $settings ) {
		global $post;

		$id  = '';
		$url = '';

		if ( has_post_thumbnail( $post ) ) {
			$id  = get_post_thumbnail_id( $post->ID );
			$url = get_the_post_thumbnail_url( $post, $settings->size );
		} elseif ( isset( $settings->default_img_src ) ) {
			$id  = $settings->default_img;
			$url = $settings->default_img_src;
		}

		return array(
			'id'  => $id,
			'url' => $url,
		);
	}

	/**
	 * @since 1.0
	 * @return array
	 */
	static public function get_attached_images() {
		global $post;

		return array_keys( get_attached_media( 'image', $post->ID ) );
	}

	/**
	 * @since 1.0
	 * @param object $settings
	 * @return string
	 */
	static public function get_terms_list( $settings ) {
		global $post;

		if ( isset( $settings->html_list ) && ( 'ul' === $settings->html_list || 'ol' === $settings->html_list ) ) {
			$seperator  = $settings->html_list;
			$terms_list = self::get_the_term_list( $post->ID, $settings->taxonomy, "<$seperator class='fl-{$settings->taxonomy}'><li>", '</li><li>', "</li></$seperator>", $settings->linked );
		} elseif ( isset( $settings->html_list ) && 'div' === $settings->html_list ) {
			$seperator  = $settings->html_list;
			$terms_list = self::get_the_term_list( $post->ID, $settings->taxonomy, "<$seperator class='fl-{$settings->taxonomy}'><span>", '</span><span>', "</span></$seperator>", $settings->linked );
		} else {
			$terms_list = self::get_the_term_list( $post->ID, $settings->taxonomy, '', $settings->separator, '', $settings->linked );
			if ( 'no' === $settings->linked ) {
				$terms_list = strip_tags( $terms_list );
			}
		}

		return $terms_list;
	}

	/**
	 * @since 1.2.3
	 */
	static public function get_the_term_list( $id, $taxonomy, $before = '', $sep = '', $after = '', $linked ) {
		$terms = get_the_terms( $id, $taxonomy );

		if ( is_wp_error( $terms ) ) {
			return;
		}

		if ( empty( $terms ) ) {
			return false;
		}

		$links = array();

		foreach ( $terms as $term ) {
			$link = get_term_link( $term, $taxonomy );
			if ( is_wp_error( $link ) ) {
				return $link;
			}
			if ( 'no' !== $linked ) {
				$links[] = '<a href="' . esc_url( $link ) . '" rel="tag" class="' . esc_attr( $term->slug ) . '">' . $term->name . '</a>';
			} else {
				$links[] = '<span class="' . esc_attr( $term->slug ) . '">' . $term->name . '</span>';
			}
		}

		/**
		 * Filters the term links for a given taxonomy.
		 *
		 * The dynamic portion of the filter name, `$taxonomy`, refers
		 * to the taxonomy slug.
		 *
		 * @since 2.5.0
		 *
		 * @param string[] $links An array of term links.
		 */
		$term_links = apply_filters( "term_links-{$taxonomy}", $links );

		return $before . join( $sep, $term_links ) . $after;
	}


	/**
	 * @since 1.0
	 * @return array
	 */
	static public function get_taxonomy_options() {
		$taxonomies = get_taxonomies( array(
			'public'  => true,
			'show_ui' => true,
		), 'objects' );
		$result     = array();

		foreach ( $taxonomies as $slug => $data ) {

			if ( stristr( $slug, 'fl-builder' ) ) {
				continue;
			}

			$result[ $slug ] = $data->label;
		}

		return $result;
	}

	/**
	 * @since 1.0
	 * @param object $settings
	 * @return string
	 */
	static public function get_comments_number( $settings ) {
		$zero = isset( $settings->none_text ) ? $settings->none_text : null;
		$one  = isset( $settings->one_text ) ? $settings->one_text : null;
		$more = isset( $settings->more_text ) ? $settings->more_text : null;

		ob_start();

		if ( '1' == $settings->link || 'yes' == $settings->link ) {
			comments_popup_link( $zero, $one, $more );
		} else {
			comments_number( $zero, $one, $more );
		}

		return ob_get_clean();
	}

	/**
	 * @since 1.0
	 * @return string
	 */
	static public function get_comments_url() {
		global $post;

		return get_comments_link( $post->ID );
	}

	/**
	 * @since 1.0
	 * @param object $settings
	 * @return string
	 */
	static public function get_author_name( $settings ) {

		$user = get_userdata( get_the_author_meta( 'ID' ) );
		$name = '';

		if ( ! $user ) {
			return '';
		}

		switch ( $settings->type ) {

			case 'display':
				$name = $user->display_name;
				break;

			case 'first':
				$name = get_user_meta( $user->ID, 'first_name', true );
				break;

			case 'last':
				$name = get_user_meta( $user->ID, 'last_name', true );
				break;

			case 'firstlast':
				$first = get_user_meta( $user->ID, 'first_name', true );
				$last  = get_user_meta( $user->ID, 'last_name', true );
				$name  = $first . ' ' . $last;
				break;

			case 'lastfirst':
				$first = get_user_meta( $user->ID, 'first_name', true );
				$last  = get_user_meta( $user->ID, 'last_name', true );
				$name  = $last . ', ' . $first;
				break;

			case 'nickname':
				$name = $user->nickname;
				break;

			case 'username':
				$name = $user->user_login;
				break;
		}

		if ( $name && 'yes' == $settings->link ) {
			$settings->type = $settings->link_type;
			$name           = '<a href="' . self::get_author_url( $settings ) . '">' . $name . '</a>';
		}

		return $name;
	}

	/**
	 * @since 1.0
	 * @return string
	 */
	static public function get_author_bio() {
		return get_the_author_meta( 'description' );
	}

	/**
	 * @since 1.0
	 * @param object $settings
	 * @return string
	 */
	static public function get_author_url( $settings ) {

		$id  = get_the_author_meta( 'ID' );
		$url = '';

		if ( 'archive' == $settings->type ) {
			$url = get_author_posts_url( $id );
		} elseif ( 'website' == $settings->type ) {
			$user = get_userdata( $id );
			$url  = $user->user_url;
		}

		return $url;
	}

	/**
	 * @since 1.0
	 * @param object $settings
	 * @return string
	 */
	static public function get_author_profile_picture( $settings ) {
		$size   = ! is_numeric( $settings->size ) ? 512 : $settings->size;
		$avatar = get_avatar( get_the_author_meta( 'ID' ), $size );
		if ( '1' == $settings->link || 'yes' == $settings->link ) {
			$settings->type = $settings->link_type;
			$avatar         = '<a href="' . self::get_author_url( $settings ) . '">' . $avatar . '</a>';
		}

		return $avatar;
	}

	/**
	 * @since 1.0
	 * @param object $settings
	 * @return string
	 */
	static public function get_author_profile_picture_url( $settings ) {

		$author = get_the_author_meta( 'ID' );

		// if not in loop use global $post to find author ID
		if ( ! $author ) {
			global $post;
			$author = $post->post_author;
		}
		// We get the url like this because not all custom avatar plugins filter get_avatar_url.
		$size   = ! is_numeric( $settings->size ) ? 512 : $settings->size;
		$avatar = get_avatar( $author, $size );

		preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $avatar, $matches, PREG_SET_ORDER );
		$url = ! empty( $matches ) && isset( $matches[0][1] ) ? $matches[0][1] : '';

		if ( ! $url && isset( $settings->default_img_src ) ) {
			$url = $settings->default_img_src;
		}

		return $url;
	}

	/**
	 * @since 1.0
	 * @param object $settings
	 * @return string
	 */
	static public function get_author_meta( $settings ) {

		if ( empty( $settings->key ) ) {
			return '';
		}

		return get_user_meta( get_the_author_meta( 'ID' ), $settings->key, true );
	}

	/**
	 * @since 1.0
	 * @param object $settings
	 * @return string
	 */
	static public function get_custom_field( $settings ) {
		global $post;

		if ( empty( $settings->key ) ) {
			return '';
		}
		$meta = get_post_meta( $post->ID, $settings->key, true );

		// expression support
		if ( isset( $settings->value ) && '' !== $settings->value && isset( $settings->exp ) && '' !== $settings->exp ) {
			switch ( $settings->exp ) {
				case 'less':
					return ( intval( $meta ) < intval( $settings->value ) ) ? $meta : '';
					break;

				case 'lessequals':
					return ( intval( $meta ) <= intval( $settings->value ) ) ? $meta : '';
					break;

				case 'greater':
					return ( intval( $meta ) > intval( $settings->value ) ) ? $meta : '';
					break;

				case 'greaterequals':
					return ( intval( $meta ) >= intval( $settings->value ) ) ? $meta : '';
					break;

				case 'equals':
					return ( $meta === $settings->value ) ? $meta : '';
					break;

				case 'notequals':
					return ( $meta !== $settings->value ) ? $meta : '';
					break;

				default:
					break;
			}
		}

		if ( isset( $settings->value ) && '' !== $settings->value ) {
			return ( $settings->value == $meta ) ? $meta : '';
		}
		return $meta;
	}

	/**
	* @return string
	*/
	static public function get_id() {
		global $post;
		return (string) $post->ID;
	}

	/**
	* @return string
	*/
	static public function get_slug() {
		global $post;
		return $post->post_name;
	}
}
