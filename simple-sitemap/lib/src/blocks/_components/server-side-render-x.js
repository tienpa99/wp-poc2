/**
 * External dependencies
 */
import { isEqual } from 'lodash';

/**
 * WordPress dependencies
 */
const { Component, RawHTML, Fragment } = wp.element;
const { __, sprintf } = wp.i18n;
const apiFetch = wp.apiFetch;
const { addQueryArgs } = wp.url;
const { Placeholder, Spinner } = wp.components;

export function rendererPath(block, attributes = null, urlQueryArgs = {}) {
	return addQueryArgs(`/wp/v2/block-renderer/${block}`, {
		context: 'edit',
		...(null !== attributes ? { attributes } : {}),
		...urlQueryArgs,
	});
}

export class ServerSideRenderX extends Component {
	constructor(props) {
		super(props);
		this.state = {
			response: null,
			prevResponse: null,
		};
	}

	componentDidMount() {
		this.isStillMounted = true;
		this.fetch(this.props);
	}

	componentWillUnmount() {
		this.isStillMounted = false;
	}

	componentDidUpdate(prevProps) {
		if (!isEqual(prevProps, this.props)) {
			this.fetch(this.props);
		}
	}

	fetch(props) {
		if (!this.isStillMounted) {
			return;
		}
		if (null !== this.state.response) {
			//this.setState({ response: null, prevResponse: this.state.response });
			this.setState((state) => ({
				response: null,
				prevResponse: state.response,
			}));
		}
		const { block, attributes = null, urlQueryArgs = {} } = props;

		// Store the latest fetch request so that when we process it, we can
		// check if it is the current request, to avoid race conditions on slow networks.
		const fetchRequest = (this.currentFetchRequest = apiFetch({
			path: `/wp/v2/block-renderer/${block}`,
			method: 'POST',
			data: {
				context: 'edit',
				...(null !== attributes ? { attributes } : {}),
				...urlQueryArgs,
			},
		})
			.then((response) => {
				if (
					this.isStillMounted &&
					fetchRequest === this.currentFetchRequest &&
					response
				) {
					this.setState({ response: response.rendered });
				}
			})
			.catch((error) => {
				if (
					this.isStillMounted &&
					fetchRequest === this.currentFetchRequest
				) {
					this.setState({
						response: {
							error: true,
							errorMsg: error.message,
						},
					});
				}
			}));
		return fetchRequest;
	}

	render() {
		const { right, top, unit } = this.props.spinnerLocation;
		const response = this.state.response;
		//let response = this.state.response;
		//response = `<div style="position:relative;"><div style="position:absolute;right:0;top:10px"><span class="components-spinner"></span></div>${response}</div>`;
		const prevResponse = this.state.prevResponse;
		let prevResponseHTML = '';
		if (prevResponse !== null) {
			prevResponseHTML = `<div style="position:relative;"><div style="position:absolute;left:-45px;bottom:50%;"><span class="spinner" style="visibility: visible;></span><span class="components-spinner"></span></div>${prevResponse}</div>`;
			//response = `<div style="position:relative;"><div style="position:absolute;right:0;top:10px"><span class="components-spinner"></span></div>${response}</div>`;
		}

		const {
			className,
			EmptyResponsePlaceholder,
			ErrorResponsePlaceholder,
			LoadingResponsePlaceholder,
		} = this.props;

		if (response === '') {
			return (
				<EmptyResponsePlaceholder response={response} {...this.props} />
			);
		} else if (!response) {
			return (
				<Fragment>
					<RawHTML key="html" className={className}>
						{prevResponseHTML}
					</RawHTML>
				</Fragment>
			);
		} else if (response.error) {
			return (
				<ErrorResponsePlaceholder response={response} {...this.props} />
			);
		}

		return (
			<RawHTML key="html" className={className}>
				{response}
			</RawHTML>
		);
	}
}

ServerSideRenderX.defaultProps = {
	spinnerLocation: { right: 0, top: 10, unit: 'px' },
	EmptyResponsePlaceholder: ({ className }) => (
		<Placeholder className={className}>
			{__('Block rendered as empty.')}
		</Placeholder>
	),
	ErrorResponsePlaceholder: ({ response, className }) => {
		const errorMessage = sprintf(
			// translators: %s: error message describing the problem
			__('Error loading block: %s'),
			response.errorMsg
		);
		return <Placeholder className={className}>{errorMessage}</Placeholder>;
	},
	LoadingResponsePlaceholder: ({ className }) => {
		return (
			<Placeholder className={className}>
				<Spinner />
			</Placeholder>
		);
	},
};

export default ServerSideRenderX;
