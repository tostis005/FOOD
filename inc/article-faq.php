<?php
/**
 * Visible article FAQ helpers.
 *
 * FAQ rich results are no longer used by Google Search, so this module focuses
 * on useful on-page content and accessibility rather than FAQ structured data.
 *
 * @package FOOD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function food_article_faq_items( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	$raw     = (string) get_post_meta( $post_id, '_food_faq', true );
	$data    = json_decode( $raw, true );
	if ( ! is_array( $data ) ) {
		return array();
	}

	$items = array();
	foreach ( $data as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}
		$question = trim( wp_strip_all_tags( (string) ( $item['question'] ?? '' ) ) );
		$answer   = trim( wp_strip_all_tags( (string) ( $item['answer'] ?? '' ) ) );
		if ( '' === $question || '' === $answer ) {
			continue;
		}
		$items[] = array(
			'question' => $question,
			'answer'   => $answer,
		);
	}
	return $items;
}

function food_article_faq_word_count( $post_id = 0 ) {
	$text = '';
	foreach ( food_article_faq_items( $post_id ) as $item ) {
		$text .= ' ' . $item['question'] . ' ' . $item['answer'];
	}
	return function_exists( 'food_word_count_unicode' ) ? food_word_count_unicode( $text ) : str_word_count( $text );
}

function food_article_faq_html( $post_id = 0, $language = '' ) {
	$items = food_article_faq_items( $post_id );
	if ( empty( $items ) ) {
		return '';
	}

	$language = $language ?: ( function_exists( 'food_current_language' ) ? food_current_language() : 'es' );
	$title    = 'en' === $language ? 'Frequently asked questions' : 'Preguntas frecuentes';
	$id       = 'article-faq';

	$html  = '<section class="article-faq" aria-labelledby="' . esc_attr( $id ) . '">';
	$html .= '<h2 id="' . esc_attr( $id ) . '">' . esc_html( $title ) . '</h2>';
	$html .= '<div class="article-faq-list">';
	foreach ( $items as $item ) {
		$html .= '<details class="article-faq-item">';
		$html .= '<summary>' . esc_html( $item['question'] ) . '</summary>';
		$html .= '<div class="article-faq-answer"><p>' . esc_html( $item['answer'] ) . '</p></div>';
		$html .= '</details>';
	}
	$html .= '</div></section>';

	return $html;
}
