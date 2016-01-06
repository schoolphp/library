<tr<?php if(!empty($this->content[$key]['error'])) {echo ' style="background-color:#FAA"';} ?>>
      <td colspan="2">
        <?php echo $this->content[$key]['title']; ?><br>
        <textarea name="<?php echo $this->content[$key]['name']; ?>" <?php echo $this->content[$key]['attrs']; ?>><?php echo $this->content[$key]['value']; ?></textarea>
      </td>
      <td>
        <?php echo $this->content[$key]['error']; ?>
      </td>
</tr>
