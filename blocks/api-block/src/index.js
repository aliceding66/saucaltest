import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';
import config from '../block.json';
import Edit from './_edit';
import './style.scss';
import saucalLogo from 'images/logos/saucal-logo.svg'; // import logo

const { name: blockName, ...restConfig } = config;

registerBlockType( blockName, {
	...restConfig,
	icon: <img src={ saucalLogo } alt="Saucal Logo" />,
	edit: Edit,
	save: () => <InnerBlocks.Content />,
} );
