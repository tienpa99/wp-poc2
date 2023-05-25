<script type="text/html" id="tmpl-boldgrid-editor-background">
	<div class='background-design'>
		<div class='preset-wrapper'>
			<div class='current-selection'>
				<div class='filters'>
					<a href="#" data-type='["image"]'  data-label="Images" class='filter'><i class="fa fa-picture-o" aria-hidden="true"></i> Image</a>
					<a href="#" data-type='["color","gradients"]' data-default="1" data-label="Flat Colors & Gradients" class='filter'><i class="fa fa-paint-brush" aria-hidden="true"></i> Color</a>
					<a href="#" data-type='["pattern"]' data-label="Patterns" class='filter'><i class="fa fa-wpforms" aria-hidden="true"></i> Pattern</a>
					<a href="#" data-type='["hover"]' data-label="Hover Effects" class='filter'><i class="fa fa-hand-pointer-o" aria-hidden="true"></i> Hover Effects</a>
				</div>
				<div class='settings'>
					<a href="#" class='panel-button customizer'><i class="fa fa-cog" aria-hidden="true"></i> Settings</a>
					<a href="#" class='panel-button remove-background'><i class="fa fa-times" aria-hidden="true"></i> Remove</a>
				</div>
			</div>
			<div class='presets'>
				<div class='background-color section color-controls'>
					<h4>Custom Color</h4>
					<label for="section-background-color" class='color-preview'></label>
					<input type="text" data-type="" name='section-background-color' class='color-control' value=''>
				</div>
				<div class='hover-background-color section color-controls'>
					<h4>Hover Color</h4>
					<label for="section-hover-background-color" class='color-preview'></label>
					<input type="text" data-type="hover" name='section-hover-background-color' class='color-control' value=''>
				</div>
				<div class='hover-background-image section hover-image-controls'>
					<h4>Hover Background Image</h4>
					<ul>
						<li data-type="hover" class='section add-hover-image-controls'>
							<a class="add-media"><span class="dashicons dashicons-images-alt"></span>Add Background Image</a>
						</li>
					</ul>
				</div>
				<div class='mobile-only-visibility section' data-tooltip-id='mobile-only-visibility'>
					<h4>Mobile Visibility</h4>
					<label>
						<input type="radio" name="mobile-only-visibility" value="default">Show Standard Background
					</label>
					<label>
						<input type="radio" name="mobile-only-visibility" value="hover">Show Hover Background
					</label>
				</div>
				<div class='title'>
					<h4>Sample Backgrounds</h4>
				</div>
				<ul>
					<li data-type="image" class='section add-image-controls'>
						<a class="add-media"><span class="dashicons dashicons-images-alt"></span> Add Media</a>
					</li>
				<# _.each( data.images, function ( typeSet, type ) { #>
					<# _.each( typeSet, function ( image, index ) { #>
					<# if( 'color' == type ) { #>
						<li data-type="{{type}}" data-class='{{index}}' class='selection' style="background: {{image}}"></li>
					<# } else if( 'gradients' == type ) { #>
						<li data-type="{{type}}" data-color1="{{image.color1}}" data-color2="{{image.color2}}"
							data-direction="{{image.direction}}" class='selection' style="background-image: {{image.css}}"></li>
					<# } else if( 'image' == type ) { #>
						<li data-type="{{type}}" data-image-url="{{image}}" class='selection' style="background-image: url({{image}})"></li>
					<# } else { #>
						<li data-type="{{type}}" data-image-url="{{image}}" class='selection' style="background-image: url({{image}})"></li>
					<# } #>
					<# }); #>
				<# }); #>
				</ul>
			</div>
		</div>
		<div class='customize'>
			<div class='back section'>
				<a class='panel-button' href="#"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back</a>
			</div>
			<div data-control-name="design">
				<div class='image-opacity section hidden'>
					<h4>Image Opacity (%)</h4>
					<div class="slider"></div>
					<span class='value'></span>
				</div>
				<div class='background-color section color-controls'>
					<h4>Background Color</h4>
					<label for="section-background-color" class='color-preview'></label>
					<input type="text" data-type="" name='section-background-color' class='color-control' value=''>
				</div>
				<div class='gradient-color-1 section color-controls'>
					<h4>Gradient Color 1</h4>
					<label for="gradient-color-1" class='color-preview'></label>
					<input type="text" data-type="" name='gradient-color-1' class='color-control' value=''>
				</div>
				<div class='gradient-color-2 section color-controls'>
					<h4>Gradient Color 2</h4>
					<label for="gradient-color-2" class='color-preview'></label>
					<input type="text" data-type="" name='gradient-color-2' class='color-control' value=''>
				</div>
				<div class='overlay-color section color-controls'>
					<h4>Overlay Color</h4>
					<label for="overlay-color" class='color-preview'></label>
					<input type="text" data-type="" name='overlay-color' class='color-control' value='rgba(255,255,255,.5)'>
					<div>
						<a class="default-color" href="#">Reset to Default</a>
					</div>
				</div>
				<div data-tooltip-id='vertical-position' class='vertical-position section'>
					<h4>Vertical Position (%)</h4>
					<div class="slider"></div>
					<span class='value'></span>
				</div>
				<div class='direction section'>
					<h4>Gradient Direction</h4>
					<label> <input type="radio" checked="checked" name="bg-direction" value="to left">Horizontal </label>
					<label> <input type="radio" name="bg-direction" value="to bottom">Vertical </label>
				</div>
				<div class='size section' data-tooltip-id='background-size'>
					<h4>Size</h4>
					<label>
						<input type="radio" checked="checked" name="background-size" value="cover">Cover Area
					</label>
					<label>
						<input type="radio" name="background-size" value="tiled">Tiled
					</label>
				</div>
				<div class='scroll-effects section' data-tooltip-id='background-scroll-effects'>
					<h4>Scroll Effects</h4>
					<label>
						<input type="radio" checked="checked" name="scroll-effects" value="none">None
					</label>
					<label>
						<input type="radio" name="scroll-effects" value="background-parallax">Parallax
					</label>
					<label>
						<input type="radio" name="scroll-effects" value="background-fixed">Fixed
					</label>
				</div>
				<div class='hover-overlay-color section color-controls'>
					<h4>Overlay Color</h4>
					<label for="hover-overlay-color" class='color-preview'></label>
					<input type="text" data-type="" name='hover-overlay-color' class='color-control' value='rgba(255,255,255,.5)'>
					<div>
						<a class="default-color" href="#">Reset to Default</a>
					</div>
				</div>
				<div data-tooltip-id='hover-vertical-position' class='hover-vertical-position section'>
					<h4>Vertical Position (%)</h4>
					<div class="slider"></div>
					<span class='value'></span>
				</div>
				<div class='hover-size section' data-tooltip-id='hover-background-size'>
					<h4>Size</h4>
					<label>
						<input type="radio" checked="checked" name="hover-background-size" value="cover">Cover Area
					</label>
					<label>
						<input type="radio" name="hover-background-size" value="tiled">Tiled
					</label>
				</div>
				<div class='hover-scroll-effects section' data-tooltip-id='hover-background-scroll-effects'>
					<h4>Scroll Effects</h4>
					<label>
						<input type="radio" checked="checked" name="hover-scroll-effects" value="none">None
					</label>
					<label>
						<input type="radio" name="hover-scroll-effects" value="hover-background-fixed">Fixed
					</label>
				</div>
			</div>
		</div>
	</div>
</script>
