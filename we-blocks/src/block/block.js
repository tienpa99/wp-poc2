/**
 * BLOCK: we-slider-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';

import edit from "./edit.js";

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const { MediaUpload, PlainText, InspectorControls } = wp.editor;
const { PanelBody, TextControl, SelectControl } = wp.components;


/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
/*
 * Image Slider
 */ 
registerBlockType( 'we/we-slider-block', {
	title: __( 'WE - Slider Block' ), 
	icon: 'format-gallery', 
	category: 'we-blocks', 
	keywords: [__("WE Slider")],
	attributes: {
	    id: {
	      source: "attribute",
	      selector: ".image_slider.slide",
	      attribute: "id"
	    },
	    slider: {
	      source: "query",
	      default: [],
	      selector: "p.slide",
	      query: {
	        image: {
	          source: "attribute",
	          selector: "img",
	          attribute: "src"
	        },
	        index: {
	          source: "text",
	          selector: "span.slide-index"
	        }
	      }
	    },
	    hidecontrol: {
            type: 'select',
            default: '0',
        },
	    slider_speed: {
            type: 'string',
            default: '2000',
        },
	  },
	
	edit: function( props ) {
		const { slider } = props.attributes;

		const hide_control = [
			{ value: '1', label: __( 'Yes' ) },
			{ value: '0', label: __( 'No' ) },
		];

		const inspectorControls = (
			<InspectorControls>
				<PanelBody title={ __( 'Slider Setting' ) }>
					<SelectControl
						label={ __( 'Hide Controls' ) }
						options={ hide_control }
						value={ props.attributes.hidecontrol }
						onChange={ ( hidecontrol ) => props.setAttributes( { hidecontrol } ) }
					/>
					<TextControl
						label={ __( 'Speed' ) }
						type="text"
						value={ props.attributes.slider_speed }
						onChange={ ( slider_speed ) => props.setAttributes( { slider_speed } ) }
					/>
				</PanelBody>
			</InspectorControls>
		);

	    if (!props.attributes.id) {
	      const id = `slide${Math.floor(Math.random() * 100)}`;
	      props.setAttributes({
	        id
	      });
	    }
		const sliderList = slider
	      .sort((a, b) => a.index - b.index)
	      .map(slide => {
	        return (
	          <div className="slide-block">

                <div className="image_slide_container">

                	<a href="#"
		                className="image_remove"
		                onClick={() => {
		                  const newslider = slider
		                    .filter(item => item.index != slide.index)
		                    .map(t => {
		                      if (t.index > slide.index) {
		                        t.index -= 1;
		                      }
		                      return t;
		                    });

		                  props.setAttributes({
		                    slider: newslider
		                  });
		                }}
		              >
		                Remove Slide
		              </a>

                  <MediaUpload
                    onSelect={media => {
                      const image = media.sizes.full
                        ? media.sizes.full.url
                        : media.url;
                      const newObject = Object.assign({}, slide, {
                        image: image
                      });
                      props.setAttributes({
                        slider: [
                          ...slider.filter(
                            item => item.index != slide.index
                          ),
                          newObject
                        ]
                      });
                    }}
                    type="image"
                    value={slide.image}
                    render={({ open }) =>
                      !!slide.image ? (
                        <div>
                          <img className="image" src={slide.image}  onClick={open} />
                        </div>
                      ) : (
                        <a
                          href="#"
                          className="image_select"
                          onClick={open}
                        >
                          Select Image
                        </a>
                      )
                    }
                  />
                </div>
	              
	          </div>
	        );
	      });
	    return ([
		    inspectorControls,	    	
	      <div className={props.className}>
	        {sliderList}
	        <button
	          className="add-more-slide"
	          onClick={content =>
	            props.setAttributes({
	              slider: [
	                ...props.attributes.slider,
	                {
	                  index: props.attributes.slider.length,
	                  content: "",
	                }
	              ]
	            })
	          }
	        >
	          +
	        </button>
	      </div>
	    ]);
	  },

	save: props => {
	    const { id, slider } = props.attributes;
	    
	    const sliderList = slider.map(function(slide) {
	      
	      return (
	        <div key={slide.index}>
	          <p className="slide">
	            <span className="slide-index" style={{ display: "none" }}>
	              {slide.index}
	            </span>
	              {slide.image && (                
	                  <img src={slide.image} />                
	              )}   
	          </p>
	        </div>
	      );
	    });
	    if (slider.length > 0) {
	      return (
	      	<div className="image_slider slide" id={id}>
	            <div className="imgSlider" data-hidecontrol={ props.attributes.hidecontrol } data-speed={ props.attributes.slider_speed }>
	              {sliderList}
	            </div>
	          </div>
	      );
	    } else return null;
	  }
});

/*
 * Testimonial Slider
 */
registerBlockType("we/we-testimonials-block", {
  title: __("WE - Testimonials Block"), 
  icon: "testimonial", 
  category: "we-blocks",
  keywords: [__("WE Testimonials Slider")],

  attributes: {
    id: {
      source: "attribute",
      selector: ".testimonial_slider.slide",
      attribute: "id"
    },
    testimonials: {
      source: "query",
      default: [],
      selector: "p.testimonial",
      query: {
        image: {
          source: "attribute",
          selector: "img",
          attribute: "src"
        },
        index: {
          source: "text",
          selector: "span.testimonial-index"
        },
        content: {
          source: "text",
          selector: "span.testimonial-text"
        },
        author: {
          source: "text",
          selector: "span.testimonial-author"
        },
        position: {
          source: "text",
          selector: "span.testimonial-position"
        }
      }
    },
    testimonial_hidecontrol: {
        type: 'select',
        default: '0',
    },
    testimonial_sliderspeed: {
        type: 'string',
        default: '2000',
    },
    testimonial_slides: {
        type: 'string',
        default: '3',
    },
    testimonial_style: {
        type: 'select',
        default: '1',
    },
  },

  edit: function( props ) {

    const { testimonials } = props.attributes;

    const t_hide_control = [
  		{ value: '1', label: __( 'Yes' ) },
  		{ value: '0', label: __( 'No' ) },
  	];

    const t_style_control = [
      { value: '1', label: __( 'Style 1' ) },
      { value: '2', label: __( 'Style 2' ) },
      { value: '3', label: __( 'Style 3' ) },
      { value: '4', label: __( 'Style 4' ) },
    ];

	const inspectorControls = (
		<InspectorControls>
			<PanelBody title={ __( 'Testimonial Slider Setting' ) }>			
        <SelectControl
          label={ __( 'Testimonial Style' ) }
          options={ t_style_control }
          value={ props.attributes.testimonial_style }
          onChange={ ( testimonial_style ) => props.setAttributes( { testimonial_style } ) }
        />
				<TextControl
					label={ __( 'Slides To Show' ) }
					type="text"
					value={ props.attributes.testimonial_slides }
					onChange={ ( testimonial_slides ) => props.setAttributes( { testimonial_slides } ) }
				/>
				<SelectControl
					label={ __( 'Hide Controls' ) }
					options={ t_hide_control }
					value={ props.attributes.testimonial_hidecontrol }
					onChange={ ( testimonial_hidecontrol ) => props.setAttributes( { testimonial_hidecontrol } ) }
				/>
				<TextControl
					label={ __( 'Speed' ) }
					type="text"
					value={ props.attributes.testimonial_sliderspeed }
					onChange={ ( testimonial_sliderspeed ) => props.setAttributes( { testimonial_sliderspeed } ) }
				/>
			</PanelBody>
		</InspectorControls>
	);

    if (!props.attributes.id) {
      const id = `testimonial${Math.floor(Math.random() * 100)}`;
      props.setAttributes({
        id
      });
    }

    const testimonialsList = testimonials
      .sort((a, b) => a.index - b.index)
      .map(testimonial => {
        return (
          <div className="we-testimonial-block">
            <p>
              <span>
                Testmonial {Number(testimonial.index) + 1} 
              </span>
              <a
                className="remove-testimonial"
                onClick={() => {
                  const newTestimonials = testimonials
                    .filter(item => item.index != testimonial.index)
                    .map(t => {
                      if (t.index > testimonial.index) {
                        t.index -= 1;
                      }

                      return t;
                    });

                  props.setAttributes({
                    testimonials: newTestimonials
                  });
                }}
              >
                Remove
              </a>
            </p>
            <div className="wp-block-testimonial">

              <PlainText
                className="testimonial-text"
                style={{ height: 58 }}
                placeholder="Testimonial Text"
                value={testimonial.content}
                autoFocus
                onChange={content => {
                  const newObject = Object.assign({}, testimonial, {
                    content: content
                  });
                  props.setAttributes({
                    testimonials: [
                      ...testimonials.filter(
                        item => item.index != testimonial.index
                      ),
                      newObject
                    ]
                  });
                }}
              />
                
              <MediaUpload
                onSelect={media => {
                  const image = media.sizes.medium
                    ? media.sizes.medium.url
                    : media.url;
                  const newObject = Object.assign({}, testimonial, {
                    image: image
                  });
                  props.setAttributes({
                    testimonials: [
                      ...testimonials.filter(
                        item => item.index != testimonial.index
                      ),
                      newObject
                    ]
                  });
                }}
                type="image"
                value={testimonial.image}
                render={({ open }) =>
                  !!testimonial.image ? (
                    
                      
                      <div
                        className="we_testimonial_author_img"
                        style={{
                          backgroundImage: `url(${testimonial.image})`
                        }}
                        onClick={open}
                      />
                    
                  ) : (
                    <a
                      href="#"
                      className="we_testimonial_author_img"
                      onClick={open}
                    >
                      Select Image
                    </a>
                  )
                }
              />
            
              <PlainText
                className="testimonial-author-text"
                placeholder="Author"
                value={testimonial.author}
                onChange={author => {
                  const newObject = Object.assign({}, testimonial, {
                    author: author
                  });
                  props.setAttributes({
                    testimonials: [
                      ...testimonials.filter(
                        item => item.index != testimonial.index
                      ),
                      newObject
                    ]
                  });
                }}
              />
              
              <PlainText
                className="testimonial-position-text"
                placeholder="Position"
                value={testimonial.position}
                onChange={position => {
                  const newObject = Object.assign({}, testimonial, {
                    position: position
                  });
                  props.setAttributes({
                    testimonials: [
                      ...testimonials.filter(
                        item => item.index != testimonial.index
                      ),
                      newObject
                    ]
                  });
                }}
              />
                
            </div>
          </div>
        );
      });
    return ([
		inspectorControls,	 
      <div className={props.className}>
        {testimonialsList}
        <button
          className="add-more-testimonial"
          onClick={content =>
            props.setAttributes({
              testimonials: [
                ...props.attributes.testimonials,
                {
                  index: props.attributes.testimonials.length,
                  content: "",
                  author: "",
                  position: ""
                }
              ]
            })
          }
        >
          +
        </button>
      </div>
    ]);
  },

  save: props => {
    const { id, testimonials } = props.attributes;
    
    const testimonialsList = testimonials.map(function(testimonial) {
      
      return (
        <div key={testimonial.index}>
          <p className="testimonial">
            <span className="testimonial-index" style={{ display: "none" }}>
              {testimonial.index}
            </span>

            {testimonial.image && (                
              <img className="testimonial-image" src={testimonial.image} />
            )}

            {testimonial.content && (
              <span className="testimonial-text">{testimonial.content}</span>                
            )}
          
            {testimonial.author && (
              <span className="testimonial-author">
              	{testimonial.author}
              </span>
            )}

            {testimonial.position && (
              <span className="testimonial-position">
                {testimonial.position}
              </span>
            )}
              
          </p>
        </div>
      );
    });
    if (testimonials.length > 0) {
      return (
        
          <div className="testimonial_slider slide" id={id}>
            <div className="testimonialSlider" data-t_style={ props.attributes.testimonial_style } data-t_show={ props.attributes.testimonial_slides } data-t_hidecontrol={ props.attributes.testimonial_hidecontrol } data-t_speed={ props.attributes.testimonial_sliderspeed }>
              {testimonialsList}
            </div>
          </div>
        
      );
    } else return null;
  }
});

/*
 * Logo Carousel Slider
 */
registerBlockType( 'we/we-logo-carousel-block', {
	title: __( 'WE - Logo Carousel Block' ),
	icon: 'image-flip-horizontal', 
	category: 'we-blocks',
	keywords: [__("WE Logo Carousel")],
	attributes: {
	    id: {
	      source: "attribute",
	      selector: ".logo_slider.slide",
	      attribute: "id"
	    },
	    slider: {
	      source: "query",
	      default: [],
	      selector: "p.slide",
	      query: {
	        image: {
	          source: "attribute",
	          selector: "img",
	          attribute: "src"
	        },
	        index: {
	          source: "text",
	          selector: "span.slide-index"
	        }
	      }
	    },
	    hidecontrol: {
            type: 'select',
            default: '0',
      },
	    slider_speed: {
            type: 'string',
            default: '2000',
      },
	    logo_slides: {
	        type: 'string',
	        default: '6',
	    },
      logo_style: {
          type: 'select',
          default: '1',
      },
	  },
	
	edit: function( props ) {
		const { slider } = props.attributes;

		const hide_control = [
			{ value: '1', label: __( 'Yes' ) },
			{ value: '0', label: __( 'No' ) },
		];

	    const logo_style_op = [
		    { value: '1', label: __( 'Slider' ) },
		    { value: '2', label: __( 'Grid' ) },
	    ];

		const inspectorControls = (
			<InspectorControls>
				<PanelBody title={ __( 'Logo Carousel Slider Setting' ) }>
			          <SelectControl
			            label={ __( 'Style' ) }
			            options={ logo_style_op }
			            value={ props.attributes.logo_style }
			            onChange={ ( logo_style ) => props.setAttributes( { logo_style } ) }
			          />
					<TextControl
						label={ __( 'Logo Slides To Show' ) }
						type="text"
						value={ props.attributes.logo_slides }
						onChange={ ( logo_slides ) => props.setAttributes( { logo_slides } ) }
					/>
					<SelectControl
						label={ __( 'Hide Controls' ) }
						options={ hide_control }
						value={ props.attributes.hidecontrol }
						onChange={ ( hidecontrol ) => props.setAttributes( { hidecontrol } ) }
					/>
					<TextControl
						label={ __( 'Speed' ) }
						type="text"
						value={ props.attributes.slider_speed }
						onChange={ ( slider_speed ) => props.setAttributes( { slider_speed } ) }
					/>
				</PanelBody>
			</InspectorControls>
		);

	    if (!props.attributes.id) {
	      const id = `slide${Math.floor(Math.random() * 100)}`;
	      props.setAttributes({
	        id
	      });
	    }
		const sliderList = slider
	      .sort((a, b) => a.index - b.index)
	      .map(slide => {
	        return (
	          <div className="logo-slide-block">

                <div className="image_slide_container">

                	<a href="#"
		                className="image_remove"
		                onClick={() => {
		                  const newslider = slider
		                    .filter(item => item.index != slide.index)
		                    .map(t => {
		                      if (t.index > slide.index) {
		                        t.index -= 1;
		                      }
		                      return t;
		                    });

		                  props.setAttributes({
		                    slider: newslider
		                  });
		                }}
		              >
		                Remove Slide
		              </a>

                  <MediaUpload
                    onSelect={media => {
                      const image = media.sizes.medium
                        ? media.sizes.medium.url
                        : media.url;
                      const newObject = Object.assign({}, slide, {
                        image: image
                      });
                      props.setAttributes({
                        slider: [
                          ...slider.filter(
                            item => item.index != slide.index
                          ),
                          newObject
                        ]
                      });
                    }}
                    type="image"
                    value={slide.image}
                    render={({ open }) =>
                      !!slide.image ? (
                        <div>
                          <img className="logo_image" src={slide.image}  onClick={open} />
                        </div>
                      ) : (
                        <a
                          href="#"
                          className="image_select"
                          onClick={open}
                        >
                          Select Image
                        </a>
                      )
                    }
                  />
                </div>
	              
	          </div>
	        );
	      });
	    return ([
		    inspectorControls,	    	
	      <div className={props.className}>
	        {sliderList}
	        <button
	          className="add-more-slide"
	          onClick={content =>
	            props.setAttributes({
	              slider: [
	                ...props.attributes.slider,
	                {
	                  index: props.attributes.slider.length,
	                  content: "",
	                }
	              ]
	            })
	          }
	        >
	          +
	        </button>
	      </div>
	    ]);
	  },

	save: props => {
	    const { id, slider } = props.attributes;
	    
	    const sliderList = slider.map(function(slide) {
	      
	      return (
	        <div class="grid">
	        	<p className="slide">
  	            <span className="slide-index" style={{ display: "none" }}>
  	              {slide.index}
  	            </span>
	              {slide.image && (                
	                  <img src={slide.image} />                
	              )}   
	            </p> 
	        </div>
	      );
	    });
	    if (slider.length > 0) {
	      return (
	      	<div className="logo_slider slide" id={id}>
	            <div className="logoSlider" data-l_style={ props.attributes.logo_style } data-l_show={ props.attributes.logo_slides } data-hidecontrol={ props.attributes.hidecontrol } data-speed={ props.attributes.slider_speed }>
	              {sliderList}
	            </div>
	          </div>
	      );
	    } else return null;
	  }
});


/*
 * We Block Post Slider
 */
registerBlockType("we/we-posts-slider-block", {
  title: __("WE - Posts Slider Block"),
  icon: 'media-text',
  category: 'we-blocks', 
  keywords: [__("WE - Posts Slider Block")],
  attributes: {
    postsToShow: {
      type: "integer",
      default: 5
    },
    categories: {
      type: "array",
      default: []
    },
    orderBy: {
      type: "string"
    },
    order: {
      type: "string"
    },
    horizontalSpacing: {
      type: "integer",
      default: 0
    },
    showTitle: {
      type: "boolean",
      default: false
    },
    enableDots: {
      type: "boolean",
      default: true
    },
    autoPlay: {
      type: "boolean",
      default: true
    },
    infinite: {
      type: "boolean",
      default: false
    },
    fadeAnimation: {
      type: "boolean",
      default: false
    },
    adaptiveHeight: {
      type: "boolean",
      default: false
    },
    pauseOnHover: {
      type: "boolean",
      default: true
    },
    ladzLoadSlides: {
      type: "boolean",
      default: false
    },
    swipeToSlide: {
      type: "boolean",
      default: false
    },
    verticalSlider: {
      type: "boolean",
      default: false
    },
    focusOnSelect: {
      type: "boolean",
      default: false
    },
    ReverseSlideScroll: {
      type: "boolean",
      default: false
    },
    showArrows: {
      type: "boolean",
      default: true
    },
    variableWidth: {
      type: "boolean",
      default: false
    },
    centerMode: {
      type: "boolean",
      default: false
    },
    responsiveSettings: {
      type: "boolean",
      default: false
    },
    deskdots: {
      type: "boolean",
      default: true
    },
    deskarrows: {
      type: "boolean",
      default: false
    },
    tabdots: {
      type: "boolean",
      default: true
    },
    tabarrows: {
      type: "boolean",
      default: true
    },
    mobdots: {
      type: "boolean",
      default: true
    },
    mobarrows: {
      type: "boolean",
      default: true
    },
    slidesToShow: {
      type: "integer",
      default: 1
    },
    slidesToScroll: {
      type: "integer",
      default: 1
    },
    deskSlidesToShow: {
      type: "integer",
      default: 1
    },
    deskSlidesToScroll: {
      type: "integer",
      default: 1
    },
    tabSlidesToShow: {
      type: "integer",
      default: 1
    },
    tabSlidesToScroll: {
      type: "integer",
      default: 1
    },
    mobSlidesToShow: {
      type: "integer",
      default: 1
    },
    mobSlidesToScroll: {
      type: "integer",
      default: 1
    },
    animationSpeed: {
      type: "integer",
      default: 1000
    },
    autoplayDelay: {
      type: "integer",
      default: 4000
    },
    initialSlide: {
      type: "integer",
      default: 1
    },
    cssEase: {
      type: "string",
      default: "ease"
    },   
    enableAnimation: {
      type: "boolean",
      default: false
    },
    displayAuthor: {
      type: "boolean",
      default: false
    },
    displayPostDate: {
      type: "boolean",
      default: false
    },
    displayPostExcerpt: {
      type: "boolean",
      default: false
    },
    displayCountReading: {
      type: "boolean",
      default: false
    },
    align: {
      type: "string"
    },
    blockId: {
      type: "string"
    },
    readMoreText: {
      type: "string",
      default: ""
    },
    sliderDotsClass: {
      type: "string",
      default: ""
    }
  },
  supports: {
    html: false
  },
  selectedCategories: {
    type: "array",
    default: []
  },

  getEditWrapperProps(attributes) {
    const { align } = attributes;
    if (
      "left" === align ||
      "right" === align ||
      "wide" === align ||
      "full" === align
    ) {
      return { "data-align": align };
    }
  },

  edit,
  save: function() {
    return null;
  }
});

