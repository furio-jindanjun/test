var siteUrl = 'http://localhost/griya/';

function resetList(){
	//alert('ahoyyy');
	$('photoalbum').empty();
	$('emptytbl').removeClass('hidden');
	$('totitem').innerHTML = '0';	
}

function delItem(el){
	//if(e){e.preventDefault();}
	//alert('sew');
	elPar = el.getParent('div');
	
	var myRequest = new Request({
	    url: el.get('rel'),
	    async : true,
	    onRequest: function(result){
			el.mask();
	    },
	    onSuccess: function(result){
	    	elPar.set('tween',{onComplete:function(){
			elPar.destroy();
				if (!$chk($('photoalbum').getElement('.row'))) {
					$('emptytbl').removeClass('hidden');
				}
			}});
			
			elPar.tween('height',0);
			
			var tot = $('totitem').innerHTML.toInt();
			tot = tot - 1;
			$('totitem').innerHTML = tot;
	    },
	    onFailure: function(result){
	    	el.unmask();
			chgResult('error','failed to delete the photo, server is unreachable.');
	   }
	}).send();
	
}

function msupload(jsontxt){
	var rawdat = jsontxt;
    
	if(!$chk($(rawdat.src))){
  
      if (!$('emptytbl').hasClass('hidden')) {
        $('emptytbl').addClass('hidden');
      }
            
      var myTr = new Element('div', {
          'id': rawdat.filen2,
        'class': 'row',
        'style': 'opacity:0'
      });
      
           
      var myImg = new Element('img', {'src': rawdat.src});
      var beer = new Element('br');
      var viewImg = new Element('a', {
        'href': siteUrl + 'photos/'+ $('id_cust').value + '/' + rawdat.filen2 + '.jpg',
        'html': 'view',
        'target': '_BLANK'
        
      });
      var separator = new Element('span', {
        'html': ' | '
      });
      var delImg = new Element('a', {
      	'rel': siteUrl + 'customer/del_img/'+ $('id_cust').value + '/' + rawdat.filen2,
      	'href': '#',
      	'html': 'hapus',
      	events: {
	        click: function(event){
	        	event.preventDefault();
	            delItem(this);
	        },
	        mouseover: function(){
	            //alert('mouseovered');
	        }
	    }
      });
            
      myImg.inject(myTr);
      beer.inject(myTr);
      viewImg.inject(myTr);
      separator.inject(myTr);
      delImg.inject(myTr);
      myTr.inject($('photoalbum'));
      myTr.tween('opacity', [0, 1]);
      
      var tot = $('totitem').innerHTML.toInt();
      tot = tot + 1;
      $('totitem').innerHTML = tot;
      
    }
    
}


window.addEvent('domready', function() {

  var link = $('select-0');
  var linkIdle = link.get('html');
  
  function linkUpdate() {
    if (!swf.uploading) return;
    var size = Swiff.Uploader.formatUnit(swf.size, 'b');
    link.set('html', '<span class="small">' + swf.percentLoaded + '% of ' + size + '</span>');
  }

  // Uploader instance http://localhost/metsys/backend/uploader/image
  //../script.php
  var swf = new Swiff.Uploader({
    path: siteUrl + 'assets/js/fu/Swiff.Uploader.swf',
    url: siteUrl + 'uploader/image/'+$('id_cust').value,
    verbose: true,
    queued: false,
    multiple: false,
    target: link,
    instantStart: true,
    fileSizeMax: 2 * 1024 * 1024,
    onSelectSuccess: function(files) {
	  //console.log(siteUrl + 'backend/uploader/image/'+$('id_cust').value);
      if (Browser.Platform.linux) window.alert('Warning: Due to a misbehaviour of Adobe Flash Player on Linux,\nthe browser will probably freeze during the upload process.\nSince you are prepared now, the upload will start right away ...');
      chgResult('updated','<b>Starting Upload</b> Uploading <em>' + files[0].name + '</em> (' + Swiff.Uploader.formatUnit(files[0].size, 'b') + ')');
      this.setEnabled(false);
      link.set('class','activeload');
    },
    onSelectFail: function(files) {
      chgResult('error','<b><em>' + files[0].name + '</em> was not added!</b> Please select an image smaller than 2 Mb. (Error: #' + files[0].validationError + ')');
    },
    appendCookieData: true,
    onQueue: linkUpdate,
    onFileComplete: function(file) {
      
      link.set('class','');
      // We *don't* save the uploaded images, we only take the md5 value and create a monsterid ;)
      if (file.response.error) {
    	//console.log(JSON.decode(file.response));
        chgResult('error','<b>iFailed Upload</b> Uploading <em>' + this.fileList[0].name + '</em> failed, please try again. (Error: #' + this.fileList[0].response.code + ' ' + this.fileList[0].response.error + ')');
      } else {
        var retJSON = JSON.decode(file.response.text, true);
        
        if(retJSON.error){
          
          chgResult('error','<b>Failed Upload!</b> '+retJSON.error);
          
        }else{
        	
	        var md5 = retJSON.hash; // secure decode
	        if($chk($(retJSON.filen2))){
	        	
	        	$(retJSON.filen2).getElement('a').click();
	        	chgResult('success','<b><em>'+this.fileList[0].name+'</em> is successfully Replaced!</b>');
	        }else{
	        	
	        	chgResult('success','<b><em>'+this.fileList[0].name+'</em> is successfully Uploaded!</b>');
	        }
	        msupload(retJSON);
          
        }
        
      }
      
      file.remove();
      this.setEnabled(true);
    },
    onComplete: function() {
      link.set('html', linkIdle);
    }
  });

  // Button state
  link.addEvents({
    click: function() {
      return false;
    },
    mouseenter: function() {
      this.addClass('hover');
      swf.reposition();
    },
    mouseleave: function() {
      this.removeClass('hover');
      this.blur();
    },
    mousedown: function() {
      this.focus();
    }
  });

});