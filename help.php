<?php

// License Information /////////////////////////////////////////////////////////////////////////////

/* 
 * PeerTracker - OpenSource BitTorrent Tracker
 * Revision - $Id: help.php 124 2009-10-28 19:54:09Z trigunflame $
 * Copyright (C) 2009 PeerTracker Team
 *
 * PeerTracker is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * PeerTracker is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with PeerTracker.  If not, see <http://www.gnu.org/licenses/>.
 */

// Note ////////////////////////////////////////////////////////////////////////////////////////////

// this 'help.php' script is by no means an especially efficient or clean script.
// for the time being, it gets the job done. it may be cleaned up at a later time.

// Enviroment Runtime //////////////////////////////////////////////////////////////////////////////

// error level
error_reporting(E_ERROR | E_PARSE);
//error_reporting(E_ALL & ~E_WARNING);
//error_reporting(E_ALL | E_STRICT | E_DEPRECATED);

// ignore disconnects
ignore_user_abort(true);

// Misc Functions //////////////////////////////////////////////////////////////////////////////////

// Check.png
function img0()
{
$img = '
iVBORw0KGgoAAAANSUhEUgAAABIAAAARCAYAAADQWvz5AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJ
bWFnZVJlYWR5ccllPAAAApVJREFUeNqclG1IE3Ecx39397+73W132267M2Fam3MqYTQfIhPFV/Ym
LBgVFJUIIQWWUIbQwwt7EYUw8IX0LnotFs1SiIogNFplojWKSg3Lh3TDqTe33e2uSUgmCtPvuz/f
Lx/+vwd+BGxHGED+8cIWp8/TrBEpVh5fHEbb4bh9Bc2uxuI7C7FFyK/Ye1SJJeP4ViEmhynPfXJ3
20I0ClhCA4QIYCRj3pZBexpL2zUOOApHwLAGgHl1KRL8HdgSyFGdc3hHteOInlSBpQ1gMjHw/V6o
bXl6+WPGIJInLWVNFf4UpIClGDDbOJjrn3o50fvDv+JnDPLWl13lXLyT1HHgOAb0eWVhqOP9xbSl
/gMRgHAaZzeDCEVCicOXe2F2aQYUFAedUSHoH7ghT8rDqxncWmArq+qoDe7rrBnZeSzvOrZ+ZRCG
SpvLO1RGoRhEgWA3w9Tzny/Gesc71+bwnXXuK5ib8WIC5co642yTzuXcT/+QWg3k13kaxP1SJa0j
sFstgEW06Jvbwaa0pfwHiozPjWi6CmE5DKMzY8DUWk7vavV0A4UZGRsjes+X3NRTKlhYDqw8D/23
Bq7J07HQ+vLRxKPR9oSoFiYr8RMUgYBIYGCpyTpkMrEBCdmjdBYlkSkMRMkKoe7Pz770fL27UR+J
9DRV+V00YLBQDr7Y4qUxCmggQfKITrtHKEIaDkK6pORMItJ19qFPkZXZjUF/pcU/yD0UR0q2Enu5
gaCAxWmgEQk8awSrmYfHl/tafg1O9W02WWLtQx5cfEIigsuuyD5gIGgwUgYQRQE+dYX6XvlfX0pH
9IxAK4oOzT8ldIzOrcqtMvMcxCeXww8aAz4lpoa3dXKKThW2Nryt/+Y+6GrIJP9HgAEAq+zTbnKP
p9sAAAAASUVORK5CYII=';

	header('Content-Type: image/png');
	echo base64_decode($img);
	exit;
}

// Delete.png
function img1()
{
$img = '
iVBORw0KGgoAAAANSUhEUgAAABIAAAARCAYAAADQWvz5AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJ
bWFnZVJlYWR5ccllPAAAAxVJREFUeNqElNtLG0EUxmdns7fEzUZTgxZrK4lrLGq0Dda7RCh97IPY
G0L/EquiINaXIvXB9qEPllQsqBTaai+opb6UQl+9IEXQVTS6zW2TuEm2Z2IjahUHhp2dmfOb73xz
dhE61m7Y7Y0MRQnogpbPcQVlolhz5uJdl+vBSmdnqsvtHoNX6jyIyDDWiTt3fi40NYVls7k2O09n
IQP19f7g1hauzs2tcjCMvBAITP4HYVnppc/3pUzTvFQsxt52OO79CAbnArq+SZN0XrS1fQgqCpVK
pVBC11G1KFbms2zpwt7eVBZiZVnbaGvr7HVNq41EIihtGMiKEN9kt3e8DwTG6NDBQcgSjxcRJQRi
EFgyeQT7tr8/SZSMtrTMVGhaXTgcRhRFIRpArMmERhRl6Fc4PHvkRbfb7W8vLHwYiscRSqcRAmAO
TaMpVfWXFBdfuRmLNWchGCACQAYUpf/N9nYXOmUq9USWX7c7HI/CiURmwTg4QBwAUwCPQzoUADAc
YuY4NJRI9I/v7nadMDvbiCdwta4qjKsiioL0nR0UDwRQkiiBdDF0M8OgwVisd2J/v/t4LH36Zogn
ecmkLEejlSk4nYZAE6RBuiAIaFDXe96qau/pOHx6wsrzktPpvEyxLMIYIxo6Bq9o6CYYu1i2+Kz6
OqFI4jjbc49npmxtrSkGvhAVGQABwZgY3cjzNXk5Oc75UGj6TJDE87ZhgLhWV+ui0SgykZRAgcDz
iPsHIXNpeDZbLB67xVL6NRicOgGycpw04vF8KllevhXRtENfIMB86Em3gvFvoiQNYAbWDFDYYrVW
2s1m+bOqEphBFwhC0bOKipnipSVvBJRkITkAearrvcTY75HIO5IOUWIQGPhHYD5JqrwkCOVzqvoR
SzTtyF1fLyd1giEFUmyZOtH1/glV7Tkq2I2Nx680zZ8LqTKwjyUHwr4qUfSyGIuZTaUcVzstSX/m
GMZYlCTjvs3Wd87HTw05nX69sdEwfD5jsaFhRWKYayd2lPJ87aTdvtkhin0X/I6oYVken/d6l60M
czU7+VeAAQD3hy6rS/0yjQAAAABJRU5ErkJggg==';

	header('Content-Type: image/png');
	echo base64_decode($img);
	exit;
}

// curvycorners.src.js
// Lots of JavaScript here
function js0()
{
	header('Content-Type: text/javascript');
?>
/*********************************************************
 *  CurvyCorners - Copyright (c) 2009 Cameron Cooke      *
 *                                                       *
 *  This library is free software; you can redistribute  *
 *  it and/or modify it under the terms of the GNU       *
 *  Lesser General Public License as published by the    *
 *  Free Software Foundation; either version 2.1 of the  *
 *  License, or (at your option) any later version.      *
 *********************************************************/
function browserdetect(){var a=navigator.userAgent.toLowerCase();this.isIE=a.indexOf("msie")>-1;if(this.isIE){this.ieVer=/msie\s(\d\.\d)/.exec(a)[1];this.quirksMode=!document.compatMode||document.compatMode.indexOf("BackCompat")>-1;this.get_style=function(d,g){if(!(g in d.currentStyle)){return""}var c=/^([\d.]+)(\w*)/.exec(d.currentStyle[g]);if(!c){return d.currentStyle[g]}if(c[1]==0){return"0"}if(c[2]&&c[2]!=="px"){var b=d.style.left;var f=d.runtimeStyle.left;d.runtimeStyle.left=d.currentStyle.left;d.style.left=c[1]+c[2];c[0]=d.style.pixelLeft;d.style.left=b;d.runtimeStyle.left=f}return c[0]}}else{this.ieVer=this.quirksMode=0;this.isMoz=a.indexOf("firefox")!==-1||("style" in document.childNodes[1]&&"MozBorderRadius" in document.childNodes[1].style);this.isSafari=a.indexOf("safari")!=-1;this.isOp="opera" in window;this.isWebKit=a.indexOf("webkit")!=-1;this.get_style=function(b,c){c=c.replace(/([a-z])([A-Z])/g,"$1-$2").toLowerCase();return document.defaultView.getComputedStyle(b,"").getPropertyValue(c)}}}var curvyBrowser=new browserdetect;if(curvyBrowser.isIE){try{document.execCommand("BackgroundImageCache",false,true)}catch(e){}}function curvyCnrSpec(a){this.selectorText=a;this.tlR=this.trR=this.blR=this.brR=0;this.tlu=this.tru=this.blu=this.bru="";this.antiAlias=true}curvyCnrSpec.prototype.setcorner=function(b,c,a,d){if(!b){this.tlR=this.trR=this.blR=this.brR=parseInt(a);this.tlu=this.tru=this.blu=this.bru=d}else{propname=b.charAt(0)+c.charAt(0);this[propname+"R"]=parseInt(a);this[propname+"u"]=d}};curvyCnrSpec.prototype.get=function(d){if(/^(t|b)(l|r)(R|u)$/.test(d)){return this[d]}if(/^(t|b)(l|r)Ru$/.test(d)){var c=d.charAt(0)+d.charAt(1);return this[c+"R"]+this[c+"u"]}if(/^(t|b)Ru?$/.test(d)){var b=d.charAt(0);b+=this[b+"lR"]>this[b+"rR"]?"l":"r";var a=this[b+"R"];if(d.length===3&&d.charAt(2)==="u"){a+=this[b="u"]}return a}throw new Error("Don't recognize property "+d)};curvyCnrSpec.prototype.radiusdiff=function(a){if(a!=="t"&&a!=="b"){throw new Error("Param must be 't' or 'b'")}return Math.abs(this[a+"lR"]-this[a+"rR"])};curvyCnrSpec.prototype.setfrom=function(a){this.tlu=this.tru=this.blu=this.bru="px";if("tl" in a){this.tlR=a.tl.radius}if("tr" in a){this.trR=a.tr.radius}if("bl" in a){this.blR=a.bl.radius}if("br" in a){this.brR=a.br.radius}if("antiAlias" in a){this.antiAlias=a.antiAlias}};curvyCnrSpec.prototype.cloneOn=function(h){var f=["tl","tr","bl","br"];var j=0;var c,a;for(c in f){if(!isNaN(c)){a=this[f[c]+"u"];if(a!==""&&a!=="px"){j=new curvyCnrSpec;break}}}if(!j){j=this}else{var b,d,g=curvyBrowser.get_style(h,"left");for(c in f){if(!isNaN(c)){b=f[c];a=this[b+"u"];d=this[b+"R"];if(a!=="px"){var g=h.style.left;h.style.left=d+a;d=h.style.pixelLeft;h.style.left=g}j[b+"R"]=d;j[b+"u"]="px"}}h.style.left=g}return j};curvyCnrSpec.prototype.radiusSum=function(a){if(a!=="t"&&a!=="b"){throw new Error("Param must be 't' or 'b'")}return this[a+"lR"]+this[a+"rR"]};curvyCnrSpec.prototype.radiusCount=function(a){var b=0;if(this[a+"lR"]){++b}if(this[a+"rR"]){++b}return b};curvyCnrSpec.prototype.cornerNames=function(){var a=[];if(this.tlR){a.push("tl")}if(this.trR){a.push("tr")}if(this.blR){a.push("bl")}if(this.brR){a.push("br")}return a};function operasheet(c){var a=document.styleSheets.item(c).ownerNode.text;a=a.replace(/\/\*(\n|\r|.)*?\*\//g,"");var d=new RegExp("^\\s*([\\w.#][-\\w.#, ]+)[\\n\\s]*\\{([^}]+border-((top|bottom)-(left|right)-)?radius[^}]*)\\}","mg");var h;this.rules=[];while((h=d.exec(a))!==null){var g=new RegExp("(..)border-((top|bottom)-(left|right)-)?radius:\\s*([\\d.]+)(in|em|px|ex|pt)","g");var f,b=new curvyCnrSpec(h[1]);while((f=g.exec(h[2]))!==null){if(f[1]!=="z-"){b.setcorner(f[3],f[4],f[5],f[6])}}this.rules.push(b)}}operasheet.contains_border_radius=function(a){return/border-((top|bottom)-(left|right)-)?radius/.test(document.styleSheets.item(a).ownerNode.text)};function curvyCorners(){var h,d,f,b,m;if(typeof arguments[0]!=="object"){throw curvyCorners.newError("First parameter of curvyCorners() must be an object.")}if(arguments[0] instanceof curvyCnrSpec){b=arguments[0];if(!b.selectorText&&typeof arguments[1]==="string"){b.selectorText=arguments[1]}}else{if(typeof arguments[1]!=="object"&&typeof arguments[1]!=="string"){throw curvyCorners.newError("Second parameter of curvyCorners() must be an object or a class name.")}d=arguments[1];if(typeof d!=="string"){d=""}if(d!==""&&d.charAt(0)!=="."&&"autoPad" in arguments[0]){d="."+d}b=new curvyCnrSpec(d);b.setfrom(arguments[0])}if(b.selectorText){m=0;var l=b.selectorText.replace(/\s+$/,"").split(/,\s*/);f=new Array;function a(j){var i=j.split("#");return(i.length===2?"#":"")+i.pop()}for(h=0;h<l.length;++h){var n=a(l[h]);var k=n.split(" ");switch(n.charAt(0)){case"#":d=k.length===1?n:k[0];d=document.getElementById(d.substr(1));if(d===null){curvyCorners.alert("No object with ID "+n+" exists yet.\nCall curvyCorners(settings, obj) when it is created.")}else{if(k.length===1){f.push(d)}else{f=f.concat(curvyCorners.getElementsByClass(k[1],d))}}break;default:if(k.length===1){f=f.concat(curvyCorners.getElementsByClass(n))}else{var c=curvyCorners.getElementsByClass(k[0]);for(d=0;d<c.length;++d){f=f.concat(curvyCorners.getElementsByClass(k[1],c[d]))}}}}}else{m=1;f=arguments}for(h=m,d=f.length;h<d;++h){if(f[h]&&(!("IEborderRadius" in f[h].style)||f[h].style.IEborderRadius!="set")){if(f[h].className&&f[h].className.indexOf("curvyRedraw")!==-1){if(typeof curvyCorners.redrawList==="undefined"){curvyCorners.redrawList=new Array}curvyCorners.redrawList.push({node:f[h],spec:b,copy:f[h].cloneNode(false)})}f[h].style.IEborderRadius="set";var g=new curvyObject(b,f[h]);g.applyCorners()}}}curvyCorners.prototype.applyCornersToAll=function(){throw curvyCorners.newError("This function is now redundant. Just call curvyCorners(). See documentation.")};curvyCorners.redraw=function(){if(!curvyBrowser.isOp&&!curvyBrowser.isIE){return}if(!curvyCorners.redrawList){throw curvyCorners.newError("curvyCorners.redraw() has nothing to redraw.")}var h=curvyCorners.block_redraw;curvyCorners.block_redraw=true;for(var c in curvyCorners.redrawList){if(isNaN(c)){continue}var g=curvyCorners.redrawList[c];if(!g.node.clientWidth){continue}var d=g.copy.cloneNode(false);for(var f=g.node.firstChild;f!=null;f=f.nextSibling){if(f.className==="autoPadDiv"){break}}if(!f){curvyCorners.alert("Couldn't find autoPad DIV");break}g.node.parentNode.replaceChild(d,g.node);var a=f.getElementsByTagName("script");for(var b=a.length-1;b>=0;--b){a[b].parentNode.removeChild(a[b])}while(f.firstChild){d.appendChild(f.removeChild(f.firstChild))}g=new curvyObject(g.spec,g.node=d);g.applyCorners()}curvyCorners.block_redraw=h};curvyCorners.adjust=function(obj,prop,newval){if(curvyBrowser.isOp||curvyBrowser.isIE){if(!curvyCorners.redrawList){throw curvyCorners.newError("curvyCorners.adjust() has nothing to adjust.")}var i,j=curvyCorners.redrawList.length;for(i=0;i<j;++i){if(curvyCorners.redrawList[i].node===obj){break}}if(i===j){throw curvyCorners.newError("Object not redrawable")}obj=curvyCorners.redrawList[i].copy}if(prop.indexOf(".")===-1){obj[prop]=newval}else{eval("obj."+prop+"='"+newval+"'")}};curvyCorners.handleWinResize=function(){if(!curvyCorners.block_redraw){curvyCorners.redraw()}};curvyCorners.setWinResize=function(a){curvyCorners.block_redraw=!a};curvyCorners.newError=function(a){return new Error("curvyCorners Error:\n"+a)};curvyCorners.alert=function(a){if(typeof curvyCornersVerbose==="undefined"||curvyCornersVerbose){alert(a)}};function curvyObject(){var y;this.box=arguments[1];this.settings=arguments[0];this.topContainer=this.bottomContainer=this.shell=y=null;var l=this.box.clientWidth;if(("canHaveChildren" in this.box&&!this.box.canHaveChildren)||this.box.tagName==="TABLE"){throw new Error(this.errmsg("You cannot apply corners to "+this.box.tagName+" elements.","Error"))}if(!l&&curvyBrowser.isIE){this.box.style.zoom=1;l=this.box.clientWidth}if(!l&&curvyBrowser.get_style(this.box,"display")==="inline"){this.box.style.display="inline-block";curvyCorners.alert(this.errmsg("Converting inline element to inline-block","warning"));l=this.box.clientWidth}if(!l){if(!this.box.parentNode){throw this.newError("box has no parent!")}for(y=this.box;;y=y.parentNode){if(!y||y.tagName==="BODY"){this.applyCorners=function(){};curvyCorners.alert(this.errmsg("zero-width box with no accountable parent","warning"));return}if(curvyBrowser.get_style(y,"display")==="none"){break}}var p=y.style.display;y.style.display="block";l=this.box.clientWidth}if(!l){curvyCorners.alert(this.errmsg("zero-width box, cannot display","error"));this.applyCorners=function(){};return}if(arguments[0] instanceof curvyCnrSpec){this.spec=arguments[0].cloneOn(this.box)}else{this.spec=new curvyCnrSpec("");this.spec.setfrom(this.settings)}var G=curvyBrowser.get_style(this.box,"borderTopWidth");var k=curvyBrowser.get_style(this.box,"borderBottomWidth");var d=curvyBrowser.get_style(this.box,"borderLeftWidth");var b=curvyBrowser.get_style(this.box,"borderRightWidth");var j=curvyBrowser.get_style(this.box,"borderTopColor");var h=curvyBrowser.get_style(this.box,"borderBottomColor");var a=curvyBrowser.get_style(this.box,"borderLeftColor");var f=curvyBrowser.get_style(this.box,"backgroundColor");var c=curvyBrowser.get_style(this.box,"backgroundImage");var D=curvyBrowser.get_style(this.box,"backgroundRepeat");if(this.box.currentStyle&&this.box.currentStyle.backgroundPositionX){var v=curvyBrowser.get_style(this.box,"backgroundPositionX");var s=curvyBrowser.get_style(this.box,"backgroundPositionY")}else{var v=curvyBrowser.get_style(this.box,"backgroundPosition");v=v.split(" ");var s=v[1];v=v[0]}var r=curvyBrowser.get_style(this.box,"position");var E=curvyBrowser.get_style(this.box,"paddingTop");var H=curvyBrowser.get_style(this.box,"paddingBottom");var u=curvyBrowser.get_style(this.box,"paddingLeft");var F=curvyBrowser.get_style(this.box,"paddingRight");var w=curvyBrowser.get_style(this.box,"border");var o=curvyBrowser.ieVer>7?curvyBrowser.get_style(this.box,"filter"):null;var i=this.spec.get("tR");var n=this.spec.get("bR");var B=function(I){if(typeof I==="number"){return I}if(typeof I!=="string"){throw new Error("unexpected styleToNPx type "+typeof I)}var t=/^[-\d.]([a-z]+)$/.exec(I);if(t&&t[1]!="px"){throw new Error("Unexpected unit "+t[1])}if(isNaN(I=parseInt(I))){I=0}return I};var x=function(t){return t<=0?"0":t+"px"};try{this.borderWidth=B(G);this.borderWidthB=B(k);this.borderWidthL=B(d);this.borderWidthR=B(b);this.boxColour=curvyObject.format_colour(f);this.topPadding=B(E);this.bottomPadding=B(H);this.leftPadding=B(u);this.rightPadding=B(F);this.boxWidth=l;this.boxHeight=this.box.clientHeight;this.borderColour=curvyObject.format_colour(j);this.borderColourB=curvyObject.format_colour(h);this.borderColourL=curvyObject.format_colour(a);this.borderString=this.borderWidth+"px solid "+this.borderColour;this.borderStringB=this.borderWidthB+"px solid "+this.borderColourB;this.backgroundImage=((c!="none")?c:"");this.backgroundRepeat=D}catch(C){throw this.newError(C.message)}var g=this.boxHeight;var A=l;if(curvyBrowser.isOp){v=B(v);s=B(s);if(v){var q=A+this.borderWidthL+this.borderWidthR;if(v>q){v=q}v=(q/v*100)+"%"}if(s){var q=g+this.borderWidth+this.borderWidthB;if(s>q){s=q}s=(q/s*100)+"%"}}if(curvyBrowser.quirksMode){}else{this.boxWidth-=this.leftPadding+this.rightPadding;this.boxHeight-=this.topPadding+this.bottomPadding}this.contentContainer=document.createElement("div");if(o){this.contentContainer.style.filter=o}while(this.box.firstChild){this.contentContainer.appendChild(this.box.removeChild(this.box.firstChild))}if(r!="absolute"){this.box.style.position="relative"}this.box.style.padding="0";this.box.style.border=this.box.style.backgroundImage="none";this.box.style.backgroundColor="transparent";this.box.style.width=(A+this.borderWidthL+this.borderWidthR)+"px";this.box.style.height=(g+this.borderWidth+this.borderWidthB)+"px";var m=document.createElement("div");m.style.position="absolute";if(o){m.style.filter=o}if(curvyBrowser.quirksMode){m.style.width=(A+this.borderWidthL+this.borderWidthR)+"px"}else{m.style.width=A+"px"}m.style.height=x(g+this.borderWidth+this.borderWidthB-i-n);m.style.padding="0";m.style.top=i+"px";m.style.left="0";if(this.borderWidthL){m.style.borderLeft=this.borderWidthL+"px solid "+this.borderColourL}if(this.borderWidth&&!i){m.style.borderTop=this.borderWidth+"px solid "+this.borderColour}if(this.borderWidthR){m.style.borderRight=this.borderWidthR+"px solid "+this.borderColourL}if(this.borderWidthB&&!n){m.style.borderBottom=this.borderWidthB+"px solid "+this.borderColourB}m.style.backgroundColor=f;m.style.backgroundImage=this.backgroundImage;m.style.backgroundRepeat=this.backgroundRepeat;m.style.direction="ltr";this.shell=this.box.appendChild(m);l=curvyBrowser.get_style(this.shell,"width");if(l===""||l==="auto"||l.indexOf("%")!==-1){throw this.newError("Shell width is "+l)}this.boxWidth=(l!=""&&l!="auto"&&l.indexOf("%")==-1)?parseInt(l):this.shell.clientWidth;this.applyCorners=function(){if(this.backgroundObject){var X=function(aq,t,ap){if(aq===0){return 0}var ao;if(aq==="right"||aq==="bottom"){return ap-t}if(aq==="center"){return(ap-t)/2}if(aq.indexOf("%")>0){return(ap-t)*100/parseInt(aq)}return B(aq)};this.backgroundPosX=X(v,this.backgroundObject.width,A);this.backgroundPosY=X(s,this.backgroundObject.height,g)}else{if(this.backgroundImage){this.backgroundPosX=B(v);this.backgroundPosY=B(s)}}if(i){W=document.createElement("div");W.style.width=this.boxWidth+"px";W.style.fontSize="1px";W.style.overflow="hidden";W.style.position="absolute";W.style.paddingLeft=this.borderWidth+"px";W.style.paddingRight=this.borderWidth+"px";W.style.height=i+"px";W.style.top=-i+"px";W.style.left=-this.borderWidthL+"px";this.topContainer=this.shell.appendChild(W)}if(n){var W=document.createElement("div");W.style.width=this.boxWidth+"px";W.style.fontSize="1px";W.style.overflow="hidden";W.style.position="absolute";W.style.paddingLeft=this.borderWidthB+"px";W.style.paddingRight=this.borderWidthB+"px";W.style.height=n+"px";W.style.bottom=-n+"px";W.style.left=-this.borderWidthL+"px";this.bottomContainer=this.shell.appendChild(W)}var ag=this.spec.cornerNames();for(var ak in ag){if(!isNaN(ak)){var ac=ag[ak];var ad=this.spec[ac+"R"];var ae,ah,M,af;if(ac=="tr"||ac=="tl"){ae=this.borderWidth;ah=this.borderColour;af=this.borderWidth}else{ae=this.borderWidthB;ah=this.borderColourB;af=this.borderWidthB}M=ad-af;var V=document.createElement("div");V.style.height=this.spec.get(ac+"Ru");V.style.width=this.spec.get(ac+"Ru");V.style.position="absolute";V.style.fontSize="1px";V.style.overflow="hidden";var T,S,R;var P=o?parseInt(/alpha\(opacity.(\d+)\)/.exec(o)[1]):100;for(T=0;T<ad;++T){var O=(T+1>=M)?-1:Math.floor(Math.sqrt(Math.pow(M,2)-Math.pow(T+1,2)))-1;if(M!=ad){var L=(T>=M)?-1:Math.ceil(Math.sqrt(Math.pow(M,2)-Math.pow(T,2)));var J=(T+1>=ad)?-1:Math.floor(Math.sqrt(Math.pow(ad,2)-Math.pow((T+1),2)))-1}var I=(T>=ad)?-1:Math.ceil(Math.sqrt(Math.pow(ad,2)-Math.pow(T,2)));if(O>-1){this.drawPixel(T,0,this.boxColour,P,(O+1),V,true,ad)}if(M!=ad){if(this.spec.antiAlias){for(S=O+1;S<L;++S){if(this.backgroundImage!=""){var K=curvyObject.pixelFraction(T,S,M)*100;this.drawPixel(T,S,ah,P,1,V,K>=30,ad)}else{if(this.boxColour!=="transparent"){var ab=curvyObject.BlendColour(this.boxColour,ah,curvyObject.pixelFraction(T,S,M));this.drawPixel(T,S,ab,P,1,V,false,ad)}else{this.drawPixel(T,S,ah,P>>1,1,V,false,ad)}}}if(J>=L){if(L==-1){L=0}this.drawPixel(T,L,ah,P,(J-L+1),V,false,0)}R=ah;S=J}else{if(J>O){this.drawPixel(T,(O+1),ah,P,(J-O),V,false,0)}}}else{R=this.boxColour;S=O}if(this.spec.antiAlias){while(++S<I){this.drawPixel(T,S,R,(curvyObject.pixelFraction(T,S,ad)*P),1,V,af<=0,ad)}}}for(var Z=0,aj=V.childNodes.length;Z<aj;++Z){var U=V.childNodes[Z];var ai=parseInt(U.style.top);var am=parseInt(U.style.left);var an=parseInt(U.style.height);if(ac=="tl"||ac=="bl"){U.style.left=(ad-am-1)+"px"}if(ac=="tr"||ac=="tl"){U.style.top=(ad-an-ai)+"px"}U.style.backgroundRepeat=this.backgroundRepeat;if(this.backgroundImage){switch(ac){case"tr":U.style.backgroundPosition=(this.backgroundPosX-this.borderWidthL+ad-A-am)+"px "+(this.backgroundPosY+an+ai+this.borderWidth-ad)+"px";break;case"tl":U.style.backgroundPosition=(this.backgroundPosX-ad+am+this.borderWidthL)+"px "+(this.backgroundPosY-ad+an+ai+this.borderWidth)+"px";break;case"bl":U.style.backgroundPosition=(this.backgroundPosX-ad+am+1+this.borderWidthL)+"px "+(this.backgroundPosY-g-this.borderWidth+(curvyBrowser.quirksMode?ai:-ai)+ad)+"px";break;case"br":if(curvyBrowser.quirksMode){U.style.backgroundPosition=(this.backgroundPosX+this.borderWidthL-A+ad-am)+"px "+(this.backgroundPosY-g-this.borderWidth+ai+ad)+"px"}else{U.style.backgroundPosition=(this.backgroundPosX-this.borderWidthL-A+ad-am)+"px "+(this.backgroundPosY-g-this.borderWidth+ad-ai)+"px"}}}}switch(ac){case"tl":V.style.top=V.style.left="0";this.topContainer.appendChild(V);break;case"tr":V.style.top=V.style.right="0";this.topContainer.appendChild(V);break;case"bl":V.style.bottom=V.style.left="0";this.bottomContainer.appendChild(V);break;case"br":V.style.bottom=V.style.right="0";this.bottomContainer.appendChild(V)}}}var Y={t:this.spec.radiusdiff("t"),b:this.spec.radiusdiff("b")};for(z in Y){if(typeof z==="function"){continue}if(!this.spec.get(z+"R")){continue}if(Y[z]){if(this.backgroundImage&&this.spec.radiusSum(z)!==Y[z]){curvyCorners.alert(this.errmsg("Not supported: unequal non-zero top/bottom radii with background image"))}var al=(this.spec[z+"lR"]<this.spec[z+"rR"])?z+"l":z+"r";var N=document.createElement("div");N.style.height=Y[z]+"px";N.style.width=this.spec.get(al+"Ru");N.style.position="absolute";N.style.fontSize="1px";N.style.overflow="hidden";N.style.backgroundColor=this.boxColour;if(o){N.style.filter=o}switch(al){case"tl":N.style.bottom=N.style.left="0";N.style.borderLeft=this.borderString;this.topContainer.appendChild(N);break;case"tr":N.style.bottom=N.style.right="0";N.style.borderRight=this.borderString;this.topContainer.appendChild(N);break;case"bl":N.style.top=N.style.left="0";N.style.borderLeft=this.borderStringB;this.bottomContainer.appendChild(N);break;case"br":N.style.top=N.style.right="0";N.style.borderRight=this.borderStringB;this.bottomContainer.appendChild(N)}}var Q=document.createElement("div");if(o){Q.style.filter=o}Q.style.position="relative";Q.style.fontSize="1px";Q.style.overflow="hidden";Q.style.width=this.fillerWidth(z);Q.style.backgroundColor=this.boxColour;Q.style.backgroundImage=this.backgroundImage;Q.style.backgroundRepeat=this.backgroundRepeat;switch(z){case"t":if(this.topContainer){if(curvyBrowser.quirksMode){Q.style.height=100+i+"px"}else{Q.style.height=100+i-this.borderWidth+"px"}Q.style.marginLeft=this.spec.tlR?(this.spec.tlR-this.borderWidthL)+"px":"0";Q.style.borderTop=this.borderString;if(this.backgroundImage){var aa=this.spec.tlR?(this.backgroundPosX-(i-this.borderWidthL))+"px ":"0 ";Q.style.backgroundPosition=aa+this.backgroundPosY+"px";this.shell.style.backgroundPosition=this.backgroundPosX+"px "+(this.backgroundPosY-i+this.borderWidthL)+"px"}this.topContainer.appendChild(Q)}break;case"b":if(this.bottomContainer){if(curvyBrowser.quirksMode){Q.style.height=n+"px"}else{Q.style.height=n-this.borderWidthB+"px"}Q.style.marginLeft=this.spec.blR?(this.spec.blR-this.borderWidthL)+"px":"0";Q.style.borderBottom=this.borderStringB;if(this.backgroundImage){var aa=this.spec.blR?(this.backgroundPosX+this.borderWidthL-n)+"px ":this.backgroundPosX+"px ";Q.style.backgroundPosition=aa+(this.backgroundPosY-g-this.borderWidth+n)+"px"}this.bottomContainer.appendChild(Q)}}}this.contentContainer.style.position="absolute";this.contentContainer.className="autoPadDiv";this.contentContainer.style.left=this.borderWidthL+"px";this.contentContainer.style.paddingTop=this.topPadding+"px";this.contentContainer.style.top=this.borderWidth+"px";this.contentContainer.style.paddingLeft=this.leftPadding+"px";this.contentContainer.style.paddingRight=this.rightPadding+"px";z=A;if(!curvyBrowser.quirksMode){z-=this.leftPadding+this.rightPadding}this.contentContainer.style.width=z+"px";this.contentContainer.style.textAlign=curvyBrowser.get_style(this.box,"textAlign");this.box.style.textAlign="left";this.box.appendChild(this.contentContainer);if(y){y.style.display=p}};if(this.backgroundImage){v=this.backgroundCheck(v);s=this.backgroundCheck(s);if(this.backgroundObject){this.backgroundObject.holdingElement=this;this.dispatch=this.applyCorners;this.applyCorners=function(){if(this.backgroundObject.complete){this.dispatch()}else{this.backgroundObject.onload=new Function("curvyObject.dispatch(this.holdingElement);")}}}}}curvyObject.prototype.backgroundCheck=function(b){if(b==="top"||b==="left"||parseInt(b)===0){return 0}if(!(/^[-\d.]+px$/.test(b))&&!this.backgroundObject){this.backgroundObject=new Image;var a=function(d){var c=/url\("?([^'"]+)"?\)/.exec(d);return(c?c[1]:d)};this.backgroundObject.src=a(this.backgroundImage)}return b};curvyObject.dispatch=function(a){if("dispatch" in a){a.dispatch()}else{throw a.newError("No dispatch function")}};curvyObject.prototype.drawPixel=function(k,h,a,g,i,j,c,f){var b=document.createElement("div");b.style.height=i+"px";b.style.width="1px";b.style.position="absolute";b.style.fontSize="1px";b.style.overflow="hidden";var d=this.spec.get("tR");b.style.backgroundColor=a;if(c&&this.backgroundImage!=""){b.style.backgroundImage=this.backgroundImage;b.style.backgroundPosition="-"+(this.boxWidth-(f-k)+this.borderWidth)+"px -"+((this.boxHeight+d+h)-this.borderWidth)+"px"}if(g!=100){curvyObject.setOpacity(b,g)}b.style.top=h+"px";b.style.left=k+"px";j.appendChild(b)};curvyObject.prototype.fillerWidth=function(a){var b=curvyBrowser.quirksMode?0:this.spec.radiusCount(a)*this.borderWidthL;return(this.boxWidth-this.spec.radiusSum(a)+b)+"px"};curvyObject.prototype.errmsg=function(c,d){var b="\ntag: "+this.box.tagName;if(this.box.id){b+="\nid: "+this.box.id}if(this.box.className){b+="\nclass: "+this.box.className}var a;if((a=this.box.parentNode)===null){b+="\n(box has no parent)"}else{b+="\nParent tag: "+a.tagName;if(a.id){b+="\nParent ID: "+a.id}if(a.className){b+="\nParent class: "+a.className}}if(d===undefined){d="warning"}return"curvyObject "+d+":\n"+c+b};curvyObject.prototype.newError=function(a){return new Error(this.errmsg(a,"exception"))};curvyObject.IntToHex=function(b){var a=["0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F"];return a[b>>>4]+""+a[b&15]};curvyObject.BlendColour=function(m,k,h){if(m==="transparent"||k==="transparent"){throw this.newError("Cannot blend with transparent")}if(m.charAt(0)!=="#"){m=curvyObject.format_colour(m)}if(k.charAt(0)!=="#"){k=curvyObject.format_colour(k)}var d=parseInt(m.substr(1,2),16);var l=parseInt(m.substr(3,2),16);var g=parseInt(m.substr(5,2),16);var c=parseInt(k.substr(1,2),16);var j=parseInt(k.substr(3,2),16);var f=parseInt(k.substr(5,2),16);if(h>1||h<0){h=1}var i=Math.round((d*h)+(c*(1-h)));if(i>255){i=255}if(i<0){i=0}var b=Math.round((l*h)+(j*(1-h)));if(b>255){b=255}if(b<0){b=0}var a=Math.round((g*h)+(f*(1-h)));if(a>255){a=255}if(a<0){a=0}return"#"+curvyObject.IntToHex(i)+curvyObject.IntToHex(b)+curvyObject.IntToHex(a)};curvyObject.pixelFraction=function(i,h,a){var k;var f=a*a;var b=new Array(2);var g=new Array(2);var j=0;var c="";var d=Math.sqrt(f-Math.pow(i,2));if(d>=h&&d<(h+1)){c="Left";b[j]=0;g[j]=d-h;++j}d=Math.sqrt(f-Math.pow(h+1,2));if(d>=i&&d<(i+1)){c+="Top";b[j]=d-i;g[j]=1;++j}d=Math.sqrt(f-Math.pow(i+1,2));if(d>=h&&d<(h+1)){c+="Right";b[j]=1;g[j]=d-h;++j}d=Math.sqrt(f-Math.pow(h,2));if(d>=i&&d<(i+1)){c+="Bottom";b[j]=d-i;g[j]=0}switch(c){case"LeftRight":k=Math.min(g[0],g[1])+((Math.max(g[0],g[1])-Math.min(g[0],g[1]))/2);break;case"TopRight":k=1-(((1-b[0])*(1-g[1]))/2);break;case"TopBottom":k=Math.min(b[0],b[1])+((Math.max(b[0],b[1])-Math.min(b[0],b[1]))/2);break;case"LeftBottom":k=g[0]*b[1]/2;break;default:k=1}return k};curvyObject.rgb2Array=function(a){var b=a.substring(4,a.indexOf(")"));return b.split(", ")};curvyObject.rgb2Hex=function(b){try{var c=curvyObject.rgb2Array(b);var h=parseInt(c[0]);var f=parseInt(c[1]);var a=parseInt(c[2]);var d="#"+curvyObject.IntToHex(h)+curvyObject.IntToHex(f)+curvyObject.IntToHex(a)}catch(g){var i="getMessage" in g?g.getMessage():g.message;throw new Error("Error ("+i+") converting RGB value to Hex in rgb2Hex")}return d};curvyObject.setOpacity=function(g,c){c=(c==100)?99.999:c;if(curvyBrowser.isSafari&&g.tagName!="IFRAME"){var b=curvyObject.rgb2Array(g.style.backgroundColor);var f=parseInt(b[0]);var d=parseInt(b[1]);var a=parseInt(b[2]);g.style.backgroundColor="rgba("+f+", "+d+", "+a+", "+c/100+")"}else{if(typeof g.style.opacity!=="undefined"){g.style.opacity=c/100}else{if(typeof g.style.MozOpacity!=="undefined"){g.style.MozOpacity=c/100}else{if(typeof g.style.filter!=="undefined"){g.style.filter="alpha(opacity="+c+")"}else{if(typeof g.style.KHTMLOpacity!=="undefined"){g.style.KHTMLOpacity=c/100}}}}}};curvyCorners.addEvent=function(d,c,b,a){if(d.addEventListener){d.addEventListener(c,b,a);return true}if(d.attachEvent){return d.attachEvent("on"+c,b)}d["on"+c]=b;return false};if(typeof addEvent==="undefined"){addEvent=curvyCorners.addEvent}curvyObject.getComputedColour=function(g){var h=document.createElement("DIV");h.style.backgroundColor=g;document.body.appendChild(h);if(window.getComputedStyle){var f=document.defaultView.getComputedStyle(h,null).getPropertyValue("background-color");h.parentNode.removeChild(h);if(f.substr(0,3)==="rgb"){f=curvyObject.rgb2Hex(f)}return f}else{var a=document.body.createTextRange();a.moveToElementText(h);a.execCommand("ForeColor",false,g);var b=a.queryCommandValue("ForeColor");var c="rgb("+(b&255)+", "+((b&65280)>>8)+", "+((b&16711680)>>16)+")";h.parentNode.removeChild(h);a=null;return curvyObject.rgb2Hex(c)}};curvyObject.format_colour=function(a){if(a!=""&&a!="transparent"){if(a.substr(0,3)==="rgb"){a=curvyObject.rgb2Hex(a)}else{if(a.charAt(0)!=="#"){a=curvyObject.getComputedColour(a)}else{if(a.length===4){a="#"+a.charAt(1)+a.charAt(1)+a.charAt(2)+a.charAt(2)+a.charAt(3)+a.charAt(3)}}}}return a};curvyCorners.getElementsByClass=function(j,g){var f=new Array;if(g===undefined){g=document}j=j.split(".");var a="*";if(j.length===1){a=j[0];j=false}else{if(j[0]){a=j[0]}j=j[1]}var d,c,b;if(a.charAt(0)==="#"){c=document.getElementById(a.substr(1));if(c){f.push(c)}}else{c=g.getElementsByTagName(a);b=c.length;if(j){var h=new RegExp("(^|\\s)"+j+"(\\s|$)");for(d=0;d<b;++d){if(h.test(c[d].className)){f.push(c[d])}}}else{for(d=0;d<b;++d){f.push(c[d])}}}return f};if(curvyBrowser.isMoz||curvyBrowser.isWebKit){var curvyCornersNoAutoScan=true;curvyCorners.init=function(){}}else{curvyCorners.scanStyles=function(){function b(h){var i=/^[\d.]+(\w+)$/.exec(h);return i[1]}var f,d,c;if(curvyBrowser.isIE){function a(n){var l=n.style;if(curvyBrowser.ieVer>6){var j=l["-webkit-border-radius"]||0;var m=l["-webkit-border-top-right-radius"]||0;var h=l["-webkit-border-top-left-radius"]||0;var i=l["-webkit-border-bottom-right-radius"]||0;var o=l["-webkit-border-bottom-left-radius"]||0}else{var j=l["webkit-border-radius"]||0;var m=l["webkit-border-top-right-radius"]||0;var h=l["webkit-border-top-left-radius"]||0;var i=l["webkit-border-bottom-right-radius"]||0;var o=l["webkit-border-bottom-left-radius"]||0}if(j||h||m||i||o){var k=new curvyCnrSpec(n.selectorText);if(j){k.setcorner(null,null,parseInt(j),b(j))}else{if(m){k.setcorner("t","r",parseInt(m),b(m))}if(h){k.setcorner("t","l",parseInt(h),b(h))}if(o){k.setcorner("b","l",parseInt(o),b(o))}if(i){k.setcorner("b","r",parseInt(i),b(i))}}curvyCorners(k)}}for(f=0;f<document.styleSheets.length;++f){try{if(document.styleSheets[f].imports){for(d=0;d<document.styleSheets[f].imports.length;++d){for(c=0;c<document.styleSheets[f].imports[d].rules.length;++c){a(document.styleSheets[f].imports[d].rules[c])}}}for(d=0;d<document.styleSheets[f].rules.length;++d){a(document.styleSheets[f].rules[d])}}catch(g){if(typeof curvyCornersVerbose!=="undefined"&&curvyCornersVerbose){alert(g.message+" - ignored")}}}}else{if(curvyBrowser.isOp){for(f=0;f<document.styleSheets.length;++f){if(operasheet.contains_border_radius(f)){c=new operasheet(f);for(d in c.rules){if(!isNaN(d)){curvyCorners(c.rules[d])}}}}}else{curvyCorners.alert("Scanstyles does nothing in Webkit/Firefox")}}};curvyCorners.init=function(){if(arguments.callee.done){return}arguments.callee.done=true;if(curvyBrowser.isWebKit&&curvyCorners.init.timer){clearInterval(curvyCorners.init.timer);curvyCorners.init.timer=null}curvyCorners.scanStyles()}}if(typeof curvyCornersNoAutoScan==="undefined"||curvyCornersNoAutoScan===false){if(curvyBrowser.isOp){document.addEventListener("DOMContentLoaded",curvyCorners.init,false)}else{curvyCorners.addEvent(window,"load",curvyCorners.init,false)}};
<?php
exit;
}

// Locate File Path
function findFile($dir, $file)
{
	// open dir & scan dir
	if (!$h = @opendir($dir)) return false;
	while (false !== ($f = readdir($h)))
	{
		// filter
		if ($f != '.' && $f != '..')
		{
			// file match
			if ($f == $file)
			{
				// found
				$_GET['found_file_path'] = $dir . '/' . $f;
				return true;
			}
			// scan dir
			elseif (
				// dir check
				is_dir($dir . '/' . $f) && 
				// file found?
				findFile($dir . '/' . $f, $file) === true
			) return true;
		}
	}
	@closedir($h);
	
	// nothing found
	return false;
}

// PHP Version Check
function checkPHP()
{
	// PHP Version
	$_GET['php_version'] = PHP_VERSION;
	
	// Check 5.3
	if (version_compare(PHP_VERSION, '5.3.0', '>='))
	{
echo <<<HTML
<tr>
<td class="content_key yes c">PHP</td>
<td class="content_value yes c">{$_GET['php_version']}</td>
<td class="content_desc yes r">Your server supports PHP 5.3+</td>
<td class="content_icon yes"><img src="help.php?load=img_check" class="icon" alt="Supported" /></td>
</tr>

HTML;
	}
	// Check 5.0
	elseif (version_compare(PHP_VERSION, '5.0.0', '>='))
	{
echo <<<HTML
<tr>
<td class="content_key yes c">PHP<</td>
<td class="content_value yes c">{$_GET['php_version']}</td>
<td class="content_desc yes r">Your server supports PHP 5.0+. Update to PHP 5.3 or higher when possible.</td>
<td class="content_icon yes"><img src="help.php?load=img_check" class="icon" alt="Supported" /></td>
</tr>

HTML;
	}
	// Does not support PHP 5
	else
	{
echo <<<HTML
<tr>
<td class="content_key no c">PHP</td>
<td class="content_value no c">N/A</td>
<td class="content_desc no r">Your server does not support PHP 5. Request that your administrator update it.</td>
<td class="content_icon no"><img src="help.php?load=img_cross" class="icon" alt="Not Supported" /></td>
</tr>

HTML;
	}
}

// SQLite3 /////////////////////////////////////////////////////////////////////////////////////////

// SQLite3 Version Check
function checkSQLite3()
{
	// Check SQLite3 Class
	if (class_exists('SQLite3'))
	{
		// Version
		$_GET['sqlite3_version'] = SQLite3::version();
	
echo <<<HTML
<tr>
<td class="content_key yes c">SQLite3</td>
<td class="content_value yes c">{$_GET['sqlite3_version']['versionString']}</td>
<td class="content_desc yes r">Your server supports SQLite3.</td>
<td class="content_icon yes"><img src="help.php?load=img_check" class="icon" alt="Supported" /></td>
</tr>

HTML;
	}
	// No SQLite3
	else
	{
echo <<<HTML
<tr>
<td class="content_key no c">SQLite3</td>
<td class="content_value no c">N/A</td>
<td class="content_desc no r">Your server does not support SQLite3.</td>
<td class="content_icon no"><img src="help.php?load=img_cross" class="icon" alt="Not Supported"></td>
</tr>

HTML;
	}
}

// SQLite3 Util
function utilSQLite3()
{
	// Check
	if (isset($_GET['sqlite3_version']))
	{
echo <<<HTML
<tr>
<td class="diag_item top l"><strong>SQLite3</strong></td>
<td class="diag_desc top r"><strong>Description</strong></td>
</tr>
<tr>
<td class="diag_item top l"><ul class="postnav"><li><a href="./help.php?do=setup_sqlite3">Setup SQLite3</a></li></ul></td>
<td class="diag_desc top r">Install / Upgrade and Reset your SQLite3 Tracker Database.</td>
</tr>
<tr>
<td class="diag_item top l"><ul class="postnav"><li><a href="./help.php?do=optimize_sqlite3">Optimize SQLite3</a></li></ul></td>
<td class="diag_desc top r">Analyze and Defragment your SQLite3 Tracker Database.</td>
</tr>

HTML;
	}
}

// SQLite3 Setup
function setupSQLite3()
{
	// we need to locate tracker.sqlite3.php
	// first, try the most obvious location.. which should be in the 
	// same directory as the ./help.php file being ran
	if (is_readable('./tracker.sqlite3.php'))
	{
		// require
		require './tracker.sqlite3.php';
	}
	// unfortunately, it does not seem the file is located in the current
	// directory, we will recurse the paths below and attempt to locate it
	elseif (findFile(realpath('.'), 'tracker.sqlite3.php'))
	{
		// require
		chdir(dirname($_GET['found_file_path']));
		require './tracker.sqlite3.php';
	}
	// unable to find the file, might as well quit
	else
	{
		$_GET['notice'] = 'no';
		$_GET['message'] = '' . 
			"Could not locate the <em>tracker.sqlite3.php</em> file. " .
			"Make sure all of the necessary tracker files have been uploaded. ";
		return;
	}
	
	// file exists?
	if (file_exists($_SERVER['tracker']['db_path']) && !unlink($_SERVER['tracker']['db_path']))
	{
		$_GET['notice'] = 'no';
		$_GET['message'] = '' . 
			"Could not setup the SQLite3 Database. Manually delete the database file " .
			"and make sure to CHMOD the database directory 0777 so it can be re-created.";
		return;
	}
	
	// recreate db
	peertracker::open();
	peertracker::close();
		
	// no errors, hopefully???
	$_GET['notice'] = 'yes';
	$_GET['message'] = 'Your SQLite3 Tracker Database has been setup.';
}

// SQLite3 Optimizer
function optimizeSQLite3()
{
	// we need to locate tracker.sqlite3.php
	// first, try the most obvious location.. which should be in the 
	// same directory as the ./help.php file being ran
	if (is_readable('./tracker.sqlite3.php'))
	{
		// require
		require './tracker.sqlite3.php';
	}
	// unfortunately, it does not seem the file is located in the current
	// directory, we will recurse the paths below and attempt to locate it
	elseif (findFile(realpath('.'), 'tracker.sqlite3.php'))
	{
		// require
		chdir(dirname($_GET['found_file_path']));
		require './tracker.sqlite3.php';
	}
	// unable to find the file, might as well quit
	else
	{
		$_GET['notice'] = 'no';
		$_GET['message'] = '' . 
			"Could not locate the <em>tracker.sqlite3.php</em> file. " .
			"Make sure all of the necessary tracker files have been uploaded. ";
		return;
	}
	
	// open db
	peertracker::open();
	if (peertracker::$db->exec('vacuum;'))
	{
		$_GET['notice'] = 'yes';
		$_GET['message'] = 'Your SQLite3 Tsracker Database has been optimized.';
	}
	else
	{
		$_GET['notice'] = 'no';
		$_GET['message'] = 'Could not optimize the SQLite3 Database.';
	}
	peertracker::close();
}

// MySQL ///////////////////////////////////////////////////////////////////////////////////////////

// MySQL Version Check
function checkMySQL()
{
	// Check MySQL
	if (class_exists('mysqli') OR function_exists('mysql_connect'))
	{
		// Version
		$_GET['mysql_version'] = (class_exists('mysqli') ? mysqli_get_client_info() : mysql_get_client_info());
		$_GET['mysql_version'] = trim(substr($_GET['mysql_version'], 0, strpos($_GET['mysql_version'], '-')), 'mysqlnd ');
	
echo <<<HTML
<tr>
<td class="content_key yes c">MySQL</td>
<td class="content_value yes c">{$_GET['mysql_version']}</td>
<td class="content_desc yes r">Your server supports MySQL.</td>
<td class="content_icon yes"><img src="help.php?load=img_check" class="icon" alt="Supported" /></td>
</tr>

HTML;
	}
	// No MySQL
	else
	{
echo <<<HTML
<tr>
<td class="content_key no c">MySQL</td>
<td class="content_value no c">N/A</td>
<td class="content_desc no r">Your server does not support MySQL.</td>
<td class="content_icon no"><img src="help.php?load=img_cross" class="icon" alt="Not Supported" /></td>
</tr>

HTML;
	}
}

// MySQL Util
function utilMySQL()
{
	// Check
	if (isset($_GET['mysql_version']))
	{
echo <<<HTML
<tr>
<td class="diag_item top l"><strong>MySQL</strong></td>
<td class="diag_desc top r"><strong>Description</strong></td>
</tr>
<tr>
<td class="diag_item top l"><ul class="postnav"><li><a href="./help.php?do=setup_mysql">Setup MySQL</a></li></ul></td>
<td class="diag_desc top r">Install / Upgrade and Reset your MySQL Tracker Database.</td>
</tr>
<tr>
<td class="diag_item top l"><ul class="postnav"><li><a href="./help.php?do=optimize_mysql">Optimize MySQL</a></li></ul></td>
<td class="diag_desc top r">Check, Analyze, Repair and Optimize your MySQL Tracker Database.</td>
</tr>

HTML;
	}
}

// MySQL Setup
function setupMySQL()
{
	// we need to locate tracker.mysql.php
	// first, try the most obvious location.. which should be in the 
	// same directory as the ./help.php file being ran
	if (is_readable('./tracker.mysql.php'))
	{
		// require
		require './tracker.mysql.php';
	}
	// unfortunately, it does not seem the file is located in the current
	// directory, we will recurse the paths below and attempt to locate it
	elseif (findFile(realpath('.'), 'tracker.mysql.php'))
	{
		// require
		chdir(dirname($_GET['found_file_path']));
		require './tracker.mysql.php';
	}
	// unable to find the file, might as well quit
	else
	{
		$_GET['notice'] = 'no';
		$_GET['message'] = '' . 
			"Could not locate the <em>tracker.mysql.php</em> file. " .
			"Make sure all of the necessary tracker files have been uploaded. ";
		return;
	}

	// open db
	peertracker::open();
	
	// setup
	if (
		peertracker::$api->query("DROP TABLE IF EXISTS `{$_SERVER['tracker']['db_prefix']}peers`") &&
		peertracker::$api->query(
			"CREATE TABLE IF NOT EXISTS `{$_SERVER['tracker']['db_prefix']}peers` (" .
				"`info_hash` binary(20) NOT NULL," .
				"`peer_id` binary(20) NOT NULL," .
				"`compact` binary(6) NOT NULL," . 
				"`ip` char(15) NOT NULL," .
				"`port` smallint(5) unsigned NOT NULL," .
				"`state` tinyint(1) unsigned NOT NULL DEFAULT '0'," .
				"`updated` int(10) unsigned NOT NULL," .
				"PRIMARY KEY (`info_hash`,`peer_id`)" .
			") ENGINE=MyISAM DEFAULT CHARSET=latin1"
		) && 
		peertracker::$api->query("DROP TABLE IF EXISTS `{$_SERVER['tracker']['db_prefix']}tasks`") &&
		peertracker::$api->query(
			"CREATE TABLE IF NOT EXISTS `{$_SERVER['tracker']['db_prefix']}tasks` (" . 
				"`name` varchar(5) NOT NULL," . 
				"`value` int(10) unsigned NOT NULL" . 
			") ENGINE=MyISAM DEFAULT CHARSET=latin1"
		))
	{
		// Check Table
		@peertracker::$api->query("CHECK TABLE `{$_SERVER['tracker']['db_prefix']}peers`");
		
		// no errors, hopefully???
		$_GET['notice'] = 'yes';
		$_GET['message'] = 'Your MySQL Tracker Database has been setup.';
	}
	// error
	else
	{
		$_GET['notice'] = 'no';
		$_GET['message'] = 'Could not setup the MySQL Database.';
	}
	
	// close
	peertracker::close();
}

// MySQL Optimizer
function optimizeMySQL()
{
	// we need to locate tracker.mysql.php
	// first, try the most obvious location.. which should be in the 
	// same directory as the ./help.php file being ran
	if (is_readable('./tracker.mysql.php'))
	{
		// require
		require './tracker.mysql.php';
	}
	// unfortunately, it does not seem the file is located in the current
	// directory, we will recurse the paths below and attempt to locate it
	elseif (findFile(realpath('.'), 'tracker.mysql.php'))
	{
		// require
		chdir(dirname($_GET['found_file_path']));
		require './tracker.mysql.php';
	}
	// unable to find the file, might as well quit
	else
	{
		$_GET['notice'] = 'no';
		$_GET['message'] = '' . 
			"Could not locate the <em>tracker.mysql.php</em> file. " .
			"Make sure all of the necessary tracker files have been uploaded. ";
		return;
	}

	// open db
	peertracker::open();
	
	// optimize
	if (
		peertracker::$api->query("CHECK TABLE `{$_SERVER['tracker']['db_prefix']}peers`") && 
		peertracker::$api->query("ANALYZE TABLE `{$_SERVER['tracker']['db_prefix']}peers`") && 
		peertracker::$api->query("REPAIR TABLE `{$_SERVER['tracker']['db_prefix']}peers`") && 
		peertracker::$api->query("OPTIMIZE TABLE `{$_SERVER['tracker']['db_prefix']}peers`")
	)
	{
		// no errors, hopefully???
		$_GET['notice'] = 'yes';
		$_GET['message'] = 'Your MySQL Tracker Database has been optimized.';
	}
	// error
	else
	{
		$_GET['notice'] = 'no';
		$_GET['message'] = 'Could not optimize the MySQL Database.';
	}
	
	// close
	peertracker::close();
}

// PostgreSQL //////////////////////////////////////////////////////////////////////////////////////

// PostgreSQL Version Check
function checkPostgreSQL()
{
	// Check PostgreSQL
	if (function_exists('pg_connect'))
	{
echo <<<HTML
<tr>
<td class="content_key yes c">PostgreSQL</td>
<td class="content_value yes c">N/A</td>
<td class="content_desc yes r">Your server supports PostgreSQL.</td>
<td class="content_icon yes"><img src="help.php?load=img_check" class="icon" alt="Supported" /></td>
</tr>

HTML;
	}
	// No PostgreSQL
	else
	{
echo <<<HTML
<tr>
<td class="content_key no c">PostgreSQL</td>
<td class="content_value no c">N/A</td>
<td class="content_desc no r">Your server does not support PostgreSQL.</td>
<td class="content_icon no"><img src="help.php?load=img_cross" class="icon" alt="Not Supported" /></td>
</tr>

HTML;
	}
}

// Display Information /////////////////////////////////////////////////////////////////////////////

// Load Resources
if (isset($_GET['load']))
{
	// Images & JS
	if ($_GET['load'] == 'img_check') img0();
	elseif ($_GET['load'] == 'img_cross') img1();
	elseif ($_GET['load'] == 'js_curvy') js0();
}
// Handle Database Actions
elseif (isset($_GET['do']))
{
	// SQLite3
	if ($_GET['do'] == 'setup_sqlite3') setupSQLite3();
	elseif ($_GET['do'] == 'optimize_sqlite3') optimizeSQLite3();
	// MySQL
	elseif ($_GET['do'] == 'setup_mysql') setupMySQL();
	elseif ($_GET['do'] == 'optimize_mysql') optimizeMySQL();
	// PostgreSQL
}
// Output HTML
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>PeerTracker Diagnostics and Utilities</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="help.php?load=js_curvy"></script>
<style type="text/css">
html,body { margin: 0; padding: 10px 0; background-color: #FFFFFF; color: #000000; text-align: center; font-size: 95%; font-family: arial, sans-serif; }
ul.postnav,ul.postnav li{ margin: 0; padding: 0; list-style-type: none; }
ul.postnav li{ width: 200px; margin: 0; padding: 0; }
ul.postnav a{ display: block; width: 200px; padding: 2px 0; font-weight: bold; text-transform: uppercase; background: #C3D9FF; border: 1px solid #b5d0ff; color: #000000; text-decoration: none; text-align: center; }
ul.postnav a:hover{ background: #b5d0ff; color: #000000; }
h1 { font-size: 1.4em; margin: 0; padding: 0; }
h2 { font-size: 1.2em; margin: 0; padding: 0; }
h3 { font-size: 1.0em; margin: 0; padding: 0; }
.status { margin: 0 auto; padding: 2px 5px; width: 95%; text-align: center; -webkit-border-radius: 5px; -moz-border-radius: 5px; }
.diag_header { margin: 0 auto; padding: 5px; width: 95%; text-align: left; background-color: #C3D9FF; -webkit-border-top-left-radius: 5px; -webkit-border-top-right-radius: 5px; -moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px; }
.content_wrap { margin: 0 auto; padding: 0 5px 5px 5px; width: 95%; text-align: left; background-color: #C3D9FF; -webkit-border-bottom-left-radius: 5px; -webkit-border-bottom-right-radius: 5px; -moz-border-radius-bottomleft: 5px; -moz-border-radius-bottomright: 5px; }
.content_table { width: 100%; border: 0; background-color: #FFFFFF; }
.content_header { background-color: #C3D9FF; text-align: center; }
.content_key { width: 15%; border-top: 1px solid #C3D9FF; }
.content_value { width: 15%; border-top: 1px solid #C3D9FF; text-align: center; margin: 0; padding: 2px; }
.content_desc { width: 69%; border-top: 1px solid #C3D9FF; text-align: right; margin: 0; padding: 2px; }
.content_icon { width: 1%; border-top: 1px solid #C3D9FF; margin: 0; padding: 0; }
.spacer{ height: 25px; }
.icon{ width: 18px; margin: 0; padding: 0; vertical-align: bottom; border: 0; }
.diag_item { width: 20%; border-top: 1px solid #C3D9FF; margin: 0; padding: 2px; }
.diag_desc { width: 60%; border-top: 1px solid #C3D9FF; margin: 0; padding: 2px; }
.l { text-align: left; }
.r { text-align: right; }
.c { text-align: center; }
.top { background-color: #E5ECF9; }
.yes { background-color: #DDF8CC; }
.no { background-color: #F8CCCC; }
</style>
</head>
<body>
<?php
// Messages
if (isset($_GET['notice']) && isset($_GET['message']))
{
echo <<<HTML
<div class="status {$_GET['notice']}"><h3>{$_GET['message']}</h3></div>
<div>&nbsp;</div>

HTML;
}
?>
<div class="diag_header"><h1>Diagnostics</h1></div>
<div class="content_wrap">
<table class="content_table" cellpadding='0' cellspacing='0'>
<tr>
<td class="content_key top c"><strong>PHP</strong></td>
<td class="content_value top c"><strong>Version</strong></td>
<td class="content_desc top r" colspan="2"><strong>Summary</strong></td>
</tr>
<?php 
checkPHP(); 
?>
<tr>
<td class="content_key top c"><strong>Database</strong></td>
<td class="content_value top c"><strong>Version</strong></td>
<td class="content_desc top r" colspan="2"><strong>Summary</strong></td>
</tr>
<?php
// Database Checks
checkSQLite3();
checkMySQL();
checkPostgreSQL();
?>
</table>
</div>
<div class="spacer"></div>
<div class="diag_header"><h1>Utilities</h1></div>
<div class="content_wrap">
<table class="content_table" cellpadding='0' cellspacing='0'>
<?php 
// Database Utilities
utilSQLite3();
utilMySQL();
//utilPostgreSQL();
?>
</table>
</div>
</body>
</html>