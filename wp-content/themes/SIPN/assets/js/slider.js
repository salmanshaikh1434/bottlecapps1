jQuery('.fp-slider-items').slick({
				 dots: false,
				 infinite: true,
				 speed: 300,
				 slidesToShow: 4,
				 slidesToScroll: 2,
				 arrows: true,
				 autoplay: true,
				 autoplaySpeed: 6000,
				 responsive: [
			{
				 breakpoint: 992,
				 settings: {
				 slidesToShow: 2,
				 slidesToScroll: 2
				}
			},
			{
				 breakpoint: 600,
				 settings: {
				 slidesToShow: 1,
				 slidesToScroll: 1
				}
			},
			{
				 breakpoint: 480,
				 settings: {
				 slidesToShow: 1,
				 slidesToScroll: 1
				}
			}
				    // You can unslick at a given breakpoint now by adding:
				    // settings: "unslick"
				    // instead of a settings object
				  ]
				});