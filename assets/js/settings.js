(function () {
'use strict';
function qs(s){return document.querySelector(s);}
function qsa(s){return Array.prototype.slice.call(document.querySelectorAll(s));}

function syncPosition(){
    var custom=qs('#aioa-pos-type-custom'), fixed=qs('#aioa-pos-type-fix');
    var controls=qs('#aioa-custom-position-controls'), grid=qs('#aioa-fixed-position-container');
    var enabled=custom && custom.checked;
    qsa('input[name="widget_position"]').forEach(function(r){r.disabled=enabled;});
    if(controls) controls.classList.toggle('aioa-hidden',!enabled);
    if(grid) grid.classList.toggle('aioa-hidden',enabled);
    if(!enabled){
        var posX=qs('#aioa-pos-x'), posY=qs('#aioa-pos-y');
        if(posX) clearFieldError(posX,'aioa-pos-x-error');
        if(posY) clearFieldError(posY,'aioa-pos-y-error');
    }
}

function syncSize(){
    var custom=qs('#aioa-size-type-custom'), controls=qs('#aioa-custom-size-controls'), fixed=qs('#aioa-fixed-size-container');
    var enabled=custom && custom.checked;
    qsa('input[name="widget_icon_size"]').forEach(function(r){r.disabled=enabled;});
    if(controls) controls.classList.toggle('aioa-hidden',!enabled);
    if(fixed) fixed.classList.toggle('aioa-hidden',enabled);
    if(!enabled){
        var iconSize=qs('#aioa-icon-size-custom');
        if(iconSize) clearFieldError(iconSize,'aioa-icon-size-custom-error');
    }
}

function showFieldError(input,errorId,message){
    var err=document.getElementById(errorId);
    if(err){err.textContent=message;err.classList.remove('aioa-hidden');}
    var box=input.closest('.aioa-value-input')||input;
    box.classList.add('aioa-input-invalid');
}
function clearFieldError(input,errorId){
    var err=document.getElementById(errorId);
    if(err){err.textContent='';err.classList.add('aioa-hidden');}
    var box=input.closest('.aioa-value-input')||input;
    box.classList.remove('aioa-input-invalid');
}
function validateRange(input,errorId,min,max,label){
    var raw=input.value.trim();
    var num=Number(raw);
    if(raw===''||!/^-?\d+$/.test(raw)||isNaN(num)||num<min||num>max){
        showFieldError(input,errorId,'Please enter a valid '+label+' between '+min+' and '+max+'.');
        return false;
    }
    clearFieldError(input,errorId);
    return true;
}
function validateColor(input,errorId){
    var raw=input.value.trim();
    if(!/^#?[0-9A-Fa-f]{6}$/.test(raw)){
        showFieldError(input,errorId,'Please enter a valid hexadecimal color such as #420083.');
        return false;
    }
    clearFieldError(input,errorId);
    return true;
}
function validateForm(){
    var ok=true;
    var colorText=qs('#aioa-color-text');
    if(colorText && !validateColor(colorText,'aioa-color-text-error'))ok=false;

    var customPos=qs('#aioa-pos-type-custom');
    if(customPos && customPos.checked){
        var posX=qs('#aioa-pos-x'), posY=qs('#aioa-pos-y');
        if(posX && !validateRange(posX,'aioa-pos-x-error',0,250,'position value'))ok=false;
        if(posY && !validateRange(posY,'aioa-pos-y-error',0,250,'position value'))ok=false;
    }

    var customSize=qs('#aioa-size-type-custom');
    if(customSize && customSize.checked){
        var iconSize=qs('#aioa-icon-size-custom');
        if(iconSize && !validateRange(iconSize,'aioa-icon-size-custom-error',20,150,'icon size'))ok=false;
    }
    return ok;
}

// Wrap initialization in DOMContentLoaded to ensure elements exist
document.addEventListener('DOMContentLoaded', function() {
    qsa('input[name="is_widget_custom_position"]').forEach(function(r){r.addEventListener('change',syncPosition);});
    qsa('input[name="is_widget_custom_size"]').forEach(function(r){r.addEventListener('change',syncSize);});
    
    qsa('input[name="widget_position"]').forEach(function(r){r.addEventListener('change',function(){var f=qs('#aioa-pos-type-fix');if(f)f.checked=true;syncPosition();});});
    qsa('input[name="widget_icon_size"]').forEach(function(r){r.addEventListener('change',function(){var f=qs('#aioa-size-type-fix');if(f)f.checked=true;syncSize();});});
    
    qsa('input[name="widget_icon_type"]').forEach(function(r){r.addEventListener('change',function(){
        var selected=qs('input[name="widget_icon_type"]:checked'), base=qs('#aioa-fixed-size-container');
        if(!selected||!base)return;
        var iconBase=base.getAttribute('data-icon-base')||'';
        qsa('.aioa-icon-size-preview').forEach(function(img){
            img.src=iconBase+selected.value+'.svg';
        });
    });});
    
    var color=qs('#aioa-color'), colorText=qs('#aioa-color-text');
    if(color&&colorText){
     color.addEventListener('input',function(){colorText.value=color.value.toUpperCase();clearFieldError(colorText,'aioa-color-text-error');});
     colorText.addEventListener('input',function(){if(/^#[0-9a-fA-F]{6}$/.test(colorText.value))color.value=colorText.value;});
    }

    var posX=qs('#aioa-pos-x'), posY=qs('#aioa-pos-y'), iconSize=qs('#aioa-icon-size-custom');
    if(posX)posX.addEventListener('input',function(){var c=qs('#aioa-pos-type-custom');if(c&&c.checked)validateRange(posX,'aioa-pos-x-error',0,250,'position value');});
    if(posY)posY.addEventListener('input',function(){var c=qs('#aioa-pos-type-custom');if(c&&c.checked)validateRange(posY,'aioa-pos-y-error',0,250,'position value');});
    if(iconSize)iconSize.addEventListener('input',function(){var c=qs('#aioa-size-type-custom');if(c&&c.checked)validateRange(iconSize,'aioa-icon-size-custom-error',20,150,'icon size');});
    if(colorText)colorText.addEventListener('input',function(){validateColor(colorText,'aioa-color-text-error');});

    var form=qs('form');
    if(form){
        form.addEventListener('submit',function(e){
            if(!validateForm()){
                e.preventDefault();
                var firstInvalid=qs('.aioa-input-invalid input')||document.querySelector('.aioa-input-invalid');
                var target=form.querySelector('.aioa-input-invalid input, input.aioa-input-invalid');
                if(target)target.focus();
            }
        });
    }
    
    syncPosition();
    syncSize();
});
})();