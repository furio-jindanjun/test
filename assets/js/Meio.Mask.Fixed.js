/*
---

description: A mask used for fixed values like date, time, phone, etc.

authors:
 - Fábio Miranda Costa

requires:
 - Meio.Mask

license: MIT-style license

provides: [Meio.Mask.Fixed]

...
*/

Meio.Mask.Fixed = new Class({

	Extends: Meio.Mask,

	options: {
		autoSetSize: false,
		placeholder: '_',
		removeIfInvalid: false, // removes the value onblur if the input is not valid
		removeInvalidTrailingChars: true
	},

    initialize: function(element, options){
		this.parent(element, options);
		this.maskArray = this.options.mask.split('');
		this.maskMold = this.element.get('value') || this.options.mask.replace(Meio.Mask.rulesRegex, this.options.placeholder);
		this.maskMoldArray = this.maskMold.split('');
		this.validIndexes = [];
		if (this.options.autoSetSize) this.setSize();
		this.maskArray.each(function(c, i){
		    if (!this.isFixedChar(c)) this.validIndexes.push(i);
		}, this);
		this.createUnmaskRegex();
	},
	
	focus: function(e, o){
		this.element.set('value', this.maskMoldArray.join(''));
		if (this.options.selectOnFocus && this.element.select) this.element.select();
		this.parent(e, o);
	},

	blur: function(e, o){
		this.parent(e, o);
		var elementValue = this.element.get('value');
		if (this.options.removeIfInvalid){
			if (elementValue.contains(this.options.placeholder)){
				this.maskMoldArray = this.maskMold.split('');
				this.element.set('value', '');
			}
			return true;
		} 
		if (this.options.removeInvalidTrailingChars) this.removeInvalidTrailingChars(elementValue);
		return true;
	},
    
	keypress: function(e, o){
		if (this.ignore) return true;
		e.preventDefault();

		var c = String.fromCharCode(e.code),
			maskArray = this.maskArray,
			start, i, returnFromTestEntry;

		if(!o.isSelection){
			// no text selected
			var finalRangePosition;
			if (o.isBksKey){
				do {
					start = this.validIndexes.indexOf(--o.range.start);
				} while (start === -1 && o.range.start >= 0);
				finalRangePosition = this.validIndexes[start] || 0;
			}
			else{
				do {
					start = this.validIndexes.indexOf(o.range.start++);
				} while (start === -1 && o.range.start < maskArray.length);
				finalRangePosition = this.validIndexes[start + 1];
			}
			
			i = this.validIndexes[start];
			if (!(returnFromTestEntry = this.testEvents(i, c, e.code, o.isRemoveKey))) return true;
			if ($type(returnFromTestEntry) === 'string') c = returnFromTestEntry;
			this.maskMoldArray[i] = (o.isRemoveKey) ? this.options.placeholder : c;
			
			var newCarretPosition = $pick(finalRangePosition, this.maskMoldArray.length);
			this.element.set('value', this.maskMoldArray.join(''))
				.setCaretPosition(newCarretPosition);
		
		} else {

			var rstart = o.range.start,
			    rend = o.range.end,
			    end;

			// text selected
			do {
				start = this.validIndexes.indexOf(o.range.start++);
			} while(start === -1 && o.range.start < maskArray.length);
			do {
				end = this.validIndexes.indexOf(o.range.end++);
			} while(end === -1 && o.range.end < maskArray.length);

            // if  you select a fixed char it will ignore your input
			if (!(end - start)) return true;
			
			// removes all the chars into the range
			for (i=rstart; i<rend; i++){
				this.maskMoldArray[i] = this.maskMold.charAt(i);
			}

			if (!o.isRemoveKey){
				i = this.validIndexes[start];
				if (!(returnFromTestEntry = this.testEvents(i, c, e.code, o.isRemoveKey))) return true;
				if ($type(returnFromTestEntry) === 'string') c = returnFromTestEntry;
				this.maskMoldArray[i] = c;
				start++;
			}
			
			this.element.set('value', this.maskMoldArray.join(''));
			this.element.setCaretPosition(this.validIndexes[start]);
		}
		return this.parent();
	},
    
	paste: function(e, o){
		var retApply = this.applyMask(this.element.get('value'), o.range.start);
		this.maskMoldArray = retApply.value;
		this.element.set('value', this.maskMoldArray.join(''))
			.setCaretPosition(retApply.rangeStart);
		return true;
	},

	mask: function(str){
		return this.applyMask(str).value.join('');
	},

	unmask: function(str){
		return this.unmaskRegex ? str.replace(this.unmaskRegex, '') : str;
	},

	createUnmaskRegex: function(){
		var fixedCharsArray = [].combine(this.options.mask.replace(Meio.Mask.rulesRegex, '').split(''));
		var chars = (fixedCharsArray.join('') + this.options.placeholder).escapeRegExp() ;
		this.unmaskRegex = chars ? new RegExp('[' + chars + ']', 'g') : null;
	},

	applyMask: function(elementValue, newRangeStart){
		var elementValueArray = elementValue.split(''),
			maskArray = this.maskArray,
			maskMold = this.maskMold,
			eli = 0,
			returnFromTestEntry;
			
		while (eli < maskMold.length){
			if (!elementValueArray[eli]){
				elementValueArray[eli] = maskMold[eli];
			} else if (Meio.Mask.rules[maskArray[eli]]){
				if (!(returnFromTestEntry = this.testEntry(eli, elementValueArray[eli]))){
					elementValueArray.splice(eli, 1);
					continue;
				} else {
				    if($type(returnFromTestEntry) === 'string') elementValueArray[eli] = returnFromTestEntry;
				}
				newStartRange = eli;
			} else if (maskArray[eli] != elementValueArray[eli]){
				elementValueArray.splice(eli, 0, maskMold[eli]);
			} else {
				elementValueArray[eli] = maskMold[eli];
			}
			eli++;
		}
		
		// makes sure the value is not bigger than the mask definition
		return {value: elementValueArray.slice(0, this.maskMold.length), rangeStart: newRangeStart + 1};
	},

    removeInvalidTrailingChars: function(elementValue){
		var truncateIndex = elementValue.length,
			placeholder = this.options.placeholder,
			i = elementValue.length - 1,
			cont;
		while (i >= 0){
			cont = false;
			while (this.isFixedChar(elementValue.charAt(i)) && elementValue.charAt(i) !== placeholder){
				if (i === 0) truncateIndex = 0;
				cont = true;
				i--;
			}
			while (elementValue.charAt(i) === placeholder){
				truncateIndex = i;
				cont = true;
				i--;
			}
			if (!cont) break;
		}
		this.element.set('value', elementValue.substring(0, truncateIndex));
    },
	
	testEntry: function(index, _char){
		var maskArray = this.maskArray,
			rule = Meio.Mask.rules[maskArray[index]],
			ret = (rule && rule.regex.test(_char));
		return (rule.check && ret) ? rule.check(this.element.get('value'), index, _char) : ret;
	},
	
	testEvents: function(index, _char, code, isRemoveKey){
		var maskArray = this.maskArray,
			rule = Meio.Mask.rules[maskArray[index]],
			returnFromTestEntry;
		if (!isRemoveKey){
			var args = [this.element, code, _char];
			if (!rule || !(returnFromTestEntry = this.testEntry(index, _char))){
				this.fireEvent('invalid', args);
				return false;
			}
			this.fireEvent('valid', args);
		}
		return returnFromTestEntry || true;
	},
	
	shouldFocusNext: function(){
		return this.unmask(this.element.get('value')).length >= this.validIndexes.length;
	}
});


Meio.Mask.createMasks('Fixed', {
	'Phone'		: {mask: '(99) 9999-9999'},
	'PhoneUs'	: {mask: '(999) 999-9999'},
	'Cpf'		: {mask: '999.999.999-99'},
	'Cnpj'		: {mask: '99.999.999/9999-99'},
	'Date'		: {mask: '3d/1m/9999'},
	'DateUs'	: {mask: '1m/3d/9999'},
	'Cep'		: {mask: '99999-999'},
	'Time'		: {mask: '2h:59'},
	'Cc'		: {mask: '9999 9999 9999 9999'}
});