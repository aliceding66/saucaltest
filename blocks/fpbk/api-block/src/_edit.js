import { InnerBlocks, InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import EditorControls from 'scripts/components/_EditorControls';
import { getCommonBlockSettingsClass } from 'scripts/components/EditorControls/_helpers';
import { name as blockName } from '../block.json';

export default function ( { attributes, clientId, setAttributes } ) {
	const blockProps = useBlockProps( {
		className: `api-block ${ getCommonBlockSettingsClass( attributes ) }`,
	} );

	const templateData = { ...attributes };

	let buttonClassName = 'api-block-blue';

	// put the edit frontend here, for example i am using a button here
	return (
		<EditorControls.Context.Provider value={ { attributes, setAttributes, blockName } }>
			<InspectorControls>
				<PanelBody initialOpen={ true }>
					<EditorControls.Link name="button_link" />
					<EditorControls.Text name="button_max_width" />
				</PanelBody>

				<EditorControls.Acf.CommonBlockSettings />
			</InspectorControls>

			<div { ...blockProps }>
				<Template
					className="button pl-3"
					template={ blockTemplate }
					attributes={ templateData }
					components={ {
						button_link: (
							<EditorControls.Link
								inline
								className={ `btn ${ buttonClassName }` }
								name="button_link"
								value={ templateData.button_link }
								onChange={ ( newLink ) => {
									setAttributes( {
										button_link: newLink,
									} );
								} }
							/>
						),
					} }
				/>
			</div>
		</EditorControls.Context.Provider>
	);
}
