# Light Carousel

Light Carousel is a lightweight responsive jQuery carousel. Here is a [demo](http://spread-word.com/frontend/demo/light-carousel/).

##Example
```html
<div class="sample1">
	<div class="carousel">
		<ul>
			<li>
				<img src="res/img/img1.jpg" alt="Description for photo 1">
			</li>
			<li>
				<img src="res/img/img2.jpg" alt="Description for photo 2">
			</li>
			<li>
				<img src="res/img/img3.jpg" alt="Description for photo 3">
			</li>
			<li>
				<img src="res/img/img4.jpg" alt="Description forof photo 4">
			</li>
		</ul>
		<div class="controls">
			<div class="prev"></div>
			<div class="next"></div>
		</div>
	</div>
	<div class="thumbnails">
		<ul>
			<li>
				<img src="res/img/img1_thumb.jpg" alt="Description for photo 1">
			</li>
			<li>
				<img src="res/img/img2_thumb.jpg" alt="Description for photo 2">
			</li>
			<li>
				<img src="res/img/img3_thumb.jpg" alt="Description for photo 3">
			</li>
			<li>
				<img src="res/img/img4_thumb.jpg" alt="Description for photo 4">
			</li>
		</ul>
	</div>
</div>
```

And initialization:
```javascript
$('.sample1').lightCarousel();

// or use options
$('.sample2').lightCarousel({
  interval: 6000, // Interval between transitions
  changeHeight: true, // If set to false, it retains the height of first slide
  slideshow: true // Auto transitons
});
```

##Instructions

Here are some instructions:

* Dependencies on jQuery and Font Awesome (prev/next controls)
* Supports IE8+
* Tweek the style at the top of the light-carousel.css for your liking (caption font/background, thumbnails sizes and selected item color)


Light Carousel is released under the **MIT License**.
