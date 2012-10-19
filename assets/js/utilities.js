var mooEdit = null;
var mooAdd = null;


var xml_special_to_escaped_one_map = {
  '&': '&amp;',
  '"': '&quot;',
  '\'': '&#039;',
  '<': '&lt;',
  '>': '&gt;'
};

var escaped_one_to_xml_special_map = {
  '&amp;': '&',
  '&quot;': '"',
  '&#039;': '\'',
  '&lt;': '<',
  '&gt;': '>'
};

function encodeXml(string) {
  return string.replace(/([\&"'<>])/g, function(str, item) {
    return xml_special_to_escaped_one_map[item];
  });
};

function decodeXml(string) {
  return string.replace(/(&quot;|&lt;|&gt;|&amp;|&#039;)/g,function(str, item) {
    return escaped_one_to_xml_special_map[item];
  });
}

function chgTab(tabID){
	$$('.toggletab').each(function(item){
	  item.addClass('invis');
	});
	$(tabID).removeClass('invis');
	//$(tabID).fade('in');
	
}

function chgVal(keyF, itemF){
	
	if($chk($$('.'+keyF))){
        
    	if(itemF == null){
    		itemF = '';
    	}
    	$$('.'+keyF).each(function(el){
	        tagName = el.get('tag').toLowerCase();
	        
	        switch (tagName){
	          case 'input':
	          	if(el.get('type').toLowerCase() == 'checkbox'){
		          	if (el.get('value') == itemF) {
	                    el.set('checked', 'checked');
	                    //console.log("got Option!!! --- "+item.innerHTML);
	                }
	                else {
	                    el.set('checked', '');
	                }
               	}else{
               		//alert('inputdd');
	            	el.set('value', itemF);
	            }
	            break;
	            
	          case 'textarea':
	            if(el.hasClass('wysiwyg')){
	              
	              if(mooEdit.mode == 'textarea'){
	                mooEdit.toggleView();
	              }
	              //console.log(itemF);
	              mooEdit.setContent(itemF);
	            }else{
	              el.set('html', itemF);
	            }
	            
	            break;
	            
	          case 'select':
	          	
	            var links = el.getElements('option');
	            if (links.length) {
	                links.each(function(item){
	                    if (item.get('value') == itemF) {
	                        item.set('selected', 'selected');
	                        //console.log("got Option!!! --- "+item.innerHTML);
	                        if(item.innerHTML == 'Keluar'){
	                          $('editkredit').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
                              $('editdebet').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
                              $('edittujuan').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
                              $('editkirim').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
	                        }
	                        else if(item.innerHTML == 'Masuk'){
	                           $('editdebet').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
                              $('edittujuan').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
                              $('editkredit').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
                              $('editkirim').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
	                        }
	                    }
	                    else {
	                        item.set('selected', '');
	                    }
	                });
	            }
	            break;
	          
	          case 'a':
	        	  el.set('href', itemF);
	              break;
	            
	          default:
	            el.set('html', itemF);
	        }
    	});//end $$('.'+keyF).each(function(el){
    	
      }//end if($chk($(keyF)))
}

function samePageEdit(){
	
	$$('.spe').each(function(item){
	  
	  var tmpJSON = new Hash(JSON.decode(item.get('rowdata')));
	  
	  item.addEvent('click', function(){
	    
	    tmpJSON.each(function(itemF , keyF){
	      
	      keyF = 'edit'+keyF;
	      
	      chgVal(keyF, itemF);
        
	    });
	    chgTab('tab-edit');
	    $$('#tab-edit .iserror').each(function(item){
	    	item.getElement('.errornote').destroy();
	    	item.removeClass('iserror');
	    });
	    
	  });
	  
	});
}

function emptyBtns(){
		
	$$('.emptiable').each(function(item){
		
		var emptyc = new Element('span', {
		    'class': 'emptybtn',
		    'html': 'x',
		    'styles': {
		        'visibility': 'hidden'
		    },
		    'events': {
		        'click': function(){
		    		item.set('value', '');
		        }
		    },
		    'tween': {'link' : 'cancel', duration: 500}
		}).injectAfter(item);;
		
		//var emptyc = emptybtn.clone()
		
		
		
		item.getParent('.emptywrap').addEvent('mouseenter', function(){
			//emptyc.fade('in');
			emptyc.tween('opacity',1);
		});
		item.getParent('.emptywrap').addEvent('mouseleave', function(){
			//emptyc.fade('hide');
			emptyc.tween('opacity',0);
		});
	});		
}

function init_numonly(){
	if($chk(document.getElement('.numonly'))){
	
		var numbers = [8, 9, 37, 39, 48,49,50,51,52,53,54,55,56,57,96,97,98,99,100,101,102,103,104,105];
		
		$$('.numonly').each(function(item){
			item.addEvent('keydown', function(key){
				for (i = 0; i < numbers.length; i++){
					if(numbers[i] == key.code){
						return true;
					}
				}
				
				return false;
			});
		});
	}
}

function paging_by_input(){
	if($chk($('frmpaging'))){
		var frmp = $('frmpaging');
		var inf = $('frmpaging').getElement('input');
		inf.addEvent('keydown', function(event){
			
		    if (event.key == 'enter') {
				event.preventDefault();
				frmp.set('action', frmp.get('action')+inf.value);
				frmp.fireEvent('submit');
			} //Executes if the user hits Ctr+S.
		});
	}
}

function popitup(url,name,height,width) {
	newwindow=window.open(url,name,'height='+height+',width='+width+',location=no');
	if (window.focus) {newwindow.focus()}
	return false;
}

window.addEvent("domready", function(){
	if($chk($$('.spe'))){
		samePageEdit();
	}
	
	if($chk($$('.wysiwyg'))){
	  //mooEdit = 'asdasd';
		$$('.wysiwyg').each(function(item){
		  
			if(item.get('id').substr(0,4) == 'edit'){
			  mooEdit = item.mooEditable({actions: "bold italic underline strikethrough | insertunorderedlist insertorderedlist indent outdent | undo redo | createlink unlink | toggleview"});
			  //alert(item.get('id').substr(0,4));
			}else{
			  mooAdd = item.mooEditable({actions: "bold italic underline strikethrough | insertunorderedlist insertorderedlist indent outdent | undo redo | createlink unlink | toggleview"});
			}
			//item.set('mooEditInst', item.mooEditable({actions: "bold italic underline strikethrough | insertunorderedlist insertorderedlist indent outdent | undo redo | createlink unlink | toggleview"}));
		});
	}
	
	if($chk($$('.emptiable'))){
		emptyBtns();
	}
	
	init_numonly();
	paging_by_input();
});