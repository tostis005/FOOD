<?php
/**
 * On-page table of contents for substantial Quinnoa articles.
 *
 * IDs are added at render time only; source JSON stays untouched.
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add stable IDs to editorial H2 headings and return a compact TOC.
 *
 * Sources and FAQ headings are intentionally excluded from the TOC because
 * they already have direct navigation elsewhere on the page.
 *
 * @param string $content  Rendered article HTML.
 * @param string $language es|en.
 * @return array{content:string,toc:string,count:int}
 */
function food_article_toc_build( $content, $language = 'es' ) {
	$content  = (string) $content;
	$language = 'en' === $language ? 'en' : 'es';
	$entries  = array();
	$used     = array();

	$updated = preg_replace_callback(
		'#<h2(?P<attrs>[^>]*)>(?P<label>.*?)</h2>#is',
		function( $match ) use ( &$entries, &$used ) {
			$attrs = isset( $match['attrs'] ) ? (string) $match['attrs'] : '';
			$label_html = isset( $match['label'] ) ? (string) $match['label'] : '';
			$label = trim( wp_strip_all_tags( html_entity_decode( $label_html, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
			if ( '' === $label ) {
				return $match[0];
			}

			$id = '';
			if ( preg_match( '/\bid\s*=\s*(["\'])(.*?)\1/i', $attrs, $id_match ) ) {
				$id = sanitize_title( $id_match[2] );
			}

			if ( in_array( $id, array( 'article-sources', 'article-faq' ), true ) ) {
				return $match[0];
			}

			if ( '' === $id ) {
				$base = sanitize_title( $label );
				if ( '' === $base ) {
					$base = 'section';
				}
				$id = $base;
				$suffix = 2;
				while ( isset( $used[ $id ] ) ) {
					$id = $base . '-' . $suffix;
					$suffix++;
				}
				$attrs .= ' id="' . esc_attr( $id ) . '"';
			} elseif ( isset( $used[ $id ] ) ) {
				$base = $id;
				$suffix = 2;
				while ( isset( $used[ $id ] ) ) {
					$id = $base . '-' . $suffix;
					$suffix++;
				}
				$attrs = preg_replace(
					'/\bid\s*=\s*(["\']).*?\1/i',
					'id="' . esc_attr( $id ) . '"',
					$attrs,
					1
				);
			}

			$used[ $id ] = true;
			$entries[] = array(
				'id'    => $id,
				'label' => $label,
			);
			return '<h2' . $attrs . '>' . $label_html . '</h2>';
		},
		$content
	);

	if ( ! is_string( $updated ) ) {
		$updated = $content;
	}
	if ( count( $entries ) < 4 ) {
		return array(
			'content' => $updated,
			'toc'     => '',
			'count'   => count( $entries ),
		);
	}

	$title = 'en' === $language ? 'In this article' : 'En este artículo';
	$aria  = 'en' === $language ? 'Article contents' : 'Índice del artículo';
	$html  = '<nav class="article-toc" aria-label="' . esc_attr( $aria ) . '">';
	$html .= '<strong class="article-toc__title">' . esc_html( $title ) . '</strong>';
	$html .= '<ol class="article-toc__list">';
	foreach ( $entries as $entry ) {
		$html .= '<li><a href="#' . esc_attr( $entry['id'] ) . '">' . esc_html( $entry['label'] ) . '</a></li>';
	}
	$html .= '</ol></nav>';

	return array(
		'content' => $updated,
		'toc'     => $html,
		'count'   => count( $entries ),
	);
}
