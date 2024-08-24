/** 
 * @preserve jQuery DateTimePicker plugin v1.0.8
 * @homepage http://xdsoft.net/jqplugins/datetimepicker/
 * (c) 2013, Chupurnov Valeriy.
 */
(function(d){d.fn.datetimepicker=function(l){var r={i18n:{ru:{months:"\u042f\u043d\u0432\u0430\u0440\u044c \u0424\u0435\u0432\u0440\u0430\u043b\u044c \u041c\u0430\u0440\u0442 \u0410\u043f\u0440\u0435\u043b\u044c \u041c\u0430\u0439 \u0418\u044e\u043d\u044c \u0418\u044e\u043b\u044c \u0410\u0432\u0433\u0443\u0441\u0442 \u0421\u0435\u043d\u0442\u044f\u0431\u0440\u044c \u041e\u043a\u0442\u044f\u0431\u0440\u044c \u041d\u043e\u044f\u0431\u0440\u044c \u0414\u0435\u043a\u0430\u0431\u0440\u044c".split(" "),
dayOfWeek:"\u0412\u0441\u043a \u041f\u043d \u0412\u0442 \u0421\u0440 \u0427\u0442 \u041f\u0442 \u0421\u0431".split(" ")},en:{months:"January February March April May June July August September October November December".split(" "),dayOfWeek:"Sun Mon Tue Wed Thu Fri Sat".split(" ")},de:{months:"Januar Februar M\u00e4rz April Mai Juni Juli August September Oktober November Dezember".split(" "),dayOfWeek:"So. Mo Di Mi Do Fr Sa.".split(" ")},nl:{months:"januari februari maart april mei juni juli augustus september oktober november december".split(" "),
dayOfWeek:"zo ma di wo do vr za".split(" ")}},value:"",lang:"en",format:"Y/m/d H:i",formatTime:"H:i",formatDate:"Y/m/d",step:60,closeOnDateSelect:0,closeOnWithoutClick:!0,timepicker:!0,datepicker:!0,minDate:!1,maxDate:!1,minTime:!1,maxTime:!1,allowTimes:[],opened:!1,inline:!1,onSelectDate:function(){},onSelectTime:function(){},onChangeMonth:function(){},onChangeDateTime:function(){},onShow:function(){},onClose:function(){},withoutCopyright:!0,inverseButton:!1,hours12:!1,next:"xdsoft_next",prev:"xdsoft_prev",
dayOfWeekStart:0,timeHeightInTimePicker:25,timepickerScrollbar:!0,scrollMonth:!0,scrollTime:!0,scrollInput:!0},a=d.isPlainObject(l)||!l?d.extend({},r,l):d.extend({},r),y=function(c){var b=d('<div class="xdsoft_datetimepicker"></div>'),l=d('<div class="xdsoft_copyright"><a target="_blank" href="http://xdsoft.net/jqplugins/datetimepicker/">xdsoft.net</a></div>'),s=d('<div class="xdsoft_datepicker active"></div>'),t=d('<div class="xdsoft_mounthpicker"><button type="button" class="xdsoft_prev"></button><div class="xdsoft_label xdsoft_month"></div><div class="xdsoft_label xdsoft_year"></div><button type="button" class="xdsoft_next"></button></div>'),
u=d('<div class="xdsoft_calendar"></div>'),m=d('<div class="xdsoft_timepicker active"><button type="button" class="xdsoft_prev"></button><div class="xdsoft_time_box"></div><button type="button" class="xdsoft_next"></button></div>'),v=m.find(".xdsoft_time_box").eq(0),f=d('<div class="xdsoft_time_variant"></div>'),p=d('<div class="xdsoft_scrollbar"></div>'),g=d('<div class="xdsoft_scroller"></div>');b.setOptions=function(k){a=d.extend({},a,k);a.inline&&(b.addClass("xdsoft_inline"),c.after(b).hide());
(a.open||a.opened||a.inline)&&c.trigger("open.xdsoft");a.inverseButton&&(a.next="xdsoft_prev",a.prev="xdsoft_next");!a.datepicker&&a.timepicker&&s.removeClass("active");a.datepicker&&!a.timepicker&&m.removeClass("active");a.value&&c&&c.val&&c.val(a.value);isNaN(a.dayOfWeekStart)||0>parseInt(a.dayOfWeekStart)||6<parseInt(a.dayOfWeekStart)?a.dayOfWeekStart=0:a.dayOfWeekStart=parseInt(a.dayOfWeekStart);a.timepickerScrollbar||p.hide();a.dayOfWeekStartPrev=0==a.dayOfWeekStart?6:a.dayOfWeekStart-1};b.data("options",
a);b.on("mousedown",function(a){a.stopPropagation()});p.append(g);m.find(".xdsoft_time_box").append(p);(function(){var k=0;g.on("mousedown",function(a){var w=a.pageY,c=parseInt(g.css("margin-top")),f=p[0].offsetHeight;d("body").addClass("xdsoft_noselect");d(window).on("mouseup",function(){d(window).off("mouseup",arguments.callee);d(window).off("mousemove",k);d("body").removeClass("xdsoft_noselect")}).on("mousemove",k=function(a){a=a.pageY-w+c;0>a&&(a=0);a+g[0].offsetHeight>f&&(a=f-g[0].offsetHeight);
g.css("margin-top",a);b.trigger("scroll.scrollbar",[a])})});b.on("scroll.timebox",function(b,k){if(a.timepickerScrollbar){var d=p.height()-g[0].offsetHeight;g.css("margin-top",k/(f[0].offsetHeight-v[0].offsetHeight)*d)}}).on("open.xdsoft",function(b){a.timepickerScrollbar&&(b=v[0].offsetHeight,height=f[0].offsetHeight,percent=b/height,sh=percent*p[0].offsetHeight,1<percent?g.hide():(g.show(),g.css("height",parseInt(10<sh?sh:10))))})})();b.on("scroll.scrollbar",function(a,b){var d=b/(p[0].offsetHeight-
g[0].offsetHeight);pheight=v[0].offsetHeight;height=f[0].offsetHeight;f.css("marginTop",-parseInt((height-pheight)*d))});m.find(".xdsoft_time_box").append(f);b.append(s).append(m);!0!==a.withoutCopyright&&b.append(l);s.append(t).append(u);d("body").append(b);t.find(".xdsoft_prev,.xdsoft_next").click(function(){var k=d(this);b.data("xdsoft_datetime").currentTime.getMonth();k.hasClass(a.next)?b.data("xdsoft_datetime").nextMonth():k.hasClass(a.prev)&&b.data("xdsoft_datetime").prevMonth()});t.find(".xdsoft_year").click(function(){d(this);
b.data("xdsoft_datetime").currentTime.getMonth();b.data("xdsoft_datetime").nextYear()});m.find(".xdsoft_prev,.xdsoft_next").click(function(){var k=d(this),e=f.parent()[0].offsetHeight,w=f[0].offsetHeight,c=Math.abs(parseInt(f.css("marginTop")));k.hasClass(a.next)&&w-e-a.timeHeightInTimePicker>=c?f.css("marginTop","-"+(c+a.timeHeightInTimePicker)+"px"):k.hasClass(a.prev)&&0<=c-a.timeHeightInTimePicker&&f.css("marginTop","-"+(c-a.timeHeightInTimePicker)+"px");b.trigger("scroll.timebox",[Math.abs(parseInt(f.css("marginTop")))])});
b.on("change.xdsoft",function(){for(var b=d(this).data("xdsoft_datetime"),e="",c=new Date(b.currentTime.getFullYear(),b.currentTime.getMonth(),1);c.getDay()!=a.dayOfWeekStart;)c.setDate(c.getDate()-1);for(var h=0,g=new Date,e=e+"<table><thead><tr>",n=0;7>n;n++)e+="<th>"+a.i18n[a.lang].dayOfWeek[6<n+a.dayOfWeekStart?0:n+a.dayOfWeekStart]+"</th>";for(e+="</tr></thead><tbody><tr>";h<b.currentTime.getDaysInMonth()||c.getDay()!=a.dayOfWeekStart||b.currentTime.getMonth()==c.getMonth();)h++,e+='<td data-date="'+
c.getDate()+'" data-month="'+c.getMonth()+'" data-year="'+c.getFullYear()+'" class="'+(!1!==a.maxDate&&Math.round(b.strtodate(a.maxDate).getTime()/864E5)<Math.round(c.getTime()/864E5)||!1!==a.minDate&&Math.round(b.strtodate(a.minDate).getTime()/864E5)>Math.round(c.getTime()/864E5)?"xdsoft_disabled ":" ")+(b.currentTime.getMonth()!=c.getMonth()?" xdsoft_other_month ":" ")+(b.currentTime.dateFormat("d.m.Y")==c.dateFormat("d.m.Y")?" xdsoft_current ":" ")+(g.dateFormat("d.m.Y")==c.dateFormat("d.m.Y")?
" xdsoft_today ":" ")+'"><div>'+c.getDate()+"</div></td>",c.getDay()==a.dayOfWeekStartPrev&&(e+="</tr>"),c.setDate(c.getDate()+1);e+="</tbody></table>";u.html(e);t.find(".xdsoft_label").eq(0).text(a.i18n[a.lang].months[b.currentTime.getMonth()]);t.find(".xdsoft_label").eq(1).text(b.currentTime.getFullYear());var l="",c=e="",m=function(e,c){var d=new Date;d.setHours(e);e=parseInt(d.getHours());d.setMinutes(c);c=parseInt(d.getMinutes());l+='<div class="'+(!1!==a.maxTime&&b.strtotime(a.maxTime).getTime()<
d.getTime()||!1!==a.minTime&&b.strtotime(a.minTime).getTime()>d.getTime()?"xdsoft_disabled ":" ")+(parseInt(b.currentTime.getHours())==parseInt(e)&&parseInt(b.currentTime.getMinutes()/a.step)*a.step==parseInt(c)?" xdsoft_current ":"")+(parseInt(g.getHours())==parseInt(e)&&parseInt(g.getMinutes())==parseInt(c)?" xdsoft_today ":"")+'" data-hour="'+e+'" data-minute="'+c+'">'+d.dateFormat(a.formatTime)+"</div>"};if(a.allowTimes&&d.isArray(a.allowTimes)&&a.allowTimes.length)for(h=0;h<a.allowTimes.length;h++)e=
b.strtotime(a.allowTimes[h]).getHours(),c=b.strtotime(a.allowTimes[h]).getMinutes(),m(e,c);else for(h=0;h<(a.hours12?12:24);h++)for(n=0;60>n;n+=a.step)e=(10>h?"0":"")+h,c=(10>n?"0":"")+n,m(e,c);f.html(l)});b.on("open.xdsoft",function(){if(f.find(".xdsoft_current").length){var b=f.parent()[0].offsetHeight,e=f[0].offsetHeight,c=f.find(".xdsoft_current").index()*a.timeHeightInTimePicker;e-b<c&&(c=e-b);f.css("marginTop","-"+parseInt(c)+"px")}});u.on("mousedown","td",function(){if(d(this).hasClass("xdsoft_disabled"))return!1;
var k=b.data("xdsoft_datetime").currentTime;k.setFullYear(d(this).data("year"));k.setMonth(d(this).data("month"));k.setDate(d(this).data("date"));b.trigger("select.xdsoft",[k]);c.val(b.data("xdsoft_datetime").str());!0!==a.closeOnDateSelect&&(0!==a.closeOnDateSelect||a.timepicker)||a.inline||b.close();a.onSelectDate&&a.onSelectDate.call&&a.onSelectDate.call(b,b.data("xdsoft_datetime").currentTime,b.data("input"));b.trigger("change.xdsoft");b.trigger("changedatetime.xdsoft")});f.on("mousedown","div",
function(){if(d(this).hasClass("xdsoft_disabled"))return!1;var c=b.data("xdsoft_datetime").currentTime;c.setHours(d(this).data("hour"));c.setMinutes(d(this).data("minute"));b.trigger("select.xdsoft",[c]);b.data("input").val(b.data("xdsoft_datetime").str());!a.inline&&b.close();a.onSelectTime&&a.onSelectTime.call&&a.onSelectTime.call(b,b.data("xdsoft_datetime").currentTime,b.data("input"));b.trigger("change.xdsoft");b.trigger("changedatetime.xdsoft")});b.mousewheel&&s.mousewheel(function(c,e,d,f){if(!a.scrollMonth)return!0;
0>e?b.data("xdsoft_datetime").nextMonth():b.data("xdsoft_datetime").prevMonth();return!1});b.mousewheel&&m.mousewheel(function(c,e,d,h){if(!a.scrollTime)return!0;c=f.parent()[0].offsetHeight;d=f[0].offsetHeight;h=Math.abs(parseInt(f.css("marginTop")));var g=!0;0>e&&d-c-a.timeHeightInTimePicker>=h?(f.css("marginTop","-"+(h+a.timeHeightInTimePicker)+"px"),g=!1):0<e&&0<=h-a.timeHeightInTimePicker&&(f.css("marginTop","-"+(h-a.timeHeightInTimePicker)+"px"),g=!1);b.trigger("scroll.timebox",[Math.abs(parseInt(f.css("marginTop")))]);
return g});b.on("changedatetime.xdsoft",function(){a.onChangeDateTime&&a.onChangeDateTime.call&&a.onChangeDateTime.call(b,b.data("xdsoft_datetime").currentTime,b.data("input"))});var q=0;c.mousewheel&&c.mousewheel(function(d,e,g,h){if(!a.scrollInput)return!0;if(!a.datepicker&&a.timepicker)return q=f.find(".xdsoft_current").length?f.find(".xdsoft_current").eq(0).index():0,0<=q+e&&q+e<f.children().length&&(q+=e),f.children().eq(q).length&&f.children().eq(q).trigger("mousedown"),!1;if(a.datepicker&&
!a.timepicker)return s.trigger(d,[e,g,h]),c.val&&c.val(b.data("xdsoft_datetime").str()),b.trigger("changedatetime.xdsoft"),!1});b.open=function(){var c=!0;a.onShow&&a.onShow.call&&(c=a.onShow.call(b,b.data("xdsoft_datetime").currentTime,b.data("input")));if(!1!==c){c=function(){var a=b.data("input").offset(),c=a.top+b.data("input")[0].offsetHeight;c+b[0].offsetHeight>d("body").height()&&(c=a.top-b[0].offsetHeight);b.css({left:a.left,top:c})};b.show();c();d(window).on("resize.xdsoft",c);if(a.closeOnWithoutClick)d(window).on("mousedown.xdsoft keydown.xdsoft",
function(){b.close();d(this).off("mousedown",arguments.callee)});b.trigger("open.xdsoft")}};b.close=function(){var c=!0;a.onClose&&a.onClose.call&&(c=a.onClose.call(b,b.data("xdsoft_datetime").currentTime,b.data("input")));!1===c||a.opened||a.inline||b.hide()};b.data("input",c);var x=new function(){var c=this;c.now=function(){return new Date};c.currentTime=this.now();c.isValidDate=function(a){return"[object Date]"!==Object.prototype.toString.call(a)?!1:!isNaN(a.getTime())};c.setCurrentTime=function(a){c.currentTime=
"string"==typeof a?c.strtodatetime(a):c.isValidDate(a)?a:c.now();b.trigger("change.xdsoft")};c.getCurrentTime=function(a){return c.currentTime};c.nextMonth=function(){var e=c.currentTime.getMonth()+1;12==e&&(c.currentTime.setFullYear(c.currentTime.getFullYear()+1),e=0);c.currentTime.setMonth(e);a.onChangeMonth&&a.onChangeMonth.call&&a.onChangeMonth.call(b,b.data("xdsoft_datetime").currentTime,b.data("input"));b.trigger("change.xdsoft");return e};c.nextYear=function(){var e=c.currentTime.getMonth();
c.currentTime.setFullYear(c.currentTime.getFullYear()+1);c.currentTime.setMonth(e);a.onChangeMonth&&a.onChangeMonth.call&&a.onChangeMonth.call(b,b.data("xdsoft_datetime").currentTime,b.data("input"));b.trigger("change.xdsoft");return e};c.prevMonth=function(){var e=c.currentTime.getMonth()-1;-1==e&&(c.currentTime.setFullYear(c.currentTime.getFullYear()-1),e=11);c.currentTime.setMonth(e);a.onChangeMonth&&a.onChangeMonth.call&&a.onChangeMonth.call(b,b.data("xdsoft_datetime").currentTime,b.data("input"));
b.trigger("change.xdsoft");return e};this.strtodatetime=function(b){b=b?Date.parseDate(b,a.format):new Date;c.isValidDate(b)||(b=new Date);return b};this.strtodate=function(b){b=b?Date.parseDate(b,a.formatDate):new Date;c.isValidDate(b)||(b=new Date);return b};this.strtotime=function(b){b=b?Date.parseDate(b,a.formatTime):new Date;c.isValidDate(b)||(b=new Date);return b};this.str=function(){return this.currentTime.dateFormat(a.format)}},r=0;b.data("xdsoft_datetime",x);b.setOptions(a);x.setCurrentTime(a.value?
a.value:c&&c.val&&c.val()?c.val():new Date);c.data("xdsoft_datetimepicker",b).on("enter.xdsoft keyup.xdsoft mousedown.xdsoft open.xdsoft",function(a){c.is(":disabled")||c.is(":hidden")||!c.is(":visible")||(clearTimeout(r),r=setTimeout(function(){c.is(":disabled")||c.is(":hidden")||!c.is(":visible")||(x.setCurrentTime(c&&c.val&&c.val()?c.val():new Date),b.open())},100))})};return this.each(function(){var c;if(c=d(this).data("xdsoft_datetimepicker")){if("string"===d.type(l))switch(l){case "show":c.open();
break;case "hide":c.close();break;case "destroy":c=d(this);var b=c.data("xdsoft_datetimepicker");b&&(delete b.data("xdsoft_datetime"),b.remove(),delete b,c.data("xdsoft_datetimepicker",null),c.off("enter.xdsoft keyup.xdsoft mousedown.xdsoft open.xdsoft"),d(window).off("resize.xdsoft"),d(window).off("mousedown.xdsoft keydown.xdsoft"),c.unmousewheel&&c.unmousewheel(),delete a)}else d(this).data("xdsoft_datetimepicker").setOptions(a);return 0}"string"!==d.type(l)&&y(d(this))})}})(jQuery);

//http://www.xaprb.com/blog/2005/12/12/javascript-closures-for-runtime-efficiency/
/*
 * Copyright (C) 2004 Baron Schwartz <baron at sequent dot org>
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as published by the
 * Free Software Foundation, version 2.1.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for more
 * details.
 */
Date.parseFunctions={count:0};Date.parseRegexes=[];Date.formatFunctions={count:0};Date.prototype.dateFormat=function(format){if(Date.formatFunctions[format]==null){Date.createNewFormat(format)}var func=Date.formatFunctions[format];return this[func]()};Date.createNewFormat=function(format){var funcName="format"+Date.formatFunctions.count++;Date.formatFunctions[format]=funcName;var code="Date.prototype."+funcName+" = function(){return ";var special=false;var ch='';for(var i=0;i<format.length;++i){ch=format.charAt(i);if(!special&&ch=="\\"){special=true}else if(special){special=false;code+="'"+String.escape(ch)+"' + "}else{code+=Date.getFormatCode(ch)}}eval(code.substring(0,code.length-3)+";}")};Date.getFormatCode=function(character){switch(character){case"d":return"String.leftPad(this.getDate(), 2, '0') + ";case"D":return"Date.dayNames[this.getDay()].substring(0, 3) + ";case"j":return"this.getDate() + ";case"l":return"Date.dayNames[this.getDay()] + ";case"S":return"this.getSuffix() + ";case"w":return"this.getDay() + ";case"z":return"this.getDayOfYear() + ";case"W":return"this.getWeekOfYear() + ";case"F":return"Date.monthNames[this.getMonth()] + ";case"m":return"String.leftPad(this.getMonth() + 1, 2, '0') + ";case"M":return"Date.monthNames[this.getMonth()].substring(0, 3) + ";case"n":return"(this.getMonth() + 1) + ";case"t":return"this.getDaysInMonth() + ";case"L":return"(this.isLeapYear() ? 1 : 0) + ";case"Y":return"this.getFullYear() + ";case"y":return"('' + this.getFullYear()).substring(2, 4) + ";case"a":return"(this.getHours() < 12 ? 'am' : 'pm') + ";case"A":return"(this.getHours() < 12 ? 'AM' : 'PM') + ";case"g":return"((this.getHours() %12) ? this.getHours() % 12 : 12) + ";case"G":return"this.getHours() + ";case"h":return"String.leftPad((this.getHours() %12) ? this.getHours() % 12 : 12, 2, '0') + ";case"H":return"String.leftPad(this.getHours(), 2, '0') + ";case"i":return"String.leftPad(this.getMinutes(), 2, '0') + ";case"s":return"String.leftPad(this.getSeconds(), 2, '0') + ";case"O":return"this.getGMTOffset() + ";case"T":return"this.getTimezone() + ";case"Z":return"(this.getTimezoneOffset() * -60) + ";default:return"'"+String.escape(character)+"' + "}};Date.parseDate=function(input,format){if(Date.parseFunctions[format]==null){Date.createParser(format)}var func=Date.parseFunctions[format];return Date[func](input)};Date.createParser=function(format){var funcName="parse"+Date.parseFunctions.count++;var regexNum=Date.parseRegexes.length;var currentGroup=1;Date.parseFunctions[format]=funcName;var code="Date."+funcName+" = function(input){\n"+"var y = -1, m = -1, d = -1, h = -1, i = -1, s = -1;\n"+"var d = new Date();\n"+"y = d.getFullYear();\n"+"m = d.getMonth();\n"+"d = d.getDate();\n"+"var results = input.match(Date.parseRegexes["+regexNum+"]);\n"+"if (results && results.length > 0) {";var regex="";var special=false;var ch='';for(var i=0;i<format.length;++i){ch=format.charAt(i);if(!special&&ch=="\\"){special=true}else if(special){special=false;regex+=String.escape(ch)}else{obj=Date.formatCodeToRegex(ch,currentGroup);currentGroup+=obj.g;regex+=obj.s;if(obj.g&&obj.c){code+=obj.c}}}code+="if (y > 0 && m >= 0 && d > 0 && h >= 0 && i >= 0 && s >= 0)\n"+"{return new Date(y, m, d, h, i, s);}\n"+"else if (y > 0 && m >= 0 && d > 0 && h >= 0 && i >= 0)\n"+"{return new Date(y, m, d, h, i);}\n"+"else if (y > 0 && m >= 0 && d > 0 && h >= 0)\n"+"{return new Date(y, m, d, h);}\n"+"else if (y > 0 && m >= 0 && d > 0)\n"+"{return new Date(y, m, d);}\n"+"else if (y > 0 && m >= 0)\n"+"{return new Date(y, m);}\n"+"else if (y > 0)\n"+"{return new Date(y);}\n"+"}return null;}";Date.parseRegexes[regexNum]=new RegExp("^"+regex+"$");eval(code)};Date.formatCodeToRegex=function(character,currentGroup){switch(character){case"D":return{g:0,c:null,s:"(?:Sun|Mon|Tue|Wed|Thu|Fri|Sat)"};case"j":case"d":return{g:1,c:"d = parseInt(results["+currentGroup+"], 10);\n",s:"(\\d{1,2})"};case"l":return{g:0,c:null,s:"(?:"+Date.dayNames.join("|")+")"};case"S":return{g:0,c:null,s:"(?:st|nd|rd|th)"};case"w":return{g:0,c:null,s:"\\d"};case"z":return{g:0,c:null,s:"(?:\\d{1,3})"};case"W":return{g:0,c:null,s:"(?:\\d{2})"};case"F":return{g:1,c:"m = parseInt(Date.monthNumbers[results["+currentGroup+"].substring(0, 3)], 10);\n",s:"("+Date.monthNames.join("|")+")"};case"M":return{g:1,c:"m = parseInt(Date.monthNumbers[results["+currentGroup+"]], 10);\n",s:"(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)"};case"n":case"m":return{g:1,c:"m = parseInt(results["+currentGroup+"], 10) - 1;\n",s:"(\\d{1,2})"};case"t":return{g:0,c:null,s:"\\d{1,2}"};case"L":return{g:0,c:null,s:"(?:1|0)"};case"Y":return{g:1,c:"y = parseInt(results["+currentGroup+"], 10);\n",s:"(\\d{4})"};case"y":return{g:1,c:"var ty = parseInt(results["+currentGroup+"], 10);\n"+"y = ty > Date.y2kYear ? 1900 + ty : 2000 + ty;\n",s:"(\\d{1,2})"};case"a":return{g:1,c:"if (results["+currentGroup+"] == 'am') {\n"+"if (h == 12) { h = 0; }\n"+"} else { if (h < 12) { h += 12; }}",s:"(am|pm)"};case"A":return{g:1,c:"if (results["+currentGroup+"] == 'AM') {\n"+"if (h == 12) { h = 0; }\n"+"} else { if (h < 12) { h += 12; }}",s:"(AM|PM)"};case"g":case"G":case"h":case"H":return{g:1,c:"h = parseInt(results["+currentGroup+"], 10);\n",s:"(\\d{1,2})"};case"i":return{g:1,c:"i = parseInt(results["+currentGroup+"], 10);\n",s:"(\\d{2})"};case"s":return{g:1,c:"s = parseInt(results["+currentGroup+"], 10);\n",s:"(\\d{2})"};case"O":return{g:0,c:null,s:"[+-]\\d{4}"};case"T":return{g:0,c:null,s:"[A-Z]{3}"};case"Z":return{g:0,c:null,s:"[+-]\\d{1,5}"};default:return{g:0,c:null,s:String.escape(character)}}};Date.prototype.getTimezone=function(){return this.toString().replace(/^.*? ([A-Z]{3}) [0-9]{4}.*$/,"$1").replace(/^.*?\(([A-Z])[a-z]+ ([A-Z])[a-z]+ ([A-Z])[a-z]+\)$/,"$1$2$3")};Date.prototype.getGMTOffset=function(){return(this.getTimezoneOffset()>0?"-":"+")+String.leftPad(Math.floor(this.getTimezoneOffset()/60),2,"0")+String.leftPad(this.getTimezoneOffset()%60,2,"0")};Date.prototype.getDayOfYear=function(){var num=0;Date.daysInMonth[1]=this.isLeapYear()?29:28;for(var i=0;i<this.getMonth();++i){num+=Date.daysInMonth[i]}return num+this.getDate()-1};Date.prototype.getWeekOfYear=function(){var now=this.getDayOfYear()+(4-this.getDay());var jan1=new Date(this.getFullYear(),0,1);var then=(7-jan1.getDay()+4);document.write(then);return String.leftPad(((now-then)/7)+1,2,"0")};Date.prototype.isLeapYear=function(){var year=this.getFullYear();return((year&3)==0&&(year%100||(year%400==0&&year)))};Date.prototype.getFirstDayOfMonth=function(){var day=(this.getDay()-(this.getDate()-1))%7;return(day<0)?(day+7):day};Date.prototype.getLastDayOfMonth=function(){var day=(this.getDay()+(Date.daysInMonth[this.getMonth()]-this.getDate()))%7;return(day<0)?(day+7):day};Date.prototype.getDaysInMonth=function(){Date.daysInMonth[1]=this.isLeapYear()?29:28;return Date.daysInMonth[this.getMonth()]};Date.prototype.getSuffix=function(){switch(this.getDate()){case 1:case 21:case 31:return"st";case 2:case 22:return"nd";case 3:case 23:return"rd";default:return"th"}};String.escape=function(string){return string.replace(/('|\\)/g,"\\$1")};String.leftPad=function(val,size,ch){var result=new String(val);if(ch==null){ch=" "}while(result.length<size){result=ch+result}return result};Date.daysInMonth=[31,28,31,30,31,30,31,31,30,31,30,31];Date.monthNames=["January","February","March","April","May","June","July","August","September","October","November","December"];Date.dayNames=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];Date.y2kYear=50;Date.monthNumbers={Jan:0,Feb:1,Mar:2,Apr:3,May:4,Jun:5,Jul:6,Aug:7,Sep:8,Oct:9,Nov:10,Dec:11};Date.patterns={ISO8601LongPattern:"Y-m-d H:i:s",ISO8601ShortPattern:"Y-m-d",ShortDatePattern:"n/j/Y",LongDatePattern:"l, F d, Y",FullDateTimePattern:"l, F d, Y g:i:s A",MonthDayPattern:"F d",ShortTimePattern:"g:i A",LongTimePattern:"g:i:s A",SortableDateTimePattern:"Y-m-d\\TH:i:s",UniversalSortableDateTimePattern:"Y-m-d H:i:sO",YearMonthPattern:"F, Y"};

//https://github.com/brandonaaron/jquery-mousewheel/blob/master/jquery.mousewheel.js
/*
 * Copyright (c) 2013 Brandon Aaron (http://brandonaaron.net)
 *
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.1.3
 *
 * Requires: 1.2.2+
 */
(function(factory){if(typeof define==='function'&&define.amd){define(['jquery'],factory)}else if(typeof exports==='object'){module.exports=factory}else{factory(jQuery)}}(function($){var toFix=['wheel','mousewheel','DOMMouseScroll','MozMousePixelScroll'];var toBind='onwheel'in document||document.documentMode>=9?['wheel']:['mousewheel','DomMouseScroll','MozMousePixelScroll'];var lowestDelta,lowestDeltaXY;if($.event.fixHooks){for(var i=toFix.length;i;){$.event.fixHooks[toFix[--i]]=$.event.mouseHooks}}$.event.special.mousewheel={setup:function(){if(this.addEventListener){for(var i=toBind.length;i;){this.addEventListener(toBind[--i],handler,false)}}else{this.onmousewheel=handler}},teardown:function(){if(this.removeEventListener){for(var i=toBind.length;i;){this.removeEventListener(toBind[--i],handler,false)}}else{this.onmousewheel=null}}};$.fn.extend({mousewheel:function(fn){return fn?this.bind("mousewheel",fn):this.trigger("mousewheel")},unmousewheel:function(fn){return this.unbind("mousewheel",fn)}});function handler(event){var orgEvent=event||window.event,args=[].slice.call(arguments,1),delta=0,deltaX=0,deltaY=0,absDelta=0,absDeltaXY=0,fn;event=$.event.fix(orgEvent);event.type="mousewheel";if(orgEvent.wheelDelta){delta=orgEvent.wheelDelta}if(orgEvent.detail){delta=orgEvent.detail*-1}if(orgEvent.deltaY){deltaY=orgEvent.deltaY*-1;delta=deltaY}if(orgEvent.deltaX){deltaX=orgEvent.deltaX;delta=deltaX*-1}if(orgEvent.wheelDeltaY!==undefined){deltaY=orgEvent.wheelDeltaY}if(orgEvent.wheelDeltaX!==undefined){deltaX=orgEvent.wheelDeltaX*-1}absDelta=Math.abs(delta);if(!lowestDelta||absDelta<lowestDelta){lowestDelta=absDelta}absDeltaXY=Math.max(Math.abs(deltaY),Math.abs(deltaX));if(!lowestDeltaXY||absDeltaXY<lowestDeltaXY){lowestDeltaXY=absDeltaXY}fn=delta>0?'floor':'ceil';delta=Math[fn](delta/lowestDelta);deltaX=Math[fn](deltaX/lowestDeltaXY);deltaY=Math[fn](deltaY/lowestDeltaXY);args.unshift(event,delta,deltaX,deltaY);return($.event.dispatch||$.event.handle).apply(this,args)}}));

$(function() {
	$('[data-rel=calendar]').datetimepicker({
	  format:'Y-m-d H:i:s',
	  step: 30,
	  closeOnDateSelect:true,
	  dayOfWeekStart: 1,
	  i18n: cal_language
	});
	
	$('[data-rel=calendardate]').datetimepicker({
	  format:'Y-m-d',
	  closeOnDateSelect:true,
	  dayOfWeekStart: 1,
	  timepicker:false,
	  i18n: cal_language
	});
  });