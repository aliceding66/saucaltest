<?php
/**
 * APIBlock class.
 *
 * @package SaucalBlocks\ApiBlock
 * @subpackage SubApiBlock
 */

namespace SaucalBlocks;

/**
 * Class ApiBlock
 *
 * @package SaucalBlocks
 */
class ApiBlock extends ABlock {
	/**
	 * ApiBlock constructor.
	 *
	 * @param string $dir __DIR__ value.
	 */
	public function __construct( $dir ) {
		parent::__construct( $dir );

		$this->enqueue_editor_script();
		$this->enqueue_style();

		$this->initiate_template_data();
	}

	/**
	 * Returns values that are used in Mustache template.
	 *
	 * @param array $additional_template_data Usually $attributes passed from render() function.
	 *
	 * @return array
	 */
	public function get_template_data( $additional_template_data = [] ) {
		$template_data = parent::get_template_data( $additional_template_data );

		if ( ! empty( $additional_template_data['xxxxx'] ) && ! empty( $additional_template_data['xxxx'] ) ) {
			$template_data['xxxx'] = xxxxxxxx;
			// here is the value you get from block editor there and put it into the frontend template
		} else {

		}

		// also we can get data from external ones
		$request = wp_remote_get('https://httpbin.org/post');
		$data = json_decode( wp_remote_retrieve_body( $request ) );

		$additional_template_data['xxxxx'] = $data->xxxxxxxx;
		$additional_template_data['yyyyy'] = $data->yyyyyyy;

		return $template_data;
	}

	/**
	 * Function that is called inside "render_callback".
	 *
	 * @param array  $attributes Block's attributes.
	 * @param string $inner_blocks_content <InnerBlocks.Content /> passed from index.js.
	 *
	 * @return string
	 */
	public function render( $attributes, $inner_blocks_content ) {
		$wrapper_properties = $this->get_wrapper_properties(
			$attributes,
			[
				'class' => 'api-block mx-auto text-center',
			]
		);

		$block_template = $this->load_template( 'templates/block.mustache', $this->get_template_data( $attributes ) );

		return <<< HTML
<div {$wrapper_properties}>
	{$block_template}
</div>
HTML;
	}

	/**
	 * Initiates static template data.
	 */
	private function initiate_template_data() {
		$this->set_static_template_data(
			[
				'default_image' => fp_get_asset( 'images/api-block/api-block-default.png' ),
			],
			'blueCtaBarTemplateData'
		);
	}

	/**
	 * Returns default title.
	 *
	 * @return string
	 */
	private function get_default_title() {
		return _x( 'This is the Title.', 'api-block Title', 'saucal-website' );
	}

	/**
	 * Initiate block attributes.
	 *
	 * @param string $override_block_name Pass block name if in ACF file is different from in block.json.
	 */
	protected function initiate_block_attributes( $override_block_name = '' ) {
		parent::initiate_block_attributes();

		$this->block_attributes['api_block_xxxx']['default'] = 'xxxxxxxxxxxxxx';
		$this->block_attributes['api_block_xxxxx']['default'] = 'xxxxxxxxxxxxxx';
		$this->block_attributes['api_block_xxxxxx']['default'] = 'xxxxxxxxxxxxxx';
		$this->block_attributes['api_block_xxxxxxx']['default'] = 'xxxxxxxxxxxxxx';

	}
}
