var headerFx;
var contentFx;
var	resFx;
var resFxStart = function(){
	
	
}

function prevResSlide(){
	//console.log($('prevresult').getPosition().y);
	var ps = $('prevresult');

	if (ps.getPosition().y > -46 && !ps.hasClass('closed')) {
		if (ps.hasClass('invi')) {
			contentSlide();
		}else {
			resFx.start(-46).chain(contentSlide);
		}
		
	}
	else if(ps.hasClass('closed')){
		contentSlide();
	}
	else{
		//alert($('prevresult').getPosition().y);
		psw = (ps.getSize().x)/2;
		ps.setStyle('margin-left', -psw);
		resFx.start(0);
	}
}

function prevResHide(){
	$("prevresult").addClass('closed');
	$("prevresult").tween("top","-46");
}

function contentSlide(){
	var cOp = $('contentframe').getStyle('opacity');
	
	//console.log(cOp);
	if(cOp == 0){
		contentFx.start(0,1).chain(
		    prevResSlide
		);
	}else{
		contentFx.start(1,0).chain(
		    headerSlide
		);
	}
}

function headerSlide(){	
	var hH = $('header').getSize().y;
	var hTop = -(hH);
	
	if($('header').getPosition().y < 0){
		headerFx.start(0).chain(
		    contentSlide
		);
	}else{
		if($chk($('submenu')))$('submenu').setStyle('display','none');
		headerFx.start(hTop).chain(
			function(){
			  toggleLoadIcon();
			  
				if(document.getElement('body').retrieve('goto') != null){
					document.location=document.getElement('body').retrieve('goto');
				}else{
				  var frmtmp = document.getElement('body').retrieve('form');
				  frmtmp.submit();
				}
			}
		    
		);
	}
	
	
}

function initNavAway(){
	$$('.aw').each(function(item){
		
		if(item.tagName.toLowerCase() == 'form'){
			
			item.addEvent('submit',function(e){
				if(e){e.preventDefault();}
				
				document.getElement('body').store('form', item);
				prevResSlide();
			});
		}
		if (item.tagName.toLowerCase() == 'a') {
			//console.log(item.get('html'));
			item.addEvent('click',function(e){
				
				e.preventDefault();
				document.getElement('body').store('goto', item.get('href'));
				prevResSlide();
			});
		}
		
	});
	
}

function toggleLoadIcon(){
  if($('wrap').hasClass('loadingbg')){
    $('wrap').removeClass('loadingbg');
  }else{
    $('wrap').addClass('loadingbg');
  }
}

function init_delbtns(){
    if ($chk($$('.btndel'))) {
        $$('.btndel').each(function(item){
            item.addEvent('click', function(event){
              event.preventDefault();
              $('btnokdel').set('href', item.get('href'));
              
              $('delq').set('html', item.get('title'));
              $('mask').setStyle('display', 'block');
              $('popup').setStyles({display: 'block', opacity: '0'});
              popFx.start(0,1);
              //$('popup').fade('in');
            });
            item.addEvent('mousedown', function(event){
              event.preventDefault();
              $('btnokdel').set('href', item.get('href'));
              
              $('delq').set('html', item.get('title'));
              $('mask').setStyle('display', 'block');
              $('popup').setStyles({display: 'block', opacity: '0'});
              popFx.start(0,1);
              //$('popup').fade('in');
            });
        });
        
    }
    $('btnokdel').addEvent('click', function(){
        $('mask').setStyle('display','none');
        popFx.start(1,0).chain(function(){
          $('popup').setStyle('display','none');
        });
    });
    $('btncancel').addEvent('click', function(){
        $('mask').setStyle('display','none');
        popFx.start(1,0).chain(function(){
          $('popup').setStyle('display','none');
        });
    });
}

function chgResult(msgClass, msg){
	var ps = $('prevresult');
  	resFx.start(-46).chain(function(){
    
    $('prevresult').set('class',msgClass);
    $('prevresult').set('html', msg);
    psw = (ps.getSize().x)/2;
	ps.setStyle('margin-left', -psw);
    resFx.start(0);
    
  });
}

function setError(el, errnote){
	
}

function ajaxReqs(){
	$$('.ajaxed').each(function(item){
		//alert('found one!');
		var btns = item.getElement('.submitbtn');
		var hideem = item.getElements('.ajaxhide');
		var rwraps = item.getElements('.errorwrap');
		
		//weird disable submit button fix
		btns.removeProperty('disabled');
		
		item.set('send', {
			url: item.get('action'),
			link: 'ignore',
			onRequest: function(result){
				btns.setProperty('disabled','disabled');
				btns.set('html','Menyimpan');
				hideem.each(function(el){
					//el.setStyle('visibility','hidden');
					el.fade('out');
				});
				
				rwraps.each(function(el){
					el.removeClass('iserror');
				});
				
		    },
			onSuccess: function(result){
				result = JSON.decode(result);
				
				if($chk(result.redirect)){
					document.location = result.redirect;
					return false;
				}
				if($chk(result.errors)){
					//console.log("errors "+result.errors);
					errs = new Hash(result.errors);
					errs.each(function(itemr, keyr){
						//console.log(itemr + ' --- key: ' +keyr);
						if(itemr != ''){
							errwrap = $('err-'+keyr);
							errwrap.addClass('iserror');
							errwrap.getElement('.errornote').set('html',itemr);
						}
					});
					
				}else{
					resetter = new Hash(result.posts);
					resetter.each(function(itemr, keyr){
						//alert(itemr + ' --- key: ' +keyr);
						chgVal(keyr, itemr);
					});
				}
				//console.log("the-rest ");
				btns.removeProperty('disabled');
				btns.set('html','Simpan');
				hideem.each(function(el){
					//el.setStyle('visibility','visible');
					el.fade('in');
				});
				chgResult(result.messageClass, result.message);		
		   },
		   onFailure: function(result){
		   		btns.removeProperty('disabled');
				btns.set('html','Simpan');
				hideem.each(function(el){
					//el.setStyle('visibility','visible');
					el.fade('in');
				});
				chgResult('error','server unreachable, please refresh this page');
		   }
		});

		
		item.addEvent('submit', function(event){
			
			event.preventDefault();
			item.send();
			
		});
	});
}

function scrollfix(){
	if($chk($('scrollme'))){
		if($('scrollme').getScrollSize().y > + ($('scrollme').getSize().y + 2)){
			document.getElement('.headertable tr').addClass('trhead');
		}
	}
}

function submenu(){
  if($chk(document.getElement('#mainmenu .submenu'))){
    var subm = new Element('div',{
      'id':'submenu',
      'morph': {duration: '2000', link:'cancel', transition: Fx.Transitions.Pow.easeOut}
    });
    subm.inject('header','top');
    
    $$('#mainmenu>li>a').each(function(item){
      item.addEvent('mouseover',function(){
        //subm.tween('opacity',[0,1]);
        //subm.tween('top',-80);
        //subm.morph({opacity: [0,1], top: 56});
      });
    });
    
    $('header').addEvent('mouseleave',function(){
      //subm.tween('opacity',[1,0]);
      //subm.tween('top',-100);
      subm.morph({opacity: [1,0], top: -10});
    });
    
    $$('#mainmenu .submenu').each(function(item){
      var ep = item.getPrevious('a');
      ep.addEvent('mouseover',function(){
        subm.setStyle('opacity',0);
        subm.empty();
        item.getChildren().each(function(l){
        	l.clone().cloneEvents(l).inject(subm);
        });
        
        //subm.set('html',item.get('html'));
        subm.position({
            relativeTo: ep,
            position: 'upperCenter',
            edge: 'center',
            offset: {x: 0, y:0}
        });
        //subm.tween('top',55);
        subm.morph({opacity:[0,1] , top: 35});
      });
      ep.addEvent('mouse',function(){
        //subm.tween('top',-35);
      });
    });
  }
}

window.addEvent("domready", function(){
  headerFx = new Fx.Tween('header', {property: 'top',duration: '1000',transition: Fx.Transitions.Pow.easeOut});
  contentFx = new Fx.Tween('contentframe', {property: 'opacity',duration: '700',transition: Fx.Transitions.Linear});
  resFx = new Fx.Tween('prevresult', {property: 'top',duration: '1200',transition: Fx.Transitions.Pow.easeOut, link: 'chain', start: resFxStart});
  popFx = new Fx.Tween('popup', {property: 'opacity',duration: '1300',transition: Fx.Transitions.Pow.easeOut});
  init_delbtns();
  if($chk($$('.ajaxed'))){
	ajaxReqs();
  }
  
  //scroll header fix
  scrollfix();
  
  if($chk($('filter'))){
  	new OverText($('filter'));
  }
});

window.addEvent("load", function(){
	$('contentframe').setStyle('opacity',0);
	initNavAway();
	toggleLoadIcon();
	headerSlide();
	submenu();
});