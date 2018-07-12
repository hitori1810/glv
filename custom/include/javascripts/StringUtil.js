
String.prototype.isUnicode=function(){for(var i=0;i<this.length;i++){if(this.charCodeAt(i)>=192){return true;}}
return false;}
String.prototype.unUnicode=function(){var result=this.toLowerCase();result=result.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a");result=result.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e");result=result.replace(/ì|í|ị|ỉ|ĩ/g,"i");result=result.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o");result=result.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u");result=result.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y");result=result.replace(/đ/g,"d");return result;}
String.prototype.unUnicodeMatch=function(lookupString){var fullString=this.unUnicode();lookupString=lookupString.unUnicode();return fullString.indexOf(lookupString)>=0;}
String.prototype.format=function(params){params=typeof params==='object'?params:Array.prototype.slice.call(arguments,1);return this.replace(/\{\{|\}\}|\{(\w+)\}/g,function(m,n){if(m=="{{"){return"{";}
if(m=="}}"){return"}";}
return params[n];});};