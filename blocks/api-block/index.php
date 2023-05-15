<?php
/**
 * ApiBlock block.
 *
 * @package SaucalBlocks\ApiBlock
 * @subpackage SubApiBlock
 */

/**
 * Fetch data from API and then display in frontend
 */
function saucal_api_block_init() {
	$block = new \FreshpressBlocks\ApiBlock( __DIR__ );
	$block->register();
}

add_action( 'init', 'saucal_api_block_init' );
