<?php
/**
 * InitBlock class.
 *
 * @package SaucalBlocks\InitBlock
 * @subpackage InitBlock
 */

namespace SaucalBlocks;

require_once __DIR__ . '/autoloader.php';

use Mustache_Engine;

/**
 * Class InitBlock.
 *
 * @package SaucalBlocks
 */
class InitBlock extends ACli {
	/**
	 * Stores Mustache template data.
	 *
	 * @var array
	 */
	private $data;
	/**
	 * Block's path.
	 *
	 * @var string
	 */
	private $blocks_path;

	/**
	 * InitBlock constructor.
	 */
	public function run() {
		$this->gather_data();
		$this->generate_block();
	}

	/**
	 * Fetches input from user and prepare other data based on input.
	 */
	public function gather_data() {
		$this->data['name'] = $this->get_text( 'Name of your block in kebab-case (e.g., awesome-banner)' );
		$this->data['title'] = $this->get_text( 'Title of your block in Title Case (e.g., Awesome Banner)' );
		// put all the extra ones here

		$this->blocks_path = __DIR__ . '/../../../blocks/SaucalBlocks/' . $this->data['name'];
	}

	/**
	 * Generate new block's directory and files.
	 */
	public function generate_block() {
		if ( is_dir( $this->blocks_path ) ) {
			$this->throw_error( 'Block already exists.' );
		}

		$this->write_line( "Creating dir {$this->blocks_path}" );
		mkdir( $this->blocks_path );
		mkdir( $this->blocks_path . '/templates' );
		mkdir( $this->blocks_path . '/src' );
		$this->save_file( 'index.php' );
		$this->save_file( 'block.json' );
		$this->save_file( 'class-__lower__block.php' );
		$this->save_file( 'src/style.scss' );
		$this->save_file( 'src/index.js' );
		$this->save_file( 'src/_edit.js' );
		$this->save_file( 'templates/block.mustache' );

		if ( $this->data['editorStyles'] ) {
			$this->save_file( 'src/editor.scss' );
		}

		if ( $this->data['frontendJs'] ) {
			mkdir( $this->blocks_path . '/src/frontend' );
			$this->save_file( 'src/frontend/__name__-block.js' );
		}

		if ( $this->data['stateManager'] ) {
			mkdir( $this->blocks_path . '/src/state' );
			mkdir( $this->blocks_path . '/src/state/' . $this->data['stateManagerAttributeName'] );
			$this->save_file( 'src/state/__stateManagerAttributeName__/_actions.js' );
			$this->save_file( 'src/state/__stateManagerAttributeName__/_reducer.js' );
		}

		if ( $this->data['mustachePartial'] ) {
			$this->save_file( 'templates/block.partial.mustache' );
		}

		$this->call_command( 'yarn lint:fix' );
		$this->call_command( 'yarn build' );

		$this->write_line( '' );
		$this->write_line( 'DONE' );
	}

	/**
	 * Generate file from template.
	 *
	 * @param string $input Template filename without ".template" extension.
	 */
	private function save_file( $input ) {
		$output = $input;
		foreach ( $this->data as $key => $value ) {
			$output = str_replace( "__{$key}__", $value, $output );
		}

		$mustache = new Mustache_Engine();

		file_put_contents(
			$this->blocks_path . '/' . $output,
			$mustache->render(
				file_get_contents( __DIR__ . '/init-block-templates/' . $input . '.template' ),
				$this->data
			)
		);

		$this->write_line( 'Created file ' . $this->blocks_path . '/' . $output );
	}

	/**
	 * Converts string into target case.
	 *
	 * @param string $input Input string.
	 * @param string $target Target case.
	 *
	 * @return mixed|string
	 */
	private function case_convert( $input, $target = 'kebab' ) {
		$parts = array_map(
			function( $part ) {
				return mb_strtolower( $part );
			},
			explode( '-', $input )
		);

		switch ( $target ) {
			case 'title':
				return implode(
					' ',
					array_map(
						function( $part ) {
							return mb_strtoupper( substr( $part, 0, 1 ) ) . substr( $part, 1 );
						},
						$parts
					)
				);
			case 'snake':
				return implode( '_', $parts );
			case 'pascal':
				return implode(
					'',
					array_map(
						function( $part ) {
							return mb_strtoupper( substr( $part, 0, 1 ) ) . substr( $part, 1 );
						},
						$parts
					)
				);
			case 'camel':
				return implode(
					'',
					array_map(
						function( $part, $key ) {
							if ( 0 === $key ) {
								  return $part;
							} else {
								return mb_strtoupper( substr( $part, 0, 1 ) ) . substr( $part, 1 );
							}
						},
						$parts,
						array_keys( $parts )
					)
				);
			case 'lower':
				return implode( '', $parts );
			case 'kebab':
			default:
				return $input;
		}
	}
}

$init_block = new InitBlock();
$init_block->run();
