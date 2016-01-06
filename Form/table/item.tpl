<?php foreach($this->content as $k=>$data) { ?>
  <tr><td colspan="3"><?php echo $k; wtf($data,1); ?></td></tr>
    <tr<?php if(!empty($data['error'])) {echo ' style="background-color:#900"';} ?>>
      <?php if($data['type'] == 'textarea') { /* TEXTAREA INPUT */ ?>
		  <td colspan="2">
			<?php echo $data['title']; ?><br>
			<?php echo $data['item']; ?>
		  </td>
		  <td>
			<?php echo $data['error']; ?>
		  </td>
      <?php } else { /* OTHER INPUT */ ?>
		  <td>
			<?php echo $data['title']; ?>
		  </td>
		  <td>
			<?php echo $data['item']; ?><br>
			<i><?php echo $data['text']; ?></i>
		  </td>
		  <td>
			<?php echo $data['error']; ?>
		  </td>
      <?php } ?>
    </tr>
<?php } ?>