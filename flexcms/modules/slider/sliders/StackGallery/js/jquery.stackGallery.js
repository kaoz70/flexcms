
/*

jQuery Stack banner slideshow with captions v1.2
http://codecanyon.net/item/jquery-stack-banner-slideshow-with-captions/476389

http://stackoverflow.com/questions/10674842/jquery-transform-safari
http://stackoverflow.com/questions/10114414/cant-animate-rotation-simultaneously-with-other-properties-only-in-safari-not

*/


	
(function($) {

	$.fn.stackGallery = function(settings) {

	var componentInited=false;
	
	var _window = jQuery(window);
	
	var isMobile = (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent));
	//isMobile=true;
	
	var isIE = false, isIEbelow9 = false;
	var ie_check = getInternetExplorerVersion();
	if (ie_check != -1){
		isIE = true;
		if(ie_check < 9)isIEbelow9 = true;
	} 
	
	function getInternetExplorerVersion(){
	 //http://msdn.microsoft.com/en-us/library/ms537509%28v=vs.85%29.aspx
	 //Returns the version of Internet Explorer or a -1 (indicating the use of another browser).
	  var rv = -1; // Return value assumes failure.
	  if (navigator.appName == 'Microsoft Internet Explorer'){
		var ua = navigator.userAgent;
		var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
		if (re.exec(ua) != null){
		  rv = parseFloat( RegExp.$1 );
		}
	  }
	  return rv;
	}
	
	var ieRotArr=[];
	
	//http://stackoverflow.com/questions/9847580/how-to-detect-safari-chrome-ie-firefox-and-opera-browser
	var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
	var isChrome = !isSafari && testCSS('WebkitTransform');
	function testCSS(prop) {
		return prop in document.documentElement.style;
	}
	//console.log(isChrome);
	if(isChrome){//if is chrome, threat same as ie
		isIE=true;
	}
	
	var componentWrapper = $(this);
	var componentPlaylist = componentWrapper.find('.componentPlaylist');
	componentPlaylist.css('zIndex',0);//fix for ie9 z index problem (apply to all)
	var indexAddOn=1;

	var slideshowOn=settings.slideshowOn;
	var slideshowTimeout = settings.slideshowDelay;
	var slideshowTimeoutID;
	var fullSize=settings.fullSize;
	var controlsAlignment = settings.controlsAlignment;
	
	var swipeOn=settings.swipeOn;
	var swipeArr = swipeOn.split(',');
	//console.log(swipeArr);
	
	var slideRatio,slideRatio2,scalerRatio,slideBorder, maxAchievedRot=0, resizeCase='width';
	
	var captionArr=[];
	
	var windowResizeTimeout = 25;//execute resize delay
	var windowResizeTimeoutID;
	
	var playlistArr = [];

	var useRotation=settings.useRotation;
	var transitionOn=false;
	var endTransitionCheckID;
	var playlistLength;
	var detailOpened=false;
	
	var currentSlideWidth, currentSlideHeight, currentSlideWidth2, currentSlideHeight2;
	var rotatedWidth,rotatedHeight,rotatedWidth2,rotatedHeight2;
	var maxTR=0, maxLR=0, minTR=500, minLR=500;//give high values for rounding down
	
	//angles
	var upPartDuration = 500;
    var downPartDuration = 500;
	var maxRandomUpAngle = -25;//angles during shuffle rotation
	var minRandomUpAngle = -15;
	var maxRandomDownAngle = 10;//angles in stationary rotation
	var minRandomDownAngle = -10;
	
	var vert_size_deduct = 0;
	var hor_size_deduct = 0;
	
	var slideshowLayout=settings.slideshowLayout;
	var slideshowDirection = settings.slideshowDirection;
	
	var captionOpenDelay=150;
	var captionToggleSpeed=500;

	var verticalValue;//(size of the holders), how far to animate slide verically/horizontally
	var horizontalValue;

	//controls
	var componentControls=componentWrapper.find('.componentControls');
	var controls_previous = componentControls.find('.controls_previous');
	var controls_toggle = componentControls.find('.controls_toggle');
	var controls_next = componentControls.find('.controls_next');
			
	controls_previous.css('cursor', 'pointer');
	controls_toggle.css('cursor', 'pointer');
	controls_next.css('cursor', 'pointer');
	
	controls_previous.bind('click touchstart', toggleControls);
	controls_toggle.bind('click touchstart', toggleControls);
	controls_next.bind('click touchstart', toggleControls);
	
	controls_previous.bind('mouseover', overControls);
	controls_toggle.bind('mouseover', overControls);
	controls_next.bind('mouseover', overControls);
	
	controls_previous.bind('mouseout', outControls);
	controls_toggle.bind('mouseout', outControls);
	controls_next.bind('mouseout', outControls);
	
	if(slideshowOn){
		controls_toggle.find('img').attr('src', 'data/icons/pause.png');
	}else{
		controls_toggle.find('img').attr('src', 'data/icons/play.png');
	}

	var sliderHit=false;
	componentPlaylist.mouseenter(function(e){       
		//console.log("enter");
		sliderHit=true;
		//if(!transitionOn && slideshowOn) pauseSlideshow();
		if(slideshowOn) pauseSlideshow();
	}).mouseleave(function(){
		//console.log("leave");
		sliderHit=false;
		if(!transitionOn && slideshowOn && !detailOpened) resumeSlideshow();
	});
	
	_window.resize(function() {
		if(!componentInited) return false;
		if(windowResizeTimeoutID) clearTimeout(windowResizeTimeoutID);
		windowResizeTimeoutID = setTimeout(doneResizing, windowResizeTimeout);
	});
	
				  
	setup();
		
	function setup(){	
		//console.log('setup');
	
		//caption vars
		var captions,len,captionSubArr,caption_list,caption;
		
		var div,i = 0,r, playlist = componentPlaylist.find("div[class=slide]"), scaler, tr, lr, temp_max_r, temp_max_r_id;
		playlistLength = playlist.length;
		
		//get playlist
		playlist.each(function() { 
			//console.log($(this));
			playlistArr.push($(this));
		});
		
		var firstSlide = $(playlistArr[0]);
		//get temp values, later in resize check if fullSize and set again
		currentSlideWidth = firstSlide.width();
		currentSlideHeight = firstSlide.height();
		
		//console.log(currentSlideWidth,currentSlideHeight );
		slideRatio = currentSlideHeight / currentSlideWidth;
		slideRatio2 = currentSlideWidth / currentSlideHeight;
		//console.log(slideRatio);
		
		scaler = firstSlide.find("div[class=scaler]");
		scalerRatio = scaler.height() / scaler.width();
		//console.log(scalerRatio);
		
		slideBorder = (firstSlide.width() - scaler.width())/2;
		//console.log(slideBorder);
		
		for(i; i <playlistLength; i++){
			
			div = $(playlistArr[i]);
			div.attr('data-id', i);
			div.attr('data-orig-id', i);
			div.css('zIndex', i+indexAddOn);
			
			if(useRotation){
				r  = randomMinMax(maxRandomDownAngle, minRandomDownAngle);
				//console.log(r, i)
				/*if(i==1 | i == 2){
					r=10;
				}else{
					r=-10;
				}*/
				if(Math.abs(r)>maxAchievedRot){
					maxAchievedRot=Math.abs(r);
					temp_max_r=Math.abs(r);
					temp_max_r_id = i;
				}  
				if(!isIEbelow9){
					if(i != playlistLength-1) div.css({rotate: r+'deg' });//dont rotate first
				}else{
					ieRotArr.push(r);	
				}
			}else{
				tr = Math.random() * 35;
				lr = Math.random() * 25;
				/*if(i==1 | i == 2){
					tr=50;
					lr=50;
				}else{
					tr=250;
					lr=250;
				}*/
				//find max
				if(tr > maxTR) maxTR = tr;
				if(lr > maxLR) maxLR = lr;
				//find min
				if(tr < minTR) minTR = tr;
				if(lr < minLR) minLR = lr;
				
				//console.log(tr, lr);
				//random position, no need for rotation case, getBounds would make math much harder for peaks
				div.css('top', tr + 'px' );
				div.css('left', lr + 'px' );
				div.data('origTop', tr);
				div.data('origLeft', lr);
			}
			
			//attach click to detect pp open
			div.find('a[class=pp_content]').bind('click', function(){
				detailOpened=true;
				if(slideshowOn) pauseSlideshow();
				detailActivated();
				return false;
			}).bind('mouseover', function(){
				$(this).find('img').attr('src', 'data/icons/detail_on.png');
				return false;
			}).bind('mouseout', function(){
				$(this).find('img').attr('src', 'data/icons/detail.png');
				return false;
			});
			
			div.find('a[class=pp_link]').bind('mouseover', function(){
				$(this).find('img').attr('src', 'data/icons/link_on.png');
				return false;
			}).bind('mouseout', function(){
				$(this).find('img').attr('src', 'data/icons/link.png');
				return false;
			});
			
			
			if(contains(swipeArr, i)){
				//console.log(i);
				div.bind("dragstart", function(e) { e.preventDefault(); return false; }).bind("selectstart", function(e) { e.preventDefault(); return false; });
				div.touchSwipe(swipeHandler, true); 
			}
			
			
			//captions
			j=0;
			caption_list = div.find('div[class=caption_list]');
			captionArr.push(caption_list);
			captions = caption_list.find('div[data-type=caption]');
			len = captions.length;
			//console.log(len);
			captionSubArr = [];
			
			for(j; j < len;j++){
				caption=captions.eq(j);
				caption.css('opacity', 0);
				captionSubArr.push(caption);
				//console.log(caption);
			}
			if(len > 0){
				div.data('caption_list', caption_list);
				div.data('captionSubArr', captionSubArr);
			}
			
			div.css('opacity', 0);//FIX FOR IE7/8, ADD AFTER BECAUSE OF DROP SHADOW
			//div.stop().delay(100 * i).animate({'opacity': 1},  {duration: 1000, easing: "easeOutSine"});
			div.stop().animate({'opacity': 1},  {duration: 1000, easing: "easeOutSine"});
		
		}
		
		//make maxAchievedRot be max and min rotation after we get maxAchievedRot, and force one slide to always have max rot (pos or neg) and always stay that way so all slides doesnt suddenly become smaller after shuffle (both fullSize and not because even in fixed size, parent can be smaller that defined, so we get fullSize).
		maxRandomDownAngle = maxAchievedRot;
		minRandomDownAngle = -maxAchievedRot;
		if(useRotation){
			div = $(playlistArr[temp_max_r_id]);
			div.data('max_rot', temp_max_r);
			//console.log(temp_max_r, temp_max_r_id);
			currentSlideWidth2 = currentSlideWidth;
			currentSlideHeight2 = currentSlideHeight;
		}else{
			//console.log('maxTR = ' , maxTR, ' , maxLR = ', maxLR);
			//console.log('minTR = ' , minTR, ' , minLR = ', minLR);
			//console.log((maxTR+minTR)/2, (maxLR+minLR)/2);
			currentSlideWidth2 = maxLR + currentSlideWidth - minLR;
			currentSlideHeight2 = maxTR + currentSlideHeight - minTR;
			//console.log(currentSlideWidth2, currentSlideHeight2);
		}

		if(!isIEbelow9)doneResizing(); 
		  
		checkCaptions(playlistLength-1);
		
		componentWrapper.css('opacity',1);
		
		if(componentControls){
			componentControls.css('opacity', 0);
			componentControls.css('display', 'block');
			componentControls.stop().animate({'opacity': 1}, {duration: 1000, easing: "easeOutSine"});
		}
		
		componentInited=true;
		stackGalleryReady();
		
		if(isIEbelow9){
			doneResizing(); 
		}
	}
	
	function getBounds(angle){//for rotation
		
		var rads = angle * Math.PI / 180;
		var cosA = Math.cos(rads);
		var sinA = Math.sin(rads);
		var width = currentSlideWidth;
		var height = currentSlideHeight;
		//console.log(currentSlideWidth, currentSlideHeight);
		
		rotatedWidth = Math.round(width * Math.abs(cosA) + height * Math.abs(sinA)),
        rotatedHeight = Math.round(width * Math.abs(sinA) + height * Math.abs(cosA));
		
		//console.log('rotatedWidth = ', parseInt(rotatedWidth,10), ' , rotatedHeight = ', parseInt(rotatedHeight,10));

		vert_size_deduct = (rotatedHeight - currentSlideHeight);
		hor_size_deduct = (rotatedWidth - currentSlideWidth);

		//console.log('vert_size_deduct = ', vert_size_deduct);
		//console.log('hor_size_deduct = ', hor_size_deduct);
	}
	
	function getBounds2(angle,tw,th){//find slide with max rotation to get bounds
		
		var rads = angle * Math.PI / 180;
		var cosA = Math.cos(rads);
		var sinA = Math.sin(rads);
		var width = tw;
		var height = th;
		//console.log(currentSlideWidth, currentSlideHeight);
		
		var x1 = cosA * width,
			y1 = sinA * width,
			x2 = -sinA * height,
			y2 = cosA * height,
			x3 = cosA * width - sinA * height,
			y3 = sinA * width + cosA * height;
		
		var minX = Math.min(0, x1, x2, x3),
			maxX = Math.max(0, x1, x2, x3),
			minY = Math.min(0, y1, y2, y3),
			maxY = Math.max(0, y1, y2, y3);
	
		rotatedWidth2  = Math.round(maxX - minX),
		rotatedHeight2 = Math.round(maxY - minY);
		
		//console.log('rotatedWidth2 = ', parseInt(rotatedWidth2,10), ' , rotatedHeight2 = ', parseInt(rotatedHeight2,10));
	}
	
	function doneResizing(){
		//if(getComponentSize('w')<300) return;	
		
		var i = 0,slide,scaler,w1,h1;
		
		if(useRotation){
			getBounds(maxAchievedRot);	
		}
		var w = getComponentSize('w'), h = getComponentSize('h');
		//console.log('getComponentSize(w) = ', w, ' , getComponentSize(h) = ', h);
		
		for(i; i <playlistLength; i++){
			slide = $(playlistArr[i]);
			scaler=slide.find("div[class=scaler]");
			//console.log(currentSlideWidth, currentSlideHeight, rotatedWidth, rotatedHeight);
			if(fullSize || currentSlideWidth2 > w || currentSlideHeight2 > h || rotatedWidth > w || rotatedHeight > h){
				 if(useRotation){
					 
				     var obj={};
					 obj.width = (h / (currentSlideHeight+vert_size_deduct)) * currentSlideWidth;
					 obj.height = h;
					 //console.log('obj.width = ', obj.width, ' , obj.height = ', obj.height);
					
					 getBounds2(maxAchievedRot,obj.width,(obj.width*slideRatio));//check newly resized
					 resizeCase = 'width';
					
					 if(rotatedWidth2-1 > w || rotatedHeight2-1 > h){//substract 1 because of rounding!
						obj.height = (w / (currentSlideWidth+hor_size_deduct)) * currentSlideHeight;
						obj.width = w;
						resizeCase = 'height';
					 }
					 //console.log('obj.width = ', obj.width, ' , obj.height = ', obj.height);
					 
				 }else{
					 //start shrinking slides down when max square with becames smaller than w/h
					 var obj = retrieveObjectRatio(componentWrapper, currentSlideWidth, currentSlideHeight, true);
				 }
				 w1 = obj.width;			
				 h1 = obj.height;
			}else{
				 w1 = currentSlideWidth;			
				 h1 = currentSlideHeight;	
			}
			//console.log(w1, ' , ', h1);
			//console.log(resizeCase);
			
			if(!isIEbelow9){
			
				if(slide){//resize slide wrapper
					if(resizeCase == 'width'){
						slide.width(w1);	
						slide.height((w1)*slideRatio);
					}else{
						slide.height((h1));
						slide.width((h1)*slideRatio2);
					}
				}
				if(scaler){//resize slide scaler
					if(resizeCase == 'width'){
						scaler.width(w1-2*slideBorder);	
						scaler.height(slide.width()*slideRatio-2*slideBorder);
					}else{
						scaler.height(h1-2*slideBorder);	
						scaler.width(slide.height()*slideRatio2-2*slideBorder);
					}
				}
			
			}else{
				
				if(fullSize || currentSlideWidth2 > w || currentSlideHeight2 > h || rotatedWidth > w || rotatedHeight > h || 
				slide.width() < currentSlideWidth2 || slide.height() < currentSlideHeight2){
					
					if(slide){//resize slide wrapper
						if(resizeCase == 'width'){
							slide.width(w1);	
							slide.height((w1)*slideRatio);
						}else{
							slide.height((h1));
							slide.width((h1)*slideRatio2);
						}
					}
					if(scaler){//resize slide scaler
						if(resizeCase == 'width'){
							scaler.width(w1-2*slideBorder);	
							scaler.height(slide.width()*slideRatio-2*slideBorder);
						}else{
							scaler.height(h1-2*slideBorder);	
							scaler.width(slide.height()*slideRatio2-2*slideBorder);
						}
					}
				}
			}
		}
		
		
		if(useRotation){
			if(resizeCase == 'width'){
				componentPlaylist.css({
					left:50+'%',
					top:50+'%',
					width:w1+'px',
					height:slide.width()*slideRatio+'px',
					marginLeft:-w1/2+'px',
					marginTop:-(slide.width()*slideRatio)/2+'px'
				});
			}else{
				componentPlaylist.css({
					left:50+'%',
					top:50+'%',
					height:h1+'px',
					width:slide.height()*slideRatio2+'px',
					marginLeft:-(slide.height()*slideRatio2)/2+'px',
					marginTop:-(slide.width()*slideRatio)/2+'px'
				});
			}
		}else{
			componentPlaylist.css({
				left:50+'%',
				top:50+'%',
				width:w1+'px',
				height:slide.width()*slideRatio+'px',
				marginLeft:-w1/2-(maxLR+minLR)/2+'px',///move by max lr and tr to center the slides
				marginTop:-(slide.width()*slideRatio)/2-(maxTR+minTR)/2+'px'
			});
		}
				
		
		//align controls
		if(componentControls){
			var pos;
			if(controlsAlignment == 'rightCenter'){
				pos = getComponentSize('w')/2 + componentPlaylist.width()/2 + componentControls.width();
				if(pos > getComponentSize('w') - componentControls.width() - 20) pos = getComponentSize('w') - componentControls.width() - 20;
				componentControls.css('left', pos+'px');
			}else if(controlsAlignment == 'topCenter'){
				pos = getComponentSize('h')/2 - componentPlaylist.height()/2 - componentControls.height()-10;
				if(pos < 10) pos = 10;
				componentControls.css('top', pos+'px');
			}
		}
		
		
		
		//captions sizes
		var i =0, len =captionArr.length, captionHolder, caption;
		for(i; i <len; i++){
			captionHolder = $(captionArr[i]);
			captionHolder.find('div[data-type=caption]').each(function(){
				caption = $(this);
				caption.removeClass('caption_font_small').removeClass('caption_font_medium').removeClass('caption_font_large');
				if(getComponentSize('w')<=570 || getComponentSize('h')<=320){
					caption.addClass('caption_font_small');
				}else if(getComponentSize('w')<=767 || getComponentSize('h')<=570){ 
					caption.addClass('caption_font_medium');
				}else if(getComponentSize('w')<=959 || getComponentSize('h')<=767){
					caption.addClass('caption_font_large');
				}
				
			});
		}
		
		if(useRotation && isIEbelow9){
			for(var i=0; i <playlistLength; i++){
				div = $(playlistArr[i]);
				if(i != playlistLength-1) div.css({rotate: ieRotArr[i]+'deg' });
			}
		}
	}
	
	function retrieveObjectRatio( obj, w, h, _fitScreen ) {
			
		var o ={};
		
		var _paddingX = 0;
		var _paddingY = 0;
		
		var objWidth = getComponentSize('w') - (currentSlideWidth2 - currentSlideWidth);//in non rotation deduct top/left offsets
		var objHeight = getComponentSize('h') - (currentSlideHeight2 - currentSlideHeight);
		
		var targetWidth = w;
		var targetHeight = h;
		
		var destinationRatio = (objWidth - _paddingX) / (objHeight - _paddingY);///destination ratio of an object
		var targetRatio = targetWidth / targetHeight;///target ratio of an object
		//console.log('targetRatio = ', targetRatio, ' , destinationRatio = ', destinationRatio);

		if (targetRatio < destinationRatio) {
			
			//console.log('targetRatio < destinationRatio 1');
			
			if (!_fitScreen) {//fullscreen
				o.height = ((objWidth - _paddingX) / targetWidth) * targetHeight;
				o.width = (objWidth - _paddingX);
			} else {//fitscreen
				o.width = ((objHeight - _paddingY) / targetHeight) * targetWidth;
				o.height = (objHeight - _paddingY);
			}
		} else if (targetRatio > destinationRatio) {
	
			//console.log('targetRatio > destinationRatio 2');
			
			if (_fitScreen) {//fitscreen
				o.height = ((objWidth - _paddingX) / targetWidth) * targetHeight;
				o.width = (objWidth - _paddingX);
			} else {//fullscreen
				o.width = ((objHeight - _paddingY) / targetHeight) * targetWidth;
				o.height = (objHeight - _paddingY);
			}
		} else {//fitscreen & fullscreen
			//console.log('targetRatio = destinationRatio 3');
			o.width = (objWidth - _paddingX);
			o.height = (objHeight - _paddingY);
		}
		
		return o;
	}
	
	function retrieveObjectRatio( obj, w, h, _fitScreen ) {
			
		var o ={};
		
		var _paddingX = 0;
		var _paddingY = 0;
		
		var objWidth = getComponentSize('w') - (currentSlideWidth2 - currentSlideWidth);//in non rotation deduct top/left offsets
		var objHeight = getComponentSize('h') - (currentSlideHeight2 - currentSlideHeight);
		
		var targetWidth = w;
		var targetHeight = h;
		
		var destinationRatio = (objWidth - _paddingX) / (objHeight - _paddingY);///destination ratio of an object
		var targetRatio = targetWidth / targetHeight;///target ratio of an object
		//console.log('targetRatio = ', targetRatio, ' , destinationRatio = ', destinationRatio);

		if (targetRatio < destinationRatio) {
			
			//console.log('targetRatio < destinationRatio 1');
			
			if (!_fitScreen) {//fullscreen
				o.height = ((objWidth - _paddingX) / targetWidth) * targetHeight;
				o.width = (objWidth - _paddingX);
			} else {//fitscreen
				o.width = ((objHeight - _paddingY) / targetHeight) * targetWidth;
				o.height = (objHeight - _paddingY);
			}
		} else if (targetRatio > destinationRatio) {
	
			//console.log('targetRatio > destinationRatio 2');
			
			if (_fitScreen) {//fitscreen
				o.height = ((objWidth - _paddingX) / targetWidth) * targetHeight;
				o.width = (objWidth - _paddingX);
			} else {//fullscreen
				o.width = ((objHeight - _paddingY) / targetHeight) * targetWidth;
				o.height = (objHeight - _paddingY);
			}
		} else {//fitscreen & fullscreen
			//console.log('targetRatio = destinationRatio 3');
			o.width = (objWidth - _paddingX);
			o.height = (objHeight - _paddingY);
		}
		
		return o;
	}
	
	
	//**************** slide engine
	
	function getSlideSize(){
		var div = $(playlistArr[0]);//to do: make slide diferrent size
		if(useRotation){
			verticalValue = div.outerHeight() + 100;//aproximations
			horizontalValue = div.outerWidth() + 50;
		}else{
			verticalValue = div.outerHeight()+ 10;
			horizontalValue = div.outerWidth()+ 5;
		}
	}
	
	function goForward(slideToOpen, original){
		
		var id = playlistLength - 1;
		var origSlideNum = parseInt($(playlistArr[id]).attr('data-orig-id'),10);
		beforeSlideChange(origSlideNum);
		if(playlistArr[id].data('captionSubArr') != undefined) removeCaptions(id);	//remove captions on top slide
		
		var slide;
		if(!slideToOpen){
			slide=$(getSlideById(id));
		}else{
			if(!original){
				slide=$(getSlideById(slideToOpen));
			}else{
				slide=$(getSlideByOrigId(slideToOpen));
			}
		}
	
		var r = randomMinMax(maxRandomUpAngle, minRandomUpAngle);
		var r2 = randomMinMax(maxRandomDownAngle, minRandomDownAngle);
		var cr=0, currentTime = 0;
		 
		getSlideSize(); 
		 
		if(slideshowLayout == 'verticalAbove' || slideshowLayout == 'verticalRound'){

			 if(useRotation){
				if(isSafari){

					slide.animate({top: -(verticalValue)+'px'}, {
						duration: upPartDuration, 
						easing: "easeOutSine",
						step: function(now,fx) {
						   //http://stackoverflow.com/questions/10568507/is-there-any-way-to-get-the-remaining-time-of-animation
						   currentTime = Math.round((now*upPartDuration)/verticalValue);
						   //console.log(currentTime);
						   cr = - (currentTime * r / upPartDuration);///negative
						   //console.log(cr);
						   setTimeout(function() { slide.css('-webkit-transform','rotate('+cr+'deg)'); },65);
						},
						complete: function(){
							//console.log(cr);
							goUpPart2(slide, r2, cr, slideToOpen, original);
						}
					});
				}else{
					slide.animate({rotate: r+'deg', top: -(verticalValue)+'px'}, {
						duration: upPartDuration, 
						easing: "easeOutCubic",
						complete: function(){
							goUpPart2(slide, r2, cr, slideToOpen, original);
						}
					});	
				}
		 	 }else{
				 slide.animate({top: -(verticalValue)+'px'}, {
					duration: upPartDuration, 
					easing: "easeOutCubic",
					complete: function(){
						goUpPart2(slide, r2, cr, slideToOpen, original);
					}
				});
			 }
			 
		 }else{//HORIZONTAL
			
			var leftValue = slideshowLayout == 'horizontalLeft' ? - horizontalValue : horizontalValue;
			
			if(useRotation){
				if(isSafari){
				
					slide.animate({left: leftValue+'px'}, {
						duration: upPartDuration, 
						easing: "easeOutSine",
						step: function(now,fx) {
						   //http://stackoverflow.com/questions/10568507/is-there-any-way-to-get-the-remaining-time-of-animation
						   currentTime = Math.round((now*upPartDuration)/leftValue);
						   //console.log(currentTime);
						   if(slideshowLayout == 'horizontalLeft'){
							   cr = (currentTime * r / upPartDuration);
						   }else{
							  cr = - (currentTime * r / upPartDuration);///negative
						   }
						   //console.log(cr);
						   setTimeout(function() { slide.css('-webkit-transform','rotate('+cr+'deg)'); },65);
						},
						complete: function(){
							//console.log(cr);
							goSidePart2(slide, r2, cr, slideToOpen, original);
						}
					});
				}else{
					slide.animate({rotate: r+'deg', left: leftValue+'px'}, {
						duration: upPartDuration, 
						easing: "easeOutCubic",
						complete: function(){
							goSidePart2(slide, r2, cr, slideToOpen, original);
						}
					});
				}
		 	}else{
				slide.animate({left: leftValue+'px'}, {
					duration: upPartDuration, 
					easing: "easeOutCubic",
					complete: function(){
						goSidePart2(slide, r2, cr, slideToOpen, original);
					}
				});
			}	
		}
	}
	
	function goUpPart2(slide, r2, r, slideToOpen, original){
		
		//change indexes
		if(!slideToOpen){
			playlistArr.unshift(playlistArr.pop());//last to front 
			changeIndexes();
		}else{
			if(original){
				var temp = $(getSlideByOrigId(slideToOpen));//get slide original id (because position in stack might be changed)
				slideToOpen = temp.attr('data-id');//or zIndex
			}
			var _item = playlistArr[slideToOpen];//selected to front
			playlistArr.splice(slideToOpen,1);
			playlistArr.push(_item);
			changeIndexes();
		}
		
		if(slide.data('max_rot') != undefined){
			r2 = parseInt(slide.data('max_rot'),10);
		}
		
		var old_r2 = r2;
		if(useRotation){	
		
			if(isSafari){
				
				var radius, direction, cr=0, currentTime = 0, startRot = r, finalRot, startValue = parseInt(slide.css('top'),10);
				if(slideToOpen)r2=0;
				
				//minus
				if(r<0 && r2<0 && r>r2){
					radius = Math.abs(Math.abs(r) - Math.abs(r2));
					direction='-';
				}else if(r>0 && r2>0 && r>r2){
					radius = r - r2;
					direction='-';
				}else if(r>0 && r2<0){
					radius = r + Math.abs(r2);
					direction='-';
				}
				//plus
				else if(r<0 && r2<0 && r<r2){
					radius = Math.abs(Math.abs(r) - Math.abs(r2));
					direction='+';
				}else if(r>0 && r2>0 && r<r2){
					radius = r2 - r;
					direction='+';
				}else if(r<0 && r2>0){
					radius = Math.abs(r) + r2;
					direction='+';
				}
				//zero examples
				else if(r==0 && r2>0){
					radius = r2;
					direction='+';
				}else if(r>0 && r2==0){
					radius = r;
					direction='-';
				}else if(r<0 && r2==0){
					radius = Math.abs(r);
					direction='+';
				}else if(r==0 && r2<0){
					radius = Math.abs(r2);
					direction='-';
				}
				
				slide.animate({top: 0+'px'}, {
					duration: downPartDuration, 
					easing: "easeOutSine",
					step: function(now,fx) {
					   //console.log(now);
					   currentTime = - Math.round(((now*downPartDuration)/startValue)-downPartDuration);
					   //console.log(currentTime);
					   cr = (currentTime * radius / downPartDuration);
					   //console.log(cr);
					   setTimeout(function() { 
						   if(direction == '+'){
							  finalRot = startRot+cr;
							  //console.log('finalRot = ', finalRot);
							  slide.css('-webkit-transform','rotate('+finalRot+'deg)');  
						   }else{
							  finalRot = startRot-cr;
							  //console.log('finalRot = ', finalRot);
							  slide.css('-webkit-transform','rotate('+finalRot+'deg)');   
						   }
					   },65);
					}
				});
			}else{//not safari
				if(!slideToOpen){	
					slide.animate({rotate: r2+'deg', top: 0+'px'}, {
						duration: downPartDuration, 
						easing: "easeOutExpo"
					});
				}else{
					slide.animate({rotate: 0+'deg', top: 0+'px'}, {
						duration: downPartDuration, 
						easing: "easeOutExpo"
					});
				}
			}
		}else{
			var ot=0;
			if(slide.data('origTop') != undefined){
				ot = parseInt(slide.data('origTop'),10);
			}
			slide.animate({top: ot+'px'}, {//no rotation
				duration: downPartDuration, 
				easing: "easeOutExpo"
			});
		}
		
		if(useRotation){
			if(!slideToOpen){	
				var nextInStack = $(playlistArr[playlistLength - 1]);//rotate first on top to zero
				nextInStack.animate({ 
					rotate:0 + 'deg',
					duration:downPartDuration,
					easing: "easeOutExpo"
				});
			}else{
				var previousFront = $(playlistArr[playlistLength - 2]);//rotate second from top to non zero
				previousFront.animate({ 
					 rotate:old_r2+'deg',
					 duration:downPartDuration,
					 easing: "easeOutSine"
				});
			}
		}
	  
	    if(endTransitionCheckID) clearTimeout(endTransitionCheckID);
	    endTransitionCheckID = setTimeout(checkEndTransition, downPartDuration);
	}

	function goSidePart2(slide, r2, r, slideToOpen, original){
		
		//change indexes
		if(!slideToOpen){
			playlistArr.unshift(playlistArr.pop());//last to front 
			changeIndexes();
		}else{
			if(original){
				var temp = $(getSlideByOrigId(slideToOpen));//get slide original id (because position in stack might be changed)
				slideToOpen = temp.attr('data-id');//or zIndex
			}
			var _item = playlistArr[slideToOpen];//selected to front
			playlistArr.splice(slideToOpen,1);
			playlistArr.push(_item);
			changeIndexes();
		}
		
		if(slide.data('max_rot') != undefined){
			r2 = parseInt(slide.data('max_rot'),10);
		}
			
		var old_r2 = r2;	
		if(useRotation){	
		
			if(isSafari){
				
				var radius, direction, cr=0, currentTime = 0, startRot = r, finalRot, startValue = parseInt(slide.css('left'),10);
				if(slideToOpen)r2=0;
				
				//minus
				if(r<0 && r2<0 && r>r2){
					radius = Math.abs(Math.abs(r) - Math.abs(r2));
					direction='-';
				}else if(r>0 && r2>0 && r>r2){
					radius = r - r2;
					direction='-';
				}else if(r>0 && r2<0){
					radius = r + Math.abs(r2);
					direction='-';
				}
				//plus
				else if(r<0 && r2<0 && r<r2){
					radius = Math.abs(Math.abs(r) - Math.abs(r2));
					direction='+';
				}else if(r>0 && r2>0 && r<r2){
					radius = r2 - r;
					direction='+';
				}else if(r<0 && r2>0){
					radius = Math.abs(r) + r2;
					direction='+';
				}
				//zero examples
				else if(r==0 && r2>0){
					radius = r2;
					direction='+';
				}else if(r>0 && r2==0){
					radius = r;
					direction='-';
				}else if(r<0 && r2==0){
					radius = Math.abs(r);
					direction='+';
				}else if(r==0 && r2<0){
					radius = Math.abs(r2);
					direction='-';
				}
				
				slide.animate({left: 0+'px'}, {
					duration: downPartDuration, 
					easing: "easeOutSine",
					step: function(now,fx) {
					   //console.log(now);
					   currentTime = - Math.round(((now*downPartDuration)/startValue)-downPartDuration);
					   //console.log(currentTime);
					   cr = (currentTime * radius / downPartDuration);
					   //console.log(cr);
					   setTimeout(function() { 
						   if(direction == '+'){
							  finalRot = startRot+cr;
							  //console.log('finalRot = ', finalRot);
							  slide.css('-webkit-transform','rotate('+finalRot+'deg)');  
						   }else{
							  finalRot = startRot-cr;
							  //console.log('finalRot = ', finalRot);
							  slide.css('-webkit-transform','rotate('+finalRot+'deg)');   
						   }
					   },65);
					}
				});
			}else{
				if(!slideToOpen){	
					slide.animate({rotate: r2+'deg', left: 0+'px'}, {
						duration: downPartDuration, 
						easing: "easeOutExpo"
					});
				}else{
					slide.animate({rotate: 0+'deg', left: 0+'px'}, {
						duration: downPartDuration, 
						easing: "easeOutExpo"
					});
				}
			}
		}else{
			var ol=0;
			if(slide.data('origLeft') != undefined){
				ol = parseInt(slide.data('origLeft'),10);
			}
			slide.animate({left: ol+'px'}, {
				duration: downPartDuration, 
				easing: "easeOutExpo"
			});
		}
		
		if(useRotation){
			if(!slideToOpen){	
				var nextInStack = $(playlistArr[playlistLength - 1]);
				nextInStack.animate({ 
					rotate:0 + 'deg',
					duration:downPartDuration,
					easing: "easeOutExpo"
				});
			}else{
				var previousFront = $(playlistArr[playlistLength - 2]);
				previousFront.animate({ 
					 rotate:old_r2+'deg',
					 duration:downPartDuration,
					 easing: "easeOutSine"
				});
			}
		}
	  
	    if(endTransitionCheckID) clearTimeout(endTransitionCheckID);
	    endTransitionCheckID = setTimeout(checkEndTransition, downPartDuration);
	}
	
	function goBack(){
		
		 var cid = $(playlistArr[playlistLength - 1]).attr('data-id');
		 var origSlideNum = parseInt($(playlistArr[cid]).attr('data-orig-id'),10);
		 beforeSlideChange(origSlideNum);
		 if(playlistArr[cid].data('captionSubArr') != undefined) removeCaptions(cid);//remove captions on top slide
		
		 var slide=$(getSlideById(0));
	
		 var r = randomMinMax(maxRandomUpAngle, minRandomUpAngle);
		 var r2 = randomMinMax(maxRandomDownAngle, minRandomDownAngle);
		 //console.log(r);
		 
		 var cr=0, currentTime = 0;
		 
		 getSlideSize();
		 
		 if(slideshowLayout == 'verticalAbove' || slideshowLayout == 'verticalRound'){

			var topValue = slideshowLayout == 'verticalRound' ? verticalValue : -verticalValue;
			if(useRotation){
				if(isSafari){
					
					slide.animate({top: topValue+'px'}, {
						duration: upPartDuration, 
						easing: "easeOutSine",
						step: function(now,fx) {
						   //http://stackoverflow.com/questions/10568507/is-there-any-way-to-get-the-remaining-time-of-animation
						   currentTime = Math.round((now*upPartDuration)/topValue);
						   //console.log(currentTime);
						   if(slideshowLayout == 'verticalRound'){
							   cr = - (currentTime * r / upPartDuration);///negative
						   }else{
							  cr = (currentTime * r / upPartDuration);
						   }
						   //console.log(cr);
						   setTimeout(function() { slide.css('-webkit-transform','rotate('+cr+'deg)'); },65);
						},
						complete: function(){
							//console.log(cr);
							goBackUpPart2(slide, r2, cr);
						}
					});
				}else{
					slide.animate({rotate: r+'deg', top: topValue+'px'}, {
						duration: upPartDuration, 
						easing: "easeOutCubic",
						complete: function(){
							goBackUpPart2(slide, r2);
						}
					});
				}
			}else{
				slide.animate({top: topValue+'px'}, {
					duration: upPartDuration, 
					easing: "easeOutCubic",
					complete: function(){
						goBackUpPart2(slide);
					}
				});
			}
		 }else{//HORIZONTAL
			
			var leftValue = slideshowLayout == 'horizontalLeft' ? - horizontalValue : horizontalValue;
			if(useRotation){
				if(isSafari){
					
					slide.animate({left: leftValue+'px'}, {
						duration: upPartDuration, 
						easing: "easeOutSine",
						step: function(now,fx) {
						   //http://stackoverflow.com/questions/10568507/is-there-any-way-to-get-the-remaining-time-of-animation
						   currentTime = Math.round((now*upPartDuration)/leftValue);
						   //console.log(currentTime);
						   if(slideshowLayout == 'horizontalLeft'){
							   cr = - (currentTime * r / upPartDuration);///negative
						   }else{
							  cr = (currentTime * r / upPartDuration);
						   }
						   //console.log(cr);
						   setTimeout(function() { slide.css('-webkit-transform','rotate('+cr+'deg)'); },65);
						},
						complete: function(){
							//console.log(cr);
							goBackSidePart2(slide, r2, cr);
						}
					});
				}else{
					slide.animate({rotate: r+'deg', left: leftValue+'px'}, {
						duration: upPartDuration, 
						easing: "easeOutCubic",
						complete: function(){
							goBackSidePart2(slide, r2);
						}
					});
				}
			}else{
				slide.animate({left: leftValue+'px'}, {
					duration: upPartDuration, 
					easing: "easeOutCubic",
					complete: function(){
						goBackSidePart2(slide);
					}
				});
			}
		}
	}
	
	function goBackUpPart2(slide, r2, r){
						
		playlistArr.push(playlistArr.shift());//push front to last 
		changeIndexes2();
		
		/*
		on each go back rotate forward to non zero. (otherwise if we go back all the time all would be zero rotated in stack)
		*/
		//check forward for non zero
		
		if(slide.data('max_rot') != undefined){
			r2 = parseInt(slide.data('max_rot'),10);
		}
		
		if(useRotation){
			var previousFront = $(playlistArr[playlistLength - 2]);
			previousFront.animate({ 
				 rotate:r2+'deg',
				 duration:downPartDuration,
				 easing: "easeOutSine"
			});
				
			if(isSafari){
				
				var radius = Math.abs(r), direction, cr=0, currentTime = 0, startRot = r, finalRot, startValue = parseInt(slide.css('top'),10);

				if(r>0){
					direction='-';
				}else if(r<0){
					direction='+';
				}
				
				slide.animate({top: 0+'px'}, {
					duration: downPartDuration, 
					easing: "easeOutSine",
					step: function(now,fx) {
					   //console.log(now);
					   currentTime = - Math.round(((now*downPartDuration)/startValue)-downPartDuration);
					   //console.log(currentTime);
					   cr = (currentTime * radius / downPartDuration);
					   //console.log(cr);
					   setTimeout(function() { 
						   if(direction == '+'){
							  finalRot = Math.floor(startRot+cr);
							  //console.log('finalRot = ', finalRot);
							  slide.css('-webkit-transform','rotate('+finalRot+'deg)');  
						   }else{
							  finalRot = Math.floor(startRot-cr);
							  //console.log('finalRot = ', finalRot);
							  slide.css('-webkit-transform','rotate('+finalRot+'deg)');   
						   }
					   },65);
					},
					complete: function(){
						//console.log(finalRot);
						checkCaptions(slide.attr('data-id'));
					}
				});
			}else{
				slide.animate({rotate: 0+'deg', top: 0+'px'}, {
					duration: downPartDuration, 
					easing: "easeOutExpo",
					complete: function(){
						checkCaptions(slide.attr('data-id'));
					}
				});
			}
		}else{
			var ot=0;
			if(slide.data('origTop') != undefined){
				ot = parseInt(slide.data('origTop'),10);
			}
			slide.animate({top: ot+'px'}, {
				duration: downPartDuration, 
				easing: "easeOutExpo",
				complete: function(){
					checkCaptions(slide.attr('data-id'));
				}
			});
		}
	}
	
	function goBackSidePart2(slide, r2, r){
		
		playlistArr.push(playlistArr.shift());//push front to last 
		changeIndexes2();
		
		/*
		on each go back rotate forward to non zero. (otherwise if we go back all the time all would be zero rotated in stack)
		*/
		//check forward for non zero
		
		if(slide.data('max_rot') != undefined){
			r2 = parseInt(slide.data('max_rot'),10);
		}
		
		if(useRotation){
			var previousFront = $(playlistArr[playlistLength - 2]);
			previousFront.animate({ 
				 rotate:r2+'deg',
				 duration:downPartDuration,
				 easing: "easeOutSine"
			});
				
			if(isSafari){
				
				var radius = Math.abs(r), direction, cr=0, currentTime = 0, startRot = r, finalRot, startValue = parseInt(slide.css('left'),10);

				if(r>0){
					direction='-';
				}else if(r<0){
					direction='+';
				}
				
				slide.animate({left: 0+'px'}, {
					duration: downPartDuration, 
					easing: "easeOutSine",
					step: function(now,fx) {
					   //console.log(now);
					   currentTime = - Math.round(((now*downPartDuration)/startValue)-downPartDuration);
					   //console.log(currentTime);
					   cr = (currentTime * radius / downPartDuration);
					   //console.log(cr);
					   setTimeout(function() { 
						   if(direction == '+'){
							  finalRot = Math.floor(startRot+cr);
							  //console.log('finalRot = ', finalRot);
							  slide.css('-webkit-transform','rotate('+finalRot+'deg)');  
						   }else{
							  finalRot = Math.floor(startRot-cr);
							  //console.log('finalRot = ', finalRot);
							  slide.css('-webkit-transform','rotate('+finalRot+'deg)');   
						   }
					   },65);
					},
					complete: function(){
						//console.log(finalRot);
						checkCaptions(slide.attr('data-id'));
					}
				});
			}else{
				slide.animate({rotate: 0+'deg', left: 0+'px'}, {
					duration: downPartDuration, 
					easing: "easeOutExpo",
					complete: function(){
						checkCaptions(slide.attr('data-id'));
					}
				});
			}
		}else{
			var ol=0;
			if(slide.data('origLeft') != undefined){
				ol = parseInt(slide.data('origLeft'),10);
			}
			slide.animate({left: ol+'px'}, {
				duration: downPartDuration, 
				easing: "easeOutExpo",
				complete: function(){
					checkCaptions(slide.attr('data-id'));
				}
			});
		}
	}
	
	//************** captions
	
	function removeCaptions(id){
		//console.log('removeCaptions');
		var i = 0,captionSubArr = playlistArr[id].data('captionSubArr'),caption_list = playlistArr[id].data('caption_list'),len =captionSubArr.length, caption;
		for(i; i < len;i++){
			caption=$(captionSubArr[i]);
			if(i != len - 1){
				caption.stop().animate({opacity: 0}, {
					duration: captionToggleSpeed, 
					easing: "easeOutQuint"
				});
			}else{
				caption.stop().animate({opacity: 0}, {
					duration: captionToggleSpeed, 
					easing: "easeOutQuint",
					complete: function(){
						caption_list.css('display','none');//hide caption holder
					}
				});
			}
		}
	}
	
	function checkCaptions(id){
		//console.log('checkCaptions');
		 var origSlideNum = parseInt($(playlistArr[id]).attr('data-orig-id'),10);
		 afterSlideChange(origSlideNum);
		 
		 if(playlistArr[id].data('captionSubArr') != undefined){
			
			var i = 0, captionSubArr = playlistArr[id].data('captionSubArr'),caption_list = playlistArr[id].data('caption_list'),len =captionSubArr.length,caption;
			
			caption_list.css('display','block');
			
			for(i; i < len;i++){
				
				caption=$(captionSubArr[i]);
				
				caption.css('opacity', 0);
				caption.css('left', -100+'px');
				
				if(i != len-1){
					caption.delay(captionOpenDelay * i).animate({opacity: 1, left: 0+'px'}, {
						duration: captionToggleSpeed, 
						easing: "easeOutQuart"
					});
				}else if(i == len -1){//last caption
					caption.delay(captionOpenDelay * i).animate({opacity: 1, left: 0+'px'}, {
					duration: captionToggleSpeed, 
					easing: "easeOutQuart",
					complete: function(){
						//if(playlistArr[id].hasLink == true){//add click to ones with no link
							//$(playlistArr[id]).bind('click touchstart', navigateToUrl);
							//$(playlistArr[id]).css('cursor', 'pointer');
						//}
						advanceButtonMode('on');
						transitionOn=false;
						checkSlideshow(); 
					}});
					
				}
				
			}
			
		}else{//no captions
			advanceButtonMode('on');
			transitionOn=false;
			checkSlideshow();
		}
	}
	
	//************
	
	function getComponentSize(type){
		if(type == "w"){//width
			return componentWrapper.width();
		}else{//height
			return componentWrapper.height();
		} 
	}
	
	function checkEndTransition(){
		//console.log("checkEndTransition ");
		if(endTransitionCheckID) clearTimeout(endTransitionCheckID);
		checkCaptions($(playlistArr[playlistLength - 1]).attr('data-id'));
	}
	
	this.checkSlideshow2 = function() {//called after prettyphoto detail close (public function)
		detailOpened=false;
		if(slideshowOn){
			if(!transitionOn){
				resumeSlideshow();
			}
		}	
		detailClosed();
	}
	
	function checkSlideshow(){
		if(slideshowOn && !sliderHit){
			 resumeSlideshow();
		}
	}
	
	function pauseSlideshow(){
		//console.log('pauseSlideshow');
		if(slideshowTimeoutID) clearTimeout(slideshowTimeoutID);
		controls_toggle.find('img').attr('src', 'data/icons/play.png');
	}
	
	function resumeSlideshow(){
		//console.log('resumeSlideshow');
		if(slideshowTimeoutID) clearTimeout(slideshowTimeoutID);
		slideshowTimeoutID = setTimeout(nextSlide, slideshowTimeout);
		controls_toggle.find('img').attr('src', 'data/icons/pause.png');
	}
	
	function swipeHandler(direction){
		//console.log(direction);
		if(!componentInited) return false;
		if(direction == 'right'){
			previousMedia();
		}else if(direction == 'left'){
			nextMedia();
		}
		return false;
	}
	
	function nextSlide(){
		//console.log("nextSlide");
		transitionOn=true;
		advanceButtonMode('off');
		if(slideshowDirection == "forward"){
			goForward();
		}else{
			goBack();
		}
	}
	
	function advanceButtonMode(mode){
		if(mode=='on'){
			controls_previous.css('cursor', 'pointer');
			controls_next.css('cursor', 'pointer');
		}else{
			controls_previous.css('cursor', 'default');
			controls_next.css('cursor', 'default');
		}
	}
	
	function previousMedia(){
		transitionOn=true;
		advanceButtonMode('off');
		goBack();
	}
	
	function nextMedia(){
		transitionOn=true;
		advanceButtonMode('off');
		goForward();
	}
	
	function toggleControls(e){
		if(!componentInited) return false;
		if (!e) var e = window.event;
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();
		var currentTarget = e.currentTarget;
		var c = $(currentTarget).attr('class');
		//console.log("toggleControls");
		
		if(c == "controls_previous"){
			if(transitionOn) return;
			previousMedia();
		}else if(c == "controls_toggle"){
			toggleSlideshow();
		}else if(c == "controls_next"){
			if(transitionOn) return;
			nextMedia();
		}
		return false;
	}
	
	function overControls(e){
		if(!componentInited) return false;
		if (!e) var e = window.event;
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();
		var currentTarget = e.currentTarget;
		var c = $(currentTarget).attr('class');
		//console.log("toggleControls = ", class);
		var img = $(currentTarget).find('img');
		
		if(c == "controls_previous"){
			img.attr('src', 'data/icons/prev_on.png');
		}else if(c == "controls_toggle"){
			if(slideshowOn){
				img.attr('src', 'data/icons/pause_on.png');
			}else{
				img.attr('src', 'data/icons/play_on.png');
			}	
		}else if(c == "controls_next"){
			img.attr('src', 'data/icons/next_on.png');
		}
		return false;
	}
	
	function outControls(e){
		if(!componentInited) return false;
		if (!e) var e = window.event;
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();
		var currentTarget = e.currentTarget;
		var c = $(currentTarget).attr('class');
		//console.log("toggleControls = ", class);
		var img = $(currentTarget).find('img');
		
		if(c == "controls_previous"){
			img.attr('src', 'data/icons/prev.png');
		}else if(c == "controls_toggle"){
			if(slideshowOn){
				img.attr('src', 'data/icons/pause.png');
			}else{
				img.attr('src', 'data/icons/play.png');
			}	
		}else if(c == "controls_next"){
			img.attr('src', 'data/icons/next.png');
		}
		return false;
	}
	
	function getSlideById(id){
		var i = 0,div,index;
		for(i; i< playlistLength; i++){
			div = $(playlistArr[i]);
			index = div.attr('data-id');
			if(index == id) break;
		}
		return div;
	}
	
	function getSlideByOrigId(id){
		var i = 0,div,index;
		for(i; i< playlistLength; i++){
			div = $(playlistArr[i]);
			index = div.attr('data-orig-id');
			if(index == id) break;
		}
		return div;
	}
	
	function changeIndexes() {//change indexes from bottom up
		var i = 0, div;
		for(i; i< playlistLength;i++){
			div=$(playlistArr[i]);
			//console.log(div);
			//console.log('before = ', div.css('zIndex'));
			div.attr('data-id', i);
			div.css('zIndex', i+indexAddOn);
			//console.log('after = ', div.css('zIndex'));
		}
	}
	
	function changeIndexes2() {//change indexes from top down
		var i = 0,div;
		for(i; i< playlistLength;i++){
			div=$(playlistArr[i]);
			div.attr('data-id', i);
			div.css('zIndex', i+indexAddOn);
		}
	}
	
	//returns a random value between min and max
	function randomMinMax(min, max){
		return Math.random() * (max - min) + min;
	}
	
	function setArgb(val) {
      var valArr = val.split("(")[1].split(")")[0].split(","),
          red = toHex(valArr[0]),
          green = toHex(valArr[1]),
          blue = toHex(valArr[2]),
          alpha = toHex(valArr[3]*255);
		  return "#" + alpha + red + green + blue;
     	  //console.log("#" + alpha + red + green + blue);
    };
	
    function toHex(val) {
      val = parseInt(val);
      val = Math.max(0,val);
      val = Math.min(val,255);
      val = Math.round(val);
      return "0123456789ABCDEF".charAt((val-val%16)/16) + "0123456789ABCDEF".charAt(val%16);
    };
	
	//contains in array
	function contains(a, obj) {
		var i = a.length;
		while (i--) {
		   if (parseInt(a[i],10) === obj) {
			   return true;
		   }
		}
		return false;
	}
	
	function toggleSlideshow(){
		if(slideshowOn){
			pauseSlideshow();
			slideshowOn = false;
		}else{
			slideshowOn=true;
			if(!transitionOn){
				 resumeSlideshow();
			}
		}	
	}
	
	function toggleSlideshow2(state){
		if(state){//start
			slideshowOn=true;
			if(!transitionOn){
				resumeSlideshow();
			}
		}else{//stop
			pauseSlideshow();
			slideshowOn = false;
		}
	}
	
	
	// ******************************** PUBLIC FUNCTIONS MUSIC **************** //
	
	this.toggleSlideshow = function(state) {
		if(!componentInited) return;
		if(state == undefined){
			toggleSlideshow();
		}else{
			toggleSlideshow2(state);
		}
	}
	
	this.toggleDirection = function(dir) {
		if(!componentInited) return;
		if(dir != 'forward' && dir != 'backward') return;
		slideshowDirection = dir;
	}
	
	this.setSlideshowDelay = function(num) {
		if(!componentInited) return;
		slideshowTimeout = num;
	}
	
	this.nextMedia = function() {
		if(!componentInited) return;
		if(transitionOn) return;
		nextMedia();
	}
	
	this.previousMedia = function() {
		if(!componentInited) return;
		if(transitionOn) return;
		previousMedia();	
	}
	
	this.openMedia = function(num) {
		if(!componentInited) return;
		if(transitionOn) return;
		if(num<0)num=0;
		else if(num>playlistLength-1)num=playlistLength-1;
		//if already on top (can only happen if requested slide is top slide in this case)
		if($(playlistArr[playlistLength-1]).attr('data-id') == num) return;
		goForward(num, false);
	}
	
	this.openOriginalMedia = function(num) {
		if(!componentInited) return;
		if(transitionOn) return;
		if(num<0)num=0;
		else if(num>playlistLength-1)num=playlistLength-1;
		//if already on top
		if($(playlistArr[playlistLength-1]).attr('data-orig-id') == num) return;
		//console.log($(playlistArr[playlistLength-1]).attr('data-orig-id'));
		goForward(num, true);
	}
	
	return this;
	
	
	
	}
	
})(jQuery);


