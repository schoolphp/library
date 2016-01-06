<h1>Заметки</h1>
<div class="tasks">
  <div class="sort">
    <form action="" class="sort_form" method="post">
      <div class="sorts sorts1">
        <img id="add_tasks" src="/modules/admin/tasks/view/img/plus.png" alt="plus" onclick="showHide('add_form')">
        Описание
      </div>
      <div class="sorts sorts2">  
        <span class="title_sort" onclick="showHideSort('s_stat_div')">Статус</span><br>
        <div id="s_stat_div" class="date_sort">
          <input id="ch1" type="checkbox" name="cat_st1" value="1" onClick="sortTasks()"><label for="ch1"> не выполнено</label><br>
          <input id="ch2" type="checkbox" name="cat_st2" value="2" onClick="sortTasks()"><label for="ch2"> выполнено</label>
        </div>
      </div>
      <div class="sorts sorts3">
        <span class="title_sort" onclick="showHideSort('s_imp_div')">Важность</span><br>
        <div id="s_imp_div" class="date_sort">
          <input id="ch3" type="checkbox" name="cat_im1" value="1" onClick="sortTasks()"><label for="ch3"> срочно</label><br>
          <input id="ch4" type="checkbox" name="cat_im2" value="2" onClick="sortTasks()"><label for="ch4"> важно</label><br>
          <input id="ch5" type="checkbox" name="cat_im3" value="3" onClick="sortTasks()"><label for="ch5"> не важно</label>
        </div>
      </div>
      <div class="sorts sorts4">
        <span class="title_sort" onclick="showHideSort('date')">Дата</span><br>
        <div id="date" class="date_sort">
          <div class="open_exit data_clear" onclick="dataClear()">x</div>
          <div id="d_err"></div>
          <div id="s_date_div">
            с &nbsp;&nbsp;<input onchange="sortTasks()" type="text" name="s_date1" value="" onfocus="this.select();lcs(this)" onclick="event.cancelBubble=true;this.select();lcs(this);"><br>
            по <input onchange="sortTasks()" type="text" value=""  name="s_date2" onfocus="this.select();lcs(this)" onclick="event.cancelBubble=true;this.select();lcs(this)">
          </div>
          <div class="tt"><div class="dd"></div></div>
        </div>
      </div>  
      <div class="clear"></div>
    </form>
  </div>
  <div id="info"></div>
  <?php if($res_c['cnt'] == 0) { ?>
    <div id="t_cont">
      <div class="out_tasks">Отсутствуют записи</div>
    </div>
  <?php } else { ?>
  <div id="t_cont"> 
    <div class="clear"></div>
  </div> <?php
  }?>
  <div id="pag">
  </div>  
</div>

<div id="add_form" class="form_cont">
  <form id="form1" name="form1" method="post" action="">
    <div id="f_err"></div>
    Заголовок:<br>
    <input name="title" class="title_input"  type="text"><br>
    Содержание:<br>
    <textarea name="text" id="editor1" cols="45" rows="5"></textarea>
    <script type="text/javascript">CKEDITOR.replace( 'editor1');</script><br>
    Тип:<br>
    <table>
      <tr>
        <td><label for="ch6">Срочно   <input id="ch6" name="imp" type="radio" checked="checked" value="1"></label></td>
        <td><label for="ch7">Важно    <input id="ch7" name="imp" type="radio" value="2"></label></td>
        <td><label for="ch8">Не важно <input id="ch8" name="imp" type="radio" value="3"></label></td>
      </tr>
    </table>
    Статус:
    <table>
      </tr>
      <tr>
        <td><label for="ch9">Не выполнено <input id="ch9" name="status" checked="checked" type="radio" value="1"></label></td>
        <td><label for="ch10">Выполнено   <input id="ch10" name="status" type="radio" value="2"></label></td>
        <td></td>
      </tr>
    </table>
    <input type="text" name="id" hidden="hidden" value="0">
    <input class="submit" type="button" name="submit" value="Сохранить" onClick="return addTasks()">
  </form>
</div>