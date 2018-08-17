
$(window).on('load', function(){ 
    console.log('DOM Load');
    Barba.Pjax.Dom.wrapperId = 'main';
Barba.Pjax.Dom.containerClass = 'main-wrap';
Barba.Pjax.cacheEnabled = false;

    Barba.Pjax.start();


    // $('#gallery-container').masonry({
    //   itemSelector: '.image-wrap'

    // });
});


Barba.Dispatcher.on('newPageReady', function(currentStatus, oldStatus, container) {
  // Re-init your scripts here.

      init();
     

});


init = function(){

console.log('init run');

let $overlay = $('#gallery-overlay');
let $label = $('#gallery-label');
let $label_title = $('#gallery-label--title');
let $label_desc = $('#gallery-label--desc');
var $label_content = $('#gallery-label--wrap');
let $image = $("<img>");
var $currentImageContainer = null;
console.log($currentImageContainer);

$overlay.append($image);


 $("#prev").on("click",function(){

  //get previous image
  //  $prevImage = $("div.active").prev();
  $prevImage = $($currentImageContainer).prev();
  
   console.log('prev: ', $prevImage[0]);

   if( $prevImage[0] == undefined){

    console.log('Go no further');

  }  else {

  showImage($prevImage[0]);
  return $prevImage.length = 0;

  }
 });

 $("#next").on("click", function(){
    //get next image

    $nextImage = $($currentImageContainer).next();
    for(i = 0; i < $nextImage.length; i++){
      console.log($nextImage[i]);
    }

    if( $nextImage[0] == undefined){
      return $nextImage.length = 0;
      console.log('Go no further');

    }  else {

    showImage($nextImage[0]);
    return $nextImage.length = 0;

    }
  
    
 });

 function showImage(newImageContainer){
     console.log('showImage: ', newImageContainer);

      //fade in label content
     $($label_content).removeClass('fadeOut');
     $($label_content).addClass('fadeIn');

     //remove active from former current image container
    $($currentImageContainer).removeClass("active");

    //get new image container, set to current image
    $currentImageContainer = newImageContainer;

      //add active class to new
    $($currentImageContainer).addClass("active");

      //set new img from new Container
     $new_img = $($currentImageContainer).find("img");

     //get attributes
     var $image_url = $($new_img ).attr("src");
     var $image_height = $($new_img ).attr("height");
     var $image_title = $($new_img ).attr("title");
     var $image_desc = $($new_img ).attr("desc");

     console.log($image_url,  $image_height,  $image_title, $image_desc  )
 
     fillLabel($image_title, $image_desc);


     $image_height = $image_height + 'px';
     $($image).css('max-height', $image_height);
     $image.attr("src", $image_url);
     $image.attr("class", "overlay_img");
 }



    $( "#gallery-container .image-wrap img" ).on( "click", function() {
      event.preventDefault();
      //fade in overlay
      // $overlay.fadeIn();
      // $($overlay).css('display', 'flex');
      $($overlay).removeClass('hidden');
      $($overlay).addClass('visible');

      //get container of image clicked on
      newContainer = $(this).parent();
      showImage(newContainer[0]);
      return newContainer.length = 0;

      // //toggle active class
      // $($current_img).removeClass("active");
      // $current_img = $(this).parent(); 
      // $($current_img).addClass("active");

      // $($label_content).removeClass('fadeOut');
      // $($label_content).addClass('fadeIn');

  
      // //get attributes
      // var $image_url = $(this).attr("src");
      // var $image_height = $(this).attr("height");
      // var $image_title = $(this).attr("title");
      // var $image_desc = $(this).attr("desc");
  
      // fillLabel($image_title, $image_desc);

      // $image_height = $image_height + 'px';
      // $($image).css('max-height', $image_height);
      // $image.attr("src", $image_url);
      // $image.attr("class", "overlay_img");

    });


    function fillLabel(title, desc){

       $($label_title).html(title);
       $($label_desc).html(desc);
    }

    $( "#gallery-overlay").on("click", function(){
      event.preventDefault();

      //hide overlay
      $($overlay).removeClass('visible');
      $($overlay).addClass('hidden');

      //hide label contents
      $($label_content).removeClass('fadeIn');
      $($label_content).addClass('fadeOut');

    })
}

var FadeTransition = Barba.BaseTransition.extend({

    start: function() {
      /**
       * This function is automatically called as soon the Transition starts
       * this.newContainerLoading is a Promise for the loading of the new container
       * (Barba.js also comes with an handy Promise polyfill!)
       */
  
      // As soon the loading is finished and the old page is faded out, let's fade the new page
      Promise
        .all([this.newContainerLoading, this.fadeOut()])
        .then(this.fadeIn.bind(this));
    },
  
    fadeOut: function() {
      /**
       * this.oldContainer is the HTMLElement of the old Container
       */
      return $(this.oldContainer).animate({ opacity: 0 }).promise();
    },
  
    fadeIn: function() {
      /**
       * this.newContainer is the HTMLElement of the new Container
       * At this stage newContainer is on the DOM (inside our #barba-container and with visibility: hidden)
       * Please note, newContainer is available just after newContainerLoading is resolved!
       */
  
      var _this = this;
      var $el = $(this.newContainer);
  
      $(this.oldContainer).hide();
  
      $el.css({
        visibility : 'visible',
        opacity : 0
      });
  
      $el.animate({ opacity: 1 }, 400, function() {
        /**
         * Do not forget to call .done() as soon your transition is finished!
         * .done() will automatically remove from the DOM the old Container
         */
  
        _this.done();
      });
    }
  });
  
  /**
   * Next step, you have to tell Barba to use the new Transition
   */
  
  Barba.Pjax.getTransition = function() {
    /**
     * Here you can use your own logic!
     * For example you can use different Transition based on the current page or link...
     */
  
    return FadeTransition;
  };

Barba.Pjax.originalPreventCheck = Barba.Pjax.preventCheck;
Barba.Pjax.preventCheck = function(evt, element) {


	if (!Barba.Pjax.originalPreventCheck(evt, element)) {
		return false;
	}

	// ignore pdf links
	if (/.pdf/.test(element.href.toLowerCase())) {
		return false;
	}

	// additional (besides .no-barba) ignore links with these classes
	// ab-item is for wp admin toolbar links
	var ignoreClasses = ['ab-item', 'image-wrap', 'attachment-full'];
	for (var i = 0; i < ignoreClasses.length; i++) {
		if (element.classList.contains(ignoreClasses[i])) {
			return false;
		}
	}

	return true;
};