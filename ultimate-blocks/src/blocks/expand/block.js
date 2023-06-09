import { ExpandRoot } from "./components";

import icon from "./icon";

import { useEffect } from "react";

const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

const { RichText, InnerBlocks, BlockControls, AlignmentToolbar } =
	wp.blockEditor || wp.editor;

const { withSelect, withDispatch } = wp.data;

const { compose } = wp.compose;

registerBlockType("ub/expand", {
	title: __("Expand"),
	description: __("Expand Block lets you add expandable content. You can hide some part of your content initially. Upon clicking on ‘Show More’ it will show.", "ultimate-blocks"),
	icon: icon,
	category: "ultimateblocks",
	keywords: [
		__("Preview"),
		__("Hidden Content"),
		__("Ultimate Blocks"),
		__("Show"),
		__("Hide"),
		__("Toggle"),
	],
	attributes: {
		blockID: {
			type: "string",
			default: "",
		},
		initialShow: {
			type: "boolean",
			default: false,
		},
		toggleAlign: {
			type: "string",
			default: "left",
		},
		allowScroll: {
			type: "boolean",
			default: false,
		},
		scrollOption: {
			type: "string",
			default: "auto", //other options: namedelement, fixedamount, off
		},
		scrollOffset: {
			type: "number",
			default: 0,
		},
		scrollTarget: {
			type: "string",
			default: "",
		},
		scrollTargetType: {
			type: "string",
			default: "id", //other types: class, element
		},
	},
	example: {},
	edit: compose([
		withSelect((select, ownProps) => {
			const {
				getBlock,
				getSelectedBlockClientId,
				getClientIdsWithDescendants,
			} = select("core/block-editor") || select("core/editor");

			const { clientId } = ownProps;

			return {
				block: getBlock(clientId),
				getBlock,
				getClientIdsWithDescendants,
				getSelectedBlockClientId,
			};
		}),
		withDispatch((dispatch) => {
			const { updateBlockAttributes, insertBlock } =
				dispatch("core/block-editor") || dispatch("core/editor");

			return {
				updateBlockAttributes,
				insertBlock,
			};
		}),
	])(ExpandRoot),

	save: () => <InnerBlocks.Content />,
});

function ExpandPortion(props) {
	const {
		attributes,
		setAttributes,
		isSelected,
		block,
		updateBlockAttributes,
		getBlock,
		getBlockRootClientId,
	} = props;
	const { clickText, displayType, isVisible, toggleAlign } = attributes;

	const parentBlockID = getBlockRootClientId(block.clientId);

	useEffect(() => {
		if (
			props.attributes.parentID === "" ||
			props.attributes.parentID !== getBlock(parentBlockID).attributes.blockID
		) {
			props.attributes.parentID = getBlock(parentBlockID).attributes.blockID;
		}
	}, []);

	return (
		<>
			{isSelected && (
				<BlockControls>
					<AlignmentToolbar
						value={toggleAlign} //attribute from parent can't be directly used
						onChange={(newAlignment) => {
							updateBlockAttributes(parentBlockID, {
								toggleAlign: newAlignment,
							});

							getBlock(parentBlockID).innerBlocks.forEach((innerBlock) =>
								updateBlockAttributes(innerBlock.clientId, {
									toggleAlign: newAlignment,
								})
							);
						}}
						controls={["left", "center", "right"]}
					></AlignmentToolbar>
				</BlockControls>
			)}
			<div
				className={`ub-expand-portion ub-expand-${displayType}${
					displayType === "full" && !isVisible ? " ub-hide" : ""
				}`}
			>
				<InnerBlocks
					templateLock={false}
					renderAppender={() => <InnerBlocks.ButtonBlockAppender />}
				/>
				<RichText
					style={{ textAlign: toggleAlign }} //attribute from parent can't be directly used
					value={clickText}
					onChange={(value) => setAttributes({ clickText: value })}
					placeholder={__(
						`Text for show ${displayType === "full" ? "less" : "more"} button`
					)}
				/>
			</div>
		</>
	);
}

registerBlockType("ub/expand-portion", {
	title: __("Expand Portion"),
	parent: "ub/expand",
	icon: icon,
	category: "ultimateblocks",
	supports: {
		inserter: false,
		reusable: false,
		lock: false,
	},
	attributes: {
		clickText: {
			type: "string",
			default: "",
		},
		displayType: {
			type: "string",
			default: "",
		},
		isVisible: {
			type: "boolean",
			default: true,
		},
		toggleAlign: {
			type: "string",
			default: "left",
		},
		parentID: {
			type: "string",
			default: "",
		},
	},
	edit: compose([
		withSelect((select, ownProps) => {
			const { getBlock, getBlockRootClientId } =
				select("core/block-editor") || select("core/editor");

			const { clientId } = ownProps;

			return {
				block: getBlock(clientId),
				getBlock,
				getBlockRootClientId,
			};
		}),
		withDispatch((dispatch) => ({
			updateBlockAttributes: (
				dispatch("core/block-editor") || dispatch("core/editor")
			).updateBlockAttributes,
		})),
	])(ExpandPortion),
	save: () => <InnerBlocks.Content />,
});
