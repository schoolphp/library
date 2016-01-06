/*   ДЛЯ АДМИНКИ   */
// дата 2014-08-21;
function dateAjax() {
	var when = new Date();
	year = when.getFullYear();
	if(when.getMonth()+1 < 10) {
		month = '0'+(when.getMonth()+1);
	} else {
		month = when.getMonth()+1;
	}
	if(when.getDate() < 10) {
		date = '0'+when.getDate();
	} else {
		date = when.getDate();
	}
	return year+'-'+month+'-'+date;
}

function isNumeric(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}

function dateComparison(dta,dtb){
	var status = 3;
	var a = 0;
	var b = 0;
	if(dta != 0 && dta != '' && dtb == 0 || dta != 0 && dta != '' && dtb == ''){
		a = Date.parse(dta);
		if(isNumeric(a) == false) {
			status = 0;
		}
		b = new Date().getTime();
		$('input[name = "s_date2"]').val(dateAjax());
		b = Date.parse($('input[name = "s_date2"]').val());
	} else if(dtb != 0 && dtb != '' && dta == 0 || dtb != 0 && dtb != '' && dta == ''){
		var f = dtb.split('-');
		a = (f[0] - 1)+'-'+f[1]+'-'+f[2];
		b = Date.parse(dtb);
		if(isNumeric(b) == false) {
			status = 0;
		}
		$('input[name = "s_date1"]').val(a);
		a = Date.parse($('input[name = "s_date1"]').val());
	} else if(dta != 0 && dtb != 0 && dta != '' && dtb != ''){
		a = Date.parse(dta);
		b = Date.parse(dtb);
		if(isNumeric(a) == false) {
			status = 0;
		}
		if(isNumeric(b) == false) {
			status = 0;
		}
	}
	if(a > b) status = 1;
	if(a < b) status = 2;
	return status;
};

function sortTasksChecked() {
	$('#d_err').html('');
	if($('input[name = "cat_im1"]:checked')) {
		var im_cat1 = $('input[name = "cat_im1"]:checked').val();
	} else {
		var im_cat1 = '';
	}
	if($('input[name = "cat_im2"]:checked')) {
		var im_cat2 = $('input[name = "cat_im2"]:checked').val();
	} else {
		var im_cat2 = '';
	}
	if($('input[name = "cat_im3"]:checked')) {
		var im_cat3 = $('input[name = "cat_im3"]:checked').val();
	} else {
		var im_cat3 = '';
	}
	if($('input[name = "cat_st1"]:checked')) {
		var st_cat1 = 
		$('input[name = "cat_st1"]:checked').val();
	} else {
		var st_cat1 = '';
	}
	if($('input[name = "cat_st2"]:checked')) {
		var st_cat2 = $('input[name = "cat_st2"]:checked').val();
	} else {
		var st_cat2 = '';
	}

	if($('input[name = "s_date1"]').val() != 0 || $('input[name = "s_date1"]').val() != '') {
		var s_date1 = $('input[name = "s_date1"]').val();
	} else {
		var s_date1 = '';
	}
	if($('input[name = "s_date2"]').val() != 0 || $('input[name = "s_date2"]').val() != '') {
		var s_date2 = $('input[name = "s_date2"]').val();
	} else {
		var s_date2 = '';
	}
	
	var allow = true;
	var date_stat = dateComparison(s_date1,s_date2);
	if(date_stat == 1) {
		allow = false;
		$('#d_err').append('Не верный<br>диапазон дат!');
		$('#fc').css('display','none');
	} else if (date_stat == 0) {
		$('#d_err').html('Одна из дат<br>некорректна!');
		$('#fc').css('display','none');
		allow = false;
	} else if (date_stat == 3) {
		var s_date1 = $('input[name = "s_date1"]').val();
		var s_date2 = $('input[name = "s_date2"]').val();
	}
	if(allow == false) {return false;}
	var checked = {im_cat1: im_cat1, im_cat2: im_cat2, im_cat3: im_cat3, st_cat1: st_cat1, st_cat2: st_cat2, s_date1: s_date1, s_date2: s_date2};
	return checked;
}

function sortTasksOutput(response) {
	if(response.s == 1){
		$('#t_cont').html('');
		if(response.pag != 0) {
			for(var key in response.tasks) {
				if(response.tasks[key].importance == 1){
					var imp = 'style="background:red" onClick="showHideImSt(\'edit_i_'+response.tasks[key].id+'\')" title="Срочно">Срочно';
					var ch11 = ' checked="checked" disabled="disabled"';
					var ch12 = ' onClick="openEditTasks(1,'+response.tasks[key].id+')" ';
					var ch13 = ' onClick="openEditTasks(1,'+response.tasks[key].id+')" ';
				} else if(response.tasks[key].importance == 2){
					var imp = 'style="background:#FC0" onClick="showHideImSt(\'edit_i_'+response.tasks[key].id+'\')" title="Важно">Важно';
					var ch11 = ' onClick="openEditTasks(1,'+response.tasks[key].id+')" ';
					var ch12 = ' checked="checked" disabled="disabled"';
					var ch13 = ' onClick="openEditTasks(1,'+response.tasks[key].id+')" ';
				} else if(response.tasks[key].importance == 3){
					var imp = 'style="background:green" onClick="showHideImSt(\'edit_i_'+response.tasks[key].id+'\')" title="Не важно">Не важно';
					var ch11 = ' onClick="openEditTasks(1,'+response.tasks[key].id+')" ';
					var ch12 = ' onClick="openEditTasks(1,'+response.tasks[key].id+')" ';
					var ch13 = ' checked="checked"  disabled="disabled"';
				}
				if(response.tasks[key].status == 1){
					var stat = 'style="background:red" onClick="showHideImSt(\'edit_s_'+response.tasks[key].id+'\')">Не выполнено';
					var stat1 = '<img src="/modules/admin/tasks/view/img/critical.png" alt="critical"  title="Не выполнено">';
					var ch14 = ' checked="checked"  disabled="disabled"';
					var ch15 = ' onClick="openEditTasks(2,'+response.tasks[key].id+')" ';
				} else if(response.tasks[key].status == 2){
					var stat = 'style="background:green" onClick="showHideImSt(\'edit_s_'+response.tasks[key].id+'\')">Выполнено';
					var stat1 = '<img src="/modules/admin/tasks/view/img/birdie.png" alt="birdie"  title="Выполнено">';
					var ch14 = ' onClick="openEditTasks(2,'+response.tasks[key].id+')" ';
					var ch15 = ' checked="checked"  disabled="disabled"';
				}
				
				$('#t_cont').append('<div class="tasks_block"><div id="t_d'+response.tasks[key].id+'" class="tasks_description"><div class="open_exit open_exit1" onclick="showHide(\'t_d'+response.tasks[key].id+'\'); showHide(\'t_c'+response.tasks[key].id+'\')">+</div><div class="tasks_title tasks_title1" onclick="showHide(\'t_d'+response.tasks[key].id+'\'); showHide(\'t_c'+response.tasks[key].id+'\')">'+response.tasks[key].title+'</div><img src="/modules/admin/tasks/view/img/edit.png" alt="edit" onclick="openEditTasks(\'\','+response.tasks[key].id+')"><img src="/modules/admin/tasks/view/img/del.png" alt="del" onclick="delTasks('+response.tasks[key].id+')"><div class="tasks_title-date tasks_title-date1"> '+response.tasks[key].date+'</div><div class="importance importance1" '+imp+'</div>'+stat1+'</div><div id="t_c'+response.tasks[key].id+'" class="tasks_content"><div class="open_exit" onclick="showHide(\'t_d'+response.tasks[key].id+'\'); showHide(\'t_c'+response.tasks[key].id+'\')">&mdash;</div><div class="tasks_title" onclick="showHide(\'t_d'+response.tasks[key].id+'\'); showHide(\'t_c'+response.tasks[key].id+'\')">'+response.tasks[key].title+'</div><div class="tasks_body">'+response.tasks[key].text+'</div><span class="tasks_title-date">'+response.tasks[key].date+'</span><div class="importance" '+imp+'</div><div id="edit_i_'+response.tasks[key].id+'" class="edit_i"><label class="ch11"><input '+ch11+' type="radio" name="edit_im" value="1"> срочно</label><br><label class="ch12"><input '+ch12+' type="radio" name="edit_im" value="2"> важно</label><br><label class="ch13"><input '+ch13+' type="radio" name="edit_im" value="3"> не важно</label></div><div class="status" '+stat+'</div><div id="edit_s_'+response.tasks[key].id+'" class="edit_s"><label class="ch14"><input '+ch14+' type="radio" name="edit_st" value="1" onClick="openEditTasks(1,'+response.tasks[key].id+')"> не выполнено</label><br><label class="ch15"><input '+ch15+' type="radio" name="edit_st" value="2" onClick="openEditTasks(1,'+response.tasks[key].id+')"> выполнено</label></div><div class="pr_st"><div class="pr">Приоритет:</div><div class="st">Статус:</div><div class="clear"></div></div></div></div>');
				if(response.tasks[key].importance == 1){
					$('#edit_i_'+response.tasks[key].id+' .ch11').css('opacity',0.5);
				} else if(response.tasks[key].importance == 2){
					$('#edit_i_'+response.tasks[key].id+' .ch12').css('opacity',0.5);
				} else if(response.tasks[key].importance == 3){
					$('#edit_i_'+response.tasks[key].id+' .ch13').css('opacity',0.5);
				}
				if(response.tasks[key].status == 1){	
					$('#edit_s_'+response.tasks[key].id+' .ch14').css('opacity',0.5);
				} else if(response.tasks[key].status == 2){
					$('#edit_s_'+response.tasks[key].id+' .ch15').css('opacity',0.5);
				}	
					
				$('.ch11, .ch14').css({
					'background':'red',
					'color':'#FFF'
				});
				$('.ch12').css({
					'background':'#FC0',
					'color':'#FFF'
				});
				$('.ch13, .ch15').css({
					'background':'green',
					'color':'#FFF'
				});
				
				$('#t_c'+response.tasks[key].id+' .importance').removeAttr('title');
			}		
			$('#pag').html('');
			$('#pag').html(response.pag);
			$('#pag a').attr('onClick','return pagClick(this)');
			$('#pag a').each(function(ind, el) {
				var ur1 = $(el).attr('href',$(el).attr('href').split('?')[0]);
			});
		} else {
			$('#t_cont').html('<div class="not_fount">Ничего не найдено :(</div>');
			$('#pag').html('');
		}
	}	
}

function sortTasks() {
	var w = sortTasksChecked();
	if(w != false) {
		$.ajax({
			url: '/admin/tasks?ajax=1',
			type: "POST",
			cache: false,
			data: {
				st:5,
				cat_im1: w.im_cat1,
				cat_im2: w.im_cat2,
				cat_im3: w.im_cat3,
				cat_st1: w.st_cat1,
				cat_st2: w.st_cat2,
				s_date1: w.s_date1,
				s_date2: w.s_date2
			},
			success: function(msg) {
				var response = JSON.parse(msg);
				sortTasksOutput(response);
				history.pushState(null, null, window.location.href.split('tasks')[0]+'tasks');
			}
		});
	}
}

function pagClick(el){
	var params = $(el).attr('href');
	history.pushState(null, null, params);
	var w = sortTasksChecked();
	if(w != false) {
		$.ajax({
			url: params+'?ajax=1',
			type: "POST",
			cache: false,
			data: {
				st:5,
				cat_im1: w.im_cat1,
				cat_im2: w.im_cat2,
				cat_im3: w.im_cat3,
				cat_st1: w.st_cat1,
				cat_st2: w.st_cat2,
				s_date1: w.s_date1,
				s_date2: w.s_date2
			},
			success: function(msg) {
				var response = JSON.parse(msg);
				sortTasksOutput(response);
			}
		});
	}
	return false;
}


/* Вывод при переходе на /admin/tasks */
$(document).ready(function(){
	var url = window.location.href.split('/');
	$("#fc").appendTo("#date");
	if(url[3] == 'admin' && url[4] == 'tasks') {
		$('input[name = "s_date1"]').val('');
		$('input[name = "s_date2"]').val('');
		$.ajax({
			url: '/admin/tasks?ajax=1',
			type: "POST",
			cache: false,
			data: {
				st:5,
				cat_im1: 1,
				cat_im2: 2,
				cat_im3: 3,
				cat_st1: 1
			},
			success: function(msg) {
				var response = JSON.parse(msg);
				$('#ch3').prop('checked',true);
				$('#ch4').prop('checked',true);
				$('#ch5').prop('checked',true);
				$('#ch1').prop('checked',true);
				sortTasksOutput(response);
				history.pushState(null, null, window.location.href.split('tasks')[0]+'tasks');
			}
		});
		return false; 
	}
});

/* ### ВЫБОР ДАТЫ ###*/
function getObj(objID) {
    if (document.getElementById) {
		return document.getElementById(objID);
	} else if (document.all) {
		return document.all[objID];
	} else if (document.layers) {
		return document.layers[objID];
	}
}

function checkClick(e) {
	e?evt=e:evt=event;
	CSE=evt.target?evt.target:evt.srcElement;
	if (CSE.tagName!='SPAN') {
		if (getObj('fc')) {
			if (!isChild(CSE,getObj('fc'))) {
				getObj('fc').style.display='none';
			}
		}
	}
}

function isChild(s,d) {
	while(s) {
		if (s==d)
			return true;
		s=s.parentNode;
	}
	return false;
}
function Left(obj) {
	var curleft = 0;
	if (obj.offsetParent) {
			curleft = obj.offsetLeft
			obj = obj.offsetParent;
	} else if (obj.x) {
		curleft += obj.x;
	}
	return curleft;
}

function Top(obj) {
	var curtop = 0;
	if (obj.offsetParent) {
			curtop = obj.offsetTop
			obj = obj.offsetParent;
	} else if (obj.y) {
		curtop += obj.y;
	}
	return curtop;
}

// Calendar script
var now = new Date;
var sccd=now.getDate();
var sccm=now.getMonth();
var sccy=now.getFullYear();
var ccm=now.getMonth();
var ccy=now.getFullYear();

// For current selected date
var selectedd, selectedm, selectedy;

document.write('<table id="fc" style="position:absolute;border-collapse:collapse;background:#FFFFFF;border:1px solid #FFD088;display:none;-moz-user-select:none;-khtml-user-select:none;user-select:none;" cellpadding="2">');
document.write('<tr style="font:bold 13px Arial" onselectstart="return false"><td style="cursor:pointer;font-size:15px" onclick="upmonth(-1)">&laquo;</td><td colspan="5" id="mns" align="center"></td><td align="right" style="cursor:pointer;font-size:15px" onclick="upmonth(1)">&raquo;</td></tr>');
document.write('<tr style="background:#FF9900;font:12px Arial;color:#FFFFFF"><td align=center>П</td><td align=center>В</td><td align=center>С</td><td align=center>Ч</td><td align=center>П</td><td align=center>С</td><td align=center>В</td></tr>');
for(var kk=1;kk<=6;kk++) {
	document.write('<tr>');
	for(var tt=1;tt<=7;tt++) {
		num=7 * (kk-1) - (-tt);
		document.write('<td id="cv' + num + '" style="width:18px;height:18px">&nbsp;</td>');
	}
	document.write('</tr>');
}
document.write('<tr><td colspan="7" align="center" style="cursor:pointer;font:13px Arial;background:#FFC266" onclick="today(); sortTasks()">Сегодня: '+addnull(sccd,sccm+1,sccy)+'</td></tr>');
document.write('</table>');

document.all?document.attachEvent('onclick',checkClick):document.addEventListener('click',checkClick,false);

/* Повторный выбор даты */
var updobj;
function lcs(ielem) {
	updobj=ielem;
	getObj('fc').style.left=Left(ielem)+'px';
	getObj('fc').style.top=Top(ielem)+ielem.offsetHeight+'px';
	getObj('fc').style.display='';

	// First check date is valid
	curdt=ielem.value;
	curdtarr=curdt.split('-');
	isdt=true;
	for(var k=0;k<curdtarr.length;k++) {
		if (isNaN(curdtarr[k]))
			isdt=false;
	}
	if (isdt&(curdtarr.length==3)) {
		ccm=curdtarr[1]-1;
		ccy=curdtarr[0];
		selectedd=parseInt ( curdtarr[2], 10 );
		selectedm=parseInt ( curdtarr[1]-1, 10 );
		selectedy=parseInt ( curdtarr[0], 10 );
		prepcalendar(curdtarr[2],curdtarr[1]-1,curdtarr[0]);
	}
}

function evtTgt(e){
	var el;
	if(e.target)el=e.target;
	else if(e.srcElement)el=e.srcElement;
	if(el.nodeType==3)el=el.parentNode; // defeat Safari bug
	return el;
}
function EvtObj(e){if(!e)e=window.event;return e;}
function cs_over(e) {
	evtTgt(EvtObj(e)).style.background='#FFEBCC';
}
function cs_out(e) {
	evtTgt(EvtObj(e)).style.background='#FFFFFF';
}
function cs_click(e) {
	updobj.value=calvalarr[evtTgt(EvtObj(e)).id.substring(2,evtTgt(EvtObj(e)).id.length)];
	getObj('fc').style.display='none';
	sortTasks();
}

var mn=new Array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентрябрь','Октябрь','Ноябрь','Декабрь');
var mnn=new Array('31','28','31','30','31','30','31','31','30','31','30','31');
var mnl=new Array('31','29','31','30','31','30','31','31','30','31','30','31');
var calvalarr=new Array(42);

function f_cps(obj) {
	obj.style.background='#FFFFFF';
	obj.style.font='10px Arial';
	obj.style.color='#333333';
	obj.style.textAlign='center';
	obj.style.textDecoration='none';
	obj.style.border='1px solid #FFD088';//'1px solid #606060';
	obj.style.cursor='pointer';
}

function f_cpps(obj) {
	obj.style.background='#C4D3EA';
	obj.style.font='10px Arial';
	obj.style.color='#FF9900';
	obj.style.textAlign='center';
	obj.style.textDecoration='line-through';
	obj.style.border='1px solid #6487AE';
	obj.style.cursor='default';
}

function f_hds(obj) {
	obj.style.background='#FFF799';
	obj.style.font='bold 10px Arial';
	obj.style.color='#333333';
	obj.style.textAlign='center';
	obj.style.border='1px solid #6487AE';
	obj.style.cursor='pointer';
}

// day selected
function prepcalendar(hd,cm,cy) {
	now=new Date();
	sd=now.getDate();
	td=new Date();
	td.setDate(1);
	td.setFullYear(cy);
	td.setMonth(cm);
	cd=td.getDay();
	if (cd==0)cd=6; else cd--;
	getObj('mns').innerHTML=mn[cm]+'&nbsp;<span style="cursor:pointer" onclick="upmonth(-12)">&lt;</span>'+cy+'<span style="cursor:pointer" onclick="upmonth(12)">&gt;</span>';
	marr=((cy%4)==0)?mnl:mnn;
	for(var d=1;d<=42;d++) {
		cv=getObj('cv'+parseInt(d));
		f_cps(cv);
		if ((d >= (cd -(-1)))&&(d<=cd-(-marr[cm]))) {
			dip=((d-cd < sd)&&(cm==sccm)&&(cy==sccy));
			htd=((hd!='')&&(d-cd==hd));

			cv.onmouseover=cs_over;
			cv.onmouseout=cs_out;
			cv.onclick=cs_click;

			// if today
			if (sccm == cm && sccd == (d-cd) && sccy == cy)
				cv.style.color='#FF9900';

			// if selected date
			if (cm == selectedm && cy == selectedy && selectedd == (d-cd) ) {
				cv.style.background='#FFEBCC';
				//cv.style.color='#e0d0c0';
				//cv.style.fontSize='1.1em';
				//cv.style.fontStyle='italic';
				//cv.style.fontWeight='bold';

				// when use style.background
				cv.onmouseout=null;
			}

			cv.innerHTML=d-cd;
			calvalarr[d]=addnull(d-cd,cm-(-1),cy);
			
		} else {
			cv.innerHTML='&nbsp;';
			cv.onmouseover=null;
			cv.onmouseout=null;
			cv.onclick=null;
			cv.style.cursor='default';
		}
	}
}

prepcalendar('',ccm,ccy);

function upmonth(s) {
	marr=((ccy%4)==0)?mnl:mnn;
	ccm+=s;
	if (ccm>=12) {
		ccm-=12;
		ccy++;
	} else if(ccm<0) {
		ccm+=12;
		ccy--;
	}
	prepcalendar('',ccm,ccy);
}

function today() {
	updobj.value=addnull(now.getDate(),now.getMonth()+1,now.getFullYear());
	getObj('fc').style.display='none';
	prepcalendar('',sccm,sccy);
}

function addnull(d,m,y) {
	var d0='',m0='';
	if (d<10)d0='0';
	if (m<10)m0='0';

	return ''+y+'-'+m0+m+'-'+d0+d;
}

/*  ВЫБОР ДАТЫ КОНЕЦ */
function dataClear() {
	$('input[name = "s_date1"]').val('');
	$('input[name = "s_date2"]').val('');
	sortTasks();
}

$(document).ready(function(){ /* Закрытие при сведении с элемента и клике вне его пределах
	$("#s_imp_div").on('mouseleave',function(){
		$('#s_imp_div').hide(800);
	});
	$("#s_stat_div").on('mouseleave',function(){
		$('#s_stat_div').hide(800);
	});
	$("#date").on('mouseleave',function(){
		$('#date').hide(800);
	});*/
	$(document).click(function (event) { //Клик за пределами
		if ($(event.target).closest('#s_imp_div').length == 0 && $(event.target).html() != 'Важность') {
			$('#s_imp_div').hide(800);
		}
		if ($(event.target).closest('#s_stat_div').length == 0 && $(event.target).html() != 'Статус') {
			$('#s_stat_div').hide(800);
		}
		if ($(event.target).closest('#date').length == 0 && $(event.target).html() != 'Дата') {
			$('#date').hide(800);
		}
		if ($(event.target).closest('#add_form').length == 0 && $(event.target).attr('id') != 'add_tasks' && $(event.target).attr('alt') != 'edit') {
			$('#add_form').hide(800);
		}
		if ($(event.target).closest('.edit_i, .edit_s').length == 0 && $(event.target).attr('class') != 'status' && $(event.target).attr('class') != 'importance') {
			$('.edit_i, .edit_s').hide(800);
		}
		
	});
	
});

function showHideSort(d) {
	if(d == 's_imp_div'){
		$('#s_imp_div').toggle("slow");
		$('#s_stat_div').css('display','none');
		$('#date').css('display','none');
	} else if(d == 's_stat_div'){
		$('#s_stat_div').toggle("slow");
		$('#s_imp_div').css('display','none');
		$('#date').css('display','none');
	} else if(d == 'date'){
		$('#date').toggle("slow");
		$('#s_imp_div').css('display','none');
		$('#s_stat_div').css('display','none');
		$('#fc').css('display','none');
	};
}

function showHideImSt(d) {
	var r = d.split('_');
	if(r[1] == 's'){
		$('#'+d).toggle("slow");
		$('#edit_i_'+r[2]).hide(800);
	} else if(r[1] == 'i'){
		$('#'+d).toggle("slow");
		$('#edit_s_'+r[2]).hide(800);
	}
}

function addTasks() {
	var allow = confirm('Вы уверены?');
	$('#f_err').html('');
	if($('input[name = "id"]').val() != 0 && allow !== false) {
		editTasks();
		allow = false;
	} else {
		if($('input[name = "title"]').val() != '') {
			var title = $('input[name = "title"]').val();
		} else {
			$('#f_err').html('Заполните ВСЕ поля!!!');
			allow = false;
		}
		if(CKEDITOR.instances.editor1.getData() != '') {
			var text = CKEDITOR.instances.editor1.getData();
		} else {
			$('#f_err').html('Заполните ВСЕ поля!!!');
			allow = false;
		}
		var imp = $('input[name = "imp"]:checked').val();
		var stat = $('input[name = "status"]:checked').val();
	}
	if(allow === true) {
		$.ajax({
			url: '/admin/tasks?ajax=1',
			type: "POST",
			cache: false,
			data: {
				st:8,
				title: title,
				text: text,
				imp: imp,
				stat: stat
			},
			success: function(msg) {
				if(msg == 8) {
					$('#info').html('<p class="info">Заметка успешно создана!</p>');
					$('#add_form').hide(800);
					$('.info').fadeOut(10000,function(){
						if($('.info').css('display') == 'none') {
							$('#info').html('');
							$('.info').removeAttr('style');
						}

					});
					sortTasks();
				}
			}
		});
	}
};

function delTasks(id) {
	var allow = confirm('Вы уверены?');
	if(allow === true) {
		var allow = isNumeric(id);
	}
	if(allow === true) {
		$.ajax({
			url: '/admin/tasks?ajax=1',
			type: "POST",
			cache: false,
			data: {
				st:9,
				id: id
			},
			success: function(msg) {
				if(msg == 9) {
					$('#info').html('<p class="info">Заметка успешно удалена!</p>');
					$('.info').fadeOut(10000,function(){
						if($('.info').css('display') == 'none') {
							$('#info').html('');
							$('.info').removeAttr('style');
						}
					});
					sortTasks();
				}
			}
		});
	}
};

function openEditTasks(cat,id) {
	var allow = isNumeric(id); 
	if(allow === true) {
		$.ajax({
			url: '/admin/tasks?ajax=1',
			type: "POST",
			cache: false,
			data: {
				st:11,
				id: id
			},
			success: function(msg) {
				var response = JSON.parse(msg);
				if(response.s == 11 && cat == ''){
					$('input[name = "title"]').val(response.tasks.title);
					CKEDITOR.instances.editor1.updateElement();
					CKEDITOR.instances.editor1.setData(response.tasks.text);
					var r = response.tasks.imp;
					if(r == 1) {
						$('#ch6').trigger('click');
						$('#ch6').attr('checked',true);
						$('#ch7').attr('checked',false);
						$('#ch8').attr('checked',false);
					} else if(r == 2) {
						$('#ch7').trigger('click');
						$('#ch7').attr('checked',true);
						$('#ch6').attr('checked',false);
						$('#ch8').attr('checked',false);
					} else if(r == 3) {
						$('#ch8').trigger('click');
					 	$('#ch6').attr('checked',false);
					 	$('#ch7').attr('checked',false);
						$('#ch8').attr('checked',true);
					}
					var s = response.tasks.stat;
					if(s == 1) {
						$('#ch9').trigger('click');
						$('#ch9').attr('checked',true);
						$('#ch10').attr('checked',false);
					} else if(s == 2) {
						$('#ch10').trigger('click');
					 	$('#ch9').attr('checked',false);
					 	$('#ch10').attr('checked',true);
					}
					$('input[name = "id"]').val(response.tasks.id);
					showHide('add_form');
				} else if(response.s == 11 && cat == 1 || response.s == 11 && cat == 2){
					$('#f_err').html('');
					var allow = confirm('Вы уверены?');
					if(id == 0) {
						allow = false;
					} 
					if(allow === true) {
						if(cat == 1) {
							var imp = $('input[name = "edit_im"]:checked').val();
							var stat = response.tasks.stat;
						} else if(cat == 2) {
							var imp = response.tasks.imp;
							var stat = $('input[name = "edit_st"]:checked').val();
						}
						$.ajax({
							url: '/admin/tasks?ajax=1',
							type: "POST",
							cache: false,
							data: {
								st:12,
								title: response.tasks.title,
								text: response.tasks.text,
								imp: imp,
								stat: stat,
								id: id
							},
							success: function(msg) {
								if(msg == 12) {
									$('#info').html('<p class="info">Заметка успешно изменена!</p>');
									$('#add_form').hide(800);
									$('.info').fadeOut(10000,function(){
										if($('.info').css('display') == 'none') {
											$('#info').html('');
											$('.info').removeAttr('style');
										}
				
									});	
									sortTasks();
									$('input[name = "title"]').val() == '';
									CKEDITOR.instances.editor1.updateElement();
									CKEDITOR.instances.editor1.setData('');
								}
							}
						});
					}
				} else if(response.s == 0) {
					$('#info').html('<p class="info">Заметка не существует!</p>').css('color','#F00');
					$('.info').fadeOut(10000,function(){
						if($('.info').css('display') == 'none') {
							$('#info').html('');
							$('.info').removeAttr('style');
						}

					});	
				}
			}
		});
	}
};

function editTasks() {
	var allow = true;
	if($('input[name = "title"]').val() != '') {
		var title = $('input[name = "title"]').val();
	} else {
		$('#f_err').html('Заполните ВСЕ поля!!!');
		allow = false;
	}
	if(CKEDITOR.instances.editor1.getData() != '') {
		var text = CKEDITOR.instances.editor1.getData();
	} else {
		$('#f_err').html('Заполните ВСЕ поля!!!');
		allow = false;
	}
	var imp = $('input[name = "imp"]:checked').val();
	var stat = $('input[name = "status"]:checked').val();
	var id = $('input[name = "id"]').val();
	if(allow === true) {
		$.ajax({
			url: '/admin/tasks?ajax=1',
			type: "POST",
			cache: false,
			data: {
				st:12,
				title: title,
				text: text,
				imp: imp,
				stat: stat,
				id: id
			},
			success: function(msg) {
				if(msg == 12) {
					$('#info').html('<p class="info">Заметка успешно изменена!</p>');
					$('#add_form').hide(800);
					$('.info').fadeOut(10000,function(){
						if($('.info').css('display') == 'none') {
							$('#info').html('');
							$('.info').removeAttr('style');
						}

					});
					sortTasks();
					$('input[name = "title"]').val() == '';
					CKEDITOR.instances.editor1.updateElement();
					CKEDITOR.instances.editor1.setData('');
				}
			}
		});
	}
};