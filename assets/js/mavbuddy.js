function delItem(el){
	//alert('sew');
	elPar = el.getParent('div');
	
	elPar.set('tween',{onComplete:function(){
		elPar.destroy();
		if (!$chk($('emptytbl').getNext('.row'))) {
			$('emptytbl').removeClass('hidden');
		}
		count_kasir();
	}});
	
	elPar.tween('height',0);
	
}

var onHideSuggest = function(){
	
		for (var i = 0, l = arguments[0].length; i < l; i++){
	        $(arguments[0][i]).value = arguments[1][i];
	    }
		//$("crewsearch").value = "Search crew";
		//$("addnama").value = "";
		//$("addiduser").value = "";
		//console.log("hide-namakat--"+$("addnamacrew").value);
		//console.log("hide-crewsearch--"+$("crewsearch").value);
		
}

var onSelSuggest = function(){
	
	//console.log(arguments);
	//arguments[0] = JSON val
	//arguments[0] = val to check for existence
	//arguments[2] = array of input elements
	//arguments[3] = array of JSON key, has the same length as arguments[1]
	
	var rawdat = JSON.decode(eval('$("'+arguments[0]+'").value'));
		
	if(!$chk($(eval('rawdat.'+arguments[1])))){
		
		for (var i = 0, l = arguments[2].length; i < l; i++){
			var e = $(arguments[2][i]); 
			var e2 = eval('rawdat.'+arguments[3][i]);
			if(e.get('tag') == 'input'){
	        	e.value = e2;
	       }else{
	       		e.innerHTML = e2;
	       }
	    }
			
		
	}else{
		
		for (var i = 0, l = arguments[2].length; i < l; i++){
			var e = $(arguments[2][i]);
	        if(e.get('tag') == 'input'){
	        	e.value = '';
	       }else{
	       		e.innerHTML = '';
	       }
	    }	
	}
		
}

var onSuggest = function(){
	
	//console.log(arguments);
	//arguments[0] = JSON val
	//arguments[0] = val to check for existence
	//arguments[2] = array of input elements
	//arguments[3] = array of JSON key, has the same length as arguments[1]
	//console.log($(arguments[0]).retrieve("value"));
	var rawdat = JSON.decode($(arguments[0]).retrieve("value"));	
	
		
	for (var i = 0, l = arguments[1].length; i < l; i++){
		var e = $(arguments[1][i]); 
		var e2 = eval('rawdat.'+arguments[2][i]);
		if(e.get('tag') == 'input'){
        	e.value = e2;
       }else{
       		e.innerHTML = e2;
       }
    }
	
	//console.log("onselect->"+$(arguments[0]).retrieve("text"));	
}

var onselInventory = function(){
  
    var rawdat = JSON.decode($("items").value);
    
    console.log($("items").value);
      
    if(!$chk($(rawdat.idbarang))){
      
      if (!$('emptytbl').hasClass('hidden')) {
        $('emptytbl').addClass('hidden');
      }
      
      //alert($("from").value);
      //var rawdat = JSON.decode($("from").value);
      //alert(rawdat.id);
      
      var myTr = new Element('div', {
          'id': rawdat.idbarang,
        'class': 'row',
        'style': 'opacity:0'
      });
      
      qstr='';
      if(rawdat.namatipe.contains('ogisti')){
        qstr='<b>'+rawdat.quantity+'</b> in stock';
      }
      
      var myTd1 = new Element('div', {'style': 'width:80%','html': '<b>'+rawdat.namabarang+'<b><br/><b>'+rawdat.serialnum+'</b><br/>'+qstr});
      var myTd2 = new Element('div', {'style': 'width:20%'});
      var myIn1 = new Element('input', {'name': 'quantity[]', 'type': 'text', 'class': 'numonly arite', 'value': '1' });
      var myIn2 = new Element('input', {'name': 'idbarang[]', 'type': 'hidden', 'value': rawdat.idbarang});
      var myTd3 = new Element('h2', {'class': 'rowinfo', 'html': '<a title="Delete Item '+rawdat.namabarang+'" href="#" class="delbtn" onclick="delItem(this);">delete</a>'});
      
      qstr='';
      if(rawdat.namatipe.contains('ogisti')){
        
      }else{
        myIn1.set('disabled','true');
      }
      
      myIn1.inject(myTd2);
      myIn2.inject(myTd2);
      myTd1.inject(myTr);
      myTd2.inject(myTr);
      myTd3.inject(myTr);           
      
      myTr.inject($('scrollme'));
      myTr.tween('opacity', [0, 1]);
      
      var tot = $('totitem').innerHTML.toInt();
      tot = tot + 1;
      $('totitem').innerHTML = tot;
      
      if($chk($('crewtabtab'))){
        
        strr = 'CREW<br/>';
        for(x = 1;x <= tot;x++){
          strr += '<label class="fill"></label>';
        }
        
        $('crewtabtab').set('html', strr);
      }
      
    }
    
    $("items").value = '';
    init_numonly();
    
}

var onselItem = function(){
	  
    var rawdat = JSON.decode($("items").value);
    
    //console.log($("items").value);
    var price = 0;
    var eid = rawdat.ktipe+rawdat.id;
    if(rawdat.ktipe == 'obat'){
  	  price = rawdat.hargajual;
  	  eid = rawdat.ktipe+rawdat.lokasi+rawdat.id;
    }else{
  	  price = rawdat.harga;
    }
    
    var inval = true;
    if(rawdat.ktipe == 'obat' && rawdat.stok.toInt() <= 0){
    	inval = false;
    }
      
    if(!$chk($(eid)) && inval && price > 0){
      
      if (!$('emptytbl').hasClass('hidden')) {
        $('emptytbl').addClass('hidden');
      }
      
      //alert($("from").value);
      //var rawdat = JSON.decode($("from").value);
      //alert(rawdat.id);      
      
      var myTr = new Element('div', {
          'id': eid,
          'class': 'row rowitem',
          'style': 'opacity:0'
      });
           
      var myTd1 = new Element('div', {'class': 'kurut', 'style': 'width:5%','html': '<span class="rowhead"></span>'});
      var myTd2 = new Element('div', {'style': 'width:15%','html': '<div>'+rawdat.kode+'</div>'});
      var myTd3 = new Element('div', {'style': 'width:35%','html': '<div>'+rawdat.nama+'</div>'});
      if(rawdat.ktipe == 'obat'){
    	  myTd3 = new Element('div', {'style': 'width:35%','html': '<div>'+rawdat.nama+' ('+rawdat.lokasi+')'+'</div>'});
      }
      var myTd4 = new Element('div', {'class': 'kprice', 'style': 'width:15%','html': '<div>'+price+'</div>'});
      var myTd5 = new Element('div', {'class': 'kamountdiv', 'style': 'width:15%'});
      var myIn1 = new Element('input', {'name': 'jumlah[]', 'type': 'text', 'class': 'kamount numonly arite', 'value': '1', onchange : "count_kasir()" });
      if(rawdat.ktipe == 'tindakan'){
    	  myIn1 = new Element('div', { 'class': 'kamount arite', 'html': '1' });
    	  new Element('input', {'name': 'jumlah[]', 'type': 'hidden', 'class': 'kamount numonly arite', 'value': '1' }).inject(myIn1);
      }
      var myIn2 = new Element('input', {'name': 'tipeitem[]', 'type': 'hidden', 'class': 'ktipe', 'value': rawdat.ktipe });
      var myIn3 = new Element('input', {'name': 'maxval[]', 'type': 'hidden', 'class': 'kmaxval', 'value': '1' });
      if(rawdat.ktipe == 'obat'){
    	  myIn3 = new Element('input', {'name': 'maxval[]', 'type': 'hidden', 'class': 'kmaxval', 'value': rawdat.stok });
      }
      var myIn4 = new Element('input', {'name': 'lokasi[]', 'type': 'hidden', 'class': 'klokasi', 'value': 'xxx' });
      if(rawdat.ktipe == 'obat'){
    	  myIn4 = new Element('input', {'name': 'lokasi[]', 'type': 'hidden', 'class': 'klokasi', 'value': rawdat.lokasi });
      }
      var myIn5 = new Element('input', {'name': 'iditem[]', 'type': 'hidden', 'class': 'kid', 'value': rawdat.id });
      var myTd6 = new Element('div', {'class': 'ksub', 'style': 'width:15%','html': '<div>'+price+'</div>'});
      var myTd7 = new Element('a', {'class': 'delbtn', 'title' : "Delete Item "+rawdat.nama, 'href' : "#", onclick : "delItem(this)", 'html': 'X'});
      
            
      myIn1.inject(myTd5);
      myIn2.inject(myTd5);
      myIn3.inject(myTd5);
      myIn4.inject(myTd5);
      myIn5.inject(myTd5);
      myTd1.inject(myTr);
      myTd2.inject(myTr);
      myTd3.inject(myTr);
      myTd4.inject(myTr);
      myTd5.inject(myTr);
      myTd6.inject(myTr);
      myTd7.inject(myTr);
      
      myTr.inject($('scrollme'));
      myTr.tween('opacity', [0, 1]);
      
      count_kasir();
      
    }
    
    $("items").value = '';
    init_numonly();
    
}

function count_kasir(){
	var brgs = $('scrollme').getElements('.rowitem');
	var urut = 0;
	var total = 0;
	brgs.each(function(item){
		urut ++;
		kurut = item.getElement('.kurut');
		if(kurut.hasClass('nokurut')){
			urut = 0;
		}
		kurut.set('html', urut);
		
		if(item.getElement('.ktipe').get('value') == 'obat'){
			if(item.getElement('.kamount').get('value').toInt() == 0 || item.getElement('.kamount').get('value') == ""){
				item.getElement('.kamount').set('value', '1');
			}
			if(item.getElement('.kamount').get('value').toInt() > item.getElement('.kmaxval').get('value').toInt()){
				item.getElement('.kamount').set('value', item.getElement('.kmaxval').get('value').toInt());
			}
			sub = item.getElement('.kprice').getElement('div').get('html').replace('.','').replace('.','').replace('.','').toInt() * item.getElement('.kamount').get('value').toInt();
			item.getElement('.kamount').set('value', item.getElement('.kamount').get('value').toInt());
			
		}else{
			sub = item.getElement('.kprice').getElement('div').get('html').replace('.','').replace('.','').replace('.','').toInt() * item.getElement('.kamount').get('html').replace('.','').replace('.','').replace('.','').toInt();
		}
		kprc=item.getElement('.kprice').getElement('div').get('html').replace('.','').replace('.','').replace('.','');
		//alert(kprc);
		kprc=item.getElement('.kprice').getElement('div').get('html').replace('.','').replace('.','').replace('.','').toInt().format({ decimal: ",", group: ".", decimals: 0});
		//alert(kprc);
		item.getElement('.kprice').getElement('div').set('html', kprc);
		
		
		item.getElement('.ksub').getElement('div').set('html', sub.format({ decimal: ",", group: ".", decimals: 0}));
		total = total + sub;
		
	});
	
	if($("bkonsul").get('value') == ""){
		$("bkonsul").set('value', '0');
	}
	$("bkonsul").set('value', $("bkonsul").get('value').replace('.','').replace('.','').replace('.','').toInt());
	if($("badmin").get('value') == ""){
		$("badmin").set('value', '0');
	}
	$("badmin").set('value', $("badmin").get('value').replace('.','').replace('.','').replace('.','').toInt());
	total = total + $("bkonsul").get('value').toInt() + $("badmin").get('value').toInt();
	sisa = total;
	
	if($("bkonsul").get('disabled')){
		$("bkonsul").set('value', $("bkonsul").get('value').toInt().format({ decimal: ",", group: ".", decimals: 0}));
		$("badmin").set('value', $("badmin").get('value').toInt().format({ decimal: ",", group: ".", decimals: 0}));
	}else{
		
	}
	
	
	$('ktotal').set('html',total.format({ decimal: ",", group: ".", decimals: 0}));
	
	if($("ktunai").get('value') == ""){
		$("ktunai").set('value', '0');
	}
	$("ktunai").set('value', $("ktunai").get('value').toInt());
	if($("kedc").get('value') == ""){
		$("kedc").set('value', '0');
	}
	if($("metodeedc").get('value') != 'none'){
		edcval =  $("kedc").get('value').toInt();
		$("kedc").set('value', edcval);
		edcptax = edcval + (edcval * ($('tax-'+$("metodeedc").get('value')).get('value')/100));
		$("edcptax").set('html', edcptax.format({ decimal: ",", group: ".", decimals: 0}));
	}else{
		$("kedc").set('value',0);
		$("edcptax").set('html', '0');
	}
	
	totedc = 0;
	if($chk($('totaledc'))){
		totedc = $('totaledc').get('value').toInt();
	}
	sisa = total - $("ktunai").get('value').toInt() - $("kedc").get('value').toInt() - totedc;
	$('sisa').set('html',sisa.format({ decimal: ",", group: ".", decimals: 0}));
	
	if(total > 0 && sisa <= 0){
		$('lunas').set('html','LUNAS').tween('opacity',[0,1]);
		$('sisa').addClass('lunas');
	}else{
		$('lunas').set('html','').tween('opacity',[1,0]);
		$('sisa').removeClass('lunas');
	}
}


function stopEnter(formid){
	$(formid).addEvent('keydown', function(event){
		
	    if (event.key == 'enter') {
			event.preventDefault();
		} //Executes if the user hits Ctr+S.
	});
	
}

function is_metode(selectid){
 if(!$('metode').getSelected()[0].innerHTML.contains("Masuk")){
      $('kredit').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
      $('debet').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
      $('tujuan').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
      $('kirim').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
    }else{
      $('debet').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
      $('tujuan').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
      $('kredit').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
      $('kirim').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
    }
    
      
  $(selectid).addEvent('change', function(event){
    
      //alert($('src_namatipe').getSelected()[0].innerHTML);
      if(!$('metode').getSelected()[0].innerHTML.contains("Masuk")){
        $('kredit').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
        $('debet').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
        $('tujuan').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
        $('kirim').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
      }else{
        $('debet').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
        $('kirim').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
        $('tujuan').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
        $('kredit').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
      }
      
  });
  
  if(!$('editmetode').getSelected()[0].innerHTML.contains("Masuk")){
      $('editkredit').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
      $('editdebet').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
      $('edittujuan').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
      $('editkirim').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
    }else{
      $('editdebet').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
      $('edittujuan').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
      $('editkredit').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
      $('editkirim').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
    }
    
      
  $('editmetode').addEvent('change', function(event){
    
      //alert($('src_namatipe').getSelected()[0].innerHTML);
      if(!$('editmetode').getSelected()[0].innerHTML.contains("Masuk")){
        $('editkredit').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
        $('editdebet').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
        $('edittujuan').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
        $('editkirim').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
      }else{
        $('editdebet').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
        $('editkirim').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
        $('edittujuan').addClass("hidden").getParent().addClass("hidden").getPrevious().addClass("hidden").getParent().addClass("hidden");
        $('editkredit').removeClass("hidden").getParent().removeClass("hidden").getPrevious().removeClass("hidden").getParent().removeClass("hidden");
      }
      
  });
  
}

function detilKirim(selectid,boxid){
    $(selectid).addEvent('focus', function(){
      if (!$(boxid).hasClass('hide')) {
         $(boxid).addClass('hide');
         $(boxid).removeClass('show');
      }
      else{
          $(boxid).addClass('show');
          $(boxid).removeClass('hide');
      }
  });
  
      $(boxid).addEvent('mouseleave', function(){
            //console.log("notfocus->");
            $(boxid).addClass('hide');
            $(boxid).removeClass('show');
      });
      
      $("closepop").addEvent('focus', function(){
            //console.log("notfocus->");
            $(boxid).addClass('hide');
            $(boxid).removeClass('show');
      });
 
  
}

function chgBlur(el, defVal, arrEl, arrVal){
	var elem = $(el);
	elem.addEvent("blur", function(){
		//console.log("preblur->"+elem.retrieve("label"));
		if(elem.retrieve("label") !== elem.value || elem.value.trim() == ""){
			for (var i = 0, l = arrEl.length; i < l; i++){
				var e = $(arrEl[i]);
				var attrib = (e.match("input") ? 'value' : 'html'); 
				e.set(attrib, arrVal[i]);
		    }
			elem.store("label","");
			elem.value = defVal;
		}
		//console.log("postblur->"+elem.retrieve("label"));
	});
	
	elem.addEvent("focus", function(){
		//console.log("prefocus->"+elem.retrieve("label"));
		if(elem.value == defVal ){
			elem.value = "";
			elem.store("label","");
		}
		//console.log("postfocus->"+elem.retrieve("label"));
	});
}

